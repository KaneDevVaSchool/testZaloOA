<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScanGuestRequest extends FormRequest
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
    public function rules()
    {
        return [
            'qr_token' => 'required|uuid|exists:guests,qr_token',
            'scanner_id' => 'nullable|string', // optional logging
        ];
    }
}
