<?php
// ============================================================
// database/seeders/DatabaseSeeder.php
// ============================================================
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{User, Mahasiswa, Dosen, Mitra};
use Spatie\Permission\Models\{Role, Permission};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Buat Roles ──────────────────────────────
        $roles = ['admin', 'dosen', 'mahasiswa', 'mitra', 'ketua_prodi'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // ── 2. Buat Permissions ────────────────────────
        $permissions = [
            // Admin
            'kelola-mahasiswa', 'kelola-dosen', 'kelola-mitra',
            'kelola-pengajuan', 'setujui-pengajuan', 'tolak-pengajuan',
            'generate-sertifikat', 'lihat-laporan', 'export-laporan',

            // Dosen
            'lihat-bimbingan', 'setujui-logbook', 'revisi-logbook', 'nilai-mahasiswa-dosen',

            // Ketua Prodi
            'lihat-mahasiswa-prodi', 'lihat-logbook-prodi', 'lihat-pengajuan-prodi', 'lihat-nilai-prodi', 'lihat-laporan-prodi',

            // Mahasiswa
            'buat-pengajuan', 'isi-logbook', 'lihat-nilai', 'download-sertifikat',

            // Mitra
            'terima-mahasiswa', 'tolak-mahasiswa', 'nilai-mahasiswa-mitra', 'lihat-mahasiswa-mitra',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ── 3. Assign permissions ke roles ────────────
        Role::findByName('admin')->givePermissionTo(Permission::all());

        Role::findByName('dosen')->givePermissionTo([
            'lihat-bimbingan','setujui-logbook','revisi-logbook','nilai-mahasiswa-dosen',
        ]);

        Role::findByName('ketua_prodi')->givePermissionTo([
            'lihat-mahasiswa-prodi','lihat-logbook-prodi','lihat-pengajuan-prodi','lihat-nilai-prodi','lihat-laporan-prodi',
        ]);

        Role::findByName('mahasiswa')->givePermissionTo([
            'buat-pengajuan','isi-logbook','lihat-nilai','download-sertifikat',
        ]);

        Role::findByName('mitra')->givePermissionTo([
            'terima-mahasiswa','tolak-mahasiswa','nilai-mahasiswa-mitra','lihat-mahasiswa-mitra',
        ]);

        // ── 4. Buat User Admin ─────────────────────────
        $admin = User::firstOrCreate(['email' => 'admin@simaga.ac.id'], [
            'name'     => 'Administrator SIMAGA',
            'password' => Hash::make('password'),
            'telepon'  => '081234567890',
            'is_active'=> true,
        ]);
        $admin->assignRole('admin');

        // ── 5. Buat Sample Dosen ───────────────────────
        $dosenData = [
            ['Dr. Budi Santoso, M.T.',     'dosen1@simaga.ac.id', '1234567890000001', 'Teknik Informatika',    'Teknik'],
            ['Prof. Siti Rahayu, Ph.D.',   'dosen2@simaga.ac.id', '1234567890000002', 'Sistem Informasi',     'Teknik'],
            ['Dr. Ahmad Fauzi, M.Kom.',    'dosen3@simaga.ac.id', '1234567890000003', 'Ilmu Komputer',        'Teknik'],
            ['Dra. Rina Wulandari, M.Si.', 'dosen4@simaga.ac.id', '1234567890000004', 'Manajemen Informatika','Bisnis'],
        ];

        foreach ($dosenData as [$nama, $email, $nidn, $prodi, $fakultas]) {
            $user = User::firstOrCreate(['email'=>$email],[
                'name'=>$nama,'password'=>Hash::make('password'),'is_active'=>true,
            ]);
            $user->assignRole('dosen');
            Dosen::firstOrCreate(['nidn'=>$nidn],[
                'user_id'=>$user->id,'nama_lengkap'=>$nama,
                'program_studi'=>$prodi,'fakultas'=>$fakultas,'kuota_bimbingan'=>5,
            ]);
        }

        // ── 6. Buat Sample Mitra ───────────────────────
        $mitraData = [
            ['PT. Teknologi Nusantara', 'Teknologi Informasi',  'Budi Gunawan',     'mitra1@company.com', 'it@nusantara.co.id', 'Jl. Sudirman No.1 Jakarta',     10],
            ['CV. Digital Kreatif',    'Media & Kreatif',      'Sari Dewi',        'mitra2@company.com', 'info@digitalkreatif.id','Jl. Gatot Subroto Bandung',   5],
            ['PT. Bank Data Indonesia','Perbankan & Keuangan',  'Hermawan Susanto', 'mitra3@company.com', 'hr@bankdata.co.id',  'Jl. Thamrin No.5 Jakarta',     8],
            ['Startup Inovasi Lab',    'Startup & Teknologi',  'Maya Sartika',     'mitra4@company.com', 'info@inovasilab.io', 'Jl. Asia Afrika Bandung',       6],
        ];

        foreach ($mitraData as [$nama, $bidang, $kontak, $emailUser, $emailPerusahaan, $alamat, $kuota]) {
            $user = User::firstOrCreate(['email'=>$emailUser],[
                'name'=>$kontak,'password'=>Hash::make('password'),'is_active'=>true,
            ]);
            $user->assignRole('mitra');
            Mitra::firstOrCreate(['email_perusahaan'=>$emailPerusahaan],[
                'user_id'=>$user->id,'nama_perusahaan'=>$nama,'bidang_usaha'=>$bidang,
                'nama_kontak'=>$kontak,'email_perusahaan'=>$emailPerusahaan,
                'alamat'=>$alamat,'kuota_magang'=>$kuota,'status'=>'aktif',
            ]);
        }

        // ── 7. Buat Sample Mahasiswa ───────────────────
        $mahasiswaData = [
            ['Ahmad Rizky Pratama',   '2021001001', 'mahasiswa1@student.ac.id', 'Teknik Informatika',  'Teknik', 7, '2021', 3.75],
            ['Dewi Sartika Putri',    '2021001002', 'mahasiswa2@student.ac.id', 'Sistem Informasi',   'Teknik', 7, '2021', 3.60],
            ['Fajar Nugroho Wibowo',  '2020001003', 'mahasiswa3@student.ac.id', 'Ilmu Komputer',      'Teknik', 9, '2020', 3.45],
            ['Siti Nurhaliza Rahman', '2021001004', 'mahasiswa4@student.ac.id', 'Teknik Informatika', 'Teknik', 7, '2021', 3.80],
            ['Eko Prasetyo Hartono',  '2020001005', 'mahasiswa5@student.ac.id', 'Sistem Informasi',   'Teknik', 9, '2020', 3.55],
        ];

        foreach ($mahasiswaData as [$nama, $nim, $email, $prodi, $fakultas, $semester, $angkatan, $ipk]) {
            $user = User::firstOrCreate(['email'=>$email],[
                'name'=>$nama,'password'=>Hash::make('password'),'is_active'=>true,
            ]);
            $user->assignRole('mahasiswa');
            Mahasiswa::firstOrCreate(['nim'=>$nim],[
                'user_id'=>$user->id,'nim'=>$nim,'nama_lengkap'=>$nama,
                'program_studi'=>$prodi,'fakultas'=>$fakultas,
                'semester'=>$semester,'angkatan'=>$angkatan,'ipk'=>$ipk,
                'status_akademik'=>'aktif',
            ]);
        }

        $this->command->info('✅ Seeding selesai!');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin',     'admin@simaga.ac.id',    'password'],
                ['Dosen',     'dosen1@simaga.ac.id',   'password'],
                ['Mahasiswa', 'mahasiswa1@student.ac.id','password'],
                ['Mitra',     'mitra1@company.com',    'password'],
            ]
        );
    }
}