<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('response_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_response_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->text('answer_text')->nullable(); // untuk text answer
            $table->integer('answer_value')->nullable(); // untuk numeric answer
            $table->text('other_answer')->nullable(); // untuk opsi "Lainnya"
            $table->json('matrix_answers')->nullable(); // untuk jawaban matrix/competency
            $table->timestamps();

            $table->unique(['user_response_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('response_details');
    }
};
