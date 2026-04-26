<?php

namespace App\Http\Controllers\Api;

use App\Helpers\MediaCollectionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreContactRequest;
use App\Http\Requests\Api\UpdateCardRequest;
use App\Http\Requests\Api\UpdateContactRequest;
use App\Http\Resources\Api\CardContactResource;
use App\Models\Contact;
use App\Models\ContactEmail;
use App\Models\ContactPhone;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function getAllContacts(): \Illuminate\Http\JsonResponse
    {
        $user = auth('sanctum')->user();
        $conversionRate = $user->contacts()->where('is_active', true)->count() / max($user->contacts()->count(), 1) * 100;
        return response()->json([
            "total_contacts" => $user->contacts()->count(),
            "total_contacts_in_this_week" => $user->contacts()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            "active_contacts" => $user->contacts()->where('is_active', true)->count(),
            "conversion_rate" => number_format($conversionRate, 0),
            'contacts' => CardContactResource::collection($user->contacts()->with(['emails', 'phones', 'card'])->paginate(PAGINATE_LIMIT))->response()->getData(true),
        ]);
    }

    public function showSingleContact(Contact $contact): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'contact' => CardContactResource::make($contact->load(['emails', 'phones', 'card'])),
        ]);
    }

    public function store(StoreContactRequest $request): \Illuminate\Http\JsonResponse
    {

        $user = auth('sanctum')->user();
        $contact = $user->contacts()->create($request->validated());
        if ($request->hasFile('image')) {
            $contact->addMediaFromRequest('image')->toMediaCollection(MediaCollectionHelper::CONTACT_IMAGE);
        }
        if ($request->hasFile('logo')) {
            $contact->addMediaFromRequest('logo')->toMediaCollection(MediaCollectionHelper::CONTACT_LOGO);
        }
        if ($request->has('emails') && is_array($request->emails) && count($request->emails) > 0) {
            foreach ($request->emails as $email) {
                if (!empty($email['email'])) {
                    ContactEmail::query()->create([
                        'contact_id' => $contact->id,
                        'email' => $email['email'],
                        'label' => $email['label'] ?? null,
                    ]);
                }
            }
        }

        if ($request->has('phones') && is_array($request->phones) && count($request->phones) > 0) {
            foreach ($request->phones as $phone) {
                if (!empty($phone['phone'])) {
                    ContactPhone::query()->create([
                        'contact_id' => $contact->id,
                        'phone' => $phone['phone'],
                        'ext' => $phone['ext'] ?? null,
                        'label' => $phone['label'] ?? null,
                    ]);
                }
            }
        }

        return response()->json([
            'message' => 'Contact created successfully',
            'contact' => CardContactResource::make($contact->load(['emails', 'phones'])),
        ]);
    }

    public function delete(Contact $contact): \Illuminate\Http\JsonResponse
    {
        $contact->delete();
        return response()->json([
            'message' => "Deleted successfully",
        ]);
    }

    public function update(UpdateContactRequest $request, Contact $contact): \Illuminate\Http\JsonResponse
    {
        $user = auth('sanctum')->user();
        $contact->update($request->validated());
        if ($request->hasFile('image')) {
            $contact->clearMediaCollection(MediaCollectionHelper::CONTACT_IMAGE);
            $contact->addMediaFromRequest('image')->toMediaCollection(MediaCollectionHelper::CONTACT_IMAGE);
        }
        if ($request->hasFile('logo')) {
            $contact->clearMediaCollection(MediaCollectionHelper::CONTACT_LOGO);
            $contact->addMediaFromRequest('logo')->toMediaCollection(MediaCollectionHelper::CONTACT_LOGO);
        }
        if ($request->has('emails') && is_array($request->emails) && count($request->emails) > 0) {
            $contact->emails()->delete();
            foreach ($request->emails as $email) {
                if (!empty($email['email'])) {
                    ContactEmail::query()->create([
                        'contact_id' => $contact->id,
                        'email' => $email['email'],
                        'label' => $email['label'] ?? null,
                    ]);
                }
            }
        }

        if ($request->has('phones') && is_array($request->phones) && count($request->phones) > 0) {
            $contact->phones()->delete();
            foreach ($request->phones as $phone) {
                if (!empty($phone['phone'])) {
                    ContactPhone::query()->create([
                        'contact_id' => $contact->id,
                        'phone' => $phone['phone'],
                        'ext' => $phone['ext'] ?? null,
                        'label' => $phone['label'] ?? null,
                    ]);
                }
            }
        }

        return response()->json([
            'message' => 'Contact updated successfully',
            'contact' => CardContactResource::make($contact->refresh()),
        ]);
    }

    public function toggleActive(Contact $contact): \Illuminate\Http\JsonResponse
    {
        $contact->update([
            'is_active' => !$contact->is_active
        ]);

        return response()->json([
            'message' => 'Contact status updated successfully',
            'contact' => CardContactResource::make($contact->refresh()),
            'is_active' => $contact->is_active
        ]);
    }
}
