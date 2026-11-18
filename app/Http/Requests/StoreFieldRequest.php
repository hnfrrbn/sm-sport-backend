<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreFieldRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()?->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:futsal,badminton'],
            'capacity' => ['required', 'integer', 'min:1'],
            'price_per_hour' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:available,unavailable,maintenance'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lapangan harus diisi',
            'type.required' => 'Jenis lapangan harus dipilih',
            'price_per_hour.required' => 'Harga per jam harus diisi',
            'capacity.required' => 'Kapasitas pemain harus diisi',
        ];
    }
}
