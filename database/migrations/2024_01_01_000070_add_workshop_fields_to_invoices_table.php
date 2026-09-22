<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Relasi ke kendaraan
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null')->after('customer_id');
            // Relasi ke mekanik
            $table->foreignId('mechanic_id')->nullable()->constrained('mechanics')->onDelete('set null')->after('vehicle_id');
            // Kilometer saat masuk & keluar
            $table->integer('mileage_in')->nullable()->after('mechanic_id');
            $table->integer('mileage_out')->nullable()->after('mileage_in');
            // Keluhan pelanggan
            $table->text('complaint')->nullable()->after('mileage_out');
            // Diagnosa mekanik
            $table->text('diagnosis')->nullable()->after('complaint');
            // Catatan kondisi kendaraan saat masuk
            $table->text('vehicle_condition')->nullable()->after('diagnosis');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id']);
            $table->dropForeign(['mechanic_id']);
            $table->dropColumn(['vehicle_id', 'mechanic_id', 'mileage_in', 'mileage_out', 'complaint', 'diagnosis', 'vehicle_condition']);
        });
    }
};
