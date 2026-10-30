<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iphones', function (Blueprint $table) {
            $table->id();
            $table->string('tipe_iphone');
            $table->string('imei')->unique();
            $table->string('qr_code_string')->unique();
            $table->string('status')->default('Tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iphones');
    }
};
