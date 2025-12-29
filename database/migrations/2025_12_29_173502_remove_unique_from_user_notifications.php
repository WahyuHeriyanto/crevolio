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
        Schema::table('user_notifications', function (Blueprint $table) {
            // 1. Hapus Foreign Key dulu (nama default: namaTabel_namaKolom_foreign)
            $table->dropForeign(['user_id']);
            
            // 2. Sekarang baru bisa hapus index Unique-nya
            $table->dropUnique(['user_id']); 
            
            // 3. Pasang lagi Foreign Key-nya (tanpa embel-embel unique)
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete();
                  
            // Tambahan: Tambahkan index biasa agar query tetap cepat
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_notifications', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->unique(['user_id']);
        });
    }
};