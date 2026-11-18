<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions (admin only)
     */
    public function index(): View
    {
        $transactions = Transaction::with(['user', 'reservation'])->paginate(15);
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show user transactions
     */
    public function myTransactions(): View
    {
        $user = Auth::user();

        if (! $user instanceof \App\Models\User) {
            abort(403, 'Unauthorized');
        }

        $transactions = $user->transactions()->with('reservation')->paginate(10);
        return view('transactions.my-transactions', compact('transactions'));
    }

    /**
     * Show transaction details
     */
    public function show(Transaction $transaction): View
    {
        $user = Auth::user();

        if (! $user instanceof \App\Models\User) {
            abort(403, 'Unauthorized');
        }

        if ($user->role !== 'admin' && $transaction->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $transaction->load(['user', 'reservation.field']);

        return view('transactions.show', compact('transaction'));
    }

    /**
     * Cancel a pending transaction for the authenticated owner or admin.
     */
    public function cancel(Transaction $transaction)
    {
        $user = Auth::user();

        if (! $user instanceof \App\Models\User) {
            abort(403, 'Unauthorized');
        }

        if ($user->role !== 'admin' && $transaction->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        if (! in_array($transaction->status, ['pending'])) {
            return back()->with('error', 'Hanya pembayaran dengan status pending dapat dibatalkan.');
        }

        $transaction->status = 'cancelled';
        $transaction->payment_date = null;
        $transaction->save();

        ActivityLog::log(
            $user,
            'update',
            'Transaction',
            $transaction->id,
            'Cancelled payment for transaction: ' . $transaction->transaction_code
        );

        return back()->with('success', 'Pembayaran berhasil dibatalkan. Anda dapat membuat pembayaran baru jika diperlukan.');
    }

    /**
     * Upload payment proof for a transaction
     */
    public function uploadProof(\Illuminate\Http\Request $request, Transaction $transaction)
    {
        $user = Auth::user();
        if (! $user instanceof \App\Models\User) {
            abort(403, 'Unauthorized');
        }

        if ($user->role !== 'admin' && $transaction->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
        ]);

        $file = $request->file('payment_proof');
        $filename = 'proof_' . $transaction->id . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $targetDir = public_path('uploads/payment_proofs');
        if (! file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $file->move($targetDir, $filename);

        $transaction->payment_proof = 'uploads/payment_proofs/' . $filename;
        $transaction->payment_proof_uploaded_at = now();
        if ($transaction->status !== 'paid') {
            $transaction->status = 'pending';
        }
        $transaction->save();

        ActivityLog::log(
            $user,
            'upload',
            'Transaction',
            $transaction->id,
            'Uploaded payment proof for transaction: ' . $transaction->transaction_code
        );

        return back()->with('success', 'Bukti pembayaran berhasil diunggah! Mohon tunggu verifikasi admin.');
    }
}
