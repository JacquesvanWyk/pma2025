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
        Schema::create('prayer_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('request');
            $table->string('prayer_room_date')->nullable(); // Which prayer room session
            $table->enum('status', ['pending', 'prayed', 'answered'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->boolean('is_private')->default(false); // If user wants it kept private
            $table->boolean('emailed')->default(false); // Track if email was sent
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prayer_requests');
    }
};
