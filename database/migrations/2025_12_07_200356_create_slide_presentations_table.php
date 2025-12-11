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
        Schema::create('slide_presentations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('source_text');
            $table->json('outline')->nullable();
            $table->json('slides')->nullable();
            $table->string('status')->default('draft');
            $table->string('style')->default('biblical-classic');
            $table->integer('current_slide')->default(0);
            $table->integer('total_slides')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slide_presentations');
    }
};
