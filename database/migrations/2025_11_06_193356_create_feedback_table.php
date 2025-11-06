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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_slot_id')->constrained('schedule_slots')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Quien da el feedback
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->foreignId('rated_user_id')->nullable()->constrained('users')->nullOnDelete(); // Usuario valorado (opcional)
            $table->integer('rating')->unsigned(); // 1-5
            $table->text('comment')->nullable();
            $table->boolean('attended')->default(true);
            $table->boolean('would_recommend')->default(true);
            $table->json('tags')->nullable(); // Tags positivos/negativos
            $table->timestamps();

            $table->index('schedule_slot_id');
            $table->index('user_id');
            $table->index('conversation_id');
            $table->index('rated_user_id');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
