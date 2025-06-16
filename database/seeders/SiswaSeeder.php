<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SiswaSeeder extends Seeder
{
    public function run()
    {
        Siswa::truncate();

        $kelas = Kelas::all();
        // Buat 3 siswa dummy
        foreach (range(1, end: 3) as $i) {
            $birth = now()->subYears(15);
            $user = User::create([
                'name' => 'Siswa' . $i . ' Test',
                'email' => "siswa{$i}@example.com",
                'password' => Hash::make(Str::random(12)),
                'email_verified_at' => now(),
            ]);
            $user->assignRole('siswa');

            Siswa::create([
                'nis' => 'NIS' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nisn' => 'NISN' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama_depan' => 'Siswa' . $i,
                'nama_belakang' => 'Test',
                'email' => "siswa{$i}@example.com",
                'tanggal_lahir' => $birth->format('Y-m-d'),
                'jenis_kelamin' => $i % 2 ? 'Laki-laki' : 'Perempuan',
                'alamat' => 'Jl. Contoh No.' . $i,
                'wali_murid' => 'Orangtua' . $i,
                'kontak_wali_murid' => '0812' . rand(10000000, 99999999),
                'tanggal_awal_masuk' => now()->subYears(1)->format('Y-m-d'),
                'status_siswa' => 'aktif',
                'status_awal_siswa' => 'baru',
                'status_akhir_siswa' => 'aktif',
                'kelas_id' => $kelas->random()->id,
                'user_id' => $user->id,
            ]);
        }
    }
}
