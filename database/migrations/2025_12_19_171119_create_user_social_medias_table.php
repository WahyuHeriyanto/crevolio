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
        Schema::create('user_social_medias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_profile_id')->nullable()->constrained('user_profiles')->nullOnDelete();
            $table->foreignId('category_social_media_id')->nullable()->constrained('category_social_medias')->nullOnDelete();
            $table->string('link');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_social_medias');
    }
};
