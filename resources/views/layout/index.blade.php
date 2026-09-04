<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('style/clime.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <section class="main-container app-shell">
        <div class="toiture app-sidebar" id="mySidebar">
          @include('layout.sidebard')
        </div>
        <div class="clime app-content" id="mainContent">
            <main class="app-content-inner">
                <div class="picture2">
                    <img src="{{ asset('image/logo.png') }}" alt="Logo de la plateforme">
                    <button id="sidebarToggle" class="toggle-button" type="button" aria-controls="mySidebar" aria-expanded="false">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
                @yield('content')
            </main>
        </div>
    </section>

    <script>
        const sidebar = document.getElementById('mySidebar');
        const content = document.getElementById('mainContent');
        const toggleButton = document.getElementById('sidebarToggle');

        function toggleSidebar() {
            if (!sidebar || !content || !toggleButton) {
                return;
            }

            const isOpen = sidebar.classList.toggle('active');
            content.classList.toggle('active', isOpen);
            toggleButton.setAttribute('aria-expanded', String(isOpen));
        }

        toggleButton?.addEventListener('click', toggleSidebar);

        sidebar?.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('active');
                    content?.classList.remove('active');
                    toggleButton?.setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>
</body>
</html>