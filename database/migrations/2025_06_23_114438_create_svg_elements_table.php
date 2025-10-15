<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('svg_elements', function (Blueprint $table) {
            $table->id('id_svg'); // Clé primaire auto-incrémentée
            $table->string('element_type', 50); // Ex: 'nose', 'eyes', 'hair'
            $table->string('element_name', 50); // Ex: 'aquilin', 'chibi', 'longs'
            $table->text('svg_content'); // Contenu du fichier SVG
            $table->unique(['element_type', 'element_name']); // Unicité sur type et nom
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('svg_elements');
    }
};
