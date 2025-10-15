<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('svg_elements', function (Blueprint $table) {
            $table->mediumText('svg_content')->change();
        });
    }

    public function down(): void
    {
        Schema::table('svg_elements', function (Blueprint $table) {
            $table->text('svg_content')->change();
        });
    }
};
