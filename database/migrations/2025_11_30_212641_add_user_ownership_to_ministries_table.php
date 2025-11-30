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
        Schema::table('ministries', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->string('status')->default('pending')->after('is_active');
            $table->text('rejection_reason')->nullable()->after('status');
            $table->timestamp('rejected_at')->nullable()->after('rejection_reason');
            $table->foreignId('rejected_by')->nullable()->after('rejected_at')->constrained('users')->nullOnDelete();
            $table->text('address')->nullable()->after('longitude');
            $table->string('province')->nullable()->after('city');
            $table->string('email')->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->boolean('show_email')->default(true)->after('phone');
            $table->boolean('show_phone')->default(false)->after('show_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ministries', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['rejected_by']);
            $table->dropColumn([
                'user_id',
                'status',
                'rejection_reason',
                'rejected_at',
                'rejected_by',
                'address',
                'province',
                'email',
                'phone',
                'show_email',
                'show_phone',
            ]);
        });
    }
};
