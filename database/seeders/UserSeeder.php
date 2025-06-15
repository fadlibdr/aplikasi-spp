<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat role jika belum ada
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $operatorRole = Role::firstOrCreate(['name' => 'operator']);
        $siswaRole = Role::firstOrCreate(['name' => 'siswa']);

        // Admin
        $admin = User::create([
            'name' => 'Admin Sekolah',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
        ]);
        $admin->assignRole($adminRole);

        // Operator
        $operator = User::create([
            'name' => 'Operator Sekolah',
            'email' => 'operator@example.com',
            'password' => Hash::make('admin123'),
        ]);
        $operator->assignRole($operatorRole);

        // Siswa
        $siswa = User::create([
            'name' => 'Siswa Aktif',
            'email' => 'siswa@example.com',
            'password' => Hash::make('admin123'),
        ]);
        $siswa->assignRole($siswaRole);
    }
}
