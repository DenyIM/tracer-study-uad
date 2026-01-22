<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use App\Models\StatusQuestionnaire;
use App\Observers\StatusQuestionnaireObserver;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
        StatusQuestionnaire::observe(StatusQuestionnaireObserver::class);

        Blade::directive('br2nl', function ($expression) {
            return "<?php echo str_replace(['<br>', '<br />', '<br/>'], \"\n\", e($expression)); ?>";
        });
        
        Blade::directive('nl2br', function ($expression) {
            return "<?php echo nl2br(e($expression)); ?>";
        });
        
        Blade::directive('brformat', function ($expression) {
            return "<?php echo str_replace(['&lt;br&gt;', '&lt;br/&gt;', '&lt;br /&gt;'], '<br>', e($expression)); ?>";
        });
    }
}
