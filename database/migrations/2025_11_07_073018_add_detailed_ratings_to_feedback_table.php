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
        Schema::table('feedback', function (Blueprint $table) {
            $table->integer('content_rating')->unsigned()->nullable()->after('rating');
            $table->integer('organization_rating')->unsigned()->nullable()->after('content_rating');
            $table->integer('atmosphere_rating')->unsigned()->nullable()->after('organization_rating');
            $table->text('testimonial')->nullable()->after('comment');
            $table->boolean('is_public')->default(false)->after('testimonial');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->dropColumn(['content_rating', 'organization_rating', 'atmosphere_rating', 'testimonial', 'is_public']);
        });
    }
};
