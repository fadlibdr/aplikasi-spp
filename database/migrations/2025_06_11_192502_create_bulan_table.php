<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateBulanTable extends Migration
{
    public function up()
    {
        Schema::create('bulan', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->unsignedTinyInteger('urutan');
            $table->timestamps();
        });

        // seed 12 bulan
        $names = [
            'Januari','Februari','Maret','April','Mei','Juni',
            'Juli','Agustus','September','Oktober','November','Desember'
        ];
        foreach ($names as $i => $nama) {
            DB::table('bulan')->insert([
                'urutan'     => $i + 1,
                'nama'       => $nama,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('bulan');
    }
}
