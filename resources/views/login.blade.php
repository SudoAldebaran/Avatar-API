<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">

    <!-- BARRE DE NAVIGATION AVEC LOGO ET TITRE -->
    <nav class="bg-white shadow-md p-4 mb-8">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16">
                <span class="text-4xl font-semibold tracking-wider" style="font-family: 'Bangers', cursive;">
                    <span style="color:#FF9800;">Avatar</span>
                    <span style="color:#00AFF5;">API</span>
                </span>
            </div>
            <div class="flex space-x-6 items-center" id="nav-auth"></div>
        </div>
    </nav>

    <div class="flex justify-center items-center min-h-[60vh]">
        <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-8">
            <h1 class="text-2xl font-bold mb-6 text-center" style="color:#00AFF5;">
                Connexion à votre compte
            </h1>
            <form id="loginForm" class="space-y-5">
                <div>
                    <label class="block mb-1 font-medium">Pseudo</label>
                    <input type="text" id="pseudo" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200 focus:outline-none">
                </div>
                <div>
                    <label class="block mb-1 font-medium">Mot de passe</label>
                    <input type="password" id="password" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200 focus:outline-none">
                </div>
                <button type="submit"
                    class="text-white w-full py-2 rounded-lg hover:bg-orange-600 transition duration-200 font-semibold"
                    style="background-color: #FF9800;">
                    Connexion
                </button>
            </form>
            <div id="result" class="mt-4 text-center"></div>
        </div>
    </div>

    <script>
        // MET À JOUR LA NAVBAR EN FONCTION DE L'ÉTAT DE CONNEXION
        async function fetchUserPseudo(token) {
            try {
                const res = await fetch('/api/user', {
                    headers: {
                        'Authorization': 'Bearer ' + token
                    }
                });
                if (res.ok) {
                    const data = await res.json();
                    return data.pseudo || 'Profil';
                }
            } catch (e) {}
            return 'Profil';
        }

        async function updateNavbarAuth() {
            const nav = document.getElementById('nav-auth');
            const token = localStorage.getItem('api_token');
            if (token) {
                const pseudo = await fetchUserPseudo(token);
                nav.innerHTML = `
            <a href="/" class="text-gray-600 hover:text-blue-500">Accueil</a>
            <a href="/bibliotheque" class="text-gray-600 hover:text-blue-500">Bibliothèque</a>
            <a href="/profil" class="text-gray-600 hover:text-blue-500">${pseudo}</a>
            <a href="#" class="text-gray-600 hover:text-blue-500" onclick="logoutApi();return false;">Déconnexion</a>
        `;
            } else {
                nav.innerHTML = `
            <a href="/" class="text-gray-600 hover:text-blue-500">Accueil</a>
            <a href="/login" class="text-gray-600 hover:text-blue-500">Connexion</a>
            <a href="/register" class="text-gray-600 hover:text-blue-500">Inscription</a>
        `;
            }
        }

        // DÉCONNECTE L'UTILISATEUR ET ACTUALISE LA NAVBAR
        function logoutApi() {
            const token = localStorage.getItem('api_token');
            if (token) {
                fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json'
                    }
                }).then(() => {
                    localStorage.removeItem('api_token');
                    updateNavbarAuth();
                    window.location.href = '/';
                });
            }
        }

        updateNavbarAuth();

        // GÈRE LA SOUMISSION DU FORMULAIRE DE CONNEXION
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const pseudo = document.getElementById('pseudo').value;
            const password = document.getElementById('password').value;

            const response = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    pseudo,
                    password
                })
            });
            const data = await response.json();
            if (response.ok) {
                localStorage.setItem('api_token', data.token);
                updateNavbarAuth();
                // REDIRIGE VERS LA PAGE D'ACCUEIL APRÈS CONNEXION
                window.location.href = "/";
            } else {
                let err = data.message || "Erreur de connexion";
                document.getElementById('result').innerHTML = '<span class="text-red-600">' + err + '</span>';
            }
        });
    </script>
</body>

</html>
