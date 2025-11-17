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
        Schema::create('church_assets', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['logo', 'watermark', 'background', 'slide_template']);
            $table->string('name', 255);
            $table->foreignId('media_file_id')->constrained('media_files')->onDelete('cascade');
            $table->boolean('is_default')->default(false);
            $table->json('settings')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['type', 'is_default'], 'idx_type_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('church_assets');
    }
};
