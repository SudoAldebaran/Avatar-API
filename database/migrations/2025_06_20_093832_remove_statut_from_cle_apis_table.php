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
        if (Schema::hasColumn('cle_apis', 'statut')) {
            Schema::table('cle_apis', function (Blueprint $table) {
                $table->dropColumn('statut');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('cle_apis', 'statut')) {
            Schema::table('cle_apis', function (Blueprint $table) {
                $table->tinyInteger('statut')->default(1);
            });
        }
    }
};
