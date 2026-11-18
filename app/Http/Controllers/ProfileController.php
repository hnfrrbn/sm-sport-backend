<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form
     */
    public function edit(): View
    {
        return view('profile.edit');
    }

    /**
     * Update the user's profile
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        if (! $user instanceof \App\Models\User) {
            abort(403, 'Unauthorized');
        }

        $oldValues = $user->toArray();

        $user->fill($request->validated());
        $user->save();

        ActivityLog::log(
            $user,
            'update',
            'User',
            $user->id,
            'Updated profile',
            $oldValues,
            $user->toArray()
        );

        return back()->with('success', 'Profil berhasil diubah');
    }
}
