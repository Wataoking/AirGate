<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="{{ asset('style/clime.css') }}">
</head>
<body>
    <div class="pointe99">
        <div class="topbar24">
            <div class="titre">
                <h1>Forfaits clients</h1>
                <p>soucrivez a un forfaits plus facilement</p>
            </div>

            <a href="home"> < back </a>
        </div>

        <div class="week0">
            <div class="week2">
                <span>Nombre de forfait</span>
                <h1>{{ $plans->count() }}</h1>
            </div>

            <div class="week2">
                <span> forfait active </span>
                <h1>{{ $plans->where('active', true)->count() }}</h1>
            </div>
        </div>

        <div class="topbar99">
            <h2>Forfaits disponible</h2>
            <input type="search" id="recherche-forfait" placeholder="Rechercher un forfait" aria-label="Rechercher un forfait">
        </div>

        <div class="week3">
            @forelse ($plans as $plan)
                <div class="week forfait">
                    <form method="POST" action="{{ route('souscription.purchase') }}">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <div class="type{{ ($loop->index % 3) + 1 }}"><label>{{ $plan->name }}</label></div>
                        <div class="pawa">
                        <div class="g-circle{{ ($loop->index % 3) + 1 }}">
                           <span>◷</span>
                        </div>
                        <h1>{{ $plan->duration }}</h1>
                        <h2>{{ number_format($plan->price, 0, ',', ' ') }}F</h2>
                        <P>{{ number_format($plan->speed, 1) }}Mbps</P>
                        <P>✓ Acces illimite</P>
                        <P>valable {{ $plan->duration }}</P>
                        <label>Moyenne de Payement</label>
                        <select name="payment_method" class="methode" required>
                            <option value="OM">ORANGE money</option>
                            <option value="MOMO">MTN money</option>
                            <option value="CB">Carte Banquaire</option>
                        </select>
                            <button type="submit" class="acheter" {{ ! $plan->active ? 'disabled' : '' }}>Acheter</button>
                        </div>
                    </form>
                </div>
            @empty
                <p>Aucun forfait disponible.</p>
            @endforelse
        </div>

        
    </div>

    <script>
        const recherche = document.getElementById('recherche-forfait');
        const forfaits = document.querySelectorAll('.forfait');

        if (recherche) {
            recherche.addEventListener('input', function () {
                const terme = this.value.trim().toLowerCase();
                forfaits.forEach(forfait => {
                    forfait.hidden = terme !== '' && !forfait.textContent.toLowerCase().includes(terme);
                });
            });
        }
    </script>
</body>
</html>