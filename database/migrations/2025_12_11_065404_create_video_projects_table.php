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
        Schema::create('video_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type')->default('lyric_video'); // lyric_video, slideshow, composite
            $table->string('status')->default('draft'); // draft, processing, completed, failed
            $table->text('description')->nullable();

            // Audio source
            $table->foreignId('audio_media_id')->nullable()->constrained('generated_media')->nullOnDelete();
            $table->string('audio_path')->nullable();
            $table->string('audio_url')->nullable();
            $table->integer('audio_duration_ms')->nullable();

            // Background configuration
            $table->string('background_type')->default('color'); // color, image, video, slideshow
            $table->string('background_value')->nullable(); // hex color or path
            $table->json('background_images')->nullable(); // for slideshow mode

            // Text styling
            $table->json('text_style')->nullable(); // font, size, color, position, animation

            // Output settings
            $table->string('resolution')->default('1080p'); // 720p, 1080p, 4k
            $table->string('aspect_ratio')->default('16:9'); // 16:9, 9:16, 1:1
            $table->integer('fps')->default(30);

            // Output file
            $table->string('output_path')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->integer('output_duration_ms')->nullable();
            $table->bigInteger('output_size_bytes')->nullable();

            // Processing info
            $table->json('settings')->nullable();
            $table->json('metadata')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('processing_started_at')->nullable();
            $table->timestamp('processing_completed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_projects');
    }
};
