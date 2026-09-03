@extends('layout.index')

@section('content')
<div class="topbar1">
    <div class="titre"><h1>Facturation</h1><span>Gérez les revenus et les paiements</span></div>
    <div class="nb400"><i class="fa-regular fa-bell"></i><a class="btn-export-csv" href="{{ route('super-admin.facturation.export', ['status' => $selectedStatus]) }}">Exporter CSV</a></div>
</div>

@if (session('status'))<p class="flash-success">{{ session('status') }}</p>@endif
@if ($errors->any())<div class="flash-error"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

<div class="airgate-analytics">
    <div class="stats-grid">
        <div class="stat-card"><div class="stat-head"><span>Total revenus</span><span class="icon-box">↗</span></div><h2>{{ number_format($totalRevenue, 0, ',', ' ') }} F</h2><p class="growth up">Transactions payées</p></div>
        <div class="stat-card"><div class="stat-head"><span>Ce mois-ci</span><span class="icon-box">↗</span></div><h2>{{ number_format($monthRevenue, 0, ',', ' ') }} F</h2><p class="growth up">Revenus du mois</p></div>
        <div class="stat-card"><div class="stat-head"><span>En attente</span><span class="icon-box">!</span></div><h2>{{ number_format($pendingAmount, 0, ',', ' ') }} F</h2><p class="growth muted">Transactions à traiter</p></div>
        <div class="stat-card"><div class="stat-head"><span>Factures payées</span><span class="icon-box">✓</span></div><h2>{{ $paidCount }}</h2><p class="growth up">Paiements confirmés</p></div>
    </div>

    <div class="billing-create">
        <details>
            <summary class="btn-green">+ Ajouter une transaction</summary>
            <form method="POST" action="{{ route('super-admin.facturation.store') }}" class="billing-form">
                @csrf
                <label>Client<select name="user_id" required><option value="">Choisir un client</option>@foreach ($users as $user)<option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>@endforeach</select></label>
                <label>Forfait<select name="plan_id" required>@foreach ($plans as $plan)<option value="{{ $plan->id }}" data-price="{{ $plan->price }}">{{ $plan->name }} - {{ number_format($plan->price, 0, ',', ' ') }} F</option>@endforeach</select></label>
                <label>Montant (F)<input id="billing-amount" name="amount" type="number" min="0" required></label>
                <label>Statut<select name="status" required><option value="paid">Payé</option><option value="pending">En attente</option><option value="free">Gratuit</option></select></label>
                <label>Méthode de paiement<input name="payment_method" placeholder="Mobile Money, Carte..."></label>
                <button class="btn-green" type="submit">Enregistrer</button>
            </form>
        </details>
    </div>

    <div class="section-head"><h3>Ventes et factures ({{ $transactions->count() }})</h3><form class="search-box" method="GET" action="{{ route('super-admin.facturation') }}"><span>⌕</span><input name="search" value="{{ $search }}" type="search" placeholder="Rechercher un client..."><button type="submit">Rechercher</button></form></div>

    <form class="filters-bar" method="GET" action="{{ route('super-admin.facturation') }}">
        <div class="filter-group"><label>Filtres :</label><select class="filter-btn" name="plan"><option value="">Tous les forfaits</option>@foreach ($plans as $plan)<option value="{{ $plan->id }}" @selected((string) $selectedPlan === (string) $plan->id)>{{ $plan->name }}</option>@endforeach</select><select class="filter-btn" name="status"><option value="">Tous les statuts</option><option value="paid" @selected($selectedStatus === 'paid')>Payé</option><option value="pending" @selected($selectedStatus === 'pending')>En attente</option><option value="free" @selected($selectedStatus === 'free')>Gratuit</option></select></div>
        <button class="filter-btn" type="submit">Filtrer</button><a class="clear-btn" href="{{ route('super-admin.facturation') }}">Effacer</a>
    </form>

    <div class="table-wrap"><table><thead><tr><th>Date</th><th>Client</th><th>Forfait</th><th>Montant</th><th>Statut</th><th>Paiement</th><th>Action</th></tr></thead><tbody>
        @forelse ($transactions as $transaction)
            <tr><td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td><td><div class="client"><span>{{ $transaction->user?->name ?? 'Client supprimé' }}<br><small>{{ $transaction->user?->email }}</small></span></div></td><td><span class="badge blue">{{ $transaction->plan?->name ?? 'Forfait supprimé' }}</span></td><td class="bold">{{ number_format($transaction->amount, 0, ',', ' ') }} F</td><td><span class="status {{ $transaction->status === 'paid' ? 'paid' : ($transaction->status === 'pending' ? 'pending' : 'no-charge') }}">{{ $transaction->status === 'paid' ? 'Payé' : ($transaction->status === 'pending' ? 'En attente' : 'Gratuit') }}</span></td><td>{{ $transaction->payment_method ?: '-' }}</td><td class="billing-actions"><form method="POST" action="{{ route('super-admin.facturation.toggle', $transaction) }}">@csrf @method('PATCH')<button class="z1" type="submit">{{ $transaction->status === 'paid' ? 'En attente' : 'Payer' }}</button></form><form method="POST" action="{{ route('super-admin.facturation.destroy', $transaction) }}" onsubmit="return confirm('Supprimer cette transaction ?');">@csrf @method('DELETE')<button class="z2" type="submit">Supprimer</button></form></td></tr>
        @empty
            <tr><td colspan="7">Aucune transaction trouvée.</td></tr>
        @endforelse
    </tbody></table></div>
</div>
<script>
const planSelect = document.querySelector('select[name="plan_id"]');
const amountInput = document.getElementById('billing-amount');
function updateAmount() { amountInput.value = planSelect.selectedOptions[0]?.dataset.price || 0; }
planSelect?.addEventListener('change', updateAmount);
updateAmount();
</script>
@endsection
