<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('distance', 8, 1)->nullable();
            $table->unsignedInteger('duration')->nullable();
            $table->Integer('elevation')->nullable();
            $table->string('notes')->nullable();
            $table->foreignId('training_type_id')->constrained('training_types')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->index(['date', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('trainings');
    }
};