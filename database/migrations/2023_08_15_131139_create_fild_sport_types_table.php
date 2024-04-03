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
        Schema::create('fild_sport_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fild_id')->nullable();
            $table->unsignedBigInteger('sport_id')->nullable();
            $table->foreign('fild_id')->references('id')->on('filds')->onDelete('cascade');
            $table->foreign('sport_id')->references('id')->on('sport_types')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fild_sport_types');
    }
};
