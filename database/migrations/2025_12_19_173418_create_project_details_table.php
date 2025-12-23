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
        Schema::create('project_details', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();

            $table->foreignId('project_field_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->foreignId('project_status_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('progress_status_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->unsignedInteger('member_count')->default(0);
            $table->unsignedInteger('like_count')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_details');
    }
};
