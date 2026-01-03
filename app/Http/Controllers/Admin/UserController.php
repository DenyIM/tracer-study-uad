<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Alumni;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

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
            'phone' => 'required|string|max:20',
            'graduation_date' => 'required|date',
            'npwp' => 'nullable|string|max:50',
            'ranking' => 'nullable|integer|min:1',
            'points' => 'nullable|integer|min:0',
            'email_verified' => 'nullable|boolean',
        ]);

        // Update user email
        $userData = ['email' => $validated['email']];
        
        // Update email verification status
        if ($request->has('email_verified')) {
            $userData['email_verified_at'] = now();
        } else {
            $userData['email_verified_at'] = null;
        }
        
        $alumni->user->update($userData);

        // Update alumni data
        $alumni->update([
            'fullname' => $validated['fullname'],
            'nim' => $validated['nim'],
            'study_program' => $validated['study_program'],
            'phone' => $validated['phone'],
            'graduation_date' => $validated['graduation_date'],
            'npwp' => $validated['npwp'],
            'ranking' => $validated['ranking'],
            'points' => $validated['points'],
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
     * Show the form for creating a new alumni.
     */
    public function alumniCreate()
    {
        $studyPrograms = ['Teknik Informatika', 'Sistem Informasi', 'Manajemen', 'Akuntansi'];
        return view('admin.views.users.alumni.create', compact('studyPrograms'));
    }

    /**
     * Store a newly created alumni in storage.
     */
    public function alumniStore(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nim' => 'required|string|max:20|unique:alumnis,nim',
            'study_program' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'graduation_date' => 'required|date',
            'npwp' => 'nullable|string|max:50',
            'ranking' => 'nullable|integer|min:1',
            'points' => 'nullable|integer|min:0',
        ]);

        // Create user with default password
        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make('password123'), // Default password
            'role' => 'alumni',
            'email_verified_at' => now(), // Auto verify for admin-created accounts
        ]);

        // Create alumni profile
        Alumni::create([
            'user_id' => $user->id,
            'fullname' => $validated['fullname'],
            'nim' => $validated['nim'],
            'study_program' => $validated['study_program'],
            'phone' => $validated['phone'],
            'graduation_date' => $validated['graduation_date'],
            'npwp' => $validated['npwp'],
            'ranking' => $validated['ranking'],
            'points' => $validated['points'],
        ]);

        return redirect()->route('admin.views.users.alumni.index')
            ->with('success', 'Alumni berhasil ditambahkan dengan password default: password123');
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
            'phone' => 'required|string|max:20',
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
        Admin::create([
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
            'phone' => 'required|string|max:20',
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
        // Check if trying to delete last admin
        $adminCount = Admin::count();
        if ($adminCount <= 1) {
            return redirect()->route('admin.views.users.admin.index')
                ->with('error', 'Tidak dapat menghapus admin terakhir');
        }

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

        // Get recent alumni (last 5)
        $recentAlumni = Alumni::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Get statistics for charts
        $studyProgramStats = Alumni::select('study_program')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('study_program')
            ->get();

        $graduationYearStats = Alumni::selectRaw('YEAR(graduation_date) as year')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        return view('admin.views.dashboard.index', compact(
            'alumniCount', 
            'adminCount', 
            'verifiedAlumniCount',
            'recentAlumni',
            'studyProgramStats',
            'graduationYearStats'
        ));
    }

    /**
     * Verify alumni email manually.
     */
    public function verifyAlumniEmail(Alumni $alumni)
    {
        $alumni->user->update(['email_verified_at' => now()]);
        
        return redirect()->back()
            ->with('success', 'Email alumni berhasil diverifikasi');
    }

    /**
     * Reset alumni password.
     */
    public function resetAlumniPassword(Alumni $alumni)
    {
        $alumni->user->update(['password' => Hash::make('password123')]);
        
        return redirect()->back()
            ->with('success', 'Password alumni berhasil direset ke: password123');
    }
}