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

        Blade::directive('cleanAnswer', function ($expression) {
            return "<?php 
                \$text = {$expression};
                if (is_string(\$text)) {
                    // 1. Hapus semua whitespace di awal dan akhir
                    \$text = trim(\$text);
                    
                    // 2. Hapus karakter newline/tab di awal
                    \$text = ltrim(\$text, \"\\n\\r\\t\\v\\x00\");
                    
                    // 3. Hapus multiple spaces/tabs
                    \$text = preg_replace('/[ \\t]+/', ' ', \$text);
                    
                    // 4. Hapus spasi di awal baris (untuk multi-line)
                    \$text = preg_replace('/^[ \\t]+/m', '', \$text);
                    
                    // 5. Hapus spasi di akhir baris
                    \$text = preg_replace('/[ \\t]+\$/m', '', \$text);
                    
                    // 6. Untuk textarea: bersihkan tapi pertahankan paragraf
                    if (isset(\$questionType) && \$questionType === 'textarea') {
                        // Ganti multiple newlines dengan satu
                        \$text = preg_replace('/\\n\\s*\\n/', \"\\n\\n\", \$text);
                    }
                }
                echo nl2br(e(\$text));
            ?>";
        });

        Blade::directive('cleanText', function ($expression) {
            return "<?php 
                \$text = {$expression};
                if (is_string(\$text)) {
                    // Hapus SEMUA whitespace di awal
                    \$text = ltrim(\$text);
                    // Hapus multiple spaces
                    \$text = preg_replace('/\\s+/', ' ', \$text);
                    // Trim akhir
                    \$text = rtrim(\$text);
                }
                echo e(\$text);
            ?>";
        });
    }
}
