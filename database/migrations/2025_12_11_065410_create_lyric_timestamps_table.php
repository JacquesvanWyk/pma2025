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
        Schema::create('lyric_timestamps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_project_id')->constrained()->cascadeOnDelete();
            $table->integer('order')->default(0);
            $table->text('text');
            $table->string('section')->nullable(); // verse, chorus, bridge, intro, outro
            $table->integer('start_ms'); // milliseconds
            $table->integer('end_ms'); // milliseconds
            $table->integer('duration_ms')->virtualAs('end_ms - start_ms');
            $table->json('style_overrides')->nullable(); // per-line style overrides
            $table->string('animation')->nullable(); // fade, slide, karaoke, typewriter
            $table->timestamps();

            $table->index(['video_project_id', 'order']);
            $table->index(['video_project_id', 'start_ms']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lyric_timestamps');
    }
};
