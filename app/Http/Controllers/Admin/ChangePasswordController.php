<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ChangePasswordController extends Controller
{
    /**
     * Show the change password form for admin
     */
    public function show()
    {
        return view('admin.auth.change-password');
    }

    /**
     * Update the admin password
     */
    public function update(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.'
            ]);
        }

        // Update password
        $userId = Auth::id();
        User::where('id', $userId)->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('admin.change-password.show')
            ->with('success', 'Password changed successfully!');
    }
}
