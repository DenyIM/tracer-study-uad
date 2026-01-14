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

// Route::get('/nav-profile', function () {
//     return view('profile.profile');
// })->name('nav-profile');

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

// Route::middleware('auth')->group(function () {
//     // Profile (Breeze)
//     Route::get('/profile', [ProfileController::class, 'edit'])
//         ->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])
//         ->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])
//         ->name('profile.destroy');
// });

Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    // Profile routes - semua bisa handle JSON response
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/update', [ProfileController::class, 'update'])->name('update');
    Route::post('/password/update', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::post('/profile/theme', [ProfileController::class, 'updateTheme'])
        ->name('profile.theme.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
    Route::post('/photo/upload', [ProfileController::class, 'uploadPhoto'])->name('photo.upload');
        
});


// Route untuk nav-profile
Route::get('/nav-profile', function () {
    return redirect()->route('profile.edit');
})->name('nav-profile')->middleware('auth');

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

// Hapus middleware ['auth', 'verified'] dari group utama
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Admin Dashboard - sekarang dapat diakses tanpa login
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('views.dashboard');
    
    // Dashboard API for real-time stats
    Route::get('/dashboard/stats', [UserController::class, 'getDashboardStats'])->name('dashboard.stats');
    
    // Tracer Study Charts API
    Route::get('/tracer-charts', [UserController::class, 'getTracerChartsData'])->name('tracer.charts');
    
    // Export Questionnaire Results to PDF
    Route::get('/export-pdf-form', [UserController::class, 'showExportPDFForm'])->name('questionnaire.export.form');
    Route::get('/export-questionnaire-pdf', [UserController::class, 'exportQuestionnaireResultsPDF'])->name('questionnaire.export.pdf');
    Route::get('/preview-questionnaire-pdf', [UserController::class, 'previewQuestionnairePDF'])->name('questionnaire.export.preview');
    Route::get('/admin/export-complete-answers-form', [UserController::class, 'showCompleteAnswersExportForm'])
        ->name('questionnaire.export.complete.form');
        
    Route::get('/admin/export-complete-answers-pdf', [UserController::class, 'exportCompleteAnswersPDF'])
        ->name('questionnaire.export.complete.pdf');
        
    Route::get('/admin/preview-complete-answers-pdf', [UserController::class, 'previewCompleteAnswersPDF'])
        ->name('questionnaire.export.complete.preview');

    // User Management
    Route::prefix('users')->name('views.users.')->group(function () {
        // Alumni Management
        Route::prefix('alumni')->name('alumni.')->group(function () {
            Route::get('/', [UserController::class, 'alumniIndex'])->name('index');
            Route::get('/create', [UserController::class, 'alumniCreate'])->name('create');
            Route::post('/store', [UserController::class, 'alumniStore'])->name('store');
            Route::get('/{alumni}/edit', [UserController::class, 'alumniEdit'])->name('edit');
            Route::put('/{alumni}', [UserController::class, 'alumniUpdate'])->name('update');
            Route::get('/{alumni}', [UserController::class, 'alumniShow'])->name('show');
            Route::delete('/{alumni}', [UserController::class, 'alumniDestroy'])->name('destroy');
            
            // Additional Alumni Actions
            Route::post('/{alumni}/verify-email', [UserController::class, 'verifyAlumniEmail'])->name('verify-email');
            Route::post('/{alumni}/reset-password', [UserController::class, 'resetAlumniPassword'])->name('reset-password');
            
            // Bulk Operations
            Route::post('/bulk-delete', [UserController::class, 'alumniBulkDestroy'])->name('bulk-destroy');
            
            // Import/Export
            Route::get('/export', [UserController::class, 'exportAlumni'])->name('export');
            Route::post('/import', [UserController::class, 'importAlumni'])->name('import');
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
    
    // ==============================================
    // QUESTIONNAIRE MANAGEMENT ROUTES (ADMIN)
    // ==============================================
    
    Route::prefix('questionnaire')->name('questionnaire.')->group(function () {
        // ============ CATEGORIES MANAGEMENT ============
        Route::get('/categories', [AdminQuestionnaireController::class, 'categories'])->name('categories');
        Route::post('/categories', [AdminQuestionnaireController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{id}', [AdminQuestionnaireController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{id}', [AdminQuestionnaireController::class, 'destroyCategory'])->name('categories.destroy');
        Route::delete('/categories/delete-all', [AdminQuestionnaireController::class, 'destroyAllCategories'])->name('categories.delete-all');
        
        // ============ QUESTIONNAIRES MANAGEMENT ============
        // List questionnaires by category
        Route::get('/categories/{categoryId}/questionnaires', [AdminQuestionnaireController::class, 'questionnaires'])->name('questionnaires');
        
        // CRUD for questionnaires
        Route::post('/categories/{categoryId}/questionnaires', [AdminQuestionnaireController::class, 'storeQuestionnaire'])->name('questionnaires.store');
        Route::put('/categories/{categoryId}/questionnaires/{id}', [AdminQuestionnaireController::class, 'updateQuestionnaire'])->name('questionnaires.update');
        Route::delete('/categories/{categoryId}/questionnaires/{id}', [AdminQuestionnaireController::class, 'destroyQuestionnaire'])->name('questionnaires.destroy');
        Route::delete('/categories/{categoryId}/questionnaires/delete-all', [AdminQuestionnaireController::class, 'destroyAllQuestionnaires'])->name('questionnaires.delete-all');
        
        // Update questionnaire order
        Route::post('/categories/{categoryId}/questionnaires/order', [AdminQuestionnaireController::class, 'updateQuestionnaireOrder'])->name('questionnaires.update-order');
        
        // ============ QUESTIONS MANAGEMENT ============
        // List questions by questionnaire
        Route::get('/categories/{categoryId}/questionnaires/{questionnaireId}/questions', [AdminQuestionnaireController::class, 'questions'])->name('questions');
        
        // CRUD for questions
        Route::post('/categories/{categoryId}/questionnaires/{questionnaireId}/questions', [AdminQuestionnaireController::class, 'storeQuestion'])->name('questions.store');
        Route::put('/categories/{categoryId}/questionnaires/{questionnaireId}/questions/{id}', [AdminQuestionnaireController::class, 'updateQuestion'])->name('questions.update');
        Route::delete('/categories/{categoryId}/questionnaires/{questionnaireId}/questions/{id}', [AdminQuestionnaireController::class, 'destroyQuestion'])->name('questions.destroy');
        Route::delete('/categories/{categoryId}/questionnaires/{questionnaireId}/questions/delete-all', [AdminQuestionnaireController::class, 'destroyAllQuestions'])->name('questions.delete-all');
        
        // Update question order
        Route::post('/categories/{categoryId}/questionnaires/{questionnaireId}/questions/order', [AdminQuestionnaireController::class, 'updateQuestionOrder'])->name('questions.update-order');
        
        // ============ STATISTICS & REPORTS ============
        Route::get('/statistics', [AdminQuestionnaireController::class, 'statistics'])->name('statistics');
        Route::get('/export/{categoryId?}', [AdminQuestionnaireController::class, 'exportData'])->name('export');
        Route::get('/export-excel/{categoryId?}', [AdminQuestionnaireController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export-csv/{categoryId?}', [AdminQuestionnaireController::class, 'exportCSV'])->name('export.csv');
        Route::get('/export-summary/{categoryId?}', [AdminQuestionnaireController::class, 'exportSummaryCSV'])->name('export.summary');
    });
});

// ==============================================
// ALUMNI QUESTIONNAIRE ROUTES (SEDERHANAKAN)
// ==============================================

Route::middleware(['auth', 'verified', 'role:alumni'])->prefix('questionnaire')->name('questionnaire.')->group(function () {
    // Middleware untuk cek apakah user adalah alumni
    
    // ===== QUESTIONNAIRE COMPLETION ===== (DI ATAS/URUTAN PERTAMA)
    Route::get('/completed', [AlumniQuestionnaireController::class, 'completed'])->name('completed');
    
    // ===== CATEGORY SELECTION =====
    // Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    // Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    // Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    // Route::delete('/category/cancel', [CategoryController::class, 'cancel'])->name('category.cancel');

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories', 'index')->name('categories');
        Route::post('/categories', 'store')->name('categories.store');
        Route::put('/categories/{id}', 'update')->name('categories.update');
        Route::delete('/category/cancel', 'cancel')->name('category.cancel');
    });
    
    // ===== DASHBOARD =====
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::post('/answer/{questionId}', [QuestionController::class, 'storeAnswer'])->name('answer.store');
    Route::post('/answer/{questionId}/skip', [QuestionController::class, 'skipQuestion'])->name('answer.skip');
    Route::delete('/answer/{questionId}', [QuestionController::class, 'clearAnswer'])->name('answer.clear');

    // ===== QUESTIONNAIRE FILLING (PALING BAWAH) =====
    Route::get('/{categorySlug}/{questionnaireSlug?}', [AlumniQuestionnaireController::class, 'show'])
        ->name('fill');
    
    // ===== QUESTIONNAIRE SUBMISSION =====
    Route::post('/questionnaire/{questionnaire}/submit', [AlumniQuestionnaireController::class, 'submitQuestionnaire'])
        ->name('submit');
    
    // ===== ANSWERS & RESULTS VIEW =====
    Route::get('/answers', [AnswerController::class, 'index'])->name('questionnaire.answers.index');
    Route::get('/answers/category/{categorySlug}', [AnswerController::class, 'showCategoryAnswers'])
        ->name('answers.category'); // <-- Nama ini
    Route::get('/answers/{categorySlug}/export', [AnswerController::class, 'exportPDF'])
        ->name('answers.export');
    
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