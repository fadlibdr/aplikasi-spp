<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('tempat_lahir')->nullable()->after('email');
            $table->string('no_hp')->nullable()->after('tanggal_lahir');
            $table->string('nama_ibu')->nullable()->after('alamat');
            $table->string('nama_ayah')->nullable()->after('nama_ibu');
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn(['tempat_lahir', 'no_hp', 'nama_ibu', 'nama_ayah']);
        });
    }
};
