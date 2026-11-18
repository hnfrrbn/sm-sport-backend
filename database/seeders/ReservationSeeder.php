<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Field;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Reservation::exists()) {
            return;
        }

        $customer = User::where('role', 'customer')->first();
        $field = Field::first();

        if (! $customer || ! $field) {
            return;
        }

        Reservation::create([
            'user_id' => $customer->id,
            'field_id' => $field->id,
            'reservation_date' => Carbon::today()->addDays(1)->toDateString(),
            'start_time' => '14:00:00',
            'end_time' => '16:00:00',
            'duration_hours' => 2,
            'total_price' => 2 * $field->price_per_hour,
            'status' => 'pending',
            'notes' => 'Reservasi awal untuk testing seeder.',
        ]);
    }
}
