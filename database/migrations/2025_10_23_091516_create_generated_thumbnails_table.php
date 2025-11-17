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
        Schema::create('generated_thumbnails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sermon_id')->constrained('sermons')->onDelete('cascade');
            $table->foreignId('media_file_id')->constrained('media_files')->onDelete('cascade');
            $table->text('prompt');
            $table->string('style_preset', 100)->nullable();
            $table->string('provider', 50)->default('replicate');
            $table->string('model', 100);
            $table->unsignedInteger('generation_time_seconds');
            $table->decimal('cost', 10, 4)->default(0.0000);
            $table->boolean('selected')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->index(['sermon_id', 'selected'], 'idx_sermon_selected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_thumbnails');
    }
};
