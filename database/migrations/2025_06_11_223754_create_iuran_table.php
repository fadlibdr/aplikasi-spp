<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('iuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->onDelete('cascade');
            $table->foreignId('jenis_pembayaran_id')
                ->constrained('jenis_pembayaran')
                ->onDelete('cascade');
            $table->unsignedTinyInteger('bulan');  // 1–12
            $table->enum('status', ['pending', 'lunas'])->default('pending');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('iuran');
    }
};
