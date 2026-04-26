<?php

namespace App\Services;


use App\Helpers\MediaCollectionHelper;
use App\Http\Requests\Api\StoreCardRequest;
use App\Http\Requests\Api\UpdateCardRequest;
use App\Models\Card;
use App\Models\CardButton;
use App\Models\CardFaq;
use App\Models\CardGallery;
use App\Models\CardReview;
use App\Models\PersonalDetail;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Str;

class CardService
{
    public function createCard(StoreCardRequest $request)
    {
        $data = $request->validated();
        $user = auth('sanctum')->user();
        $card = $user->cards()->create([
            'name' => $data['name'],
            'type' => $data['type'],
            'color' => $data['color'],
            'contact_button_color' => $data['contact_button_color'] ?? '#1976D2',
            'published' => true === $request->boolean('published'),
            'pro_mode' => true === $request->boolean('pro_mode'),
        ]);

        $personalDetails = PersonalDetail::query()->create([
            'card_id' => $card->id,
            'name' => $data['personal_details']['name'],
            'phone' => $data['personal_details']['phone'],
            'bio' => $data['personal_details']['bio'],
            'about' => $data['personal_details']['about'],
            'conclusion' => $data['personal_details']['conclusion'],
        ]);

        if ($request->hasFile('personal_details.cover_image')) {
            $personalDetails->addMedia($data['personal_details']['cover_image'])->toMediaCollection(MediaCollectionHelper::PERSONAL_DETAILS_COVER);
        }
        if ($request->hasFile('personal_details.profile_image')) {
            $personalDetails->addMedia($data['personal_details']['profile_image'])->toMediaCollection(MediaCollectionHelper::PERSONAL_DETAILS_LOGO);
        }
        if ($request->hasFile('personal_details.background_image')) {
            $personalDetails->addMedia($data['personal_details']['background_image'])->toMediaCollection(MediaCollectionHelper::PERSONAL_BACKGROUND_IMAGE);
        }
        if ($request->hasFile('personal_details.contact_details_background')) {
            $personalDetails->addMedia($data['personal_details']['contact_details_background'])->toMediaCollection(MediaCollectionHelper::CONTACT_DETAILS_BACKGROUND);
        }

        if (isset($data['faqs']) && is_array($data['faqs']) && count($data['faqs']) > 0) {
            foreach ($data['faqs'] as $faq) {
                CardFaq::query()->create([
                    'card_id' => $card->id,
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'background_color' => $faq['background_color'],
                ]);
            }
        }

        if (isset($data['buttons']) && is_array($data['buttons']) && count($data['buttons']) > 0) {
            foreach ($data['buttons'] as $button) {
                $link = $button['link'];
                if(Str::contains($button['icon'], 'email')) {
                    $link = str_replace("mailto:", "", $link);
                }
                if(Str::contains($button['icon'], 'phone')) {
                    $link = str_replace("tel:", "", $link);
                }
                CardButton::query()->create([
                    'card_id' => $card->id,
                    'title' => $button['title'] ?? "No value",
                    'icon' => $button['icon'],
                    'color' => $button['color'],
                    'link' => $link,
                    'font_family' => $button['font_family'],
                    'font_size' => $button['font_size'],
                ]);
            }
        }


        if (isset($data['reviews']) && is_array($data['reviews']) && count($data['reviews']) > 0) {
            foreach ($data['reviews'] as $review) {
                CardReview::query()->create([
                    'card_id' => $card->id,
                    'review' => $review['review'],
                    'user_name' => $review['user_name'],
                    'rating' => $review['rating'],
                ]);
            }
        }

        if (isset($data['gallery']) && is_array($data['gallery']) && count($data['gallery']) > 0) {
            foreach ($data['gallery'] as $item) {
                $image = CardGallery::query()->create([
                    'card_id' => $card->id,
                    'rows' => $item['rows'],
                    'cols' => $item['cols'],
                ]);
                $image->addMedia($item['image'])->toMediaCollection(MediaCollectionHelper::CARD_GALLERY);
            }
        }


        if ($request->hasFile('qrcode_logo')) {
            $ext = $request->file('qrcode_logo')?->getClientOriginalExtension();
            $name = time() . '.' . $ext;
            $path = "qrlogos/";
            $request->file('qrcode_logo')?->move($path, $name);
            $fullPathLogo = $path . $name;
            $result = new Builder(
                writer: new PngWriter(),
                data: config('app.url') . '/cards/' . $card->uuid,
                size: 300,
                margin: 10,
                logoPath: public_path($fullPathLogo),
                logoResizeToWidth: 50,
                logoResizeToHeight: 50
            );
            Card::query()->where('id', $card->id)->update([
                'qrcode_logo' => config('app.url') . '/' . $fullPathLogo,
            ]);
        } else {
            $result = new Builder(
                writer: new PngWriter(),
                data: config('app.url') . '/cards/' . $card->uuid,
                size: 300,
                margin: 10,
            );
        }


        $qrcodeName = time() . '-' . $card->id . '.png';
        $result->build()->saveToFile(public_path("qrcodes/$qrcodeName"));

        $card->update([
            'qrcode' => config('app.url') . "/qrcodes/$qrcodeName",
        ]);

        return $card->refresh();
    }

