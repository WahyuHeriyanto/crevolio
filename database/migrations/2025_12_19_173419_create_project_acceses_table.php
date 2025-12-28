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
        Schema::create('project_acceses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('access_user_id')->constrained('users')->cascadeOnDelete();

            $table->tinyInteger('access_level')
                ->comment('0: member, 1: owner, 2: blocked');

            $table->string('project_role')->nullable();

            $table->foreignId('project_detail_id')->constrained('project_details')->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['access_user_id', 'project_detail_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_acceses');
    }
};
