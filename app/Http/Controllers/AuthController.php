<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('invoices.create');
        }

        return view('auth.login');
    }

    /**
     * Traite la tentative de connexion.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()
                ->intended(route('invoices.create'))
                ->with('success', 'Bienvenue ' . Auth::user()->name . ' !');
        }

        return back()
            ->withErrors(['email' => 'Identifiants incorrects. Vérifie ton e-mail et ton mot de passe.'])
            ->onlyInput('email');
    }

    /**
     * Déconnecte l'utilisateur courant.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Tu as été déconnecté avec succès.');
    }
}