    public function updateCard(UpdateCardRequest $request, Card $card)
    {
        $data = $request->validated();
        $user = auth('sanctum')->user();

        if ($card->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $card->update([
            'name' => $data['name'],
            'type' => $data['type'],
            'color' => $data['color'],
            'contact_button_color' => $data['contact_button_color'] ?? '#000000',
            'published' => $request->boolean('published'),
            'pro_mode' => $request->boolean('pro_mode'),
        ]);

        $personalDetails = $card->personalDetails;
        if ($personalDetails) {
            $personalDetails->update([
                'name' => $data['personal_details']['name'],
                'phone' => $data['personal_details']['phone'],
                'bio' => $data['personal_details']['bio'],
                'about' => $data['personal_details']['about'],
                'conclusion' => $data['personal_details']['conclusion'],
            ]);

            if ($request->hasFile('personal_details.cover_image')) {
                $personalDetails->clearMediaCollection(MediaCollectionHelper::PERSONAL_DETAILS_COVER);
                $personalDetails->addMedia($data['personal_details']['cover_image'])
                    ->toMediaCollection(MediaCollectionHelper::PERSONAL_DETAILS_COVER);
            }
            if ($request->hasFile('personal_details.profile_image')) {
                $personalDetails->clearMediaCollection(MediaCollectionHelper::PERSONAL_DETAILS_LOGO);
                $personalDetails->addMedia($data['personal_details']['profile_image'])
                    ->toMediaCollection(MediaCollectionHelper::PERSONAL_DETAILS_LOGO);
            }
            if ($request->hasFile('personal_details.background_image')) {
                $personalDetails->clearMediaCollection(MediaCollectionHelper::PERSONAL_BACKGROUND_IMAGE);
                $personalDetails->addMedia($data['personal_details']['background_image'])
                    ->toMediaCollection(MediaCollectionHelper::PERSONAL_BACKGROUND_IMAGE);
            }
            if ($request->hasFile('personal_details.contact_details_background')) {
                $personalDetails->clearMediaCollection(MediaCollectionHelper::CONTACT_DETAILS_BACKGROUND);
                $personalDetails->addMedia($data['personal_details']['contact_details_background'])
                    ->toMediaCollection(MediaCollectionHelper::CONTACT_DETAILS_BACKGROUND);
            }
        }

        if (isset($data['faqs']) && is_array($data['faqs'])) {
            $card->faqs()->delete();
            foreach ($data['faqs'] as $faq) {
                $card->faqs()->create([
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'background_color' => $faq['background_color'],
                ]);
            }
        }

        if (isset($data['buttons']) && is_array($data['buttons'])) {
            $card->buttons()->delete();
            foreach ($data['buttons'] as $button) {
                $link = $button['link'];
                if(Str::contains($button['icon'], 'email')) {
                    $link = str_replace("mailto:", "", $link);
                }
                if(Str::contains($button['icon'], 'phone')) {
                    $link = str_replace("tel:", "", $link);
                }
                $card->buttons()->create([
                    'title' => $button['title'] ?? "No value",
                    'icon' => $button['icon'],
                    'color' => $button['color'],
                    'link' => $link,
                    'font_family' => $button['font_family'],
                    'font_size' => $button['font_size'],
                ]);
            }
        }

        if (isset($data['reviews']) && is_array($data['reviews'])) {
            $card->reviews()->delete();
            foreach ($data['reviews'] as $review) {
                $card->reviews()->create([
                    'review' => $review['review'],
                    'user_name' => $review['user_name'],
                    'rating' => $review['rating'],
                ]);
            }
        }

        if (isset($data['gallery']) && is_array($data['gallery'])) {
            foreach ($data['gallery'] as $item) {
                if (isset($item['id']) && $item['id'] != "null") {
                    $card->gallery()->where('id', $item['id'])->update([
                        'rows' => $item['rows'],
                        'cols' => $item['cols'],
                        'order' => $item['order'],
                    ]);
                    continue;
                }
                $galleryItem = $card->gallery()->create([
                    'rows' => $item['rows'],
                    'cols' => $item['cols'],
                ]);
                if (isset($item['image']) && is_file($item['image'])) {
                    $galleryItem->addMedia($item['image'])
                        ->toMediaCollection(MediaCollectionHelper::CARD_GALLERY);
                }
            }
        } else {
            $card->gallery()->delete();
        }

        if ($request->hasFile('qrcode_logo')) {
            $ext = $request->file('qrcode_logo')->getClientOriginalExtension();
            $name = time() . '.' . $ext;
            $path = "qrlogos/";
            $request->file('qrcode_logo')->move($path, $name);
            $fullPathLogo = $path . $name;
            $result = new Builder(
                writer: new PngWriter(),
                data: config('app.url') . '/cards/' . $card->uuid,
                size: 300,
                margin: 10,
                logoPath: public_path($fullPathLogo),
                logoResizeToWidth: 50,
                logoResizeToHeight: 50
            );
            Card::query()->where('id', $card->id)->update([
                'qrcode_logo' => config('app.url') . '/' . $fullPathLogo,
            ]);
            $qrcodeName = time() . '-' . $card->id . '.png';
            $result->build()->saveToFile(public_path("qrcodes/$qrcodeName"));

            $card->update([
                'qrcode' => config('app.url') . "/qrcodes/$qrcodeName",
            ]);
        }


        return $card->refresh();
    }
}
