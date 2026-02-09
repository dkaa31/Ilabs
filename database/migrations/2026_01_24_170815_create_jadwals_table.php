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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']);
            $table->string('jam_ke');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->foreignId('id_guru')->nullable()->constrained('gurus', 'id_guru')->nullOnDelete();
            $table->foreignId('id_mapel')->nullable()->constrained('mapels', 'id_mapel')->nullOnDelete();
            $table->foreignId('id_ruangan')->nullable()->constrained('ruangans', 'id_ruangan')->nullOnDelete();
            $table->foreignId('id_kelas')->nullable()->constrained('kelas', 'id_kelas')->nullOnDelete();
            $table->enum('status', ['Aktif', 'Istirahat'])->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
