<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Alumni;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        // Data statistik untuk sidebar
        $totalAlumni = User::where('role', 'alumni')->count();
        $activeAlumni = User::where('role', 'alumni')
            ->whereNotNull('email_verified_at')
            ->where('last_login_at', '>=', now()->subMonths(3))
            ->count();

        // Data untuk notifikasi (contoh, sesuaikan dengan model Anda)
        $unreadMessages = 0; // Ganti dengan query dari model Message
        $unreadNotifications = auth()->user()->unreadNotifications()->count();
        $newAlumniCount = User::where('role', 'alumni')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $recentMessages = []; // Ganti dengan data aktual
        $recentNotifications = auth()->user()->notifications()->take(5)->get();
        $surveyResponses = 0; // Ganti dengan query dari model Response

        return view('admin.dashboard.index', compact(
            'totalAlumni',
            'activeAlumni',
            'unreadMessages',
            'unreadNotifications',
            'newAlumniCount',
            'recentMessages',
            'recentNotifications',
            'surveyResponses'
        ));
    }
}
