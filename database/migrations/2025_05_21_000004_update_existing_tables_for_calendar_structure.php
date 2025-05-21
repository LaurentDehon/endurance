<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modifications de la table Week pour la nouvelle structure
        Schema::table('weeks', function (Blueprint $table) {
            // Ajouter la relation avec le modèle Month
            $table->foreignId('month_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
        });
        
        // Modifications de la table Workout pour la nouvelle structure
        Schema::table('workouts', function (Blueprint $table) {
            // Ajouter la relation avec le modèle Day
            $table->foreignId('day_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
        });
        
        // Modifications de la table Activity pour la nouvelle structure
        Schema::table('activities', function (Blueprint $table) {
            // Ajouter la relation avec le modèle Day
            $table->foreignId('day_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('weeks', function (Blueprint $table) {
            $table->dropForeign(['month_id']);
            $table->dropColumn('month_id');
        });
        
        Schema::table('workouts', function (Blueprint $table) {
            $table->dropForeign(['day_id']);
            $table->dropColumn('day_id');
        });
        
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['day_id']);
            $table->dropColumn('day_id');
        });
    }
};
