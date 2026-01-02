<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Role mapping:
     * - admin -> admin (unchanged)
     * - senior_pastor -> pastor
     * - pastor -> pastor (unchanged)
     * - member -> team_member
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            // Step 1: Expand ENUM to include both old and new values
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'senior_pastor', 'pastor', 'member', 'team_member') DEFAULT 'member'");

            // Step 2: Migrate data from old roles to new roles
            DB::table('users')
                ->where('role', 'senior_pastor')
                ->update(['role' => 'pastor']);

            DB::table('users')
                ->where('role', 'member')
                ->update(['role' => 'team_member']);

            // Step 3: Shrink ENUM to only new values
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pastor', 'team_member') DEFAULT 'team_member'");
        } else {
            // For SQLite (used in testing), just update the data
            DB::table('users')
                ->where('role', 'senior_pastor')
                ->update(['role' => 'pastor']);

            DB::table('users')
                ->where('role', 'member')
                ->update(['role' => 'team_member']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            // Restore original enum with all old values
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'senior_pastor', 'pastor', 'member') DEFAULT 'member'");
        }

        // Note: We cannot fully reverse the data migration because
        // we don't know which 'pastor' users were originally 'senior_pastor'
        // and which were already 'pastor'. Same applies to team_member -> member.
    }
};
