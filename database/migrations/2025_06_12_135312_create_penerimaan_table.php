<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('penerimaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_id')->nullable()->constrained('pembayaran')->onDelete('set null');
            $table->string('sumber');
            $table->decimal('jumlah', 12, 2);
            $table->text('keterangan')->nullable();
            $table->date('tanggal');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('penerimaan');
    }
};
