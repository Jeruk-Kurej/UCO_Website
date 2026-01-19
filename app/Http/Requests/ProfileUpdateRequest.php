<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'username' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif', 'max:10240'], // Max 10MB
            
            // Contact Information
            'phone_number' => ['nullable', 'string', 'max:50'],
            'mobile_number' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            
            // Personal Information
            'birth_date' => ['nullable', 'date'],
            'birth_city' => ['nullable', 'string', 'max:255'],
            'religion' => ['nullable', 'string', 'max:255'],
            
            // Academic Information
            'NIS' => ['nullable', 'string', 'max:255'],
            'Student_Year' => ['nullable', 'string', 'max:50'],
            'Major' => ['nullable', 'string', 'max:255'],
            'CGPA' => ['nullable', 'numeric', 'min:0', 'max:4'],
            'Is_Graduate' => ['nullable', 'boolean'],
            
            // Password Change (ALL fields required if changing password)
            'current_password' => ['nullable', 'required_with:password', 'string'],
            'password' => ['nullable', 'required_with:current_password', 'confirmed', 'min:8'],
            'password_confirmation' => ['nullable', 'required_with:password'],
        ];
    }
}
