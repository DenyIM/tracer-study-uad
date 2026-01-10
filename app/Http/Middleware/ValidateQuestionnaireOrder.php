<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Questionnaire;
use App\Models\QuestionnaireSequence;
use App\Models\QuestionnaireProgress;

class ValidateQuestionnaireOrder
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $alumni = $user->alumni;
        
        $categorySlug = $request->route('categorySlug');
        $questionnaireSlug = $request->route('questionnaireSlug');
        
        if (!$categorySlug || !$alumni) {
            return $next($request);
        }
        
        $category = Category::where('slug', $categorySlug)->first();
        if (!$category) {
            return redirect()->route('questionnaire.dashboard')
                ->with('error', 'Kategori tidak ditemukan.');
        }
        
        // If no specific questionnaire slug, allow access to general
        if (!$questionnaireSlug) {
            return $next($request);
        }
        
        $questionnaire = Questionnaire::where('slug', $questionnaireSlug)
            ->where('category_id', $category->id)
            ->first();
        
        if (!$questionnaire) {
            return redirect()->route('questionnaire.dashboard')
                ->with('error', 'Kuesioner tidak ditemukan.');
        }
        
        // If questionnaire is general, always allow
        if ($questionnaire->is_general) {
            return $next($request);
        }
        
        // Check if previous questionnaire in sequence is completed
        $sequence = QuestionnaireSequence::where('category_id', $category->id)
            ->where('questionnaire_id', $questionnaire->id)
            ->first();
        
        if ($sequence && $sequence->order > 1) {
            $prevSequence = QuestionnaireSequence::where('category_id', $category->id)
                ->where('order', $sequence->order - 1)
                ->first();
            
            if ($prevSequence) {
                $prevProgress = QuestionnaireProgress::where('alumni_id', $alumni->id)
                    ->where('questionnaire_id', $prevSequence->questionnaire_id)
                    ->where('status', 'completed')
                    ->exists();
                
                if (!$prevProgress) {
                    return redirect()->route('questionnaire.dashboard')
                        ->with('error', 'Harap selesaikan kuesioner sebelumnya terlebih dahulu.');
                }
            }
        }
        
        return $next($request);
    }
}