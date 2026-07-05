<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = Setting::current();

        return view('settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'phone'        => ['nullable', 'string', 'max:30'],
            'whatsapp'     => ['nullable', 'string', 'max:30'],
            'address'      => ['nullable', 'string', 'max:255'],
            'email'        => ['nullable', 'email', 'max:255'],
            'footer_text'  => ['nullable', 'string', 'max:500'],
            'logo'         => ['nullable', 'image', 'max:2048'],
        ]);

        $settings = Setting::current();

        if ($request->hasFile('logo')) {
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('logo', 'public');
        }

        $settings->update($data);

        return redirect()
            ->route('settings.edit')
            ->with('success', 'Paramètres mis à jour avec succès.');
    }
}
