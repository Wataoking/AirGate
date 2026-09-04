@extends('layout.index')

@section('content')
<div class="topbar78">
    <div class="titre"><h1>Notifications</h1><p>Alertes de sécurité et d'activité du réseau</p></div>
    <div class="user0"><i class="fa-regular fa-bell"></i><div class="user"><span>{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span></div></div>
</div>

@if (session('status'))<p class="flash-success">{{ session('status') }}</p>@endif

<div class="notif-container">
    <div class="notif-filters">
        <div class="be1">
            <a class="filter {{ $type === 'all' ? 'active' : '' }}" href="{{ route('super-admin.notification', ['type' => 'all']) }}">Toutes</a>
            <a class="filter {{ $type === 'alert' ? 'active' : '' }}" href="{{ route('super-admin.notification', ['type' => 'alert']) }}">Alertes</a>
            <a class="filter {{ $type === 'info' ? 'active' : '' }}" href="{{ route('super-admin.notification', ['type' => 'info']) }}">Infos</a>
            <form method="POST" action="{{ route('super-admin.notification.read-all') }}">@csrf<button class="btn-mark-all" type="submit">Marquer tout comme lu ({{ $unreadCount }})</button></form>
        </div>
    </div>

    <div class="notif-list">
        @forelse ($alerts as $alert)
            <div class="notif-item border-{{ $alert->type === 'alert' ? 'red' : 'blue' }} {{ $alert->read_at ? 'is-read' : 'is-unread' }}">
                <div class="icon bg-{{ $alert->type === 'alert' ? 'red' : 'blue' }}">{{ $alert->type === 'alert' ? '!' : 'i' }}</div>
                <div class="content"><b>{{ $alert->title }}</b><p>{{ $alert->message }}</p><small>{{ $alert->created_at->diffForHumans() }}</small></div>
                <div class="notification-actions">
                    @if ($alert->kind === 'account_registration' && $alert->user && $alert->user->account_status === 'pending')
                        <form method="POST" action="{{ route('super-admin.notification.approve-account', $alert) }}">@csrf @method('PATCH')<button class="btn-outline" type="submit">Valider le compte</button></form>
                        <form method="POST" action="{{ route('super-admin.notification.reject-account', $alert) }}">@csrf @method('PATCH')<button class="btn-outline gray" type="submit">Refuser le compte</button></form>
                    @endif
                    @if (! $alert->read_at)
                        <form method="POST" action="{{ route('super-admin.notification.read', $alert) }}">@csrf @method('PATCH')<button class="btn-outline" type="submit">Marquer comme lu</button></form>
                    @else
                        <span class="read-label">Lu</span>
                    @endif
                    <form method="POST" action="{{ route('super-admin.notification.destroy', $alert) }}" onsubmit="return confirm('Supprimer cette notification ?');">@csrf @method('DELETE')<button class="btn-outline gray" type="submit">Supprimer</button></form>
                </div>
            </div>
        @empty
            <div class="empty-state">Aucune notification pour ce filtre.</div>
        @endforelse
    </div>
</div>
@endsection
