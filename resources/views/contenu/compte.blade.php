@extends('layout.index')

@section('content')
<div class="topbar2">
    <div class="titre">
        <h1>Gestion des comptes</h1>
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

<div class="content-admin">
    <div class="grid-2">
        <form class="card" method="POST" action="{{ route('super-admin.compte.store') }}">
            @csrf
            <div class="card-title">Créer un compte</div>
            <label for="name">Nom complet</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Ex : Marie Dupont" required>
            <label for="email">Adresse email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="exemple@entreprise.fr" required>
            <label for="phone_number">Téléphone</label>
            <input id="phone_number" name="phone_number" type="text" value="{{ old('phone_number') }}" placeholder="+2250700000000" required>
            <label for="password">Mot de passe</label>
            <input id="password" name="password" type="password" minlength="8" required>
            <label for="role">Rôle</label>
            <select id="role" name="role" required>
                <option value="admin" @selected(old('role', 'admin') === 'admin')>Administrateur</option>
                <option value="manager" @selected(old('role') === 'manager')>Manager</option>
                <option value="user" @selected(old('role') === 'user')>Utilisateur</option>
            </select>
            <button class="btn-green" type="submit">Créer le compte</button>
        </form>

        <div class="card">
            <div class="card-title">Créer un utilisateur WiFi</div>
            <p class="hint">Utilisez le formulaire de création avec le rôle « Utilisateur ».</p>
            <button class="btn-green" type="button" onclick="document.querySelector('#role').value = 'user'; document.querySelector('#name').focus();">
                Préparer un utilisateur WiFi
            </button>
        </div>
    </div>

    @if ($editing)
        <form class="card mt-20" method="POST" action="{{ route('super-admin.compte.update', $editing) }}">
            @csrf
            @method('PUT')
            <div class="card-title">Modifier {{ $editing->name }}</div>
            <label for="edit-name">Nom complet</label>
            <input id="edit-name" name="name" type="text" value="{{ old('name', $editing->name) }}" required>
            <label for="edit-email">Adresse email</label>
            <input id="edit-email" name="email" type="email" value="{{ old('email', $editing->email) }}" required>
            <label for="edit-phone">Téléphone</label>
            <input id="edit-phone" name="phone_number" type="text" value="{{ old('phone_number', $editing->{'phone number'}) }}" required>
            <label for="edit-role">Rôle</label>
            <select id="edit-role" name="role" required>
                <option value="admin" @selected($editing->role === 'admin')>Administrateur</option>
                <option value="manager" @selected($editing->role === 'manager')>Manager</option>
                <option value="user" @selected($editing->role === 'user')>Utilisateur</option>
            </select>
            <button class="btn-green" type="submit">Enregistrer les modifications</button>
            <a class="btn-light" href="{{ route('super-admin.compte') }}">Annuler</a>
        </form>
    @endif

    <div class="card mt-20">
        <div class="table-top">
            <div class="card-title">Comptes ({{ $accounts->count() }})</div>
            <a class="btn-light" href="{{ route('super-admin.compte.export', ['role' => $role]) }}">Exporter CSV</a>
        </div>
        <form class="account-filters" method="GET" action="{{ route('super-admin.compte') }}">
            <input name="search" type="search" value="{{ $search }}" placeholder="Rechercher par nom ou email">
            <select name="role">
                <option value="">Tous les rôles</option>
                <option value="admin" @selected($role === 'admin')>Administrateur</option>
                <option value="manager" @selected($role === 'manager')>Manager</option>
                <option value="user" @selected($role === 'user')>Utilisateur</option>
            </select>
            <button class="btn-light" type="submit">Filtrer</button>
            <a class="btn-light" href="{{ route('super-admin.compte') }}">Réinitialiser</a>
        </form>
        <div class="accounts-table-wrap">
            <table>
                <thead><tr><th>Nom</th><th>Rôle</th><th>Statut</th><th>Création</th><th>Actions</th></tr></thead>
                <tbody>
                @forelse ($accounts as $account)
                    <tr>
                        <td><div class="u"><span class="av av1">{{ strtoupper(substr($account->name, 0, 2)) }}</span><div><b>{{ $account->name }}</b><i>{{ $account->email }}</i></div></div></td>
                        <td><span class="b b-blue">{{ ucfirst(str_replace('_', ' ', $account->role)) }}</span></td>
                        <td><span class="b {{ $account->account_status === 'approved' ? 'b-green' : ($account->account_status === 'pending' ? 'b-gray' : 'b-red') }}">{{ $account->account_status === 'approved' ? 'Actif' : ($account->account_status === 'pending' ? 'En attente' : 'Refusé') }}</span></td>
                        <td>{{ $account->created_at?->format('d/m/Y H:i') }}</td>
                        <td class="acts">
                            <a class="btn-light" href="{{ route('super-admin.compte', ['edit' => $account->id]) }}">Modifier</a>
                            <form method="POST" action="{{ route('super-admin.compte.reset-password', $account) }}" class="inline-form">
                                @csrf
                                <button class="btn-light" type="submit">Réinitialiser</button>
                            </form>
                            <form method="POST" action="{{ route('super-admin.compte.destroy', $account) }}" class="inline-form" onsubmit="return confirm('Supprimer ce compte ?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn-light" type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">Aucun compte trouvé.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
