<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('months', function (Blueprint $table) {
            $table->id();
            $table->foreignId('year_id')->constrained()->onDelete('cascade');
            $table->integer('month');
            $table->timestamps();
            
            // Unique constraint to prevent duplicate months within a year
            $table->unique(['year_id', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('months');
    }
};
