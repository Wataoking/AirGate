<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->get('type', 'all');
        $alerts = Alert::with(['modem', 'user'])->when(in_array($type, ['alert', 'info']), fn ($query) => $query->where('type', $type))->latest()->get();

        return view('contenu.notification', [
            'alerts' => $alerts,
            'type' => $type,
            'unreadCount' => Alert::whereNull('read_at')->count(),
        ]);
    }

    public function markRead(Alert $alert): RedirectResponse
    {
        $alert->update(['read_at' => now()]);
        return back()->with('status', 'Notification marquée comme lue.');
    }

    public function markAllRead(): RedirectResponse
    {
        Alert::whereNull('read_at')->update(['read_at' => now()]);
        return back()->with('status', 'Toutes les notifications sont lues.');
    }

    public function destroy(Alert $alert): RedirectResponse
    {
        $alert->delete();
        return back()->with('status', 'Notification supprimée.');
    }

    public function approveAccount(Alert $alert): RedirectResponse
    {
        abort_unless($alert->kind === 'account_registration' && $alert->user_id, 404);

        $user = User::findOrFail($alert->user_id);
        abort_unless($user->account_status === 'pending', 409);
        $user->update([
            'account_status' => 'approved',
            'email_verified_at' => $user->email_verified_at ?? now(),
        ]);
        $alert->update([
            'type' => 'info',
            'title' => 'Compte validé',
            'message' => "Le compte de {$user->name} a été validé.",
            'read_at' => now(),
        ]);

        return back()->with('status', 'Le compte a été validé.');
    }

    public function rejectAccount(Alert $alert): RedirectResponse
    {
        abort_unless($alert->kind === 'account_registration' && $alert->user_id, 404);

        $user = User::findOrFail($alert->user_id);
        abort_unless($user->account_status === 'pending', 409);
        $user->update(['account_status' => 'rejected']);
        $alert->update([
            'type' => 'alert',
            'title' => 'Compte refusé',
            'message' => "Le compte de {$user->name} a été refusé.",
            'read_at' => now(),
        ]);

        return back()->with('status', 'Le compte a été refusé.');
    }
}