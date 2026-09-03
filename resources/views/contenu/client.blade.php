@extends('layout.index')

@section('content')
<div class="topbar4">
    <div class="titre">
        <h1>Gestion des clients</h1>
        <p>Gérez vos clients plus facilement</p>
    </div>
    <div class="nb10">
        <i class="fas fa-search"></i>
        <i class="fa-regular fa-bell"></i>
    </div>
</div>

@if (session('status'))
    <p class="flash-success">{{ session('status') }}</p>
@endif

@if ($errors->any())
    <div class="flash-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="null">
    <div class="nb12">
        <h2>Stat</h2>
        <span>{{ $totalClients }} clients WiFi connectés</span>
    </div>

    <div class="nb13">
        <button type="button" onclick="document.getElementById('client-form').hidden = false; document.getElementById('client-name').focus();">+ ajouter un client</button>
    </div>
</div>

<div class="nb32">
    <div class="total">
        <i class="fa-solid fa-circle-user"></i>
        <p>total clients</p>
        <h1>{{ $totalClients }}</h1>
        <p>cette semaine</p>
    </div>

    <div class="f1">
        <p>bouquet</p>
        <span>ACCES</span>
        <h1>{{ $planCounts['ACCES'] ?? 0 }}</h1>
        <p>clients actifs</p>
    </div>

    <div class="f2">
        <p>bouquet</p>
        <span>ACCES+</span>
        <h1>{{ $planCounts['ACCESS+'] ?? 0 }}</h1>
        <p>clients actifs</p>
    </div>

    <div class="f3">
        <p>bouquet</p>
        <span>EVASION</span>
        <h1>{{ $planCounts['EVASION'] ?? 0 }}</h1>
        <p>clients actif</p>
    </div>
</div>

<div class="nb30">
    <div class="garr">
        <div class="g1">
            <h2>Clients WiFi</h2>
        </div>

        <form method="GET" action="{{ route('super-admin.client') }}" class="g2">
            <i class="fas fa-search"></i>
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="rechercher par nom, code ou MAC">
        </form>
    </div>

    <form id="client-form" class="client-form" method="POST" action="{{ route('super-admin.client.store') }}" hidden>
        @csrf
        <h3>Nouveau client</h3>
        <div class="modem-form-grid">
            <label>Nom <input id="client-name" name="name" required placeholder="Bébé Mpa"></label>
            <label>Email <input type="email" name="email" required placeholder="client@email.com"></label>
            <label>Mot de passe <input type="password" name="password" required placeholder="Minimum 8 caractères"></label>
            <label>Téléphone <input name="phone_number" placeholder="+237 6xx xx xx xx"></label>
        </div>
        <button class="btn-add" type="submit">Enregistrer</button>
        <button class="btn-light" type="button" onclick="document.getElementById('client-form').hidden = true;">Annuler</button>
    </form>

    @forelse ($clients as $client)
        <div class="deff">
            <div class="e1">
                <p>Nom</p>
                <p class="p2">{{ $client['name'] }}</p>
            </div>
            <div class="e1">
                <p>Code</p>
                <p class="p2">{{ $client['code'] }}</p>
            </div>
            <div class="e1">
                <p>Plan</p>
                <p class="p2">{{ $client['plan'] }}</p>
            </div>
            <div class="e1">
                <p>Status</p>
                <p class="p2">{{ $client['status'] }}</p>
            </div>
            <div class="e1">
                <p>Temps restant</p>
                <p class="p2">{{ $client['remaining_minutes'] }} min</p>
            </div>
            <div class="e1">
                <p>Adresse MAC</p>
                <p class="p2">{{ $client['mac_address'] }}</p>
            </div>
            <div class="e1">
                <p>Action</p>
                <button class="p2" type="button">Déconnexion</button>
            </div>
        </div>
    @empty
        <div class="empty-state">Aucun client trouvé.</div>
    @endforelse
</div>

@endsection