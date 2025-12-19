<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Alumni;
use App\Models\Admin as AdminModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AlumniExport;

class UserController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('admin');
    // }

    /**
     * Menampilkan daftar alumni dengan filter
     */
    public function index(Request $request)
    {
        // Mulai query dengan eager loading untuk alumni
        $query = User::where('role', 'alumni')
            ->with('alumni');

        // Filter berdasarkan program studi
        if ($request->filled('study_program')) {
            $query->whereHas('alumni', function ($q) use ($request) {
                $q->where('study_program', $request->study_program);
            });
        }

        // Filter berdasarkan tahun lulus
        if ($request->filled('graduation_year')) {
            $query->whereHas('alumni', function ($q) use ($request) {
                $q->whereYear('graduation_date', $request->graduation_year);
            });
        }

        // Filter berdasarkan tanggal registrasi
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter berdasarkan verifikasi email
        if ($request->filled('email_verified')) {
            if ($request->email_verified == 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->email_verified == 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhereHas('alumni', function ($q) use ($search) {
                        $q->where('fullname', 'like', "%{$search}%")
                            ->orWhere('nim', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('study_program', 'like', "%{$search}%");
                    });
            });
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        // Validasi field untuk sorting
        $allowedSortFields = ['email', 'created_at', 'last_login_at'];

        // Sorting untuk relasi alumni
        if (in_array($sortField, ['fullname', 'nim', 'study_program', 'graduation_date'])) {
            if ($sortField === 'fullname') {
                $query->join('alumnis', 'users.id', '=', 'alumnis.user_id')
                    ->orderBy('alumnis.fullname', $sortDirection)
                    ->select('users.*');
            } elseif ($sortField === 'nim') {
                $query->join('alumnis', 'users.id', '=', 'alumnis.user_id')
                    ->orderBy('alumnis.nim', $sortDirection)
                    ->select('users.*');
            } elseif ($sortField === 'study_program') {
                $query->join('alumnis', 'users.id', '=', 'alumnis.user_id')
                    ->orderBy('alumnis.study_program', $sortDirection)
                    ->select('users.*');
            } elseif ($sortField === 'graduation_date') {
                $query->join('alumnis', 'users.id', '=', 'alumnis.user_id')
                    ->orderBy('alumnis.graduation_date', $sortDirection)
                    ->select('users.*');
            }
        } elseif (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->get('per_page', 20);
        $users = $query->paginate($perPage)->withQueryString();

        // Untuk filter dropdown
        $programStudiList = Alumni::select('study_program')
            ->distinct()
            ->orderBy('study_program')
            ->pluck('study_program');

        $graduationYearList = Alumni::select(DB::raw('YEAR(graduation_date) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('admin.users.index', compact('users', 'programStudiList', 'graduationYearList'));
    }

    /**
     * Menampilkan daftar admin
     */
    public function adminIndex(Request $request)
    {
        $query = User::where('role', 'admin')
            ->with('admin');

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhereHas('admin', function ($q) use ($search) {
                        $q->where('fullname', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('job_title', 'like', "%{$search}%");
                    });
            });
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        if ($sortField === 'fullname') {
            $query->join('admins', 'users.id', '=', 'admins.user_id')
                ->orderBy('admins.fullname', $sortDirection)
                ->select('users.*');
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $perPage = $request->get('per_page', 20);
        $admins = $query->paginate($perPage)->withQueryString();

        return view('admin.users.admins', compact('admins'));
    }

    /**
     * Menampilkan detail user (alumni atau admin)
     */
    public function show($id)
    {
        $user = User::with(['alumni', 'admin'])->findOrFail($id);

        // Hitung statistik berdasarkan role
        $stats = [
            'registered_since' => $user->created_at->diffForHumans(),
            'email_status' => $user->email_verified_at ? 'Verified' : 'Unverified',
            'last_login' => $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never',
        ];

        if ($user->isAlumni() && $user->alumni) {
            $stats['years_since_graduation'] = $user->alumni->graduation_date
                ? Carbon::parse($user->alumni->graduation_date)->diffInYears(now()) . ' years'
                : '-';
            $stats['age'] = $user->alumni->age ?? '-';
        }

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Menampilkan form edit user
     */
    public function edit($id)
    {
        $user = User::with(['alumni', 'admin'])->findOrFail($id);

        $programStudiList = [
            'Informatika',
            'Sistem Informasi',
            'Teknik Elektro',
            'Teknik Mesin',
            'Teknik Industri',
            'Teknik Sipil',
            'Arsitektur',
            'Akuntansi',
            'Manajemen',
            'Ilmu Komunikasi',
            'Psikologi',
            'Hukum',
            'Kedokteran',
            'Farmasi',
            'Pendidikan',
            'Lainnya'
        ];

        return view('admin.users.edit', compact('user', 'programStudiList'));
    }

    /**
     * Update data user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->isAlumni()) {
            $validated = $request->validate([
                'email' => 'required|email|unique:users,email,' . $id,
                'fullname' => 'required|string|max:255',
                'nim' => 'required|string|max:20|unique:alumnis,nim,' . $user->alumni->id,
                'study_program' => 'required|string|max:100',
                'graduation_date' => 'required|date',
                'npwp' => 'nullable|string|max:50',
                'phone' => 'required|string|max:20',
                'date_of_birth' => 'required|date',
            ]);

            // Update user
            $user->update([
                'email' => $validated['email'],
            ]);

            // Update alumni
            $user->alumni->update([
                'fullname' => $validated['fullname'],
                'nim' => $validated['nim'],
                'study_program' => $validated['study_program'],
                'graduation_date' => Carbon::parse($validated['graduation_date'])->format('Y-m-d'),
                'npwp' => $validated['npwp'],
                'phone' => $validated['phone'],
                'date_of_birth' => Carbon::parse($validated['date_of_birth'])->format('Y-m-d'),
            ]);
        } elseif ($user->isAdmin()) {
            $validated = $request->validate([
                'email' => 'required|email|unique:users,email,' . $id,
                'fullname' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'job_title' => 'required|string|max:100',
            ]);

            // Update user
            $user->update([
                'email' => $validated['email'],
            ]);

            // Update admin
            $user->admin->update([
                'fullname' => $validated['fullname'],
                'phone' => $validated['phone'],
                'job_title' => $validated['job_title'],
            ]);
        }

        // Log activity
        // \Log::channel('admin')->info('User updated', [
        //     'admin_id' => auth()->id(),
        //     'user_id' => $user->id,
        //     'action' => 'update',
        // ]);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User data updated successfully.');
    }

    /**
     * Hapus user 
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Log activity sebelum delete
        // \Log::channel('admin')->info('User deleted', [
        //     'admin_id' => auth()->id(),
        //     'user_id' => $user->id,
        //     'user_email' => $user->email,
        //     'action' => 'delete',
        // ]);

        // Hapus user (akan otomatis hapus alumni/admin karena cascade)
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Bulk delete users
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $count = User::whereIn('id', $request->user_ids)->delete();

        // Log bulk delete
        // \Log::channel('admin')->info('Bulk users deleted', [
        //     'admin_id' => auth()->id(),
        //     'count' => $count,
        //     'user_ids' => $request->user_ids,
        // ]);

        return response()->json([
            'success' => true,
            'message' => $count . ' users deleted successfully.'
        ]);
    }

    /**
     * Export data alumni ke Excel
     */
    public function exportAlumni(Request $request)
    {
        $filename = 'alumni_export_' . date('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new AlumniExport($request), $filename);
    }

    /**
     * Tampilkan form tambah admin
     */
    public function createAdmin()
    {
        return view('admin.users.create-admin');
    }

    /**
     * Simpan admin baru
     */
    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'fullname' => 'required|string|max:255',
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
        AdminModel::create([
            'user_id' => $user->id,
            'fullname' => $validated['fullname'],
            'phone' => $validated['phone'],
            'job_title' => $validated['job_title'],
        ]);

        // \Log::channel('admin')->info('New admin created', [
        //     'admin_id' => auth()->id(),
        //     'new_admin_id' => $user->id,
        //     'action' => 'create',
        // ]);

        return redirect()->route('admin.users.admins')
            ->with('success', 'Admin created successfully.');
    }

    /**
     * Verifikasi email user secara manual
     */
    public function verifyEmail($id)
    {
        $user = User::findOrFail($id);

        if (!$user->email_verified_at) {
            $user->update(['email_verified_at' => now()]);

            // \Log::channel('admin')->info('Email verified manually', [
            //     'admin_id' => auth()->id(),
            //     'user_id' => $user->id,
            // ]);

            return redirect()->back()->with('success', 'Email verified successfully.');
        }

        return redirect()->back()->with('info', 'Email already verified.');
    }

    /**
     * Reset password user
     */
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // \Log::channel('admin')->info('Password reset', [
        //     'admin_id' => auth()->id(),
        //     'user_id' => $user->id,
        // ]);

        return redirect()->back()->with('success', 'Password reset successfully.');
    }

    /**
     * Update status verifikasi email
     */
    public function updateEmailVerification(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->action == 'verify') {
            $user->update(['email_verified_at' => now()]);
            $message = 'Email verified successfully.';
        } else {
            $user->update(['email_verified_at' => null]);
            $message = 'Email verification removed.';
        }

        return redirect()->back()->with('success', $message);
    }
}
