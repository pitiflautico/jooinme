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
        Schema::create('mentorships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mentee_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'accepted', 'active', 'completed', 'cancelled'])->default('pending');
            $table->decimal('price', 10, 2)->nullable(); // Precio por sesión
            $table->string('currency')->default('USD');
            $table->integer('duration_minutes')->default(60);
            $table->json('availability')->nullable(); // Disponibilidad del mentor
            $table->dateTime('next_session_at')->nullable();
            $table->integer('total_sessions')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('mentor_id');
            $table->index('mentee_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentorships');
    }
};
