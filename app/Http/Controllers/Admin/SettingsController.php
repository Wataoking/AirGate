<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    private function defaults(): array
    {
        return ['wifi_ssid' => '', 'wifi_password' => '', 'bandwidth_limit' => 0, 'radius_url' => '', 'radius_password' => '', 'radius_secret' => ''];
    }

    public function index(): View
    {
        return view('contenu.parametre', ['settings' => Setting::first() ?? new Setting($this->defaults())]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'wifi_ssid' => ['required', 'string', 'min:8', 'max:32'],
            'wifi_password' => ['required', 'string', 'min:8', 'max:32'],
            'bandwidth_limit' => ['required', 'integer', 'min:0'],
            'radius_url' => ['nullable', 'url', 'max:255'],
            'radius_password' => ['nullable', 'string', 'max:255'],
            'radius_secret' => ['nullable', 'string', 'max:255'],
        ]);

        Setting::updateOrCreate(['id' => 1], $validated);

        return back()->with('status', 'Paramètres enregistrés avec succès.');
    }

    public function reset(): RedirectResponse
    {
        Setting::updateOrCreate(['id' => 1], $this->defaults());

        return back()->with('status', 'Paramètres réinitialisés.');
    }
}