<?php
// database/migrations/2024_01_01_000003_create_dosen_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dosen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nidn')->unique();
            $table->string('nama_lengkap');
            $table->string('program_studi');
            $table->string('fakultas');
            $table->string('jabatan_fungsional')->nullable();
            $table->string('no_hp')->nullable();
            $table->integer('kuota_bimbingan')->default(5);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('dosen'); }
};