<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Questionnaire\CategoryController;
use App\Http\Controllers\Questionnaire\DashboardController;
use App\Http\Controllers\Questionnaire\QuestionnaireController as AlumniQuestionnaireController;
use App\Http\Controllers\Admin\QuestionnaireController as AdminQuestionnaireController;
use App\Http\Controllers\Questionnaire\AnswerController;
use App\Http\Controllers\Questionnaire\ProgressController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\OtpVerificationController;

require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('pages.homepage');
})->name('public');

// Route::get('/', function () {
//     return ['Laravel' => app()->version()];
// });

Route::get('/homepage-login', function () {
    return view('auth.login');
});

Route::get('/homepage-register', function () {
    return view('auth.register');
});

Route::get('/lupa-pass', function () {
    return view('auth.lupa-pass');
})->name('lupa-pass');

Route::get('/go-to-kuesioner1', function () {
    return view('pages.page-kuesioner');
});

Route::get('/go-to-register-form', function () {
    return view('pages.register-form');
});

Route::get('/nav-kuesioner', function () {
    return view('pages.main-kuesioner');
})->name('main');

Route::get('/nav-leaderboard', function () {
    return view('pages.leaderboard');
})->name('nav-leaderboard');

Route::get('/nav-forum', function () {
    return view('pages.forum');
})->name('nav-forum');

Route::get('/nav-mentor', function () {
    return view('pages.mentor');
})->name('nav-mentor');

Route::get('/nav-lowongan', function () {
    return view('pages.list-lowongan');
})->name('nav-lowongan');

Route::get('/nav-profile', function () {
    return view('pages.profile');
})->name('nav-profil');

Route::get('/nav-bookmark', function () {
    return view('pages.bookmark');
})->name('nav-bookmark');

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

Route::middleware(['guest'])->group(function () {
    Route::get('/verify-otp', [OtpVerificationController::class, 'show'])->name('otp.show');
    Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])->name('otp.verify');
    Route::post('/resend-otp', [OtpVerificationController::class, 'resend'])->name('otp.resend');
});

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])
    ->name('google.redirect');

Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
    ->name('google.callback');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('views.dashboard');
    
    Route::prefix('users')->name('views.users.')->group(function () {
        // Daftar Alumni 
        Route::prefix('alumni')->name('alumni.')->group(function () {
            Route::get('/', [UserController::class, 'alumniIndex'])->name('index');
            Route::get('/create', [UserController::class, 'alumniCreate'])->name('create');
            Route::post('/store', [UserController::class, 'alumniStore'])->name('store');
            Route::get('/{alumni}', [UserController::class, 'alumniShow'])->name('show');
            Route::get('/{alumni}/edit', [UserController::class, 'alumniEdit'])->name('edit');
            Route::put('/{alumni}', [UserController::class, 'alumniUpdate'])->name('update');
            Route::delete('/{alumni}', [UserController::class, 'alumniDestroy'])->name('destroy');
            
            // Additional Alumni Actions
            Route::post('/{alumni}/verify-email', [UserController::class, 'verifyAlumniEmail'])->name('verify-email');
            Route::post('/{alumni}/reset-password', [UserController::class, 'resetAlumniPassword'])->name('reset-password');
        });
        
        // Daftar Admin 
        Route::prefix('admins')->name('admin.')->group(function () {
            Route::get('/', [UserController::class, 'adminIndex'])->name('index');
            Route::get('/create', [UserController::class, 'adminCreate'])->name('create');
            Route::post('/', [UserController::class, 'adminStore'])->name('store');
            Route::get('/{admin}', [UserController::class, 'adminShow'])->name('show');
            Route::get('/{admin}/edit', [UserController::class, 'adminEdit'])->name('edit');
            Route::put('/{admin}', [UserController::class, 'adminUpdate'])->name('update');
            Route::delete('/{admin}', [UserController::class, 'adminDestroy'])->name('destroy');
        });
    });
});

