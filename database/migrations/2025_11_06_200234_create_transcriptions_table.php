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
        Schema::create('transcriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_slot_id')->constrained()->onDelete('cascade');
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->longText('content');
            $table->longText('summary')->nullable();
            $table->json('key_points')->nullable();
            $table->json('participants')->nullable();
            $table->string('language')->default('en');
            $table->enum('status', ['processing', 'completed', 'failed'])->default('processing');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index('schedule_slot_id');
            $table->index('conversation_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transcriptions');
    }
};
