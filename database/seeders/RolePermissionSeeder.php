<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::truncate();
        Role::truncate();

        // Buat permissions
        $modules = [
            'tahun_ajaran',
            'kelas',
            'siswa',
            'jenis_pembayaran',
            'iuran',
            'pembayaran',
        ];
        foreach ($modules as $mod) {
            Permission::create(['name' => "view $mod"]);
            Permission::create(['name' => "create $mod"]);
            Permission::create(['name' => "edit $mod"]);
            Permission::create(['name' => "delete $mod"]);
        }

        // Roles
        $admin = Role::create(['name' => 'admin']);
        $operator = Role::create(['name' => 'operator']);
        $siswa = Role::create(['name' => 'siswa']);

        // Assign permissions
        $admin->syncPermissions(Permission::all());
        $operator->syncPermissions(
            Permission::where('name', 'like', 'view %')
                ->orWhere('name', 'like', 'create %')
                ->orWhere('name', 'like', 'edit %')
                ->get()
        );
        $siswa->syncPermissions(['view siswa', 'edit siswa']);
    }
}
