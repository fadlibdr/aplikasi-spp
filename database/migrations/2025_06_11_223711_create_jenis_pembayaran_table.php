<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('jenis_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();    // e.g. SPP, UANG_KULIAH
            $table->string('nama');
            $table->unsignedBigInteger('nominal');
            $table->enum('frekuensi', ['Bulanan', 'Tahunan']);
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('jenis_pembayaran');
    }
};
