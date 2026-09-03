<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function index(Request $request): View
    {
        return view('contenu.forfaits', [
            'plans' => Plan::latest()->get(),
            'editing' => $request->filled('edit') ? Plan::find($request->integer('edit')) : null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'duration' => ['required', 'string', 'max:50'],
            'price' => ['required', 'integer', 'min:0'],
            'speed' => ['required', 'numeric', 'min:0'],
        ]);

        Plan::create([...$validated, 'active' => true]);

        return back()->with('status', 'Forfait ajouté avec succès.');
    }

    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $plan->update($request->validate([
            'name' => ['required', 'string', 'max:100'],
            'duration' => ['required', 'string', 'max:50'],
            'price' => ['required', 'integer', 'min:0'],
            'speed' => ['required', 'numeric', 'min:0'],
        ]));

        return redirect()->route('super-admin.forfaits')->with('status', 'Forfait modifié avec succès.');
    }

    public function toggle(Plan $plan): RedirectResponse
    {
        $plan->update(['active' => ! $plan->active]);

        return back()->with('status', 'Statut du forfait mis à jour.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        $plan->delete();

        return back()->with('status', 'Forfait supprimé.');
    }
}