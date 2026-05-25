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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt')->nullable();
            $table->longText('description')->nullable();

            // Stored as JSON arrays
            // ingredients: [{ "name": "saffron", "amount": "1", "unit": "pinch" }]
            // steps:       [{ "order": 1, "instruction": "Heat the oil...", "tip": null }]
            $table->json('ingredients')->nullable();
            $table->json('steps')->nullable();

            // Timing & servings
            $table->unsignedSmallInteger('prep_time')->nullable()->comment('minutes');
            $table->unsignedSmallInteger('cook_time')->nullable()->comment('minutes');
            $table->unsignedSmallInteger('servings')->default(4);

            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->string('country')->nullable();
            $table->string('region')->nullable(); // e.g. Valencia, Andalusia, Catalonia

            // Relationships
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Publishing
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
