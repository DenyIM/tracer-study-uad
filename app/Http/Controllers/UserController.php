<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware(function ($request, $next) {
            $admin = auth('admin')->user();
            if (!$admin || ($admin->role !== 'super_admin' && $admin->role !== 'admin')) {
                abort(403, 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.');
            }
            return $next($request);
        });
    }

    /**
     * Menampilkan daftar alumni dengan filter
     */
    public function index(Request $request)
    {
        // Mulai query dengan eager loading
        $query = User::query();

        // Filter berdasarkan program studi
        if ($request->filled('program_studi')) {
            $query->where('program_studi', $request->program_studi);
        }

        // Filter berdasarkan tahun lulus
        if ($request->filled('tahun_lulus')) {
            $query->whereYear('tanggal_lulus', $request->tahun_lulus);
        }

        // Filter berdasarkan tanggal registrasi
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%")
                    ->orWhere('program_studi', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        // Validasi field untuk sorting
        $allowedSortFields = ['nama_lengkap', 'email', 'nim', 'program_studi', 'tanggal_lulus', 'created_at'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }

        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = $request->get('per_page', 20);
        $users = $query->paginate($perPage)->withQueryString();

        // Untuk filter dropdown
        $programStudiList = User::select('program_studi')
            ->distinct()
            ->orderBy('program_studi')
            ->pluck('program_studi');

        $tahunLulusList = User::select(DB::raw('YEAR(tanggal_lulus) as tahun'))
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('admin.users.index', compact('users', 'programStudiList', 'tahunLulusList'));
    }

    /**
     * Menampilkan detail alumni
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        // Hitung statistik sederhana
        $stats = [
            'terdaftar_sejak' => $user->created_at->diffForHumans(),
            'lama_lulus' => $user->lama_lulus ? $user->lama_lulus . ' tahun' : '-',
            'status_email' => $user->email_verified_at ? 'Terverifikasi' : 'Belum diverifikasi',
        ];

        // Aktivitas login terakhir (dari created_at sebagai simulasi)
        $lastLogin = $user->updated_at->diffForHumans();

        return view('admin.users.show', compact('user', 'stats', 'lastLogin'));
    }

    /**
     * Menampilkan form edit alumni
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        $programStudiList = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Manajemen Informatika',
            'Teknik Komputer',
            'Ilmu Komputer',
            'Teknologi Informasi',
            'Sistem Informasi Akuntansi',
            'Manajemen',
            'Akuntansi',
            'Ekonomi',
            'Psikologi',
            'Hukum',
            'Kedokteran',
            'Farmasi',
            'Teknik Sipil',
            'Teknik Elektro',
            'Teknik Mesin',
            'Teknik Industri',
            'Arsitektur',
            'Ilmu Komunikasi',
            'Sastra Inggris',
            'Pendidikan',
            'Lainnya'
        ];

        return view('admin.users.edit', compact('user', 'programStudiList'));
    }

    /**
     * Update data alumni
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'nim' => 'required|string|max:20|unique:users,nim,' . $id,
            'program_studi' => 'required|string|max:100',
            'tanggal_lulus' => 'required|date',
            'npwp' => 'nullable|string|max:20',
            'no_hp' => 'required|string|max:20',
        ]);

        // Format tanggal lulus
        $validated['tanggal_lulus'] = Carbon::parse($validated['tanggal_lulus'])->format('Y-m-d');

        // Update data
        $user->update($validated);

        // Log activity manual (karena tidak ada spatie activity log)
        $this->logActivity(auth('admin')->user(), 'update', $user);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Data alumni berhasil diperbarui.');
    }

    /**
     * Hapus alumni (hard delete karena tidak ada soft delete)
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Log activity sebelum delete
        $this->logActivity(auth('admin')->user(), 'delete', $user);

        // Hapus user
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Alumni berhasil dihapus.');
    }

    /**
     * Bulk delete alumni
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $count = User::whereIn('id', $request->user_ids)->delete();

        return response()->json([
            'success' => true,
            'message' => $count . ' alumni berhasil dihapus.'
        ]);
    }

    /**
     * Export data alumni ke Excel
     */
    public function export(Request $request)
    {
        $filename = 'alumni_uad_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new UsersExport($request), $filename);
    }

    /**
     * Fungsi untuk log aktivitas sederhana
     */
    private function logActivity($admin, $action, $user)
    {
        // Simpan log ke database atau file
        $logData = [
            'timestamp' => now(),
            'admin_id' => $admin->id,
            'admin_name' => $admin->name,
            'action' => $action,
            'user_id' => $user->id,
            'user_name' => $user->nama_lengkap,
            'details' => "Admin {$admin->name} {$action}d alumni {$user->nama_lengkap} (NIM: {$user->nim})"
        ];

        // Simpan ke database jika ada tabel activity_logs
        // Atau simpan ke file log
        \Log::channel('admin')->info('Admin Action', $logData);
    }
}
