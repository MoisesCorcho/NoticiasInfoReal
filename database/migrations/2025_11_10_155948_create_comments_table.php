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
        Schema::create('comments', function (Blueprint $table) {
            $table->id('id_comment');

            $table->foreignId('article_id')
                ->constrained('articles', 'id_article')
                ->onDelete('cascade');

            $table->string('author_name');
            $table->string('author_email');
            $table->text('content');

            $table->enum('status', ['approved', 'pending', 'spam'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
