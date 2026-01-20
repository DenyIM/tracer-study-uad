<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('alumnis', function (Blueprint $table) {
            $table->string('temp_nim')->nullable()->after('nim');
            $table->boolean('has_temp_nim')->default(false)->after('is_data_complete');
        });
    }

    public function down()
    {
        Schema::table('alumnis', function (Blueprint $table) {
            $table->dropColumn(['temp_nim', 'has_temp_nim']);
        });
    }
};