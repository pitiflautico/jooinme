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
        Schema::create('participations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'rejected', 'cancelled'])->default('pending');
            $table->enum('role', ['participant', 'co_host', 'moderator'])->default('participant');
            $table->text('join_message')->nullable(); // Mensaje al solicitar unirse
            $table->text('rejection_reason')->nullable();
            $table->boolean('notifications_enabled')->default(true);
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'conversation_id']);
            $table->index('user_id');
            $table->index('conversation_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participations');
    }
};
