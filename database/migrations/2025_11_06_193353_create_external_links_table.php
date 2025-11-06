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
        Schema::create('external_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->enum('type', ['zoom', 'meet', 'teams', 'jitsi', 'custom'])->default('custom');
            $table->string('url');
            $table->string('meeting_id')->nullable();
            $table->string('password')->nullable();
            $table->json('metadata')->nullable(); // Para datos adicionales del proveedor
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('conversation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_links');
    }
};
