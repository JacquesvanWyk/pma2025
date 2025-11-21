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
            $table->string('city')->nullable()->after('latitude');
            $table->string('province')->nullable()->after('city');
            $table->string('country')->nullable()->after('province');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('network_members', function (Blueprint $table) {
            $table->dropColumn(['city', 'province', 'country']);
        });
    }
};
