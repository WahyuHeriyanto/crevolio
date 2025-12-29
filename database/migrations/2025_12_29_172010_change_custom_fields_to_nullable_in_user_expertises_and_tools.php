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
        Schema::table('user_expertises', function (Blueprint $table) {
            // Mengubah kolom menjadi nullable
            $table->string('custom_expertise')->nullable()->change();
        });

        Schema::table('user_tools', function (Blueprint $table) {
            // Mengubah kolom menjadi nullable
            $table->string('custom_tool')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_expertises', function (Blueprint $table) {
            $table->string('custom_expertise')->nullable(false)->change();
        });

        Schema::table('user_tools', function (Blueprint $table) {
            $table->string('custom_tool')->nullable(false)->change();
        });
    }
};
