<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',        // 'service' atau 'part'
        'description',
        'price',
        'stock',
        'unit',        // pcs, liter, jam, set, dll
    ];

    /**
     * Satu produk/jasa bisa ada di banyak invoice detail.
     */
    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class);
    }

    /**
     * Label tipe untuk tampilan.
     */
    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'service' ? 'Jasa' : 'Spare Part';
    }

    /**
     * Warna badge tipe.
     */
    public function getTypeColorAttribute(): string
    {
        return $this->type === 'service'
            ? 'bg-blue-100 text-blue-700'
            : 'bg-orange-100 text-orange-700';
    }

    /**
     * Scope untuk filter tipe jasa saja.
     */
    public function scopeServices($query)
    {
        return $query->where('type', 'service');
    }

    /**
     * Scope untuk filter spare part saja.
     */
    public function scopeParts($query)
    {
        return $query->where('type', 'part');
    }
}
