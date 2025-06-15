<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->unsignedInteger('kapasitas')->default(0);

            // Cukup satu: gunakan foreignId() untuk membuat kolom + FK
            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->onDelete('cascade');

            $table->unique(['nama', 'tahun_ajaran_id']);
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('kelas');
    }
};
