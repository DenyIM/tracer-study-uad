<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumniRegistrationController extends Controller
{
    public function showForm()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        $alumni = Alumni::where('user_id', $user->id)->first();
        
        if (!$alumni) {
            // Fallback: buat data alumni jika belum ada (seharusnya sudah dibuat di GoogleAuthController)
            $alumni = Alumni::create([
                'user_id' => $user->id,
                'fullname' => $user->name ?? 'Nama Belum Diisi',
                'nim' => null,
                'study_program' => null,
                'graduation_date' => null,
                'phone' => null,
                'npwp' => null,
                'is_data_complete' => false,
            ]);
        }
        
        // Jika data sudah lengkap, redirect ke main
        if ($alumni->is_data_complete) {
            return redirect()->route('main');
        }
        
        return view('auth.register-form', [
            'user' => $user,
            'alumni' => $alumni,
            'email_warning' => session('email_warning')
        ]);
    }
    
    public function submitForm(Request $request)
    {
        $user = Auth::user();
        $alumni = Alumni::where('user_id', $user->id)->firstOrFail();
        
        // Validasi input
        $request->validate([
            'fullname' => 'required|string|min:3|max:100',
            'study_program' => 'required|string',
            'graduation_date' => 'required|date',
            'phone' => 'required|string|min:10|max:15',
            'npwp' => 'nullable|string|max:20',
            'dataConsent' => 'required|accepted',
        ], [
            'dataConsent.required' => 'Anda harus menyetujui pernyataan data',
            'dataConsent.accepted' => 'Anda harus menyetujui pernyataan data',
        ]);
        
        // Update data alumni
        $alumni->update([
            'fullname' => $request->fullname,
            'study_program' => $request->study_program,
            'graduation_date' => $request->graduation_date,
            'phone' => $request->phone,
            'npwp' => $request->npwp,
            'is_data_complete' => true,
        ]);
        
        // Update nama user jika berbeda
        if ($user->name !== $request->fullname) {
            $user->update(['name' => $request->fullname]);
        }
        
        return redirect()->route('main')->with('success', 'Data berhasil disimpan!');
    }
}