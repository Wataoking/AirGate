<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()
            ->where('role', 'user')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone number', 'like', "%{$search}%");
                });
            })
            ->latest();

        $clients = $query->get()->map(function (User $user) {
            $latestTransaction = $user->transactions()->latest()->first();
            $plan = $latestTransaction?->plan;
            $planName = $plan?->name ?? 'Aucun';
            $status = $latestTransaction?->status === 'paid' ? 'Actif' : 'Inactif';

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'code' => $this->buildClientCode($user),
                'plan' => $planName,
                'status' => $status,
                'remaining_minutes' => $status === 'Actif' ? 34 : 0,
                'mac_address' => $this->buildMacAddress($user),
            ];
        });

        $planCounts = ['ACCES' => 0, 'ACCESS+' => 0, 'EVASION' => 0];

        foreach ($clients as $client) {
            if (isset($planCounts[$client['plan']])) {
                $planCounts[$client['plan']]++;
            }
        }

        return view('contenu.client', [
            'clients' => $clients,
            'search' => $request->search,
            'totalClients' => $clients->count(),
            'activeClients' => $clients->where('status', 'Actif')->count(),
            'planCounts' => $planCounts,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone_number' => ['nullable', 'string', 'max:30'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'phone number' => $validated['phone_number'] ?? '',
            'email_verified_at' => now(),
        ]);

        return back()->with('status', 'Client ajouté avec succès.');
    }

    private function buildClientCode(User $user): string
    {
        $base = preg_replace('/[^A-Za-z0-9]/', '', $user->name ?? 'client');
        $prefix = strtoupper(substr($base, 0, 3));

        return $prefix ? $prefix.$user->id : 'CL-'.$user->id;
    }

    private function buildMacAddress(User $user): string
    {
        $values = [];

        for ($index = 0; $index < 6; $index++) {
            $value = ($user->id * 17 + $index * 13) % 256;
            $values[] = strtoupper(str_pad(dechex($value), 2, '0', STR_PAD_LEFT));
        }

        return implode(':', $values);
    }
}
