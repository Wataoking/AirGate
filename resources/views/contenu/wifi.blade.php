@extends('layout.index')

@section('content')
<div class="topbar100">
    <div class="titre">
        <h1>Gestion WiFi</h1>
        <p>Gérez vos points d'accès AirGate</p>
    </div>
</div>

@if (session('status'))
    <p class="flash-success">{{ session('status') }}</p>
@endif

@if ($errors->any())
    <div class="flash-error"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif

<div class="wifi-content">
    <div class="stats-wifi">
        <div class="w-card"><div class="w-icon">↓</div><div><b>Total téléchargé</b><h2>{{ number_format($totalData, 1) }} GB</h2><small>Données enregistrées</small></div></div>
        <div class="w-card"><div class="w-icon">◍</div><div><b>Bande passante active</b><h2>{{ number_format($activeBandwidth, 1) }} Mbps</h2><small>Modems en ligne</small></div></div>
        <div class="w-card"><div class="w-icon">⧉</div><div><b>Modems connectés</b><h2>{{ $onlineCount }} / {{ $modems->count() }}</h2><small>{{ $modems->where('status', 'blocked')->count() }} bloqué(s)</small></div></div>
    </div>

    <div class="table-header">
        <h3>Appareils connectés</h3>
        <div class="right-actions">
            <span class="update-badge">Mis à jour maintenant</span>
            <button class="btn-add" type="button" onclick="document.getElementById('modem-form').hidden = false; document.getElementById('modem-name').focus();">+ Ajouter un modem</button>
        </div>
    </div>

    <form id="modem-form" class="modem-form" method="POST" action="{{ route('super-admin.wifi.store') }}" hidden>
        @csrf
        <h3>Nouveau modem</h3>
        <div class="modem-form-grid">
            <label>Nom <input id="modem-name" name="name" required placeholder="Modem-Bureau"></label>
            <label>Modèle <input name="model" required value="AirGate Pro"></label>
            <label>Adresse MAC <input name="mac_address" required placeholder="A4:6D:3B:9B:12:7F"></label>
            <label>Adresse IP <input name="ip_address" required placeholder="192.168.1.102"></label>
            <label>Bande passante (Mbps) <input name="bandwidth" type="number" min="0" step="0.1" required value="0"></label>
        </div>
        <button class="btn-add" type="submit">Enregistrer</button>
        <button class="btn-light" type="button" onclick="document.getElementById('modem-form').hidden = true;">Annuler</button>
    </form>

    <div class="modem-table">
        <div class="t-head"><span>Appareil</span><span>Adresse IP</span><span>Données utilisées</span><span>Bande passante</span><span>Statut</span><span>Action</span></div>
        @forelse ($modems as $modem)
            <div class="t-row">
                <div class="appareil"><div class="ic blue">📶</div><div><b>{{ $modem->name }}</b><small>{{ $modem->model }} - MAC: {{ $modem->mac_address }}</small></div></div>
                <div class="ip">{{ $modem->ip_address }}</div>
                <div class="data"><b>{{ number_format($modem->data_used, 1) }} GB</b><small>Utilisation enregistrée</small></div>
                <div class="bande"><b>{{ number_format($modem->bandwidth, 1) }} Mbps</b><small>Limite configurée</small></div>
                <div><span class="statut {{ $modem->status === 'online' ? 'online' : 'blocked' }}">● {{ $modem->status === 'online' ? 'En ligne' : 'Bloqué' }}</span></div>
                <div class="actions">
                    <form method="POST" action="{{ route('super-admin.wifi.toggle', $modem) }}">
                        @csrf @method('PATCH')
                        <button class="btn-block" type="submit">{{ $modem->status === 'online' ? 'Bloquer' : 'Débloquer' }}</button>
                    </form>
                    <form method="POST" action="{{ route('super-admin.wifi.destroy', $modem) }}" onsubmit="return confirm('Supprimer ce modem ?');">
                        @csrf @method('DELETE')
                        <button class="btn-del" type="submit">Supprimer</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state">Aucun modem enregistré. Cliquez sur « Ajouter un modem ».</div>
        @endforelse
    </div>
</div>
@endsection
