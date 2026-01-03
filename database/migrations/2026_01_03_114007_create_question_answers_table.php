<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('question_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('answer_question_id')->constrained()->onDelete('cascade');
            $table->string('item_key')->nullable(); // Untuk pertanyaan per baris: key dari row item
            $table->string('item_label')->nullable(); // Label dari row item
            $table->text('text_answer')->nullable(); // Jawaban text untuk item
            $table->integer('scale_value')->nullable(); // Nilai skala untuk item
            $table->json('selected_options')->nullable(); // Opsi terpilih untuk item
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();
            
            $table->index(['answer_question_id', 'item_key']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('question_answers');
    }
};