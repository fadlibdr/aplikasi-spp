<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Siswa([
            'nis'                => $row['nis'],
            'nisn'               => $row['nisn'] ?? null,
            'nama_depan'         => $row['nama_depan'],
            'nama_belakang'      => $row['nama_belakang'],
            'foto'               => $row['foto'] ?? null,
            'email'              => $row['email'],
            'tanggal_lahir'      => $row['tanggal_lahir'],
            'jenis_kelamin'      => $row['jenis_kelamin'],
            'alamat'             => $row['alamat'] ?? null,
            'wali_murid'         => $row['wali_murid'] ?? null,
            'kontak_wali_murid'  => $row['kontak_wali_murid'] ?? null,
            'tanggal_awal_masuk' => $row['tanggal_awal_masuk'] ?? null,
            'status_siswa'       => $row['status_siswa'] ?? 'aktif',
            'status_awal_siswa'  => $row['status_awal_siswa'] ?? null,
            'status_akhir_siswa' => $row['status_akhir_siswa'] ?? null,
            'kelas_id'           => $row['kelas_id'],
        ]);
    }
}
