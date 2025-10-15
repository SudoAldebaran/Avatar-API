<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Avatar API')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
    <!-- POLICE BANGERS POUR TITRES -->
    <link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <!-- INCLUSION DE LA BARRE DE NAVIGATION -->
    @include('layouts.nav')
    <div class="container mx-auto py-4">
        @yield('content')
    </div>
    @yield('scripts')
    <script>
        // MET A JOUR L'ETAT DE LA NAVBAR EN FONCTION DE L'AUTHENTIFICATION
        document.addEventListener('DOMContentLoaded', function() {
            updateNavbarAuth();
        });
    </script>
</body>

</html>
