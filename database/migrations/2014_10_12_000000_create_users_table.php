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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('weight')->nullable();
            $table->string('gender')->nullable();
            $table->string('sport_modality')->nullable();
            $table->date('birth_day')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('email_verify_code')->nullable();
            $table->string('email_forgot_code')->nullable();
            $table->string('email_candidate')->nullable();
            $table->string('email_candidate_code')->nullable();
            $table->decimal('balance',10,2)->default(0);
            $table->string('password')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
