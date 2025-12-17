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
        Schema::create('pw_songs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained('pw_albums')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->integer('track_number');
            $table->string('duration')->nullable();
            $table->string('wav_file')->nullable();
            $table->string('mp4_video')->nullable();
            $table->text('description')->nullable();
            $table->text('lyrics')->nullable();
            $table->boolean('is_published')->default(false);
            $table->integer('download_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['album_id', 'track_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pw_songs');
    }
};
