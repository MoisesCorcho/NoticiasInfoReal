<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\EnumHomepageSectionLayout;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();

            // La categoría que queremos mostrar
            $table->foreignId('category_id')
                ->constrained('categories', 'id_category')
                ->onDelete('cascade');

            // Título opcional (si es nulo, usamos el nombre de la categoría)
            $table->string('display_title')->nullable();

            // Para controlar el orden de las secciones en la portada
            $table->integer('display_order')->default(0);

            // Para activar/desactivar esta sección de la portada
            $table->boolean('is_active')->default(true);

            $table->enum('layout', EnumHomepageSectionLayout::values())
                  ->default(EnumHomepageSectionLayout::Grid->value)
                  ->comment('Controla el estilo visual de la sección (ej. grid, magazine)');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_sections');
    }
};
