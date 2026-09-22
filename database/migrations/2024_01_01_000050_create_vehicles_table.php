<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            // Kendaraan dimiliki oleh customer
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('plate_number');          // Nomor plat: B 1234 ABC
            $table->string('brand');                 // Merek: Toyota
            $table->string('model');                 // Model: Avanza
            $table->string('year', 4)->nullable();   // Tahun: 2020
            $table->string('color')->nullable();     // Warna: Putih
            $table->string('engine_number')->nullable();  // Nomor mesin
            $table->string('chassis_number')->nullable(); // Nomor rangka
            $table->text('notes')->nullable();       // Catatan tambahan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
