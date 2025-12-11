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
        Schema::table('video_projects', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('background_images');
            $table->string('logo_position')->default('bottom-right')->after('logo_path');
        });
    }

    public function down(): void
    {
        Schema::table('video_projects', function (Blueprint $table) {
            $table->dropColumn(['logo_path', 'logo_position']);
        });
    }
};
