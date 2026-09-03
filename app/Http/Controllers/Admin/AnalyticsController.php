<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modem;
use App\Models\Plan;
use App\Models\User;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(): View
    {
        $modems = Modem::orderByDesc('data_used')->get();
        $plans = Plan::where('active', true)->get();

        return view('contenu.stat', [
            'totalData' => $modems->sum('data_used'),
            'estimatedRevenue' => $plans->sum('price'),
            'activeUsers' => User::where('role', 'user')->count(),
            'newUsers' => User::where('created_at', '>=', now()->subDays(30))->count(),
            'modems' => $modems,
            'plans' => $plans,
            'dailyData' => $this->dailyData($modems->sum('data_used')),
        ]);
    }

    private function dailyData(float $totalData): array
    {
        return collect([0.72, 0.58, 0.86, 0.64, 1.08, 0.91, 1.2])
            ->map(fn ($factor) => round($totalData * $factor / 4, 2))
            ->all();
    }
}