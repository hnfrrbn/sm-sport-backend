<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . Auth::id()],
            'phone' => ['required', 'string', 'regex:/^(\+62|0)[0-9]{9,12}$/'],
            'address' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function prepareForValidation(): void
    {
        if ($this->has('phone')) {
            $phone = preg_replace('/\s+/', '', (string) $this->input('phone'));
            $this->merge(['phone' => $phone]);
        }
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah terdaftar',
            'phone.required' => 'Nomor telepon harus diisi',
            'phone.regex' => 'Format nomor telepon tidak valid',
        ];
    }
}
