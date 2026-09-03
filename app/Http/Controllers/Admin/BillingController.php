<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function index(Request $request): View
    {
        $transactions = Transaction::with(['user', 'plan'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->whereHas('user', fn ($query) => $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            })
            ->when($request->filled('plan'), fn ($query) => $query->where('plan_id', $request->plan))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->get();

        return view('contenu.facturation', [
            'transactions' => $transactions,
            'plans' => Plan::orderBy('name')->get(),
            'users' => User::where('role', 'user')->orderBy('name')->get(),
            'search' => $request->search,
            'selectedPlan' => $request->plan,
            'selectedStatus' => $request->status,
            'totalRevenue' => Transaction::where('status', 'paid')->sum('amount'),
            'monthRevenue' => Transaction::where('status', 'paid')->whereMonth('created_at', now()->month)->sum('amount'),
            'pendingAmount' => Transaction::where('status', 'pending')->sum('amount'),
            'paidCount' => Transaction::where('status', 'paid')->count(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'plan_id' => ['required', 'exists:plans,id'],
            'amount' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:paid,pending,free'],
            'payment_method' => ['nullable', 'string', 'max:50'],
        ]);
        $validated['reference'] = 'TXN-'.str()->upper(str()->random(10));
        Transaction::create($validated);

        return back()->with('status', 'Transaction ajoutée avec succès.');
    }

    public function purchasePlan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
            'payment_method' => ['required', 'in:OM,MOMO,CB'],
        ]);

        $plan = Plan::where('active', true)->findOrFail($validated['plan_id']);

        Transaction::create([
            'user_id' => auth()->id(),
            'plan_id' => $plan->id,
            'amount' => $plan->price,
            'status' => 'pending',
            'payment_method' => $validated['payment_method'],
            'reference' => 'TXN-'.str()->upper(str()->random(10)),
        ]);

        return back()->with('status', 'Le forfait '. $plan->name .' a été sélectionné avec succès. Votre paiement est en attente.');
    }

    public function toggle(Transaction $transaction): RedirectResponse
    {
        $transaction->update(['status' => $transaction->status === 'paid' ? 'pending' : 'paid']);

        return back()->with('status', 'Statut de la transaction mis à jour.');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $transaction->delete();

        return back()->with('status', 'Transaction supprimée.');
    }

    public function export(Request $request)
    {
        $transactions = Transaction::with(['user', 'plan'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()->get();

        return response()->streamDownload(function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'Client', 'Email', 'Forfait', 'Montant', 'Statut', 'Paiement', 'Référence']);
            foreach ($transactions as $transaction) {
                fputcsv($handle, [$transaction->created_at->format('Y-m-d H:i'), $transaction->user?->name, $transaction->user?->email, $transaction->plan?->name, $transaction->amount, $transaction->status, $transaction->payment_method, $transaction->reference]);
            }
            fclose($handle);
        }, 'facturation.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}