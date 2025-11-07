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
        Schema::table('conversations', function (Blueprint $table) {
            // Add missing columns
            $table->integer('current_participants')->default(0)->after('max_participants');
            $table->string('meeting_platform')->nullable()->after('location_details');
            $table->string('meeting_url')->nullable()->after('meeting_platform');
            $table->timestamp('starts_at')->nullable()->after('last_session_at');
            $table->timestamp('ends_at')->nullable()->after('starts_at');
            $table->boolean('require_approval')->default(false)->after('auto_approve');
            $table->boolean('auto_confirm')->default(true)->after('require_approval');
            $table->boolean('is_active')->default(true)->after('status');
            $table->json('settings')->nullable()->after('tags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn([
                'current_participants',
                'meeting_platform',
                'meeting_url',
                'starts_at',
                'ends_at',
                'require_approval',
                'auto_confirm',
                'is_active',
                'settings',
            ]);
        });
    }
};
