@extends('layout.index')

@section('content')
<div class="topbar6">
    <div class="titre"><h1>Paramètres</h1><p>Configuration réseau</p></div>
    <div class="fake"><i class="fa-regular fa-bell"></i></div>
</div>

@if (session('status'))<p class="flash-success">{{ session('status') }}</p>@endif
@if ($errors->any())<div class="flash-error"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

<form class="settings-form" method="POST" action="{{ route('super-admin.parametre.update') }}">
    @csrf
    @method('PUT')
    <section class="settings-card">
        <div class="settings-heading"><i class="fa-solid fa-wifi"></i><div><h2>Configuration WiFi</h2><p>Définissez le réseau utilisé par vos appareils.</p></div></div>
        <div class="settings-grid">
            <label>SSID<input name="wifi_ssid" value="{{ old('wifi_ssid', $settings->wifi_ssid) }}" minlength="8" maxlength="32" required placeholder="Nom du réseau WiFi"></label>
            <label>Mot de passe<input name="wifi_password" type="password" value="{{ old('wifi_password', $settings->wifi_password) }}" minlength="8" maxlength="32" required placeholder="Mot de passe WiFi"></label>
        </div>
        <p class="settings-help">Le SSID et le mot de passe doivent contenir entre 8 et 32 caractères.</p>
    </section>

    <section class="settings-card">
        <div class="settings-heading"><i class="fa-solid fa-hourglass"></i><div><h2>Limite de la bande passante</h2><p>Définissez la limite autorisée pour les appareils connectés.</p></div></div>
        <label class="settings-single">Débit maximum (Mbps)<input name="bandwidth_limit" type="number" min="0" value="{{ old('bandwidth_limit', $settings->bandwidth_limit) }}" required></label>
    </section>

    <section class="settings-card">
        <div class="settings-heading"><i class="fa-solid fa-gear"></i><div><h2>Configuration RADIUS API</h2><p>Paramètres d'authentification auprès du serveur RADIUS.</p></div></div>
        <div class="settings-grid settings-grid-three">
            <label>URL<input name="radius_url" type="url" value="{{ old('radius_url', $settings->radius_url) }}" placeholder="https://radius.exemple.com"></label>
            <label>Mot de passe<input name="radius_password" type="password" value="{{ old('radius_password', $settings->radius_password) }}" placeholder="Mot de passe API"></label>
            <label>Clé secrète<input name="radius_secret" type="password" value="{{ old('radius_secret', $settings->radius_secret) }}" placeholder="Clé secrète RADIUS"></label>
        </div>
    </section>

    <div class="settings-actions">
        <button class="settings-reset" type="submit" form="settings-reset-form">Réinitialiser</button>
        <button class="settings-save" type="submit">Enregistrer les modifications</button>
    </div>
</form>
<form id="settings-reset-form" method="POST" action="{{ route('super-admin.parametre.reset') }}">
    @csrf
</form>
@endsection
