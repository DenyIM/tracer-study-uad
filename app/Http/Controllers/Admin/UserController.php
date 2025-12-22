<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Alumni;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of alumni users.
     */
    public function alumniIndex(Request $request)
    {
        $query = Alumni::with('user')->latest();
        
        // Filter by study program
        if ($request->has('study_program') && $request->study_program) {
            $query->where('study_program', $request->study_program);
        }
        
        // Filter by graduation year
        if ($request->has('graduation_year') && $request->graduation_year) {
            $query->whereYear('graduation_date', $request->graduation_year);
        }
        
        $alumni = $query->paginate(10);
        
        // Get unique study programs for filter dropdown
        $studyPrograms = Alumni::distinct('study_program')->pluck('study_program');
        $graduationYears = Alumni::selectRaw('YEAR(graduation_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
            
        return view('admin.views.users.alumni.index', compact('alumni', 'studyPrograms', 'graduationYears'));
    }

    /**
     * Display the specified alumni.
     */
    public function alumniShow(Alumni $alumni)
    {
        $alumni->load('user');
        return view('admin.views.users.alumni.show', compact('alumni'));
    }

    /**
     * Show the form for editing the specified alumni.
     */
    public function alumniEdit(Alumni $alumni)
    {
        $alumni->load('user');
        $studyPrograms = ['Teknik Informatika', 'Sistem Informasi', 'Manajemen', 'Akuntansi'];
        return view('admin.views.users.alumni.edit', compact('alumni', 'studyPrograms'));
    }

    /**
     * Update the specified alumni in storage.
     */
    public function alumniUpdate(Request $request, Alumni $alumni)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($alumni->user_id)],
            'nim' => 'required|string|max:20',
            'study_program' => 'required|string|max:100',
            'date_of_birth' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'graduation_date' => 'required|date',
            'npwp' => 'nullable|string|max:50',
        ]);

        // Update user email
        $alumni->user->update(['email' => $validated['email']]);

        // Update alumni data
        $alumni->update([
            'fullname' => $validated['fullname'],
            'nim' => $validated['nim'],
            'study_program' => $validated['study_program'],
            'date_of_birth' => $validated['date_of_birth'],
            'phone' => $validated['phone'],
            'graduation_date' => $validated['graduation_date'],
            'npwp' => $validated['npwp'],
        ]);

        return redirect()->route('admin.views.users.alumni.show', $alumni->id)
            ->with('success', 'Data alumni berhasil diperbarui');
    }

    /**
     * Remove the specified alumni from storage.
     */
    public function alumniDestroy(Alumni $alumni)
    {
        // Delete associated user
        $user = $alumni->user;
        $alumni->delete();
        $user->delete();

        return redirect()->route('admin.views.users.alumni.index')
            ->with('success', 'Alumni berhasil dihapus');
    }

    /**
     * Display a listing of admin users.
     */
    public function adminIndex()
    {
        $admins = Admin::with('user')->latest()->get();
        return view('admin.views.users.admin.index', compact('admins'));
    }

    /**
     * Display the specified admin.
     */
    public function adminShow(Admin $admin)
    {
        $admin->load('user');
        return view('admin.views.users.admin.show', compact('admin'));
    }

    /**
     * Show the form for creating a new admin.
     */
    public function adminCreate()
    {
        return view('admin.views.users.admin.create');
    }

    /**
     * Store a newly created admin in storage.
     */
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'job_title' => 'required|string|max:100',
        ]);

        // Create user
        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create admin profile
        $admin = Admin::create([
            'user_id' => $user->id,
            'fullname' => $validated['fullname'],
            'phone' => $validated['phone'],
            'job_title' => $validated['job_title'],
        ]);

        return redirect()->route('admin.views.users.admin.index')
            ->with('success', 'Admin berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified admin.
     */
    public function adminEdit(Admin $admin)
    {
        $admin->load('user');
        return view('admin.views.users.admin.edit', compact('admin'));
    }

    /**
     * Update the specified admin in storage.
     */
    public function adminUpdate(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($admin->user_id)],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'job_title' => 'required|string|max:100',
        ]);

        // Update user
        $userData = ['email' => $validated['email']];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($validated['password']);
        }
        $admin->user->update($userData);

        // Update admin
        $admin->update([
            'fullname' => $validated['fullname'],
            'phone' => $validated['phone'],
            'job_title' => $validated['job_title'],
        ]);

        return redirect()->route('admin.views.users.admin.show', $admin->id)
            ->with('success', 'Data admin berhasil diperbarui');
    }

    /**
     * Remove the specified admin from storage.
     */
    public function adminDestroy(Admin $admin)
    {
        // Delete associated user
        $user = $admin->user;
        $admin->delete();
        $user->delete();

        return redirect()->route('admin.views.users.admin.index')
            ->with('success', 'Admin berhasil dihapus');
    }

    /**
     * Display dashboard.
     */
    public function dashboard()
    {
        $alumniCount = Alumni::count();
        $adminCount = Admin::count();
        $verifiedAlumniCount = User::where('role', 'alumni')
            ->whereNotNull('email_verified_at')
            ->count();

        return view('admin.views.dashboard.index', compact('alumniCount', 'adminCount', 'verifiedAlumniCount'));
    }
}