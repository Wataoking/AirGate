<div class="picture">
    <img src="{{ asset('image/logo.png') }}" alt="Logo de la plateforme">
</div>
    
<div class="plat">
    <div class="perso">
        <h2>MENU</h2>
    </div>
            <div class="phrase0">
                <a href="{{ route('super-admin.dashboard') }}">
                    <i class="fa-solid fa-house"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="phrase0">
                <a href="{{ route('super-admin.compte') }}">
                    <i class="fa-solid fa-circle-user"></i>
                    <span>Gestion des comptes</span>
                </a>
            </div>

            <div class="phrase0">
                <a href="{{ route('super-admin.forfaits') }}">
                    <i class="fa-solid fa-inbox"></i>
                    <span>Gestion des forfaits</span>
                </a>
            </div>

            <div class="phrase0">
                <a href="{{ route('super-admin.client') }}">
                    <i class="fa-solid fa-users"></i>
                    <span>Gestion clients</span>
                </a>
            </div>

            <div class="phrase0">
                <a href="{{ route('super-admin.wifi') }}">
                    <i class="fa-solid fa-wifi"></i>
                    <span>Appareil WiFi</span>
                </a>
            </div>

            <div class="phrase0">
                <a href="{{ route('super-admin.stat') }}">
                    <i class="fa-solid fa-chart-simple"></i>
                    <span>Analytique</span>
                </a>
            </div>

            
            <div class="phrase0">
                <a href="{{ route('super-admin.facturation') }}">
                    <i class="fa-regular fa-credit-card"></i>
                    <span>Facturation</span>
                </a>
            </div>

            <div class="phrase0">
                <a href="{{ route('super-admin.notification') }}">
                    <i class="fa-regular fa-bell"></i>
                    <span>Notification</span>
                </a>
            </div>

            <div class="phrase0">
                <a href="{{ route('super-admin.parametre') }}">
                    <i class="fa-solid fa-gear"></i>
                    <span>Parametre</span>
                </a>
            </div>
        
    <div class="phrase9">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">
            <i class="fa-solid fa-circle-user"></i>
            <span>Deconnexion</span>
            <i class="fa-solid fa-right-from-bracket"></i>
            </button>
        </form>
    </div>

</div>

<script>
    const menuItems = document.querySelectorAll('.phrase0');
const indicator = document.querySelector('.indicator');

// Positionne l'indicator sur l'item .active au chargement
function moveIndicator(target) {
    indicator.style.top = target.offsetTop + 'px';
}

// Au démarrage
moveIndicator(document.querySelector('.phrase0.active'));

// Au clic
menuItems.forEach(item => {
    item.addEventListener('click', () => {
        menuItems.forEach(i => i.classList.remove('active'));
        item.classList.add('active');
        moveIndicator(item);
    });
});
</script>


     
    
 