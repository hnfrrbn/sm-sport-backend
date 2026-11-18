<?php

namespace Tests\Feature;

use App\Models\Field;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationPaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_transaction_endpoint_returns_transaction_for_own_reservation(): void
    {
        $user = User::create([
            'name' => 'Test Customer',
            'email' => 'customer-' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $field = Field::create([
            'name' => 'Lapangan A',
            'type' => 'futsal',
            'capacity' => 10,
            'price_per_hour' => 120000,
            'status' => 'available',
            'description' => 'Lapangan test',
        ]);

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'field_id' => $field->id,
            'status' => 'pending',
            'reservation_date' => now()->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'duration_hours' => 2,
            'total_price' => 240000,
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/reservations/' . $reservation->id . '/transaction', []);

        $response->assertOk();
        $response->assertJsonPath('ok', true);
        $this->assertNotNull($response->json('transaction_id'));
        $this->assertDatabaseHas('transactions', [
            'reservation_id' => $reservation->id,
            'user_id' => $user->id,
        ]);
    }
}
