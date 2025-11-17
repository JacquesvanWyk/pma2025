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
        Schema::create('sermon_slides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sermon_id')->constrained('sermons')->onDelete('cascade');
            $table->unsignedInteger('slide_number')->default(1);
            $table->string('slide_type', 50)->default('content'); // title, outline, content, scripture, application, conclusion
            $table->text('html_content'); // Full HTML of the slide
            $table->text('css_styles')->nullable(); // Custom CSS for this slide
            $table->string('background_type', 50)->default('color'); // color, gradient, image
            $table->string('background_value')->nullable(); // hex color, gradient CSS, or image URL
            $table->json('ai_prompt_history')->nullable(); // Track prompts that created/modified this slide
            $table->json('metadata')->nullable(); // Additional data (theme, fonts, etc.)
            $table->timestamps();

            $table->index('sermon_id');
            $table->index(['sermon_id', 'slide_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sermon_slides');
    }
};
