<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of users (admin only)
     */
    public function index(): View
    {
        $users = User::paginate(15);
        return view('users.index', compact('users'));
    }

    /**
     * Show user details
     */
    public function show(User $user): View
    {
        $paidTransactions = $user->transactions()->whereIn('status', ['completed', 'paid']);

        $paidTransactionCount = (clone $paidTransactions)->count();
        $paidTransactionRevenue = (clone $paidTransactions)->sum('amount');

        return view('users.show', compact('user', 'paidTransactionCount', 'paidTransactionRevenue'));
    }

    /**
     * Update user status (admin only)
     */
    public function updateStatus(User $user, $status)
    {
        $user->update(['status' => $status]);
        return back()->with('success', 'Status pengguna berhasil diubah');
    }

    /**
     * Delete user (admin only)
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus');
    }
}
