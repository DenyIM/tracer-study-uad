<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alumni_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumnis')->onDelete('cascade');
            $table->string('achievement_type'); // questionnaire_completed, points_milestone, etc
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('points_rewarded')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamp('achieved_at');
            $table->timestamps();
            
            $table->index(['alumni_id', 'achievement_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('alumni_achievements');
    }
};