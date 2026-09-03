<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(Request $request): View
    {
        $accounts = User::query()
            ->whereIn('role', ['admin', 'manager', 'user'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where(fn ($query) => $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"));
            })
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->role))
            ->latest()
            ->get();

        return view('contenu.compte', [
            'accounts' => $accounts,
            'editing' => $request->filled('edit') ? User::find($request->integer('edit')) : null,
            'search' => $request->search,
            'role' => $request->role,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:admin,manager,user'],
            'phone_number' => ['required', 'string', 'max:30'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone number' => $validated['phone_number'],
            'email_verified_at' => now(),
        ]);

        return back()->with('status', 'Compte créé avec succès.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:admin,manager,user'],
            'phone_number' => ['required', 'string', 'max:30'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'phone number' => $validated['phone_number'],
        ]);

        return back()->with('status', 'Compte modifié avec succès.');
    }

    public function resetPassword(User $user): RedirectResponse
    {
        $user->update(['password' => Hash::make('password')]);

        return back()->with('status', 'Mot de passe réinitialisé à « password ».');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()->is($user), 403, 'Vous ne pouvez pas supprimer votre propre compte.');

        $user->delete();

        return back()->with('status', 'Compte supprimé avec succès.');
    }

    public function export(Request $request)
    {
        $accounts = User::query()
            ->whereIn('role', ['admin', 'manager', 'user'])
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->role))
            ->orderBy('name')
            ->get(['name', 'email', 'role', 'email_verified_at', 'created_at']);

        return response()->streamDownload(function () use ($accounts) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Nom', 'Email', 'Rôle', 'Statut', 'Créé le']);

            foreach ($accounts as $account) {
                fputcsv($handle, [
                    $account->name,
                    $account->email,
                    $account->role,
                    $account->email_verified_at ? 'Actif' : 'Inactif',
                    $account->created_at?->format('d/m/Y H:i'),
                ]);
            }

            fclose($handle);
        }, 'comptes.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}