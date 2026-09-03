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
                    <button class="toggle-button" type="button" aria-controls="mySidebar" aria-expanded="false" onclick="toggleSidebar()">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
                @yield('content')
            </main>
        </div>
    </section>

    <script>
        function toggleSidebar() {
            // 1. On récupère les éléments HTML par leur ID
            const sidebar = document.getElementById("mySidebar");
            const content = document.getElementById("mainContent");
            const button = document.querySelector(".toggle-button");
            
            // 2. On ajoute ou supprime la classe "active" à chaque clic
            const isOpen = sidebar.classList.toggle("active");
            content.classList.toggle("active", isOpen);
            button.setAttribute("aria-expanded", String(isOpen));
        }
    </script>
</body>
</html>