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
        Schema::create('user_expertises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_profile_id')->nullable()->constrained('user_profiles')->nullOnDelete();
            $table->foreignId('expertise_id')->nullable()->constrained('expertises')->nullOnDelete();
            $table->string('custom_expertise');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_expertises');
    }
};
