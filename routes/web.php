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
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AlumniRegistrationController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\Admin\LeaderboardAdminController;
use App\Http\Controllers\Admin\AdminProfileController;

require __DIR__.'/auth.php';

// ==============================================
// PUBLIC ROUTES
// ==============================================

Route::get('/', function () {
    return view('pages.homepage');
})->name('public');

Route::get('/homepage-login', function () {
    return view('auth.login');
})->name('login');

Route::get('/homepage-register', function () {
    return view('auth.register');
});

Route::get('/lupa-pass', function () {
    return view('auth.reset-password');
})->name('lupa-pass');

// Static Pages (for development/testing)
Route::get('/go-to-kuesioner1', function () {
    return view('questionnaire.dashboard.index');
});

Route::get('/nav-kuesioner', function () {
    return view('questionnaire.dashboard.index');
})->name('main');

// Route::get('/nav-leaderboard', function () {
//     return view('pages.leaderboard');
// })->name('nav-leaderboard');

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

Route::get('/homepage-login', [AuthenticatedSessionController::class, 'create'])->name('homepage-login');
Route::post('/homepage-login', [AuthenticatedSessionController::class, 'store'])->name('login.post'); 

Route::middleware(['guest'])->group(function () {
    Route::get('/verify-otp', [OtpVerificationController::class, 'show'])->name('otp.show');
    Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])->name('otp.verify');
    Route::post('/resend-otp', [OtpVerificationController::class, 'resend'])->name('otp.resend');
});

Route::get('/forgot-password', [ResetPasswordController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLink'])->name('password.email');
Route::post('/forgot-password/verify', [ResetPasswordController::class, 'verifyCode'])->name('password.verify-code');
Route::post('/forgot-password/reset', [ResetPasswordController::class, 'resetPassword'])->name('password.reset');
Route::post('/forgot-password/resend', [ResetPasswordController::class, 'resendCode'])->name('password.resend');

// Google Authentication
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])
    ->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
    ->name('google.callback');

// Alumni Registration
Route::middleware(['auth'])->group(function () {
    Route::get('/alumni/registration', [AlumniRegistrationController::class, 'showForm'])
        ->name('alumni.registration.form');
    
    Route::post('/alumni/registration', [AlumniRegistrationController::class, 'submitForm'])
        ->name('alumni.registration.submit');
});

