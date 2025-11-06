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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('slug')->unique();

            // Configuración de frecuencia
            $table->enum('frequency', ['once', 'daily', 'weekly', 'biweekly', 'monthly'])->default('weekly');
            $table->integer('duration_minutes')->default(60); // Duración en minutos
            $table->time('preferred_time')->nullable();
            $table->string('day_of_week')->nullable(); // Para reuniones semanales
            $table->integer('day_of_month')->nullable(); // Para reuniones mensuales

            // Tipo de conversación
            $table->enum('type', ['online', 'in_person', 'hybrid'])->default('online');

            // Ubicación (para presenciales)
            $table->string('location')->nullable();
            $table->text('location_details')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Configuración de privacidad
            $table->enum('privacy', ['public', 'moderated', 'private'])->default('public');
            $table->integer('max_participants')->default(10);
            $table->integer('min_participants')->default(2);

            // Imagen y personalización
            $table->string('cover_image')->nullable();
            $table->json('tags')->nullable();

            // Estado
            $table->enum('status', ['draft', 'active', 'paused', 'completed', 'cancelled'])->default('active');
            $table->boolean('is_featured')->default(false);
            $table->boolean('allow_chat')->default(true);
            $table->boolean('allow_recording')->default(false);
            $table->boolean('auto_approve')->default(true);

            $table->timestamp('first_session_at')->nullable();
            $table->timestamp('last_session_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('owner_id');
            $table->index('topic_id');
            $table->index('slug');
            $table->index('status');
            $table->index('privacy');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
