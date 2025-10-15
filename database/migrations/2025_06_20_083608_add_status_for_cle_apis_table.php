<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ajoute la colonne seulement si elle n'existe pas déjà
        if (!Schema::hasColumn('cle_apis', 'status')) {
            Schema::table('cle_apis', function (Blueprint $table) {
                $table->string('status')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprime la colonne si elle existe
        if (Schema::hasColumn('cle_apis', 'status')) {
            Schema::table('cle_apis', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
