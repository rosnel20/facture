<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Application mono-utilisateur, sans authentification.
        return true;
    }

    public function rules(): array
    {
        return [
            'client_name'    => ['required', 'string', 'max:255'],
            'client_phone'   => ['required', 'string', 'max:30'],
            'client_address' => ['nullable', 'string', 'max:255'],
            'discount'       => ['nullable', 'numeric', 'min:0'],
            'observation'    => ['nullable', 'string', 'max:1000'],

            'items'                      => ['required', 'array', 'min:1'],
            'items.*.designation'        => ['required', 'string', 'max:255'],
            'items.*.quantity'           => ['required', 'integer', 'min:1'],
            'items.*.unit_price'         => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'client_name.required'         => "Le nom du client est obligatoire.",
            'client_phone.required'        => "Le numéro WhatsApp est obligatoire.",
            'items.required'               => "Ajoutez au moins un article.",
            'items.*.designation.required' => "La désignation de l'article est obligatoire.",
            'items.*.quantity.min'         => "La quantité doit être d'au moins 1.",
            'items.*.unit_price.min'       => "Le prix unitaire ne peut pas être négatif.",
        ];
    }
}
