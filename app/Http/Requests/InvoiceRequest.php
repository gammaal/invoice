<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id'      => ['required', 'exists:customers,id'],
            'vehicle_id'       => ['required', 'exists:vehicles,id'],
            'mechanic_id'      => ['nullable', 'exists:mechanics,id'],
            'invoice_date'     => ['required', 'date'],
            'due_date'         => ['required', 'date', 'after_or_equal:invoice_date'],
            'status'           => ['required', 'in:draft,in_progress,waiting,done,unpaid,paid,cancelled'],
            'mileage_in'       => ['nullable', 'integer', 'min:0'],
            'mileage_out'      => ['nullable', 'integer', 'min:0'],
            'complaint'        => ['nullable', 'string', 'max:1000'],
            'diagnosis'        => ['nullable', 'string', 'max:1000'],
            'vehicle_condition'=> ['nullable', 'string', 'max:1000'],
            'discount'         => ['nullable', 'numeric', 'min:0'],
            'tax'              => ['nullable', 'numeric', 'min:0'],
            'products'                => ['required', 'array', 'min:1'],
            'products.*.product_id'   => ['required', 'exists:products,id'],
            'products.*.quantity'     => ['required', 'numeric', 'min:0.1'],
            'products.*.price'        => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required'         => 'Customer wajib dipilih.',
            'vehicle_id.required'          => 'Kendaraan wajib dipilih.',
            'vehicle_id.exists'            => 'Kendaraan tidak ditemukan.',
            'invoice_date.required'        => 'Tanggal invoice wajib diisi.',
            'due_date.required'            => 'Tanggal jatuh tempo wajib diisi.',
            'due_date.after_or_equal'      => 'Jatuh tempo tidak boleh sebelum tanggal invoice.',
            'status.required'              => 'Status wajib dipilih.',
            'discount.min'                 => 'Diskon tidak boleh negatif.',
            'tax.min'                      => 'Pajak tidak boleh negatif.',
            'products.required'            => 'Minimal satu item harus ditambahkan.',
            'products.*.product_id.required' => 'Item wajib dipilih.',
            'products.*.quantity.min'        => 'Qty minimal 0.1.',
            'products.*.price.required'      => 'Harga wajib diisi.',
        ];
    }
}
