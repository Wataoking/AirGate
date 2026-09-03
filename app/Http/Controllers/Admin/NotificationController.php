<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->get('type', 'all');
        $alerts = Alert::with('modem')->when(in_array($type, ['alert', 'info']), fn ($query) => $query->where('type', $type))->latest()->get();

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
}