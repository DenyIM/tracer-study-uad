<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\QuestionnaireController; // untuk memanggil controller

Route::get('/', function () {
    return view('admin.views.users.alumni.index');
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

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('views.dashboard');
    
    // Alumni Management
    Route::prefix('users')->name('views.users.')->group(function () {
        // Alumni
        Route::get('/alumni', [UserController::class, 'alumniIndex'])->name('alumni.index');
        Route::get('/alumni/{alumni}', [UserController::class, 'alumniShow'])->name('alumni.show');
        Route::get('/alumni/{alumni}/edit', [UserController::class, 'alumniEdit'])->name('alumni.edit');
        Route::put('/alumni/{alumni}', [UserController::class, 'alumniUpdate'])->name('alumni.update');
        Route::delete('/alumni/{alumni}', [UserController::class, 'alumniDestroy'])->name('alumni.destroy');
        
        // Admin
        Route::get('/admins', [UserController::class, 'adminIndex'])->name('admin.index');
        Route::get('/admins/create', [UserController::class, 'adminCreate'])->name('admin.create');
        Route::post('/admins', [UserController::class, 'adminStore'])->name('admin.store');
        Route::get('/admins/{admin}', [UserController::class, 'adminShow'])->name('admin.show');
        Route::get('/admins/{admin}/edit', [UserController::class, 'adminEdit'])->name('admin.edit');
        Route::put('/admins/{admin}', [UserController::class, 'adminUpdate'])->name('admin.update');
        Route::delete('/admins/{admin}', [UserController::class, 'adminDestroy'])->name('admin.destroy');
    });
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
