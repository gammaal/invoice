<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'plate_number',
        'brand',
        'model',
        'year',
        'color',
        'engine_number',
        'chassis_number',
        'notes',
    ];

    /**
     * Kendaraan dimiliki oleh satu customer.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Kendaraan bisa memiliki banyak invoice (riwayat servis).
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Label singkat kendaraan: "Toyota Avanza (B 1234 ABC)"
     */
    public function getLabelAttribute(): string
    {
        return "{$this->brand} {$this->model} ({$this->plate_number})";
    }
}
