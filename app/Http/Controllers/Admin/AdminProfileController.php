<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    /**
     * Display the admin profile page.
     */
    public function index()
    {
        $admin = Admin::with('user')->where('user_id', Auth::id())->firstOrFail();
        return view('admin.views.profile.index', compact('admin'));
    }

    /**
     * Update admin profile information.
     */
    public function updateProfile(Request $request)
    {
        $admin = Admin::where('user_id', Auth::id())->firstOrFail();
        $user = Auth::user();
        
        // Validasi tanpa job_title karena tidak bisa diubah di profile
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($admin->user_id),
            ],
            'phone' => 'required|string|max:20',
        ]);

        // Update user email
        $admin->user->update([
            'email' => $validated['email'],
        ]);

        // Update admin profile (tanpa mengubah job_title)
        $admin->update([
            'fullname' => $validated['fullname'],
            'phone' => $validated['phone'],
        ]);

        return redirect()->route('admin.profile')
            ->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Change admin password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Password saat ini tidak sesuai');
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.profile')
            ->with('success', 'Password berhasil diubah');
    }

    /**
     * Upload profile photo.
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Delete old photo if exists
        if ($user->pp_url && Storage::exists($user->pp_url)) {
            Storage::delete($user->pp_url);
        }

        // Store new photo
        $path = $request->file('profile_photo')->store('profile-photos', 'public');

        // Update user photo URL
        $user->update([
            'pp_url' => $path,
        ]);

        return redirect()->route('admin.profile')
            ->with('success', 'Foto profil berhasil diupload');
    }

    /**
     * Delete profile photo.
     */
    public function deletePhoto(Request $request)
    {
        $user = Auth::user();

        if ($user->pp_url && Storage::exists($user->pp_url)) {
            Storage::delete($user->pp_url);
        }

        $user->update([
            'pp_url' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil dihapus'
        ]);
    }
}