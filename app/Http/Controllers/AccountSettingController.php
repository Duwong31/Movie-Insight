<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AccountSettingController extends Controller
{
    /**
     * Hiển thị trang cài đặt tài khoản của người dùng.
     * GET /account-settings
     */
    public function edit(Request $request)
    {
        $user = $request->user(); // Lấy người dùng đang đăng nhập
        
        return view('users.settings.account', compact('user'));
    }

    /**
     * Cập nhật thông tin tài khoản của người dùng.
     * PUT /account-settings
     */
    public function update(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:password',
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_profile_image' => 'nullable|boolean',
        ]);

        // Kiểm tra mật khẩu hiện tại nếu người dùng muốn đổi mật khẩu
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
            }
        }

        // Cập nhật thông tin
        $data = $request->only(['fullname', 'email', 'phone']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle profile image
        if ($request->boolean('remove_profile_image')) {
            // Remove existing profile image
            if ($user->profile_image) {
                $imagePath = storage_path('profile/' . $user->profile_image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $data['profile_image'] = null;
            }
        } elseif ($request->hasFile('profile_image')) {
            // Remove old image if exists
            if ($user->profile_image) {
                $oldImagePath = storage_path('profile/' . $user->profile_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            // Store new image
            $image = $request->file('profile_image');
            $imageName = 'profile_' . $user->id . '_' . time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = storage_path('profile');
            $image->move($destinationPath, $imageName);
            $data['profile_image'] = $imageName;
        }

        $user->update($data);

        return back()->with('success', 'Thông tin tài khoản đã được cập nhật thành công.');
    }
}
