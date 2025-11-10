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
        Schema::create('article_tag', function (Blueprint $table) {
            // Clave primaria compuesta es opcional pero recomendada para evitar duplicados exactos
            $table->primary(['article_id', 'tag_id']);

            $table->foreignId('article_id')
                  ->constrained('articles', 'id_article')
                  ->onDelete('cascade');

            $table->foreignId('tag_id')
                  ->constrained('tags', 'id_tag')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_tag');
    }
};
