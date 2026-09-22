<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'vehicle_id',
        'mechanic_id',
        'invoice_date',
        'due_date',
        'mileage_in',
        'mileage_out',
        'complaint',
        'diagnosis',
        'vehicle_condition',
        'subtotal',
        'discount',
        'tax',
        'total',
        'status',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date'     => 'date',
        'subtotal'     => 'decimal:2',
        'discount'     => 'decimal:2',
        'tax'          => 'decimal:2',
        'total'        => 'decimal:2',
    ];

    /** Invoice dimiliki oleh satu customer. */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /** Invoice terkait satu kendaraan. */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /** Invoice ditangani satu mekanik. */
    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class);
    }

    /** Invoice memiliki banyak detail item. */
    public function details()
    {
        return $this->hasMany(InvoiceDetail::class);
    }

    /**
     * Generate nomor invoice otomatis: WO-YYYYMMDD-001
     * Format WO (Work Order) lebih umum dipakai di bengkel.
     */
    public static function generateNumber(): string
    {
        $date   = now()->format('Ymd');
        $prefix = "WO-{$date}-";

        $last = self::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();

        $newNumber = $last
            ? ((int) substr($last->invoice_number, -3)) + 1
            : 1;

        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /** Label status dalam Bahasa Indonesia. */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'       => 'Draft',
            'in_progress' => 'Sedang Dikerjakan',
            'waiting'     => 'Menunggu Sparepart',
            'done'        => 'Selesai',
            'unpaid'      => 'Belum Dibayar',
            'paid'        => 'Lunas',
            'cancelled'   => 'Dibatalkan',
            default       => ucfirst($this->status),
        };
    }

    /** Warna badge per status. */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft'       => 'bg-gray-100 text-gray-700',
            'in_progress' => 'bg-blue-100 text-blue-700',
            'waiting'     => 'bg-orange-100 text-orange-700',
            'done'        => 'bg-teal-100 text-teal-700',
            'unpaid'      => 'bg-yellow-100 text-yellow-700',
            'paid'        => 'bg-green-100 text-green-700',
            'cancelled'   => 'bg-red-100 text-red-700',
            default       => 'bg-gray-100 text-gray-700',
        };
    }
}
