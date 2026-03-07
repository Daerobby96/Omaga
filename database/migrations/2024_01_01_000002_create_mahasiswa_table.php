<?php
// database/migrations/2024_01_01_000002_create_mahasiswa_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nim')->unique();
            $table->string('nama_lengkap');
            $table->string('program_studi');
            $table->string('fakultas');
            $table->integer('semester');
            $table->string('angkatan', 4);
            $table->string('no_hp')->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto')->nullable();
            $table->string('cv')->nullable();
            $table->string('transkrip')->nullable();
            $table->float('ipk', 3, 2)->default(0.00);
            $table->enum('status_akademik', ['aktif', 'cuti', 'lulus'])->default('aktif');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('mahasiswa'); }
};