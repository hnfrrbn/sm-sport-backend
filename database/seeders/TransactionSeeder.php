<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('transactions')->exists()) {
            return;
        }

        $customer = User::where('role', 'customer')->first();
        $reservation = Reservation::first();

        if (! $customer || ! $reservation) {
            return;
        }

        Transaction::create([
            'user_id' => $customer->id,
            'reservation_id' => $reservation->id,
            'transaction_code' => Transaction::generateTransactionCode(),
            'amount' => $reservation->total_price ?: 150000,
            'payment_method' => 'transfer',
            'status' => 'completed',
            'description' => 'Pembayaran reservasi lapangan via transfer bank.',
            'payment_date' => now()->subDays(1),
        ]);

        Transaction::create([
            'user_id' => $customer->id,
            'reservation_id' => $reservation->id,
            'transaction_code' => Transaction::generateTransactionCode(),
            'amount' => 95000,
            'payment_method' => 'cash',
            'status' => 'pending',
            'description' => 'Menunggu pembayaran untuk reservasi lapangan.',
            'payment_date' => null,
        ]);
    }
}
