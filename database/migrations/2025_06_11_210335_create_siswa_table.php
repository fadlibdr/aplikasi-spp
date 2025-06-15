<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique();
            $table->string('nisn')->unique()->nullable();
            $table->string('nama_depan');
            $table->string('nama_belakang');
            $table->string('foto')->nullable();
            $table->string('email')->unique();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki','Perempuan'])->nullable();
            $table->text('alamat')->nullable();
            $table->string('wali_murid')->nullable();
            $table->string('kontak_wali_murid')->nullable();
            $table->date('tanggal_awal_masuk')->nullable();
            $table->enum('status_siswa', ['aktif','nonaktif','lulus'])->default('aktif');
            $table->string('status_awal_siswa')->nullable();
            $table->string('status_akhir_siswa')->nullable();
            $table->foreignId('kelas_id')
                  ->constrained('kelas')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('siswa');
    }
};
