<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained()->onDelete('cascade');
            $table->string('code')->nullable(); // F8, F502, F12, dll
            $table->text('question_text');
            $table->text('description')->nullable();
            $table->enum('type', [
                'dropdown',
                'radio',
                'checkbox',
                'text',
                'textarea',
                'number',
                'date',
                'scale', // likert scale
                'competency_scale', // khusus kompetensi
                'matrix' // table/matrix
            ]);
            $table->integer('order')->default(0);
            $table->boolean('is_required')->default(true);
            $table->boolean('has_other_option')->default(false); // punya opsi "Lainnya"
            $table->json('validation_rules')->nullable(); // rules validasi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
