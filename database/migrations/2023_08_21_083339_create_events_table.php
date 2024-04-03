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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('fild_id')->nullable();
            $table->unsignedBigInteger('grafik_id')->nullable();
            $table->date('date')->nullable();
            $table->decimal('price',10,2)->default(0);

            $table->foreign('fild_id')->references('id')->on('filds')->onDelete('cascade');
            $table->foreign('grafik_id')->references('id')->on('fild_grafiks')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
