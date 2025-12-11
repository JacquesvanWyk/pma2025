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
        Schema::create('video_exports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_project_id')->constrained()->cascadeOnDelete();
            $table->string('resolution');
            $table->string('file_path');
            $table->string('thumbnail_path')->nullable();
            $table->unsignedBigInteger('file_size_bytes')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->string('status')->default('completed');
            $table->timestamps();

            $table->index(['video_project_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_exports');
    }
};
