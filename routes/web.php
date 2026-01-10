<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Questionnaire\CategoryController;
use App\Http\Controllers\Questionnaire\DashboardController;
use App\Http\Controllers\Questionnaire\QuestionnaireController as AlumniQuestionnaireController;
use App\Http\Controllers\Admin\QuestionnaireController as AdminQuestionnaireController;
use App\Http\Controllers\Questionnaire\AnswerController;
use App\Http\Controllers\Questionnaire\ProgressController;
use App\Http\Controllers\Questionnaire\QuestionController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\ProfileController;

require __DIR__.'/auth.php';

// ==============================================
// PUBLIC ROUTES
// ==============================================

Route::get('/', function () {
    return view('pages.homepage');
})->name('public');

Route::get('/homepage-register', function () {
    return view('auth.register');
});

Route::get('/lupa-pass', function () {
    return view('auth.lupa-pass');
})->name('lupa-pass');

// Static Pages (for development/testing)
Route::get('/go-to-kuesioner1', function () {
    return view('questionnaire.dashboard.index');
});

Route::get('/go-to-register-form', function () {
    return view('pages.register-form');
});

Route::get('/nav-kuesioner', function () {
    return view('questionnaire.dashboard.index');
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
})->name('nav-profile');

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

Route::get('/back-to-main', function () {
    return view('questionnaire.dashboard.index');
});

Route::get('/next-section2', function () {
    return view('pages.section2-kuesioner');
});

Route::get('/next-section3', function () {
    return view('pages.section3-kuesioner');
});

// ==============================================
// AUTHENTICATION ROUTES
// ==============================================

Route::middleware('auth')->group(function () {
    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/verify-otp', [OtpVerificationController::class, 'show'])->name('otp.show');
    Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])->name('otp.verify');
    Route::post('/resend-otp', [OtpVerificationController::class, 'resend'])->name('otp.resend');
});

// Google Authentication
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])
    ->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
    ->name('google.callback');

// ==============================================
// ADMIN ROUTES (SEDERHANAKAN - HAPUS MIDDLEWARE YANG BERMASALAH)
// ==============================================

// GANTI dengan middleware yang lebih sederhana
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Cek manual apakah user adalah admin
    Route::middleware([\App\Http\Middleware\EnsureUserIsAdmin::class])->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('views.dashboard');
        
        // User Management
        Route::prefix('users')->name('views.users.')->group(function () {
            // Alumni Management
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
            
            // Admin Management
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
        
        // Questionnaire Management (Admin)
        Route::prefix('questionnaire')->name('questionnaire.')->group(function () {
            // Categories Management
            Route::get('/categories', [AdminQuestionnaireController::class, 'categories'])->name('categories');
            Route::post('/categories', [AdminQuestionnaireController::class, 'storeCategory'])->name('categories.store');
            Route::put('/categories/{id}', [AdminQuestionnaireController::class, 'updateCategory'])->name('categories.update');
            Route::delete('/categories/{id}', [AdminQuestionnaireController::class, 'destroyCategory'])->name('categories.destroy');
            
            // Questionnaires Management - PERBAIKI NAMA ROUTE YANG BENTROK
            Route::get('/categories/{category}/questionnaires', [AdminQuestionnaireController::class, 'questionnaires'])->name('questionnaires.index');
            Route::post('/categories/{category}/questionnaires', [AdminQuestionnaireController::class, 'storeQuestionnaire'])->name('questionnaires.store');
            Route::put('/categories/{category}/questionnaires/{id}', [AdminQuestionnaireController::class, 'updateQuestionnaire'])->name('questionnaires.update');
            Route::delete('/categories/{category}/questionnaires/{id}', [AdminQuestionnaireController::class, 'destroyQuestionnaire'])->name('questionnaires.destroy');
            
            // ROUTE ORDER YANG BARU - GUNAKAN NAMA YANG BERBEDA
            Route::post('/categories/{category}/questionnaires/update-order', [AdminQuestionnaireController::class, 'updateQuestionnaireOrder'])->name('questionnaires.updateOrder');
            
            // Questions Management
            Route::get('/categories/{category}/questionnaires/{questionnaire}/questions', [AdminQuestionnaireController::class, 'questions'])->name('questions.index');
            Route::post('/categories/{category}/questionnaires/{questionnaire}/questions', [AdminQuestionnaireController::class, 'storeQuestion'])->name('questions.store');
            Route::put('/categories/{category}/questionnaires/{questionnaire}/questions/{id}', [AdminQuestionnaireController::class, 'updateQuestion'])->name('questions.update');
            Route::delete('/categories/{category}/questionnaires/{questionnaire}/questions/{id}', [AdminQuestionnaireController::class, 'destroyQuestion'])->name('questions.destroy');
            
            // ROUTE ORDER YANG BARU - GUNAKAN NAMA YANG BERBEDA
            Route::post('/categories/{category}/questionnaires/{questionnaire}/questions/update-order', [AdminQuestionnaireController::class, 'updateQuestionOrder'])->name('questions.updateOrder');
            
            // Statistics & Reports
            Route::get('/statistics', [AdminQuestionnaireController::class, 'statistics'])->name('statistics');
            Route::get('/export/{category?}', [AdminQuestionnaireController::class, 'exportData'])->name('export');
        });
    });
});

