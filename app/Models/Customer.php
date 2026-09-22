<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    /**
     * Satu customer bisa memiliki banyak kendaraan.
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * Satu customer bisa memiliki banyak invoice.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
