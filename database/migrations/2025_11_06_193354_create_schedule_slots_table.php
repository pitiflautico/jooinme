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
        Schema::create('schedule_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->dateTime('scheduled_at');
            $table->dateTime('ends_at');
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->integer('confirmed_participants')->default(0);
            $table->integer('attended_participants')->default(0);
            $table->text('notes')->nullable();
            $table->string('recording_url')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();

            $table->index('conversation_id');
            $table->index('scheduled_at');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_slots');
    }
};
