<?php
// database/migrations/2024_01_01_000005_create_pengajuan_magang_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengajuan_magang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengajuan')->unique();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignId('mitra_id')->constrained('mitra')->onDelete('cascade');
            $table->foreignId('dosen_id')->nullable()->constrained('dosen')->nullOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('bidang_kerja');
            $table->text('deskripsi_pekerjaan')->nullable();
            $table->string('surat_pengantar');
            $table->string('proposal')->nullable();

            // Status alur persetujuan berlapis
            $table->enum('status', [
                'draft',
                'diajukan',         // Mahasiswa submit
                'review_koordinator',
                'disetujui_koordinator',
                'ditolak_koordinator',
                'review_mitra',
                'diterima_mitra',
                'ditolak_mitra',
                'berjalan',
                'selesai',
                'dibatalkan',
            ])->default('draft');

            $table->text('catatan_koordinator')->nullable();
            $table->text('catatan_mitra')->nullable();
            $table->timestamp('disetujui_koordinator_at')->nullable();
            $table->timestamp('diterima_mitra_at')->nullable();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pengajuan_magang'); }
};