<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{    
    public function up(): void
    {
        // Drop foreign key constraint first
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropForeign(['training_type_id']);
        });

        // Rename the training_types table to workout_types
        Schema::rename('training_types', 'workout_types');
        
        // Rename the trainings table to workouts
        Schema::rename('trainings', 'workouts');
        
        // Rename the foreign key column and recreate the constraint
        Schema::table('workouts', function (Blueprint $table) {
            $table->renameColumn('training_type_id', 'workout_type_id');
            $table->foreign('workout_type_id')
                  ->references('id')
                  ->on('workout_types')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        // Drop foreign key constraint first
        Schema::table('workouts', function (Blueprint $table) {
            $table->dropForeign(['workout_type_id']);
        });

        // Rename the workout_types table back to training_types
        Schema::rename('workout_types', 'training_types');
        
        // Rename the workouts table back to trainings
        Schema::rename('workouts', 'trainings');
        
        // Rename the foreign key column back and recreate the constraint
        Schema::table('trainings', function (Blueprint $table) {
            $table->renameColumn('workout_type_id', 'training_type_id');
            $table->foreign('training_type_id')
                  ->references('id')
                  ->on('training_types')
                  ->cascadeOnDelete();
        });
    }
};
