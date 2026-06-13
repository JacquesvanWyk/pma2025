<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camp_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accommodation_type_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedTinyInteger('adults')->default(2);
            $table->unsignedTinyInteger('children')->default(0);
            $table->unsignedTinyInteger('nights')->default(1);
            $table->date('arrival_date')->nullable();
            $table->date('departure_date')->nullable();
            $table->decimal('estimated_total', 10, 2)->default(0);
            $table->decimal('deposit_amount', 10, 2)->default(0);
            $table->boolean('deposit_paid')->default(false);
            $table->timestamp('deposit_paid_at')->nullable();
            $table->string('proof_of_payment')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camp_bookings');
    }
};
