<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('bulan_tahun_ajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->onDelete('cascade');
            $table->foreignId('bulan_id')
                ->constrained('bulan')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulan_tahun_ajaran');
    }
};