// Questionnaire Routes for Alumni
Route::middleware(['auth', 'verified', 'role:alumni'])->prefix('questionnaire')->name('questionnaire.')->group(function () {
    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/features', [DashboardController::class, 'features'])->name('features');
    
    // Questionnaire Filling
    Route::get('/{category}', [AlumniQuestionnaireController::class, 'show'])->name('fill.category');
    Route::get('/{category}/{questionnaire}', [AlumniQuestionnaireController::class, 'show'])->name('fill.questionnaire');
    Route::post('/answers/{question}', [AlumniQuestionnaireController::class, 'storeAnswer'])->name('answers.store');
    Route::post('/submit/{questionnaire}', [AlumniQuestionnaireController::class, 'submitQuestionnaire'])->name('submit');
    Route::get('/completed', [AlumniQuestionnaireController::class, 'completed'])->name('completed');
    
    // Answers & Results
    Route::get('/answers', [AnswerController::class, 'index'])->name('answers.index');
    Route::get('/answers/{category}', [AnswerController::class, 'showCategoryAnswers'])->name('answers.category');
    Route::get('/answers/{category}/export', [AnswerController::class, 'exportPDF'])->name('answers.export');
    
    // Progress
    Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');
    Route::post('/progress/update', [ProgressController::class, 'updateProgress'])->name('progress.update');
    Route::post('/progress/reset/{category}', [ProgressController::class, 'resetProgress'])->name('progress.reset');
    
    // API Routes
    Route::get('/api/progress', [ProgressController::class, 'getProgress'])->name('api.progress');
    Route::get('/api/answers/{question}', [AnswerController::class, 'getAnswer'])->name('api.answers.get');
    Route::get('/api/questionnaire/{questionnaire}/answers', [AnswerController::class, 'getQuestionnaireAnswers'])->name('api.questionnaire.answers');
});

// Admin Questionnaire Management Routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin/questionnaire')->name('admin.questionnaire.')->group(function () {
    // Categories
    Route::get('/categories', [AdminQuestionnaireController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminQuestionnaireController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{id}', [AdminQuestionnaireController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminQuestionnaireController::class, 'destroyCategory'])->name('categories.destroy');
    
    // Questionnaires
    Route::get('/categories/{category}/questionnaires', [AdminQuestionnaireController::class, 'questionnaires'])->name('questionnaires');
    Route::post('/categories/{category}/questionnaires', [AdminQuestionnaireController::class, 'storeQuestionnaire'])->name('questionnaires.store');
    Route::put('/categories/{category}/questionnaires/{id}', [AdminQuestionnaireController::class, 'updateQuestionnaire'])->name('questionnaires.update');
    Route::delete('/categories/{category}/questionnaires/{id}', [AdminQuestionnaireController::class, 'destroyQuestionnaire'])->name('questionnaires.destroy');
    Route::post('/categories/{category}/questionnaires/order', [AdminQuestionnaireController::class, 'updateQuestionnaireOrder'])->name('questionnaires.order');
    
    // Questions
    Route::get('/categories/{category}/questionnaires/{questionnaire}/questions', [AdminQuestionnaireController::class, 'questions'])->name('questions');
    Route::post('/categories/{category}/questionnaires/{questionnaire}/questions', [AdminQuestionnaireController::class, 'storeQuestion'])->name('questions.store');
    Route::put('/categories/{category}/questionnaires/{questionnaire}/questions/{id}', [AdminQuestionnaireController::class, 'updateQuestion'])->name('questions.update');
    Route::delete('/categories/{category}/questionnaires/{questionnaire}/questions/{id}', [AdminQuestionnaireController::class, 'destroyQuestion'])->name('questions.destroy');
    Route::post('/categories/{category}/questionnaires/{questionnaire}/questions/order', [AdminQuestionnaireController::class, 'updateQuestionOrder'])->name('questions.order');
    
    // Statistics & Reports
    Route::get('/statistics', [AdminQuestionnaireController::class, 'statistics'])->name('statistics');
    Route::get('/export/{category?}', [AdminQuestionnaireController::class, 'exportData'])->name('export');
});


