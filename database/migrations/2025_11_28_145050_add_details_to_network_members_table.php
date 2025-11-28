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
        Schema::table('network_members', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('bio');
            $table->json('professional_skills')->nullable()->after('image_path');
            $table->json('ministry_skills')->nullable()->after('professional_skills');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('network_members', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'professional_skills', 'ministry_skills']);
        });
    }
};
