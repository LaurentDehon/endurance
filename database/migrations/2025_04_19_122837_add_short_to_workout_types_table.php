<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workout_types', function (Blueprint $table) {
            $table->string('short', 5)->nullable()->after('icon');
        });
    }

    public function down(): void
    {
        Schema::table('workout_types', function (Blueprint $table) {
            $table->dropColumn('short');
        });
    }
};
