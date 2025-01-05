<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingTransactionRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'required|string',
            'total_amount' => 'required|numeric',
            'place_id' => 'required|exists:places,id',
            'field_id' => 'required|exists:fields,id',
            'booking_date' => 'required|date',
            'booking_time' => 'required',
            'total_sesi' => 'required|numeric',
            'proof' => 'required|image|file|mimes:jpeg,png,jpg',
        ];
    }
}
