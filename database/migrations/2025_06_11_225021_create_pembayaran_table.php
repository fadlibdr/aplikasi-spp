<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iuran_id')
                ->constrained('iuran')
                ->onDelete('cascade');
            $table->string('order_id')->unique();
            $table->unsignedBigInteger('jumlah');
            $table->enum('metode', ['midtrans', 'manual']);
            $table->string('midtrans_id')->nullable();
            $table->timestamp('tgl_bayar')->nullable();
            $table->enum('status', ['pending', 'settlement', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayaran');
    }
};