// ==============================================
// ALUMNI QUESTIONNAIRE ROUTES (SEDERHANAKAN)
// ==============================================

Route::middleware(['auth', 'verified', 'role:alumni'])->prefix('questionnaire')->name('questionnaire.')->group(function () {
    // Middleware untuk cek apakah user adalah alumni
        // ===== CATEGORY SELECTION =====
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
        
        // ===== DASHBOARD =====
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // // ===== ANSWER MANAGEMENT =====
        // Route::post('/answer/{questionId}', [QuestionController::class, 'storeAnswer'])->name('answer.store');
        // Route::post('/answer/{questionId}/skip', [QuestionController::class, 'skipQuestion'])->name('answer.skip');
        // Route::delete('/answer/{questionId}', [QuestionController::class, 'clearAnswer'])->name('answer.clear');

        Route::post('/answer/{questionId}', [QuestionController::class, 'storeAnswer'])->name('answer.store');
        Route::post('/answer/{questionId}/skip', [QuestionController::class, 'skipQuestion'])->name('answer.skip');
        Route::delete('/answer/{questionId}', [QuestionController::class, 'clearAnswer'])->name('answer.clear');

        // ===== QUESTIONNAIRE FILLING (PALING BAWAH) =====
        Route::get('/{categorySlug}/{questionnaireSlug?}', [AlumniQuestionnaireController::class, 'show'])
            ->name('fill');
            

        // // ===== QUESTIONNAIRE FILLING =====
        // // HAPUS MIDDLEWARE YANG BERMASALAH
        // Route::get('/{categorySlug}/{questionnaireSlug?}', [AlumniQuestionnaireController::class, 'show'])
        //     ->name('fill');
        
        // ===== QUESTIONNAIRE SUBMISSION =====
        Route::post('/{questionnaireId}/submit', [AlumniQuestionnaireController::class, 'submitQuestionnaire'])
            ->name('submit');
        
        // ===== ANSWERS & RESULTS VIEW =====
        Route::get('/answers', [AnswerController::class, 'index'])->name('answers.index');
        Route::get('/answers/{categorySlug}', [AnswerController::class, 'showCategoryAnswers'])
            ->name('answers.category');
        Route::get('/answers/{categorySlug}/export', [AnswerController::class, 'exportPDF'])
            ->name('answers.export');
        
        // ===== QUESTIONNAIRE COMPLETION =====
        Route::get('/completed', [AlumniQuestionnaireController::class, 'completed'])->name('completed');
        
        // ===== PROGRESS TRACKING =====
        Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');
        Route::post('/progress/reset/{categoryId}', [ProgressController::class, 'resetProgress'])
            ->name('progress.reset');
        
        // ===== QUESTION NAVIGATION API =====
        Route::get('/question/{questionId}/detail', [QuestionController::class, 'getQuestionDetail'])
            ->name('question.detail');
        Route::get('/question/{questionId}/validate', [QuestionController::class, 'validateAnswer'])
            ->name('question.validate');
        
        // ===== API ROUTES FOR AJAX =====
        Route::prefix('api')->name('api.')->group(function () {
            // Progress API
            Route::get('/progress', [ProgressController::class, 'getProgress'])->name('progress');
            Route::post('/progress/update', [ProgressController::class, 'updateProgress'])->name('progress.update');
            
            // Answer API
            Route::get('/question/{questionId}/answer', [AnswerController::class, 'getAnswer'])->name('answer.get');
            Route::get('/questionnaire/{questionnaireId}/answers', [AnswerController::class, 'getQuestionnaireAnswers'])
                ->name('questionnaire.answers');
            
            // Question Navigation API
            Route::get('/questionnaire/{questionnaireId}/next/{currentOrder}', [QuestionController::class, 'getNextQuestion'])
                ->name('question.next');
            Route::get('/questionnaire/{questionnaireId}/prev/{currentOrder}', [QuestionController::class, 'getPrevQuestion'])
                ->name('question.prev');
    });
});

// ==============================================
// ADDITIONAL STATIC ROUTES (for blade testing)
// ==============================================

// Kuesioner Pages (Static for reference)
Route::get('/kuesioner', function () {
    return view('questionnaire.fill');
})->name('kuesioner.page');

Route::get('/section1-kuesioner', function () {
    return view('pages.section1-kuesioner');
});

// ==============================================
// FALLBACK ROUTE
// ==============================================

Route::fallback(function () {
    return redirect()->route('public');
});