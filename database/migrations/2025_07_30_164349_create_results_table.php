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
        Schema::create('results', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained();
        $table->string('exam_id');           // ID desde el JSON
        $table->string('category');          // lpic1, lpic2, etc.
        $table->integer('score');
        $table->integer('total');
        $table->json('details')->nullable(); // Respuestas incorrectas, etc.
        $table->timestamps();                // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
