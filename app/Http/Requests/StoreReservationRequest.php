<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
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
            'field_id' => ['required', 'exists:fields,id'],
            'reservation_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'customer_name' => ['nullable', 'string', 'max:100'],
            'customer_phone' => ['nullable', 'string', 'max:25'],
            'payment_method' => ['nullable', 'string', 'in:whatsapp,transfer,qris'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'field_id.required' => 'Lapangan harus dipilih.',
            'field_id.exists' => 'Lapangan tidak ditemukan.',
            'reservation_date.required' => 'Tanggal bermain harus diisi.',
            'reservation_date.after_or_equal' => 'Tanggal bermain minimal hari ini.',
            'start_time.required' => 'Jam mulai bermain harus dipilih.',
            'end_time.required' => 'Jam selesai bermain harus dipilih.',
            'end_time.after' => 'Jam selesai harus lebih besar dari jam mulai.',
        ];
    }
}
