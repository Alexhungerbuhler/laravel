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
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('race', ['humain'])->default('humain');
            $table->enum('class', ['guerrier'])->default('guerrier');
            $table->integer('level')->default(1);
            $table->integer('xp')->default(0);
            $table->integer('hp')->default(20);
            $table->integer('max_hp')->default(20);
            $table->integer('armor')->default(0);
            $table->integer('power')->default(8);
            $table->integer('x')->default(0);
            $table->integer('y')->default(0);
            $table->json('equipped_items')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character');
    }
};
