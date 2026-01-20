<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\PasswordUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Alumni;
use App\Models\AnswerQuestion;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $alumni = $user->alumni;
        
        // Hitung ranking dan points yang benar
        $ranking = $this->calculateRanking($alumni);
        $totalPoints = $alumni->points ?? 0;
        
        return view('profile.profile', [
            'user' => $user,
            'alumni' => $alumni,
            'ranking' => $ranking,
            'totalPoints' => $totalPoints
        ]);
    }

    /**
     * Hitung ranking alumni berdasarkan points
     */
    private function calculateRanking($alumni): int
    {
        if (!$alumni) return 0;
        
        $currentPoints = $alumni->points ?? 0;
        
        // PERBAIKAN: Ganti 'alumni' menjadi 'alumnis'
        // Hitung berapa banyak alumni yang memiliki points lebih tinggi
        $higherCount = DB::table('alumnis')  // <-- PERBAIKAN DI SINI
            ->where('points', '>', $currentPoints)
            ->count();
        
        $ranking = $higherCount + 1;
        
        // Total peserta
        $totalParticipants = DB::table('alumnis')->count();  // <-- PERBAIKAN DI SINI
        
        // Jika points 0, ranking terakhir
        if ($currentPoints <= 0 && $totalParticipants > 0) {
            $ranking = $totalParticipants;
        }
        
        return $ranking;
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        try {
            // Validasi manual
            $validator = Validator::make($request->all(), [
                'fullname' => 'required|string|max:255',
                'nim' => 'required|string|max:20',
                'study_program' => 'required|string|max:100',
                'graduation_year' => 'required|integer|min:2000|max:' . (date('Y') + 5),
                'phone' => 'required|string|max:20',
                'npwp' => 'nullable|string|max:50',
            ], [
                'fullname.required' => 'Nama lengkap harus diisi.',
                'nim.required' => 'NIM harus diisi.',
                'study_program.required' => 'Jurusan/prodi harus diisi.',
                'graduation_year.required' => 'Tahun lulus harus diisi.',
                'graduation_year.integer' => 'Tahun lulus harus berupa angka.',
                'graduation_year.min' => 'Tahun lulus minimal 2000.',
                'graduation_year.max' => 'Tahun lulus maksimal ' . (date('Y') + 5),
                'phone.required' => 'Nomor HP harus diisi.',
            ]);

            if ($validator->fails()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasi gagal',
                        'errors' => $validator->errors()
                    ], 422);
                }
                return back()->withErrors($validator)->withInput();
            }

            $user = $request->user();
            $alumni = $user->alumni;
            
            if (!$alumni) {
                // Jika alumni tidak ada, buat baru
                $alumni = Alumni::create([
                    'user_id' => $user->id,
                    'fullname' => $request->fullname,
                    'nim' => $request->nim,
                    'study_program' => $request->study_program,
                    'phone' => $request->phone,
                    'npwp' => $request->npwp,
                    'graduation_date' => $request->graduation_year . '-01-01',
                ]);
            } else {
                // Update alumni data
                $alumni->update([
                    'fullname' => $request->fullname,
                    'nim' => $request->nim,
                    'study_program' => $request->study_program,
                    'phone' => $request->phone,
                    'npwp' => $request->npwp,
                    'graduation_date' => $request->graduation_year . '-01-01',
                ]);
            }

            // Hitung ranking baru setelah update
            $ranking = $this->calculateRanking($alumni);
            $totalPoints = $alumni->points ?? 0;

            // Response untuk AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profil berhasil diperbarui!',
                    'data' => [
                        'fullname' => $alumni->fresh()->fullname,
                        'nim' => $alumni->fresh()->nim,
                        'study_program' => $alumni->fresh()->study_program,
                        'phone' => $alumni->fresh()->phone,
                        'npwp' => $alumni->fresh()->npwp,
                        'graduation_year' => date('Y', strtotime($alumni->fresh()->graduation_date)),
                        'ranking' => $ranking,
                        'points' => $totalPoints,
                    ]
                ]);
            }

            return Redirect::route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
            
        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        // Validasi manual
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini harus diisi.',
            'new_password.required' => 'Password baru harus diisi.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $user = $request->user();
        
        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['current_password' => ['Password saat ini salah.']]
                ], 422);
            }
            return back()->withErrors(['current_password' => 'Password saat ini salah.'])->withInput();
        }
        
        // Update password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
        
        // Response untuk AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah!'
            ]);
        }
        
        return back()->with('success', 'Password berhasil diubah!');
    }

    /**
     * Update user theme preference.
     */
    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark',
        ]);
        
        // Simpan tema di database untuk user
        $user = $request->user();
        $user->update([
            'theme_preference' => $request->theme,
        ]);
        
        // Simpan juga di session untuk immediate effect
        session(['theme' => $request->theme]);
        
        return response()->json([
            'success' => true,
            'message' => 'Tema berhasil diubah!'
        ]);
    }

    /**
     * Upload profile photo.
     */
    public function uploadPhoto(Request $request)
    {
        try {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);

            $user = $request->user();
            
            // Upload foto
            if ($request->hasFile('photo')) {
                // Hapus foto lama jika ada
                if ($user->pp_url && Storage::disk('public')->exists(str_replace('storage/', '', $user->pp_url))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $user->pp_url));
                }
                
                // Simpan foto baru
                $path = $request->file('photo')->store('profile-photos', 'public');
                
                // Update path di database - HANYA nama file/folder tanpa 'storage/'
                $user->pp_url = $path; // Simpan hanya path relatif
                $user->save();
                
                // Generate URL lengkap untuk response
                $photoUrl = asset('storage/' . $path);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Foto profil berhasil diubah!',
                    'photo_url' => $photoUrl
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada file yang diupload.'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload foto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}