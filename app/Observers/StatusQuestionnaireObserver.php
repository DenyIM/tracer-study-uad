<?php

namespace App\Observers;

use App\Models\StatusQuestionnaire;
use App\Models\Alumni;

class StatusQuestionnaireObserver
{
    /**
     * Handle the StatusQuestionnaire "created" event.
     */
    public function created(StatusQuestionnaire $statusQuestionnaire): void
    {
        $this->updateAlumniPoints($statusQuestionnaire);
    }

    /**
     * Handle the StatusQuestionnaire "updated" event.
     */
    public function updated(StatusQuestionnaire $statusQuestionnaire): void
    {
        if ($statusQuestionnaire->isDirty('total_points')) {
            $this->updateAlumniPoints($statusQuestionnaire);
        }
    }

    /**
     * Handle the StatusQuestionnaire "deleted" event.
     */
    public function deleted(StatusQuestionnaire $statusQuestionnaire): void
    {
        $this->updateAlumniPoints($statusQuestionnaire);
    }

    /**
     * Update alumni points based on all status questionnaires
     */
    private function updateAlumniPoints(StatusQuestionnaire $statusQuestionnaire): void
    {
        $alumni = Alumni::find($statusQuestionnaire->alumni_id);
        if ($alumni) {
            $totalPoints = StatusQuestionnaire::where('alumni_id', $alumni->id)
                ->sum('total_points');
            
            $alumni->update(['points' => $totalPoints]);
        }
    }
}