<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('answer_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumnis')->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->text('answer')->nullable(); // Jawaban text biasa
            $table->json('selected_options')->nullable(); // Untuk multiple choice (disimpan sebagai JSON)
            $table->integer('scale_value')->nullable(); // Untuk skala (1-5)
            $table->boolean('is_skipped')->default(false);
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();
            
            $table->unique(['alumni_id', 'question_id']); // Satu jawaban per pertanyaan
        });
    }

    public function down()
    {
        Schema::dropIfExists('answer_questions');
    }
};