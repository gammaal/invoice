<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Tipe item: service = jasa, part = spare part
            $table->enum('type', ['service', 'part'])->default('part')->after('name');
            // Unit satuan: pcs, liter, jam, set, dll
            $table->string('unit', 20)->default('pcs')->after('stock');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['type', 'unit']);
        });
    }
};
