<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dosen', function (Blueprint $table) {
            $table->boolean('is_ketua_prodi')->default(false)->after('fakultas');
            $table->string('prodi_yang_dikelola')->nullable()->after('is_ketua_prodi');
        });
    }

    public function down(): void
    {
        Schema::table('dosen', function (Blueprint $table) {
            $table->dropColumn(['is_ketua_prodi', 'prodi_yang_dikelola']);
        });
    }
};
