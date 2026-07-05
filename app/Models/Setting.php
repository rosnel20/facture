<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'company_name',
        'phone',
        'whatsapp',
        'address',
        'email',
        'logo_path',
        'footer_text',
    ];

    /**
     * Récupère l'unique ligne de paramètres, ou la crée avec des
     * valeurs par défaut si elle n'existe pas encore.
     */
    public static function current(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            [
                'company_name' => 'MUNDO TECH',
                'footer_text'  => 'Merci pour votre confiance.',
            ]
        );
    }
}
