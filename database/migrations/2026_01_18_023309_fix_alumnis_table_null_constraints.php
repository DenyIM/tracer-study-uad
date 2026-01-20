<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('alumnis', function (Blueprint $table) {
            // Pastikan semua kolom bisa null saat registrasi awal
            $table->string('nim')->nullable()->change();
            $table->string('study_program')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('npwp')->nullable()->change();
            
            // Kolom graduation_date juga nullable
            $table->date('graduation_date')->nullable()->change();
            
            // Tambahkan kolom-kolom baru jika belum ada
            if (!Schema::hasColumn('alumnis', 'temp_nim')) {
                $table->string('temp_nim')->nullable()->after('nim');
            }
            
            if (!Schema::hasColumn('alumnis', 'has_temp_nim')) {
                $table->boolean('has_temp_nim')->default(false)->after('temp_nim');
            }
            
            if (!Schema::hasColumn('alumnis', 'is_data_complete')) {
                $table->boolean('is_data_complete')->default(false)->after('has_temp_nim');
            }
        });
    }

    public function down()
    {
        Schema::table('alumnis', function (Blueprint $table) {
            // Tidak perlu revert jika tidak diperlukan
        });
    }
};