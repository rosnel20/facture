<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            // Numéro automatique de facture (ex : MT-000001)
            $table->string('invoice_number')->unique();

            // Informations client
            $table->string('client_name');
            $table->string('client_phone');
            $table->string('client_address')->nullable();

            // Montants
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            // Observation libre
            $table->text('observation')->nullable();

            // Fichiers générés
            $table->string('pdf_path')->nullable();
            $table->string('qrcode_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
