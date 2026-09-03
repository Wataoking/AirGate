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
        <div class="toiture app-sidebar">
          @include('layout.sidebard')
        </div>
        <div class="clime app-content">
            <main class="app-content-inner">
                @yield('content')
            </main>
        </div>
    </section>
</body>
</html>