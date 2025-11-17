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
        Schema::create('sermon_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sermon_id')->constrained('sermons')->onDelete('cascade');
            $table->date('date');
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('unique_views')->default(0);
            $table->unsignedInteger('downloads')->default(0);
            $table->unsignedInteger('shares')->default(0);
            $table->unsignedInteger('average_read_time_seconds')->default(0);
            $table->timestamps();

            $table->unique(['sermon_id', 'date'], 'unique_sermon_date');
            $table->index('date', 'idx_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sermon_analytics');
    }
};
