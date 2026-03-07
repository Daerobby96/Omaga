<?php
// database/migrations/2024_01_01_000006_create_logbook_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('logbook', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan_magang')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->text('kegiatan');
            $table->text('hasil')->nullable();
            $table->text('kendala')->nullable();
            $table->string('foto_kegiatan')->nullable();
            $table->enum('status', ['draft', 'submitted', 'disetujui', 'revisi'])->default('draft');
            $table->text('catatan_dosen')->nullable();
            $table->text('catatan_mitra')->nullable();
            $table->timestamp('disetujui_at')->nullable();
            $table->timestamps();
        });

        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan_magang')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');

            // Nilai dari Dosen Pembimbing
            $table->decimal('nilai_pembimbingan', 5, 2)->nullable();
            $table->decimal('nilai_laporan', 5, 2)->nullable();
            $table->decimal('nilai_seminar', 5, 2)->nullable();
            $table->text('catatan_dosen')->nullable();
            $table->timestamp('dinilai_dosen_at')->nullable();

            // Nilai dari Mitra / Supervisor
            $table->decimal('nilai_kedisiplinan', 5, 2)->nullable();
            $table->decimal('nilai_kemampuan_teknis', 5, 2)->nullable();
            $table->decimal('nilai_komunikasi', 5, 2)->nullable();
            $table->decimal('nilai_inisiatif', 5, 2)->nullable();
            $table->decimal('nilai_kerjasama', 5, 2)->nullable();
            $table->text('catatan_mitra')->nullable();
            $table->timestamp('dinilai_mitra_at')->nullable();

            // Nilai Akhir (dihitung otomatis)
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->string('grade', 2)->nullable(); // A, B, C, D, E
            $table->boolean('lulus')->default(false);

            $table->timestamps();
        });

        Schema::create('sertifikat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan_magang')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->string('nomor_sertifikat')->unique();
            $table->string('file_sertifikat')->nullable();
            $table->timestamp('diterbitkan_at')->nullable();
            $table->foreignId('diterbitkan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('judul');
            $table->text('pesan');
            $table->string('tipe'); // info, success, warning, danger
            $table->string('url')->nullable();
            $table->boolean('dibaca')->default(false);
            $table->timestamp('dibaca_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
        Schema::dropIfExists('sertifikat');
        Schema::dropIfExists('penilaian');
        Schema::dropIfExists('logbook');
    }
};