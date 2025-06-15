<?php

// database/migrations/2025_06_12_create_tahun_ajaran_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->boolean('aktif')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tahun_ajaran');
    }
};
