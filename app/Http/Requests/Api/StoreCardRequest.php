<?php

namespace App\Http\Requests\Api;

use App\Enums\CardTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class StoreCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'type' => ['required', 'string', 'in:' . implode(',', CardTypeEnum::getValues())],
            'qrcode_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'color' => ['required', 'string', 'hex_color'],
            'contact_button_color' => ['required', 'string', 'hex_color'],

            'personal_details' => ['array', 'required'],
            'personal_details.name' => ['required', 'string'],
            'personal_details.phone' => ['required', 'string', 'max:20'],
            'personal_details.bio' => ['nullable', 'string'],
            'personal_details.about' => ['nullable', 'string'],
            'personal_details.conclusion' => ['nullable', 'string'],
            'personal_details.cover_image' => ['nullable'],
            'personal_details.profile_image' => ['nullable'],
            'personal_details.background_image' => ['nullable'],
            'personal_details.contact_details_background' => ['nullable'],

            // ---- FAQs ----
            'faqs' => ['array', 'nullable'],
            'faqs.*.question' => ['required_with:faqs', 'string'],
            'faqs.*.answer' => ['required_with:faqs', 'string'],
            'faqs.*.background_color' => ['required_with:faqs', 'string'],

            // ---- Buttons ----
            'buttons' => ['array', 'nullable'],
            'buttons.*.title' => ['required_with:buttons', 'string'],
            'buttons.*.icon' => ['nullable', 'string'],
            'buttons.*.link' => ['required_with:buttons', 'string'],
            'buttons.*.color' => ['nullable', 'hex_color', 'string'],
            'buttons.*.font_family' => ['required', 'string'],
            'buttons.*.font_size' => ['required', 'string'],

            // ---- Reviews ----
            'reviews' => ['array', 'nullable'],
            'reviews.*.review' => ['required_with:reviews', 'string'],
            'reviews.*.user_name' => ['required_with:reviews', 'string'],
            'reviews.*.rating' => ['required_with:reviews', 'numeric', 'min:0', 'max:5'],

            // ---- Gallery ----
            'gallery' => ['array', 'nullable'],
            'gallery.*.image' => ['required_with:gallery', 'image'],
            'gallery.*.cols' => ['required_with:gallery', 'numeric'],
            'gallery.*.rows' => ['required_with:gallery', 'numeric'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Basic card fields
            'name.required' => 'Card name is required.',
            'name.string' => 'Card name must be a valid text.',
            'type.required' => 'Card type is required.',
            'type.in' => 'Please select a valid card type.',
            'qrcode_logo.image' => 'QR code logo must be a valid image.',
            'qrcode_logo.mimes' => 'QR code logo must be a JPG, JPEG, PNG, or WebP image.',
            'qrcode_logo.max' => 'QR code logo size cannot exceed 4MB.',
            'color.required' => 'Card background color is required.',
            'color.hex_color' => 'Card background color must be a valid hex color code.',
            'contact_button_color.required' => 'Contact button color is required.',
            'contact_button_color.hex_color' => 'Contact button color must be a valid hex color code.',

            // Personal details
            'personal_details.required' => 'Personal details are required.',
            'personal_details.array' => 'Personal details must be provided in correct format.',
            'personal_details.name.required' => 'Your name is required.',
            'personal_details.name.string' => 'Name must be valid text.',
            'personal_details.phone.required' => 'Phone number is required.',
            'personal_details.phone.string' => 'Phone number must be valid text.',
            'personal_details.phone.max' => 'Phone number cannot exceed 20 characters.',
            'personal_details.bio.string' => 'Bio must be valid text.',
            'personal_details.about.string' => 'About section must be valid text.',
            'personal_details.conclusion.string' => 'Conclusion must be valid text.',

            // FAQs
            'faqs.array' => 'FAQs must be provided in correct format.',
            'faqs.*.question.required_with' => 'FAQ question is required when adding FAQs.',
            'faqs.*.question.string' => 'FAQ question must be valid text.',
            'faqs.*.answer.required_with' => 'FAQ answer is required when adding FAQs.',
            'faqs.*.answer.string' => 'FAQ answer must be valid text.',
            'faqs.*.background_color.required_with' => 'FAQ background color is required when adding FAQs.',
            'faqs.*.background_color.string' => 'FAQ background color must be valid text.',

            // Buttons
            'buttons.array' => 'Buttons must be provided in correct format.',
            'buttons.*.title.required_with' => 'Button title is required when adding buttons.',
            'buttons.*.title.string' => 'Button title must be valid text.',
            'buttons.*.icon.string' => 'Button icon must be valid text.',
            'buttons.*.link.required_with' => 'Button link is required when adding buttons.',
            'buttons.*.link.string' => 'Button link must be valid text.',
            'buttons.*.color.hex_color' => 'Button color must be a valid hex color code.',
            'buttons.*.color.string' => 'Button color must be valid text.',
            'buttons.*.font_family.required' => 'Button font family is required.',
            'buttons.*.font_family.string' => 'Button font family must be valid text.',
            'buttons.*.font_size.required' => 'Button font size is required.',
            'buttons.*.font_size.string' => 'Button font size must be valid text.',

            // Reviews
            'reviews.array' => 'Reviews must be provided in correct format.',
            'reviews.*.review.required_with' => 'Review text is required when adding reviews.',
            'reviews.*.review.string' => 'Review text must be valid text.',
            'reviews.*.user_name.required_with' => 'Reviewer name is required when adding reviews.',
            'reviews.*.user_name.string' => 'Reviewer name must be valid text.',
            'reviews.*.rating.required_with' => 'Review rating is required when adding reviews.',
            'reviews.*.rating.numeric' => 'Review rating must be a number.',
            'reviews.*.rating.min' => 'Review rating must be at least 0.',
            'reviews.*.rating.max' => 'Review rating cannot exceed 5.',

            // Gallery
            'gallery.array' => 'Gallery must be provided in correct format.',
            'gallery.*.image.required_with' => 'Gallery image is required when adding gallery items.',
            'gallery.*.image.image' => 'Gallery item must be a valid image.',
            'gallery.*.cols.required_with' => 'Gallery column size is required when adding gallery items.',
            'gallery.*.cols.numeric' => 'Gallery column size must be a number.',
            'gallery.*.rows.required_with' => 'Gallery row size is required when adding gallery items.',
            'gallery.*.rows.numeric' => 'Gallery row size must be a number.',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $user = auth('sanctum')->user();
        $proMode = false;
        if ($user->hasActiveSubscription() && $this->has('pro_mode') && $this->boolean('pro_mode')) {
            $proMode = true;
        }
        return array_merge(parent::validated($key, $default), [
            'published' => $this->boolean('published', false),
            'pro_mode' => $proMode,
        ]);
    }

}
