<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Matikan cek FK agar truncate aman di SQLite
        Schema::disableForeignKeyConstraints();

        // Truncate semua tabel sesuai nama migration (singular)
        DB::table('bulan')->truncate();
        DB::table('bulan_tahun_ajaran')->truncate();
        DB::table('tahun_ajaran')->truncate();
        DB::table('kelas')->truncate();
        DB::table('jenis_pembayaran')->truncate();
        DB::table('siswa')->truncate();
        DB::table('iuran')->truncate();
        DB::table('pembayaran')->truncate();


        // Spatie tables
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();

        // Panggil seeders
        $this->call([
            RolePermissionSeeder::class,
            BulanSeeder::class,
            TahunAjaranSeeder::class,
            KelasSeeder::class,
            JenisPembayaranSeeder::class,
            SiswaSeeder::class,
            IuranSeeder::class,
            PembayaranSeeder::class,
            UserSeeder::class,
            PenerimaanPengeluaranSeeder::class
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
