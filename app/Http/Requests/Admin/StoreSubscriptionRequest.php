<?php

namespace App\Http\Requests\Admin;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'plan_id' => ['required', 'exists:plans,id'],
            'from' => ['required', 'string', 'date'],
            'to' => ['required', 'string', 'date'],
        ];
    }

    public function validated($key = null, $default = null)
    {
        $from = Carbon::parse($this->get('from'));
        $to = Carbon::parse($this->get('to'));

        $months = $from->diffInMonths($to);

        if ($months <= 1 || $this->get('plan_id') === 3) {
            $months = 1;
        }

        return array_merge(parent::validated(), [
            'number_of_months' => $months,
        ]);
    }
}