// ==============================================
// ADMIN ROUTES 
// ==============================================
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Admin Dashboard
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('views.dashboard');
    
    // Admin Profile
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [AdminProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/change-password', [AdminProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::post('/profile/upload-photo', [AdminProfileController::class, 'uploadPhoto'])->name('profile.upload-photo');
    Route::post('/profile/delete-photo', [AdminProfileController::class, 'deletePhoto'])->name('profile.delete-photo');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Dashboard API for real-time stats
    Route::get('/dashboard/stats', [UserController::class, 'getDashboardStats'])->name('dashboard.stats');
    
    // Tracer Study Charts API
    Route::get('/tracer-charts', [UserController::class, 'getTracerChartsData'])->name('tracer.charts');
    
    // Export Questionnaire Results to PDF (SINGLE VERSION - RECOMMENDED)
    Route::get('/questionnaire/export/pdf-form', [UserController::class, 'showExportPDFForm'])
        ->name('questionnaire.export.form');

    Route::get('/questionnaire/export/pdf', [UserController::class, 'exportQuestionnaireResultsPDF'])
        ->name('questionnaire.export.pdf');
    
    // Export Complete Answers
    Route::get('/export/complete-answers-form', [UserController::class, 'showCompleteAnswersExportForm'])
        ->name('questionnaire.export.complete.form');
        
    Route::get('/export/complete-answers-pdf', [UserController::class, 'exportCompleteAnswersPDF'])
        ->name('questionnaire.export.complete.pdf');
        
    // Preview Route (optional)
    Route::get('/export/complete-answers-preview', [UserController::class, 'previewCompleteAnswersPDF'])
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
            Route::get('/admin/questionnaire/export/pdf', [UserController::class, 'exportQuestionnaireResultsPDF'])
                ->name('admin.questionnaire.export.pdf');
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
            
            // Additional admin actions
            Route::post('/{admin}/upload-photo', [UserController::class, 'adminUploadPhoto'])->name('upload-photo');
            Route::delete('/{admin}/delete-photo', [UserController::class, 'adminDeletePhoto'])->name('delete-photo');
            Route::post('/{admin}/verify-email', [UserController::class, 'adminVerifyEmail'])->name('verify-email');
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
        Route::delete('/categories/delete-selected', [AdminQuestionnaireController::class, 'deleteSelectedCategories'])->name('categories.delete-selected');
        
        // ============ GENERAL QUESTIONNAIRES MANAGEMENT ============
        Route::get('/general-questionnaires', [AdminQuestionnaireController::class, 'generalQuestionnaires'])->name('general-questionnaires');
        Route::get('general-questionnaires/{questionnaireId}/questions/create', [AdminQuestionnaireController::class, 'createGeneralQuestion'])->name('general-questions.create');
        Route::get('general-questionnaires/{questionnaireId}/questions/{id}/edit', [AdminQuestionnaireController::class, 'editGeneralQuestion'])->name('general-questions.edit');
        Route::put('/general-questionnaires/{id}', [AdminQuestionnaireController::class, 'updateGeneralQuestionnaire'])->name('general-questionnaires.update');
        Route::post('/general-questionnaires/{questionnaireId}/questions', [AdminQuestionnaireController::class, 'storeGeneralQuestion'])->name('general-questions.store');
        Route::put('/general-questionnaires/{questionnaireId}/questions/{id}', [AdminQuestionnaireController::class, 'updateGeneralQuestion'])->name('general-questions.update');
        Route::delete('/general-questionnaires/{questionnaireId}/questions/delete-selected', [AdminQuestionnaireController::class, 'deleteSelectedGeneralQuestions'])->name('general-questions.delete-selected');


        // ============ SPECIFIC QUESTIONNAIRES MANAGEMENT ============
        Route::get('/categories/{categoryId}/questionnaires', [AdminQuestionnaireController::class, 'questionnaires'])->name('questionnaires');
        Route::post('/categories/{categoryId}/questionnaires', [AdminQuestionnaireController::class, 'storeQuestionnaire'])->name('questionnaires.store');
        Route::put('/categories/{categoryId}/questionnaires/{id}', [AdminQuestionnaireController::class, 'updateQuestionnaire'])->name('questionnaires.update');
        Route::delete('/categories/{categoryId}/questionnaires/delete-selected', [AdminQuestionnaireController::class, 'deleteSelectedQuestionnaires'])->name('questionnaires.delete-selected');
        Route::post('/categories/{categoryId}/questionnaires/order', [AdminQuestionnaireController::class, 'updateQuestionnaireOrder'])->name('questionnaires.update-order');
                
        // ============ SPECIFIC QUESTIONS MANAGEMENT ============
        Route::get('/categories/{categoryId}/questionnaires/{questionnaireId}/questions', [AdminQuestionnaireController::class, 'questions'])->name('questions');
        Route::get('categories/{categoryId}/questionnaires/{questionnaireId}/questions/create', [AdminQuestionnaireController::class, 'createQuestion'])->name('questions.create');
        Route::get('categories/{categoryId}/questionnaires/{questionnaireId}/questions/{id}/edit', [AdminQuestionnaireController::class, 'editQuestion'])->name('questions.edit');
        Route::post('/categories/{categoryId}/questionnaires/{questionnaireId}/questions', [AdminQuestionnaireController::class, 'storeQuestion'])->name('questions.store');
        Route::put('/categories/{categoryId}/questionnaires/{questionnaireId}/questions/{id}', [AdminQuestionnaireController::class, 'updateQuestion'])->name('questions.update');
        Route::delete('/categories/{categoryId}/questionnaires/{questionnaireId}/questions/delete-selected', [AdminQuestionnaireController::class, 'deleteSelectedQuestions'])->name('questions.delete-selected');
        Route::post('/categories/{categoryId}/questionnaires/{questionnaireId}/questions/order', [AdminQuestionnaireController::class, 'updateQuestionOrder'])->name('questions.update-order');


        // ============ STATISTICS & REPORTS ============
        Route::get('/statistics', [AdminQuestionnaireController::class, 'statistics'])->name('statistics');
        Route::get('/export/{categoryId?}', [AdminQuestionnaireController::class, 'exportData'])->name('export');
    });

    // ==============================================
    // LEADERBOARD ADMIN ROUTE
    // ==============================================

    Route::prefix('leaderboard')->name('leaderboard.')->group(function () {
        Route::get('dashboard', [LeaderboardAdminController::class, 'dashboard'])->name('dashboard');
        Route::get('alumni', [LeaderboardAdminController::class, 'alumniLeaderboard'])->name('alumni');
        Route::get('forum-submissions', [LeaderboardAdminController::class, 'forumSubmissions'])->name('forum.submissions');
        Route::get('forum-submissions/{id}', [LeaderboardAdminController::class, 'showForumSubmission'])->name('forum.show');
        Route::post('forum-submissions/{id}/approve', [LeaderboardAdminController::class, 'approveForumSubmission'])->name('forum.approve');
        
        Route::get('job-submissions', [LeaderboardAdminController::class, 'jobSubmissions'])->name('job.submissions');
        Route::get('job-submissions/{id}', [LeaderboardAdminController::class, 'showJobSubmission'])->name('job.show');
        Route::post('job-submissions/{id}/approve', [LeaderboardAdminController::class, 'approveJobSubmission'])->name('job.approve');
        Route::delete('forum-submissions/{id}', [LeaderboardAdminController::class, 'deleteForumSubmission'])->name('forum.delete');
        Route::delete('job-submissions/{id}', [LeaderboardAdminController::class, 'deleteJobSubmission'])->name('job.delete');

        Route::post('submissions/{type}/{id}/reject', [LeaderboardAdminController::class, 'rejectSubmission'])->name('reject');
        Route::post('bulk-approve', [LeaderboardAdminController::class, 'bulkApprove'])->name('bulk.approve');
        Route::post('alumni/{id}/edit-points', [LeaderboardAdminController::class, 'editAlumniPoints'])->name('edit.points');
        Route::get('statistics', [LeaderboardAdminController::class, 'getStatistics'])->name('statistics');
        Route::get('pending-counts', [LeaderboardAdminController::class, 'getPendingCounts'])->name('pending.counts');
    });
});
    

