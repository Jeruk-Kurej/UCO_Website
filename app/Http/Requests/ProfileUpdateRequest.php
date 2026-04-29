<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'whatsapp'      => ['nullable', 'string', 'max:50'],
            'linkedin'      => ['nullable', 'string', 'max:255'],
            'testimony'     => ['nullable', 'string'],
            'profile_photo' => ['nullable', 'image', 'max:10240'],
            'password'      => ['nullable', 'confirmed', 'min:8'],
        ];
    }
}
