<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'client_name',
        'client_phone',
        'client_address',
        'subtotal',
        'discount',
        'total',
        'observation',
        'pdf_path',
        'qrcode_path',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total'    => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Génère le prochain numéro de facture au format MT-000001.
     * On verrouille la table le temps de la lecture/incrémentation
     * pour éviter les doublons en cas d'accès concurrent.
     */
    public static function generateNextNumber(): string
    {
        return DB::transaction(function () {
            $last = static::orderByDesc('id')->lockForUpdate()->first();

            $nextId = $last
                ? ((int) substr($last->invoice_number, 3)) + 1
                : 1;

            return 'MT-' . str_pad((string) $nextId, 6, '0', STR_PAD_LEFT);
        });
    }
}
