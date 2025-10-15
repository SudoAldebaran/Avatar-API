<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvatarCompletTable extends Migration
{
    public function up()
    {
        Schema::create('avatar_complet', function (Blueprint $table) {
            $table->id(); 
            $table->uuid('avatar_id')->unique(); // UUID unique pour chaque avatar
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('avatar_name');
            $table->longtext('avatar_svg'); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('avatar_complet');
    }
}