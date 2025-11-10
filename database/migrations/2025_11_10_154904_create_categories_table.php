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
        Schema::create('categories', function (Blueprint $table) {
            $table->id('id_category'); // Clave primaria personalizada
            $table->string('name');
            $table->string('slug')->unique();
            // Relación recursiva para subcategorías. Puede ser nulo si es categoría principal.
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('categories', 'id_category')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
