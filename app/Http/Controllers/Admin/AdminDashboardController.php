<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modem;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $modems = Modem::latest()->get();
        $usersThisWeek = User::where('created_at', '>=', now()->subWeek())->count();
        $bandwidth = $modems->where('status', 'online')->sum('bandwidth');
        $chartBase = max($bandwidth / 100, 0.1);

        return view('contenu.dashboard', [
            'bandwidth' => $bandwidth,
            'onlineModems' => $modems->where('status', 'online')->count(),
            'totalModems' => $modems->count(),
            'totalData' => $modems->sum('data_used'),
            'activePlans' => Plan::where('active', true)->count(),
            'usersThisWeek' => $usersThisWeek,
            'chartLabels' => ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00', '24:00'],
            'chartData' => collect([0.45, 0.7, 0.55, 0.9, 1.15, 0.8, 0.65])
                ->map(fn ($value) => round($value * $chartBase, 2))
                ->all(),
        ]);
    }
}
