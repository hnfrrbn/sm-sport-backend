<?php

namespace Tests\Feature;

use App\Models\Field;
use App\Models\Reservation;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardRevenueTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_counts_paid_transactions_in_revenue_stats(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $customer = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'status' => 'active',
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
            'user_id' => $customer->id,
            'field_id' => $field->id,
            'status' => 'confirmed',
            'reservation_date' => now()->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'duration_hours' => 2,
            'total_price' => 240000,
        ]);

        Transaction::create([
            'user_id' => $customer->id,
            'reservation_id' => $reservation->id,
            'transaction_code' => 'TRXTEST001',
            'amount' => 240000,
            'payment_method' => 'qris',
            'status' => 'paid',
            'description' => 'Pembayaran test',
            'payment_date' => now(),
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Rp 240.000');
    }
}
