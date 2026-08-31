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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publisher_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('slug')->unique();
            $table->string('isbn_10', 20)->nullable()->unique();
            $table->string('isbn_13', 20)->nullable()->unique();
            $table->text('description')->nullable();
            $table->string('language', 10)->default('en');
            $table->smallInteger('publication_year')->nullable();
            $table->string('edition')->nullable();
            $table->unsignedSmallInteger('page_count')->nullable();
            $table->string('cover_image_path')->nullable();
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->unsignedInteger('ratings_count')->default(0);
            $table->string('source')->nullable();
            $table->string('source_identifier')->nullable();
            $table->string('source_url')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['language', 'publication_year']);
            $table->index(['title', 'publication_year']);
            $table->unique(['source', 'source_identifier']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
