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
        Schema::table('pw_songs', function (Blueprint $table) {
            $table->unsignedInteger('play_count')->default(0)->after('bundle_download_count');
        });
    }

    public function down(): void
    {
        Schema::table('pw_songs', function (Blueprint $table) {
            $table->dropColumn('play_count');
        });
    }
};