// ==============================================
// ALUMNI QUESTIONNAIRE ROUTES
// ==============================================

Route::middleware(['auth', 'verified', 'role:alumni'])->prefix('questionnaire')->name('questionnaire.')->group(function () {
    
    // ===== QUESTIONNAIRE COMPLETION ===== (DI ATAS/URUTAN PERTAMA)
    Route::get('/completed', [AlumniQuestionnaireController::class, 'completed'])->name('completed');

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories', 'index')->name('categories');
        Route::post('/categories', 'store')->name('categories.store');
        Route::put('/categories/{id}', 'update')->name('categories.update');
        Route::delete('/category/cancel', 'cancel')->name('category.cancel');
    });
    
    // ===== DASHBOARD =====
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/question/{questionId}', [QuestionController::class, 'show'])->name('question.show');
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
// LEADERBOARD ROUTE
// ==============================================

Route::middleware(['auth', 'verified', 'role:alumni'])->group(function () {
    // Leaderboard routes
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
    Route::post('/leaderboard/submit-forum', [LeaderboardController::class, 'submitForum'])->name('leaderboard.submit.forum');
    Route::post('/leaderboard/submit-job', [LeaderboardController::class, 'submitJob'])->name('leaderboard.submit.job');
    Route::get('/leaderboard/submission-history', [LeaderboardController::class, 'getSubmissionHistory'])->name('leaderboard.submission.history');
    Route::get('/leaderboard/user/{id}', [LeaderboardController::class, 'getUserRankInfo'])->name('leaderboard.user.info');
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