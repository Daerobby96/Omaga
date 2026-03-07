<?php
// database/migrations/2024_01_01_000004_create_mitra_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mitra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_perusahaan');
            $table->string('bidang_usaha');
            $table->string('nama_kontak');
            $table->string('jabatan_kontak')->nullable();
            $table->string('email_perusahaan');
            $table->string('telepon')->nullable();
            $table->text('alamat');
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('kuota_magang')->default(0);
            $table->enum('status', ['aktif', 'nonaktif', 'pending'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('mitra'); }
};