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
            $table->integer('total_believers')->default(1)->after('bio');
            $table->json('household_members')->nullable()->after('total_believers');
            $table->boolean('show_household_members')->default(false)->after('household_members');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('network_members', function (Blueprint $table) {
            $table->dropColumn(['total_believers', 'household_members', 'show_household_members']);
        });
    }
};
