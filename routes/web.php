<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\QuestionnaireController; // untuk memanggil controller

Route::get('/', function () {
    return view('admin.views.layouts.app');
});

// Route::get('/', function () {
//     return ['Laravel' => app()->version()];
// });

Route::get('/homepage-login', function () {
    return view('login');
});

Route::get('/homepage-register', function () {
    return view('register');
});

Route::get('/lupa-pass', function () {
    return view('lupa-pass');
});

Route::get('/go-to-kuesioner1', function () {
    return view('pages.page-kuesioner');
});

Route::get('/go-to-register-form', function () {
    return view('pages.register-form');
});

Route::get('/nav-kuesioner', function () {
    return view('pages.main-kuesioner');
});

Route::get('/nav-leaderboard', function () {
    return view('pages.leaderboard');
});

Route::get('/nav-forum', function () {
    return view('pages.forum');
});

Route::get('/nav-mentor', function () {
    return view('pages.mentor');
});

Route::get('/nav-lowongan', function () {
    return view('pages.list-lowongan');
});

Route::get('/nav-profile', function () {
    return view('pages.profile');
});

Route::get('/nav-bookmark', function () {
    return view('pages.bookmark');
});

Route::get('/logout', function () {
    return view('pages.homepage');
});

Route::get('/detlow', function () {
    return view('pages.lowongan');
});

Route::get('/lowongan/detail-lowongan', function () {
    return view('detail-lowongan');
})->name('lowongan.detail-lowongan');

Route::get('/back-to-list', function () {
    return view('pages.list-lowongan');
});

Route::get('/kuesioner', function () {
    return view('pages.page-kuesioner');
})->name('kuesioner.page');

Route::get('/section1-kuesioner', function () {
    return view('pages.section1-kuesioner');
});

Route::get('/back-to-main', function () {
    return view('pages.main-kuesioner');
});

Route::get('/next-section2', function () {
    return view('pages.section2-kuesioner');
});

Route::get('/next-section3', function () {
    return view('pages.section3-kuesioner');
});

Route::get('/next-section4', function () {
    return view('pages.section4-kuesioner');
});

// Routes untuk testing template
Route::prefix('admin')->name('admin.views.')->group(function () {
    // Dashboard
    Route::get('dashboard', function () {
        return view('admin.views.dashboard.index');
    })->name('dashboard');

    // Alumni Management
    Route::get('users', function () {
        return view('admin.views.users.index');
    })->name('users.index');

    Route::get('users/admins', function () {
        return view('admin.views.users.admins');
    })->name('users.admins');

    Route::get('users/{id}/edit', function ($id) {
        return view('admin.views.users.edit');
    })->name('users.edit');

    Route::get('users/{id}', function ($id) {
        return view('admin.views.users.show');
    })->name('users.show');

    // Dummy routes untuk form submission
    Route::post('users/store-admin', function () {
        return back()->with('success', 'Admin berhasil ditambahkan!');
    })->name('users.store-admin');

    Route::put('users/{id}', function ($id) {
        return back()->with('success', 'Data alumni berhasil diperbarui!');
    })->name('users.update');

    Route::delete('users/{id}', function ($id) {
        return redirect()->route('admin.views.users.index')->with('success', 'Alumni berhasil dihapus!');
    })->name('users.destroy');
});

// Route::get('/', [UserController::class, 'index'])->name('admin.views.layout');

// // Admin User Management Routes
// Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
//     // Alumni Management
//     Route::get('/users', [UserController::class, 'index'])->name('users.index');
//     Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
//     Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
//     Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
//     Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
//     Route::post('/users/bulk-delete', [UserController::class, 'bulkDestroy'])->name('users.bulk-destroy');
//     Route::get('/users/export/alumni', [UserController::class, 'exportAlumni'])->name('users.export.alumni');

//     // Admin Management
//     Route::get('/admins', [UserController::class, 'adminIndex'])->name('users.admins');
//     Route::get('/admins/create', [UserController::class, 'createAdmin'])->name('users.create-admin');
//     Route::post('/admins', [UserController::class, 'storeAdmin'])->name('users.store-admin');

//     // Additional Actions
//     Route::post('/users/{user}/verify-email', [UserController::class, 'verifyEmail'])->name('users.verify-email');
//     Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
//     Route::put('/users/{user}/email-verification', [UserController::class, 'updateEmailVerification'])->name('users.update-email-verification');
// });

// untuk mendefinisikan bahwa hanya admin yang dapat mengakses halaman tersebut
// Route::middleware(['auth', 'role:admin'])
//     ->prefix('admin') 
//     ->name('admin.')
//     ->group(function () {

//         // Menampilkan daftar kuesioner
//         Route::get('/questionnaires', [QuestionnaireController::class, 'index'])
//             ->name('questionnaires.index');

//         // Menyimpan kuesioner baru
//         Route::post('/questionnaires', [QuestionnaireController::class, 'store'])
//             ->name('questionnaires.store');

//         // Mengupdate kuesioner
//         Route::put('/questionnaires/{id}', [QuestionnaireController::class, 'update'])
//             ->name('questionnaires.update');

//         // Menghapus kuesioner
//         Route::delete('/questionnaires/{id}', [QuestionnaireController::class, 'destroy'])
//             ->name('questionnaires.destroy');
//     });


require __DIR__ . '/auth.php';
