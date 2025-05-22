<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Étape 1: Ajouter une référence à year_id dans la table weeks
        // (si elle n'existe pas déjà de façon explicite)
        if (!Schema::hasColumn('weeks', 'year_id')) {
            Schema::table('weeks', function (Blueprint $table) {
                $table->foreignId('year_id')->nullable()->after('user_id')->constrained()->onDelete('cascade');
            });
            
            // Mettre à jour les year_id en utilisant le champ year existant
            DB::statement('UPDATE weeks w JOIN years y ON w.user_id = y.user_id AND w.year = y.year SET w.year_id = y.id');
        }
        
        // Étape 2: Ajouter une référence directe à year_id dans la table days
        Schema::table('days', function (Blueprint $table) {
            $table->foreignId('year_id')->nullable()->after('week_id')->constrained()->onDelete('cascade');
        });
        
        // Étape 3: Mettre à jour year_id dans la table days en se basant sur month_id
        DB::statement('UPDATE days d JOIN months m ON d.month_id = m.id SET d.year_id = m.year_id');
        
        // Étape 4: Supprimer la contrainte de clé étrangère month_id dans days
        Schema::table('days', function (Blueprint $table) {
            $table->dropForeign(['month_id']);
            $table->dropColumn('month_id');
        });
        
        // Étape 5: Supprimer la contrainte de clé étrangère month_id dans weeks (si elle existe)
        if (Schema::hasColumn('weeks', 'month_id')) {
            Schema::table('weeks', function (Blueprint $table) {
                $table->dropForeign(['month_id']);
                $table->dropColumn('month_id');
            });
        }
        
        // Étape 6: Supprimer la table months
        Schema::dropIfExists('months');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recréer la table months
        Schema::create('months', function (Blueprint $table) {
            $table->id();
            $table->foreignId('year_id')->constrained()->onDelete('cascade');
            $table->integer('month');
            $table->timestamps();
            
            // Unique constraint to prevent duplicate months within a year
            $table->unique(['year_id', 'month']);
        });
        
        // Ajouter une référence à month_id dans la table days
        Schema::table('days', function (Blueprint $table) {
            $table->foreignId('month_id')->nullable()->after('id');
        });
        
        // Pour le rollback, on ne peut pas restaurer les données exactes des mois
        // mais on peut essayer de reconstruire les références en fonction des dates
        DB::statement('
            INSERT INTO months (year_id, month, created_at, updated_at)
            SELECT DISTINCT d.year_id, MONTH(d.date), NOW(), NOW()
            FROM days d
            ORDER BY d.year_id, MONTH(d.date)
        ');
        
        // Mettre à jour les références month_id dans la table days
        DB::statement('
            UPDATE days d 
            JOIN months m ON d.year_id = m.year_id AND MONTH(d.date) = m.month
            SET d.month_id = m.id
        ');
        
        // Ajouter month_id à la table weeks si elle existait avant
        Schema::table('weeks', function (Blueprint $table) {
            $table->foreignId('month_id')->nullable()->after('week_type_id');
        });
        
        // Nettoyer
        if (Schema::hasColumn('weeks', 'year_id')) {
            Schema::table('weeks', function (Blueprint $table) {
                $table->dropForeign(['year_id']);
                $table->dropColumn('year_id');
            });
        }
        
        Schema::table('days', function (Blueprint $table) {
            $table->dropForeign(['year_id']);
            $table->dropColumn('year_id');
        });
    }
};
