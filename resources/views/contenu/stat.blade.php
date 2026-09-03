@extends('layout.index')

@section('content')
<div class="topbar8">
    <div class="titre">
        <h1>Analytique</h1>
        <span>Analyse du réseau sur les 30 derniers jours</span>
    </div>
    <button class="btn-export-pdf" type="button" onclick="window.print()">Exporter PDF</button>
</div>

<div class="airgate-analytique-content">
    <div class="stats-top">
        <div class="a-card"><div class="a-icon">↗</div><div class="a-label">Consommation totale</div><div class="a-value">{{ number_format($totalData, 1) }} Go</div><div class="a-trend up">Données des modems enregistrés</div></div>
        <div class="a-card"><div class="a-icon">€</div><div class="a-label">Revenus estimés</div><div class="a-value">{{ number_format($estimatedRevenue, 0, ',', ' ') }} F</div><div class="a-trend neutral">Total des forfaits actifs</div></div>
        <div class="a-card"><div class="a-icon">👥</div><div class="a-label">Utilisateurs actifs</div><div class="a-value">{{ $activeUsers }}</div><div class="a-trend neutral">{{ $newUsers }} créé(s) ce mois-ci</div></div>
    </div>

    <div class="main-row">
        <div class="a-card big">
            <div class="a-card-header"><h3>Consommation journalière en Go</h3><span class="info" title="Données estimées à partir des modems">i</span></div>
            <div class="graph"><svg viewBox="0 0 500 200" preserveAspectRatio="none"><path id="graph-area" d="" fill="rgba(19,115,51,0.13)"/><path id="graph-line" d="" fill="none" stroke="#137333" stroke-width="2.5" stroke-linecap="round"/></svg><div class="y"><span>60</span><span>40</span><span>20</span><span>0</span></div><div class="x"><span>J-6</span><span>J-5</span><span>J-4</span><span>J-3</span><span>J-2</span><span>J-1</span><span>Aujourd'hui</span></div></div>
            <div class="moy">Moyenne journalière : {{ number_format(collect($dailyData)->avg(), 1) }} Go</div>
        </div>

        <div class="right-col">
            <div class="a-card"><div class="a-card-header"><h3>Modems les plus utilisés</h3><span>🏆</span></div>
                @forelse ($modems->take(5) as $modem)
                    <div class="u-row"><span class="av c{{ ($loop->index % 5) + 1 }}">{{ strtoupper(substr($modem->name, 0, 2)) }}</span><span class="u-name">{{ $modem->name }}</span><div class="bar"><div style="width:{{ $totalData > 0 ? min(100, ($modem->data_used / $totalData) * 100) : 0 }}%"></div></div><span class="u-val {{ $loop->first ? 'active' : '' }}">{{ number_format($modem->data_used, 1) }} Go</span></div>
                @empty
                    <p>Aucun modem enregistré.</p>
                @endforelse
            </div>

            <div class="a-card"><div class="a-card-header"><h3>Revenus par forfait</h3><span>↻</span></div><div class="pie-box"><div class="pie"></div><div class="legend">
                @forelse ($plans as $plan)
                    <p><i class="dot d{{ ($loop->index % 3) + 1 }}"></i>{{ $plan->name }} <b>{{ number_format($plan->price, 0, ',', ' ') }} F</b></p>
                @empty
                    <p>Aucun forfait actif.</p>
                @endforelse
            </div></div></div>
            <p class="p8">Les revenus affichés sont une estimation basée sur les forfaits actifs.</p>
        </div>
    </div>
</div>

<script>
const values = @json($dailyData);
const points = values.map((value, index) => `${(index * 500) / (values.length - 1)},${Math.max(10, 190 - (value / Math.max(...values, 1)) * 170)}`);
const line = `M${points.join(' L')}`;
document.getElementById('graph-line').setAttribute('d', line);
document.getElementById('graph-area').setAttribute('d', `${line} L500,200 L0,200 Z`);
</script>
@endsection
