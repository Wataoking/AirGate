@extends('layout.index')

@section('content')
<div class="topbar3">
    <div class="titre"><h1>Gestion des forfaits</h1></div>
</div>

@if (session('status'))
    <p class="flash-success">{{ session('status') }}</p>
@endif
@if ($errors->any())
    <div class="flash-error"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif

<div class="forfait-content">
    <div class="f-top">
        <div class="f-stat"><div class="f-ico">📦</div><div><small>Total forfaits</small><h2>{{ $plans->count() }}</h2><span>Disponibles</span></div></div>
        <div class="f-stat"><div class="f-ico ok">◷</div><div><small>Forfaits actifs</small><h2>{{ $plans->where('active', true)->count() }}</h2><span class="green">En service</span></div></div>
        <div class="f-stat"><div class="f-ico">↗</div><div><small>Vendus aujourd'hui</small><h2>0</h2><span>Pas encore de ventes</span></div></div>
    </div>

    <div class="f-header">
        <h3>Forfaits disponibles</h3>
        <details class="plan-details" {{ $editing ? 'open' : '' }}>
            <summary class="btn-new">+ Ajouter un forfait</summary>
            <form class="plan-form" method="POST" action="{{ $editing ? route('super-admin.forfaits.update', $editing) : route('super-admin.forfaits.store') }}">
                @csrf
                @if ($editing) @method('PUT') @endif
                <h3>{{ $editing ? 'Modifier le forfait' : 'Nouveau forfait' }}</h3>
                <label>Nom <input name="name" value="{{ old('name', $editing?->name) }}" required placeholder="ACCES, ACCESS+ ou EVASION"></label>
                <label>Durée <input name="duration" value="{{ old('duration', $editing?->duration) }}" required placeholder="1H, 1 JOUR ou 7 JOURS"></label>
                <label>Prix (F) <input name="price" type="number" min="0" value="{{ old('price', $editing?->price) }}" required></label>
                <label>Débit (Mbps) <input name="speed" type="number" min="0" step="0.1" value="{{ old('speed', $editing?->speed) }}" required></label>
                <button class="btn-new" type="submit">{{ $editing ? 'Enregistrer' : 'Créer' }}</button>
                @if ($editing)<a class="btn-light" href="{{ route('super-admin.forfaits') }}">Annuler</a>@endif
            </form>
        </details>
    </div>

    <div class="f-grid">
        @forelse ($plans as $plan)
            <div class="f-card">
                <div class="f-tag {{ $loop->index % 3 === 0 ? 'orange' : ($loop->index % 3 === 1 ? 'violet' : 'green') }}">{{ $plan->name }}</div>
                <div class="f-circle {{ $loop->index % 3 === 0 ? 'orange' : ($loop->index % 3 === 1 ? 'purple' : 'lightgreen') }}">◷</div>
                <h2 class="f-time">{{ $plan->duration }}</h2>
                <div class="f-price">{{ number_format($plan->price, 0, ',', ' ') }} F</div>
                <div class="f-speed">{{ number_format($plan->speed, 1) }} Mbps</div>
                <div class="f-list"><p>✓ Accès illimité</p><p>✓ {{ $plan->active ? 'Forfait actif' : 'Forfait désactivé' }}</p></div>
                <div class="f-actions">
                    <a class="mod" href="{{ route('super-admin.forfaits', ['edit' => $plan->id]) }}">Modifier</a>
                    <form method="POST" action="{{ route('super-admin.forfaits.toggle', $plan) }}">
                        @csrf @method('PATCH')
                        <button class="mod" type="submit">{{ $plan->active ? 'Désactiver' : 'Activer' }}</button>
                    </form>
                    <form method="POST" action="{{ route('super-admin.forfaits.destroy', $plan) }}" onsubmit="return confirm('Supprimer ce forfait ?');">
                        @csrf @method('DELETE')
                        <button class="sup" type="submit">Supprimer</button>
                    </form>
                </div>
            </div>
        @empty
            <p>Aucun forfait disponible.</p>
        @endforelse
    </div>
</div>
@endsection
