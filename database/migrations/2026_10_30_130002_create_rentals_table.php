<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('iphone_id')->constrained('iphones')->cascadeOnDelete();
            $table->dateTime('tgl_pinjam');
            $table->integer('durasi_jam');
            $table->dateTime('tgl_kembali')->nullable();
            $table->string('status_rental')->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
