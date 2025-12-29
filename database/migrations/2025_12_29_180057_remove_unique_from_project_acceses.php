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
        Schema::table('project_acceses', function (Blueprint $table) {
            $table->dropForeign(['access_user_id']);
            $table->dropForeign(['project_detail_id']);
            
            $table->dropUnique('project_acceses_access_user_id_project_detail_id_unique');
            
            $table->foreign('access_user_id')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete();

            $table->foreign('project_detail_id')
                  ->references('id')
                  ->on('project_details')
                  ->cascadeOnDelete();

            $table->index(['access_user_id', 'project_detail_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_acceses', function (Blueprint $table) {
            $table->dropIndex(['access_user_id', 'project_detail_id']);
            $table->unique(['access_user_id', 'project_detail_id'], 'project_acceses_access_user_id_project_detail_id_unique');
        });
    }
};