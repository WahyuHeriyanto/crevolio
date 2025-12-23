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
        Schema::create('portfolio_tools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('tool_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('custom_tool')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_tools');
    }
};
