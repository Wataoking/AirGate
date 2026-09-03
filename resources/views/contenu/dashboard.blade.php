@extends('layout.index')

@section('content')
<div class="topbar">
    <div class="titre">
        <h1>Dashboard</h1>
        <p>Gérez votre plateforme depuis cet espace</p>
    </div>
    <div class="nb4">
        <i class="fa-regular fa-bell"></i>
        <i class="fa-solid fa-circle-user"></i>
        <span>{{ auth()->user()->name }}</span>
    </div>
</div>

<div class="stat dashboard-stats">
    <div class="stat1"><i class="fa-solid fa-chart-line"></i><p>Bande passante active</p><h2>{{ number_format($bandwidth, 1) }} Mbps</h2><p>Modems en ligne</p></div>
    <div class="stat2"><i class="fa-solid fa-wifi"></i><p>Appareils connectés</p><h2>{{ $onlineModems }}</h2><p>Sur {{ $totalModems }} modem(s)</p></div>
    <div class="stat3"><i class="fa-solid fa-box"></i><p>Forfaits actifs</p><h2>{{ $activePlans }}</h2><p>Disponibles</p></div>
    <div class="stat4"><i class="fa-solid fa-circle-user"></i><p>Nouveaux utilisateurs</p><h2>{{ $usersThisWeek }}</h2><p>Cette semaine</p></div>
</div>

<div class="cards-container dashboard-cards">
    <div class="cardf">
        <div class="card-header">
            <div class="card-title"><h2>Consommation réseau</h2></div>
            <div class="btn-group">
                <button class="btn active" type="button" data-period="24H">24H</button>
                <button class="btn" type="button" data-period="7j">7j</button>
                <button class="btn" type="button" data-period="30j">30j</button>
                <button class="btn btn-export" type="button" id="export-dashboard">Exporter</button>
            </div>
        </div>
        <div class="chart-wrap"><canvas id="bandwidthChart" aria-label="Graphique de consommation réseau"></canvas></div>
        <div class="stats dashboard-chart-stats">
            <span>Moyenne : {{ number_format($bandwidth, 1) }} Mbps</span>
            <span>Modems : {{ $totalModems }}</span>
            <span>Total : {{ number_format($totalData, 1) }} GB</span>
        </div>
    </div>

    <div class="card2">
        <div class="card-header2">
            <div class="card-title2"><h2>Alertes récentes</h2></div>
            <a href="{{ route('super-admin.notification') }}" class="view-all">Voir tout</a>
        </div>
        <div class="duck">
            @if ($totalModems === 0)
                <div class="alert-item"><div class="dot yellow"></div><div><div class="alert-text">Aucun modem</div><div class="alert-sub">Ajoutez un appareil WiFi pour commencer.</div></div></div>
            @elseif ($onlineModems < $totalModems)
                <div class="alert-item"><div class="dot orange"></div><div><div class="alert-text">Modem bloqué</div><div class="alert-sub">{{ $totalModems - $onlineModems }} modem(s) ne sont pas en ligne.</div></div></div>
            @else
                <div class="alert-item"><div class="dot blue"></div><div><div class="alert-text">Réseau opérationnel</div><div class="alert-sub">Tous les modems enregistrés sont en ligne.</div></div></div>
            @endif
            <div class="alert-item"><div class="dot blue"></div><div><div class="alert-text">Forfaits disponibles</div><div class="alert-sub">{{ $activePlans }} forfait(s) actif(s) dans la plateforme.</div></div></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const chartData = @json($chartData);
const chartLabels = @json($chartLabels);
const periods = {
    '24H': chartData,
    '7j': chartData.map((value, index) => Number((value * (1.1 + index / 10)).toFixed(2))),
    '30j': chartData.map((value, index) => Number((value * (0.8 + index / 14)).toFixed(2)))
};
const chart = new Chart(document.getElementById('bandwidthChart'), {
    type: 'line',
    data: { labels: chartLabels, datasets: [{ label: 'Mbps', data: periods['24H'], borderColor: '#14b8b8', backgroundColor: 'rgba(20, 184, 184, 0.15)', fill: true, tension: 0.35, pointRadius: 3 }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { callback: value => value + ' Mbps' } }, x: { grid: { display: false } } } }
});

document.querySelectorAll('[data-period]').forEach(button => button.addEventListener('click', () => {
    document.querySelectorAll('[data-period]').forEach(item => item.classList.remove('active'));
    button.classList.add('active');
    chart.data.datasets[0].data = periods[button.dataset.period];
    chart.update();
}));

document.getElementById('export-dashboard').addEventListener('click', () => {
    const link = document.createElement('a');
    link.download = 'consommation-reseau.png';
    link.href = chart.toBase64Image();
    link.click();
});
</script>
@endsection
