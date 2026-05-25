<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();

            // Polymorphic — attaches to Recipe, Category, or any future model
            $table->morphs('mediable');

            // Collection groups media by purpose
            // featured, gallery, step, video, category, document
            $table->string('collection')->default('default');

            // For uploaded files (images, documents)
            $table->string('path')->nullable();
            $table->string('filename')->nullable();
            $table->string('disk')->default('public');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable()->comment('bytes');

            // For external embeds (YouTube, Vimeo)
            // disk = 'external' when url is used
            $table->string('url')->nullable();

            $table->string('alt')->nullable();
            $table->enum('type', ['image', 'video', 'document'])->default('image');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};