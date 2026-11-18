<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class VirtualAccountController extends Controller
{
    /**
     * Show Virtual Account payment page for a transaction.
     */
    public function show(Transaction $transaction)
    {
        if ($transaction->status === 'paid' || $transaction->status === 'completed') {
            return redirect()->route('transactions.show', $transaction->id)->with('success', 'Transaksi telah dibayar.');
        }

        return view('payments.virtual_account', compact('transaction'));
    }

    /**
     * Initiate Virtual Account payment (mock).
     */
    public function initiate(Request $request, Transaction $transaction)
    {
        $data = $request->validate([
            'bank' => 'required|in:bca,mandiri,bri',
        ]);

        $bankName = strtoupper($data['bank']);
        $transaction->payment_method = 'VA ' . $bankName;
        $transaction->status = 'pending';
        $transaction->save();

        $vaNumber = match ($data['bank']) {
            'bca' => '1234567890',
            'mandiri' => '9001234567890',
            'bri' => '9876543210',
            default => '0000000000',
        };

        return response()->json([
            'ok' => true,
            'bank' => $data['bank'],
            'va_number' => $vaNumber,
            'instruction' => 'Lakukan pembayaran ke Virtual Account ' . $bankName . ' dengan nomor ' . $vaNumber,
        ]);
    }

    /**
     * Webhook to mark Virtual Account as PAID (simulate).
     */
    public function webhook(Request $request)
    {
        $data = $request->validate([
            'transaction_code' => 'required|string',
            'status' => 'required|string',
        ]);

        $transaction = Transaction::where('transaction_code', $data['transaction_code'])->first();
        if (! $transaction) {
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

    /**
     * Status endpoint.
     */
    public function status(Transaction $transaction)
    {
        return response()->json([
            'status' => $transaction->status,
            'payment_method' => $transaction->payment_method,
        ]);
    }
}
