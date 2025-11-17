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
        Schema::create('powerpoint_presentations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sermon_id')->constrained('sermons')->onDelete('cascade');
            $table->foreignId('media_file_id')->constrained('media_files')->onDelete('cascade');
            $table->string('template_name', 100)->nullable();
            $table->unsignedInteger('slide_count');
            $table->boolean('includes_animations')->default(false);
            $table->boolean('auto_generated')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('sermon_id', 'idx_sermon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('powerpoint_presentations');
    }
};
