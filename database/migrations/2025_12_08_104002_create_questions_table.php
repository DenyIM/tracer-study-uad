<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained()->onDelete('cascade');
            $table->text('question_text');
            $table->string('question_type'); // radio, dropdown, text, textarea, date, number, checkbox, likert_per_row
            $table->text('description')->nullable();
            $table->json('options')->nullable(); // Untuk radio, dropdown, checkbox
            $table->json('scale_options')->nullable(); // Opsi untuk skala likert [1,2,3,4,5]
            $table->json('scale_information')->nullable(); // Keterangan untuk setiap opsi skala
            $table->json('row_items')->nullable(); // Item untuk pertanyaan per baris (kompetensi, metode)
            $table->string('scale_label_low')->nullable(); // Label untuk skala rendah (e.g., "Sangat Rendah")
            $table->string('scale_label_high')->nullable(); // Label untuk skala tinggi (e.g., "Sangat Tinggi")
            $table->integer('min_value')->nullable(); // Untuk input angka: nilai minimum
            $table->integer('max_value')->nullable(); // Untuk input angka: nilai maksimum
            $table->string('input_type')->nullable(); // Untuk text input: text, email, phone, dll
            $table->integer('max_length')->nullable(); // Maksimal karakter
            $table->integer('rows')->default(3); // Untuk textarea: jumlah baris
            $table->boolean('is_required')->default(true);
            $table->integer('order')->default(0);
            $table->integer('points')->default(0);
            $table->json('validation_rules')->nullable();
            $table->string('placeholder')->nullable();
            $table->text('helper_text')->nullable(); // Teks bantuan di bawah pertanyaan
            $table->boolean('has_other_option')->default(false);
            $table->boolean('has_none_option')->default(false);
            $table->boolean('is_locked_by_default')->default(false);
            $table->boolean('allow_multiple_selection')->default(false); // Untuk checkbox
            $table->integer('max_selections')->nullable(); // Maksimal pilihan (untuk checkbox)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
};