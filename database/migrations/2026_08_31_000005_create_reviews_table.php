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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating'); // 1 to 5
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('status', 20)->default('approved'); // approved, pending, rejected
            $table->timestamps();

            // One review per user per book
            $table->unique(['user_id', 'book_id']);
            $table->index(['book_id', 'status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
