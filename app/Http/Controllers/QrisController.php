<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class QrisController extends Controller
{
    /**
     * Show QRIS payment page for a transaction (mock)
     */
    public function show(Transaction $transaction)
    {
        // mark transaction as pending QRIS
        $transaction->payment_method = 'QRIS';
        $transaction->status = 'pending';
        $transaction->save();

        $qrData = urlencode('QRIS:' . $transaction->transaction_code);
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=400x400&data={$qrData}";

        return view('payments.qris', compact('transaction', 'qrUrl'));
    }

    /**
     * Polling endpoint to check payment status
     */
    public function status(Transaction $transaction)
    {
        return response()->json([
            'status' => $transaction->status,
            'payment_method' => $transaction->payment_method,
            'payment_date' => $transaction->payment_date,
        ]);
    }

    /**
     * Webhook endpoint (simulate provider callback)
     * Expect JSON: { transaction_code: 'TRX...', status: 'PAID' }
     */
    public function webhook(Request $request)
    {
        $data = $request->validate([
            'transaction_code' => 'required|string',
            'status' => 'required|string',
        ]);

        $transaction = Transaction::where('transaction_code', $data['transaction_code'])->first();
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        if (strtoupper($data['status']) === 'PAID') {
            $transaction->status = 'paid';
            $transaction->payment_date = now();
            $transaction->save();
        } else {
            $transaction->status = strtolower($data['status']);
            $transaction->save();
        }

        return response()->json(['ok' => true]);
    }
}
