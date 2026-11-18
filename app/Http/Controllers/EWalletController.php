<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\PaymentAttempt;
use Illuminate\Http\Request;

class EWalletController extends Controller
{
    /**
     * Show E-Wallet payment page (choose provider)
     */
    public function show(Transaction $transaction)
    {
        // ensure transaction exists and is pending
        if ($transaction->status === 'paid') {
            return redirect()->route('transactions.show', $transaction->id)->with('success', 'Transaksi telah dibayar.');
        }

        return view('payments.ewallet', compact('transaction'));
    }

    /**
     * Initiate e-wallet payment (mock)
     */
    public function initiate(Request $request, Transaction $transaction)
    {
        $data = $request->validate([
            'provider' => 'required|in:gopay,shopeepay',
        ]);

        $transaction->payment_method = strtoupper($data['provider']);
        $transaction->status = 'pending';
        $transaction->save();

        // return a mock deeplink or instruction
        $deeplink = url('/payments/ewallet/deeplink/' . $transaction->id . '/' . $data['provider']);

        // create a payment attempt record to track this try
        $attempt = PaymentAttempt::create([
            'transaction_id' => $transaction->id,
            'provider' => $data['provider'],
            'method' => 'ewallet',
            'status' => 'pending',
            'deeplink' => $deeplink,
            'payload' => null,
        ]);

        return response()->json(['ok' => true, 'deeplink' => $deeplink, 'attempt_id' => $attempt->id]);
    }

    /**
     * Simulated deeplink page (show QR or instruction to open app)
     */
    public function deeplink(Transaction $transaction, $provider)
    {
        $providerName = strtoupper($provider);
        $transaction->payment_method = $providerName;
        $transaction->save();

        $instruction = "Buka aplikasi {$providerName} dan selesaikan pembayaran. Kode transaksi: " . $transaction->transaction_code;

        return view('payments.ewallet_deeplink', compact('transaction', 'provider', 'instruction'));
    }

    /**
     * Webhook to mark e-wallet as PAID (simulate)
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
     * Status endpoint
     */
    public function status(Transaction $transaction)
    {
        return response()->json(['status' => $transaction->status, 'payment_method' => $transaction->payment_method]);
    }
}
