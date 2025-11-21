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
        Schema::create('individuals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('profile_photo')->nullable();
            $table->text('bio')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('show_phone')->default(false);
            $table->boolean('show_email')->default(false);
            $table->string('city');
            $table->string('country');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->json('focus_areas')->nullable();
            $table->json('languages')->nullable();
            $table->json('skills')->nullable();
            $table->json('needs')->nullable(); // What they need: funding, prayer, skills, etc.
            $table->json('offers')->nullable(); // What they offer: teaching, medical, etc.
            $table->json('tags')->nullable();
            $table->enum('privacy_level', ['public', 'network_only'])->default('network_only');
            $table->boolean('is_approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individuals');
    }
};
