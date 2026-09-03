<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModemController extends Controller
{
    public function index(): View
    {
        $modems = Modem::latest()->get();

        return view('contenu.wifi', [
            'modems' => $modems,
            'totalData' => $modems->sum('data_used'),
            'activeBandwidth' => $modems->where('status', 'online')->sum('bandwidth'),
            'onlineCount' => $modems->where('status', 'online')->count(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'mac_address' => ['required', 'string', 'max:30', 'unique:modems,mac_address'],
            'ip_address' => ['required', 'ip'],
            'bandwidth' => ['required', 'numeric', 'min:0'],
        ]);

        Modem::create($validated);

        return back()->with('status', 'Modem ajouté avec succès.');
    }

    public function toggle(Modem $modem): RedirectResponse
    {
        $modem->update(['status' => $modem->status === 'online' ? 'blocked' : 'online']);

        return back()->with('status', 'Statut du modem mis à jour.');
    }

    public function destroy(Modem $modem): RedirectResponse
    {
        $modem->delete();

        return back()->with('status', 'Modem supprimé.');
    }
}