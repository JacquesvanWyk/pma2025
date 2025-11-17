<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sermons', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('series_id')->nullable()->constrained('sermon_series')->onDelete('set null');
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->longText('content');
            $table->text('excerpt')->nullable();
            $table->date('sermon_date');
            $table->time('sermon_time')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->string('primary_scripture')->nullable();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('template_id')->nullable()->constrained('sermon_templates')->onDelete('set null');
            $table->string('language', 10)->default('en');
            $table->enum('status', ['draft', 'review', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('downloads_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('sermon_date');
            $table->index('status');
            $table->index('language');
            $table->index('author_id');
            $table->index('series_id');

            // Only create fulltext index on MySQL
            if (DB::connection()->getDriverName() === 'mysql') {
                $table->fullText(['title', 'subtitle', 'content', 'excerpt']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sermons');
    }
};
