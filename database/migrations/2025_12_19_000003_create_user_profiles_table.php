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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('gender')->nullable();
            $table->date('birth')->nullable();
            $table->string('photo_profile')->nullable();
            $table->string('background_image')->nullable();

            $table->text('description')->nullable();
            $table->string('short_description', 255)->nullable();

            $table->foreignId('career_position_id')->nullable()->constrained('career_positions')->nullOnDelete();

            $table->string('email')->nullable();

            $table->enum('status', ['public', 'private'])->default('public');

            $table->unsignedInteger('followers')->default(0);
            $table->unsignedInteger('following')->default(0);

            $table->foreignId('subscription_tier_id')->nullable()->constrained('subscription_tiers')->nullOnDelete();

            $table->timestamp('last_seen_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
