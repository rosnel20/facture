<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

// Redirection racine vers la création de facture
Route::redirect('/', '/factures/nouvelle');

// --- Factures ---
Route::prefix('factures')->name('invoices.')->group(function () {
    Route::get('/nouvelle', [InvoiceController::class, 'create'])->name('create');
    Route::post('/', [InvoiceController::class, 'store'])->name('store');

    Route::get('/historique', [InvoiceController::class, 'index'])->name('index');

    Route::get('/{invoice}/succes', [InvoiceController::class, 'success'])->name('success');
    Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
    Route::get('/{invoice}/telecharger', [InvoiceController::class, 'download'])->name('download');
    Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');
});

// Page publique de vérification (cible du QR code sur le PDF).
// Volontairement en dehors du prefix "factures" pour avoir une URL courte et propre.
Route::get('/verifier/{invoiceNumber}', [InvoiceController::class, 'verify'])->name('invoices.verify');

// --- Paramètres de l'entreprise ---
Route::get('/parametres', [SettingController::class, 'edit'])->name('settings.edit');
Route::put('/parametres', [SettingController::class, 'update'])->name('settings.update');
