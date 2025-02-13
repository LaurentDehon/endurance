<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weeks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->integer('year')->index();
            $table->integer('week_number')->index();
            $table->foreignId('week_type_id')->nullable()->constrained();
            $table->timestamps();

            $table->unique(['user_id', 'year', 'week_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weeks');
    }
};
