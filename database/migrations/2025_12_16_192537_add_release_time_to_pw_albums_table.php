<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pw_albums', function (Blueprint $table) {
            $table->time('release_time')->nullable()->after('release_date');
        });
    }

    public function down(): void
    {
        Schema::table('pw_albums', function (Blueprint $table) {
            $table->dropColumn('release_time');
        });
    }
};
