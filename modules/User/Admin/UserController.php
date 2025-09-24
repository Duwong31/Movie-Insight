<?php

namespace Modules\User\Admin; 

use App\Http\Controllers\Controller; 
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role', 2)->orderBy('created_at', 'desc')->paginate(15); 

        // Trả về view, truyền biến $users
        // 'User::admin.index' nghĩa là: namespace 'User', thư mục 'admin', file 'index.blade.php'
        return view('User::admin.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('User::admin.create'); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Prepare user data
        $userData = [
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password), // Nhớ hash password
            'role' => 2, // Mặc định không phải admin
        ];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = 'profile_admin_' . time() . '.' . $image->getClientOriginalExtension();
            
            // Store image in storage/profile directory
            $image->move(storage_path('profile'), $imageName);
            $userData['profile_image'] = $imageName;
        }

        // Tạo user mới
        User::create($userData);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user) // Route model binding
    {
        // User đã được tự động fetch dựa vào ID trên URL
        return view('User::admin.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed', // Password có thể không thay đổi
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_profile_image' => 'nullable|boolean',
        ]);

        $data = $request->only(['fullname', 'email', 'phone']);
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
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
            $imageName = 'profile_admin_' . $user->id . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(storage_path('profile'), $imageName);
            $data['profile_image'] = $imageName;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}