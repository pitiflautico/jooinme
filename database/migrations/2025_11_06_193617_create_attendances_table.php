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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('schedule_slot_id')->constrained('schedule_slots')->onDelete('cascade');
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->enum('status', ['confirmed', 'attended', 'no_show', 'cancelled'])->default('confirmed');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'schedule_slot_id']);
            $table->index('user_id');
            $table->index('schedule_slot_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
