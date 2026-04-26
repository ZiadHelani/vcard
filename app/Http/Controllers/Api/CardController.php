<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SendMailToCardUserRequest;
use App\Http\Requests\Api\StoreCardRequest;
use App\Http\Requests\Api\UpdateCardRequest;
use App\Http\Resources\Api\CardResource;
use App\Mail\SendMailToCardUser;
use App\Models\Card;
use App\Services\CardService;
use Astrotomic\Vcard\Properties\Email;
use Astrotomic\Vcard\Properties\Gender;
use Astrotomic\Vcard\Properties\Kind;
use Astrotomic\Vcard\Properties\Tel;
use Astrotomic\Vcard\Vcard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CardController extends Controller
{

    protected CardService $cardService;

    public function __construct(CardService $cardService)
    {
        $this->cardService = $cardService;
    }

    public function getAllCards(Request $request): \Illuminate\Http\JsonResponse
    {
        $userId = auth('sanctum')->id();
        $search = $request->search;

        $cards = Card::query()
            ->where('user_id', $userId)
            ->when(filled($search), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhereRelation('personalDetails', 'name', 'LIKE', "%{$search}%");
                });
            })
            ->paginate(PAGINATE_LIMIT);

        return response()->json([
            'cards' => CardResource::collection($cards)->response()->getData(true),
        ]);
    }

    public function store(StoreCardRequest $request)
    {

        try {
            DB::beginTransaction();
            $user = Auth::guard('sanctum')->user();
            $cardsCount = $user?->cards()->count();
            // limit cards depending on subscription.
            if ($user?->hasActiveSubscription()) {
                if ($user?->activeSubscriptionNow()->plan_id === 1 && $cardsCount >= 1) {
                    return response()->json([
                        'message' => 'You have reached the limit of cards',
                    ], 400);
                }
                if ($user?->activeSubscriptionNow()->plan_id === 2 && $cardsCount >= 3) {
                    return response()->json([
                        'message' => 'You have reached the limit of cards',
                    ], 400);
                }
            }
            $card = $this->cardService->createCard($request);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error creating card',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Card created successfully',
            'card' => CardResource::make($card),
        ]);
    }

    public function update(UpdateCardRequest $request, Card $card): ?\Illuminate\Http\JsonResponse
    {
        try {
            DB::beginTransaction();
            $card = $this->cardService->updateCard($request, $card);
            DB::commit();
            return response()->json([
                'message' => 'Card updated successfully',
                'card' => CardResource::make($card),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error updating card',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete(Card $card): \Illuminate\Http\JsonResponse
    {
        if ($card->user_id !== auth('sanctum')->id()) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }
        $card->delete();
        return response()->json([
            'message' => 'Card deleted successfully',
        ]);
    }

    public function show(Card $card): \Illuminate\Http\JsonResponse
    {
        $card->load(['personalDetails', 'buttons', 'reviews', 'gallery', 'contacts', 'faqs']);
        $card->increment('total_views');
        $card->save();
        return response()->json([
            'card' => CardResource::make($card),
        ]);
    }

    public function updateCardStatus(Request $request, Card $card): \Illuminate\Http\JsonResponse
    {
        if ($card->user_id !== auth('sanctum')->id()) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $data = $request->validate([
            'published' => ['required', 'boolean'],
        ]);

        $card->update(['published' => $data['published']]);

        return response()->json([
            'message' => 'Card status updated successfully',
            'card' => CardResource::make($card->fresh()),
        ]);
    }

    public function removeQrCodeLogo(Card $card): \Illuminate\Http\JsonResponse
    {
        if ($card->user_id !== auth('sanctum')->id()) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $card->clearMediaCollection('qrcode_logo');

        // $card->update(['qrcode_logo' => null]);

        $result = new Builder(
            writer: new PngWriter(),
            data: config('app.url') . '/cards/' . $card->uuid,
            size: 300,
            margin: 10,
        );
        $qrcodeName = time() . '-' . $card->id . '.png';
        $result->build()->saveToFile(public_path("qrcodes/$qrcodeName"));

        $card->update([
            'qrcode' => config('app.url') . "/qrcodes/$qrcodeName",
        ]);

        return response()->json([
            'message' => 'QR code logo removed successfully',
        ]);
    }

    public function downloadVCard(Request $request, Card $card)
    {
        if (!$card->pro_mode) {
            return response()->json([
                'status' => 400,
                'message' => "You can't download this card when you are not in Pro Mode",
            ], 400);
        }

        $vcard = Vcard::make()
            ->kind(Kind::INDIVIDUAL)
            ->fullName(strip_tags($card->personalDetails->name))
            ->tel($card->personalDetails->phone, [Tel::WORK, Tel::VOICE])
            ->url("https://api.ultratech.co.il/cards/{$card->slug}");

        if ($card->personalDetails->logo && file_exists($card->personalDetails->logo)) {
            $vcard->photo(
                'data:image/jpeg;base64,' .
                base64_encode(file_get_contents($card->personalDetails->logo))
            );
        }

        $fileName = str_replace(' ', '_', $card->personalDetails->name) . '.vcf';

        $card->increment('total_saves');
        $card->save();

        return response($vcard)
            ->header('Content-Type', 'text/vcard; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    public function sendMail(SendMailToCardUserRequest $request, Card $card): ?\Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $cardButtons = $card->buttons;
        $hasEmailBtn = false;
        $email = "";
        foreach ($cardButtons as $button) {
            if (Str::contains($button->icon, 'email')) {
                $hasEmailBtn = true;
                $email = $button->link;
                break;
            }
        }
        if (!$hasEmailBtn || blank($email)) {
            return response()->json([
                'message' => 'Card does not have an email button',
            ], 400);
        }
        Mail::to($email)->send(new SendMailToCardUser($data));
        return response()->json([
            'message' => 'Message sent successfully',
        ]);

    }
}
