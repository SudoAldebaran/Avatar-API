@extends('layouts.app')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <div class="container mx-auto p-4 sm:p-6 max-w-6xl bg-gray-50">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6">Gestion des clés API</h1>

        <!-- AFFICHAGE DES MESSAGES D'ERREUR OU DE SUCCÈS -->
        <div id="message" class="mb-6 p-4 rounded-lg hidden bg-blue-50 text-blue-800 font-medium"></div>

        <!-- FORMULAIRE POUR GÉNÉRER UNE NOUVELLE CLÉ API -->
        <form id="generate-form" class="mb-6 sm:mb-8 flex flex-col sm:flex-row gap-3 sm:gap-4 items-center">
            <input type="text" id="raison_sociale" placeholder="Raison sociale"
                class="border border-gray-200 rounded-lg px-4 py-2 w-full sm:max-w-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-sm font-medium text-gray-700"
                required>
            <button type="submit"
                class="text-white px-4 sm:px-6 py-2 rounded-lg hover:bg-orange-600 transition duration-200 w-full sm:w-auto font-semibold"
                style="background-color: #FF9800;">
                Générer une nouvelle clé
            </button>
        </form>

        <!-- BARRE DE RECHERCHE POUR FILTRER LES CLEFS PAR RAISON SOCIALE -->
        <div class="mb-6">
            <input type="text" id="search-bar" placeholder="Rechercher une raison sociale..."
                class="border border-gray-200 rounded-lg px-4 py-2 w-full sm:max-w-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-sm font-medium text-gray-700">
        </div>

        <!-- LISTE DES CLÉS API AVEC ACTIONS -->
        <div id="keys-list" class="bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-blue-100 text-gray-700">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-sm font-semibold">Clé</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-sm font-semibold">Raison sociale</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-sm font-semibold">Statut</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-sm font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="keys-table-body"></tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        /* STYLES POUR LES SWITCHS DE STATUT */
        .switch-peer:checked+.switch-track {
            background-color: #22c55e !important;
        }

        .switch-peer:checked+.switch-track .switch-dot {
            transform: translateX(20px);
        }

        .switch-peer:not(:checked)+.switch-track {
            background-color: #ef4444 !important;
        }

        .switch-peer:not(:checked)+.switch-track .switch-dot {
            transform: translateX(0);
        }
    </style>

    <script>
        let allKeys = [];

        // CHARGE TOUTES LES CLÉS API DEPUIS L'API
        async function loadApiKeys() {
            const token = localStorage.getItem('api_token');
            const res = await fetch('/api/admin/api-keys', {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            });
            const keysTableBody = document.getElementById('keys-table-body');
            if (!res.ok) {
                keysTableBody.innerHTML =
                    `<tr><td colspan="4" class="text-center text-red-600 py-4 font-medium">Non autorisé ou erreur d'authentification</td></tr>`;
                return;
            }
            allKeys = await res.json();
            displayFilteredKeys();
        }

        // FILTRE ET AFFICHE LES CLÉS EN FONCTION DE LA RECHERCHE
        function displayFilteredKeys() {
            const search = document.getElementById('search-bar').value.toLowerCase();
            const keysTableBody = document.getElementById('keys-table-body');
            const keys = allKeys.filter(k => (k.raison_sociale || '').toLowerCase().includes(search));
            if (keys.length === 0) {
                keysTableBody.innerHTML =
                    `<tr><td colspan="4" class="text-center text-gray-500 py-4 font-medium">Aucune clé API trouvée</td></tr>`;
                return;
            }
            let html = '';
            keys.forEach(k => {
                // MASQUE LA CLÉ PAR DEFAUT, AVEC BOUTON POUR AFFICHER/MASQUER
                const maskedKey = '*'.repeat(k.cle_api.length);
                html += `
            <tr class="border-t hover:bg-gray-100">
                <td class="px-4 sm:px-6 py-4 text-gray-800 flex items-center gap-2">
                    <span class="key-display bg-gray-100 p-2 rounded text-xs font-mono overflow-auto max-h-20 text-gray-700" data-key="${k.cle_api}" title="${k.cle_api}">
                        ${maskedKey}
                    </span>
                    <button onclick="toggleKeyVisibility(this)" class="toggle-visibility text-gray-500 hover:text-blue-600 p-1 rounded" title="Afficher/Masquer la clé">
                        <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                    <button onclick="copyToClipboard('${k.cle_api}')" class="text-gray-500 hover:text-blue-600 p-1 rounded" title="Copier la clé">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                </td>
                <td class="px-4 sm:px-6 py-4 text-gray-800 font-medium">${k.raison_sociale ?? '-'}</td>
                <td class="px-4 sm:px-6 py-4">
                    <!-- SWITCH POUR ACTIVER/DÉSACTIVER LA CLÉ -->
                    <label class="inline-flex relative items-center cursor-pointer">
                        <input type="checkbox"
                            class="sr-only switch-peer"
                            ${k.status === 'Actif' ? 'checked' : ''}
                            onchange="toggleStatus(${k.id_cle_api})">
                        <div class="switch-track w-11 h-6 rounded-full transition duration-200">
                            <div class="switch-dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-200" style="${k.status === 'Actif' ? 'transform: translateX(20px);' : ''}"></div>
                        </div>
                    </label>
                    <span class="ml-3 text-sm font-semibold ${k.status === 'Actif' ? 'text-green-700' : 'text-red-600'}">${k.status}</span>
                </td>
                <td class="px-4 sm:px-6 py-4 flex gap-2 items-center">
                    <!-- BOUTON POUR SUPPRIMER LA CLÉ -->
                    <button onclick="deleteKey(${k.id_cle_api})" class="text-red-500 hover:text-red-600 p-1 rounded" title="Supprimer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </td>
            </tr>
        `;
            });
            keysTableBody.innerHTML = html;
        }

        document.getElementById('search-bar').addEventListener('input', displayFilteredKeys);

        // AFFICHE OU MASQUE LA CLÉ API EN CLAIR
        function toggleKeyVisibility(button) {
            const keySpan = button.previousElementSibling;
            const eyeIcon = button.querySelector('.eye-icon');
            const key = keySpan.getAttribute('data-key');
            const isMasked = keySpan.innerText.includes('*');

            if (isMasked) {
                keySpan.innerText = key;
                eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
        `;
                button.title = 'Masquer la clé';
            } else {
                keySpan.innerText = '*'.repeat(key.length);
                eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        `;
                button.title = 'Afficher la clé';
            }
        }

        // ACTIVE/DÉSACTIVE UNE CLÉ API
        async function toggleStatus(id) {
            const token = localStorage.getItem('api_token');
            const res = await fetch(`/api/admin/api-keys/${id}/toggle`, {
                method: 'PATCH',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                }
            });
            const messageDiv = document.getElementById('message');
            if (res.ok) {
                messageDiv.innerText = 'Statut mis à jour !';
                messageDiv.className = 'mb-6 p-4 rounded-lg bg-green-50 text-green-800 font-medium';
                messageDiv.classList.remove('hidden');
                await loadApiKeys();
                setTimeout(() => messageDiv.classList.add('hidden'), 2000);
            } else {
                messageDiv.innerText = 'Erreur lors du changement de statut.';
                messageDiv.className = 'mb-6 p-4 rounded-lg bg-red-50 text-red-800 font-medium';
                messageDiv.classList.remove('hidden');
                setTimeout(() => messageDiv.classList.add('hidden'), 2000);
            }
        }

        // SUPPRIME UNE CLÉ API APRÈS CONFIRMATION
        async function deleteKey(id) {
            if (!confirm('Voulez-vous vraiment supprimer cette clé ?')) return;
            const token = localStorage.getItem('api_token');
            const res = await fetch(`/api/admin/api-keys/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            });
            const messageDiv = document.getElementById('message');
            if (res.ok) {
                messageDiv.innerText = 'Clé supprimée avec succès !';
                messageDiv.className = 'mb-6 p-4 rounded-lg bg-green-50 text-green-800 font-medium';
                messageDiv.classList.remove('hidden');
                await loadApiKeys();
                setTimeout(() => messageDiv.classList.add('hidden'), 2000);
            } else {
                messageDiv.innerText = 'Erreur lors de la suppression.';
                messageDiv.className = 'mb-6 p-4 rounded-lg bg-red-50 text-red-800 font-medium';
                messageDiv.classList.remove('hidden');
                setTimeout(() => messageDiv.classList.add('hidden'), 2000);
            }
        }

        // COPIE LA CLÉ API DANS LE PRESSE-PAPIERS
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                const messageDiv = document.getElementById('message');
                messageDiv.innerText = 'Clé copiée dans le presse-papiers !';
                messageDiv.className = 'mb-6 p-4 rounded-lg bg-blue-50 text-blue-800 font-medium';
                messageDiv.classList.remove('hidden');
                setTimeout(() => messageDiv.classList.add('hidden'), 2000);
            }).catch(() => {
                const messageDiv = document.getElementById('message');
                messageDiv.innerText = 'Erreur lors de la copie.';
                messageDiv.className = 'mb-6 p-4 rounded-lg bg-red-50 text-red-800 font-medium';
                messageDiv.classList.remove('hidden');
                setTimeout(() => messageDiv.classList.add('hidden'), 2000);
            });
        }

        // GERE LE FORMULAIRE DE GÉNÉRATION DE CLÉ
        document.getElementById('generate-form').onsubmit = async (e) => {
            e.preventDefault();
            const raison = document.getElementById('raison_sociale').value.trim();
            if (!raison) return;

            // VÉRIFIE SI LA RAISON SOCIALE EXISTE DÉJÀ
            if (allKeys.some(k => (k.raison_sociale || '').toLowerCase() === raison.toLowerCase())) {
                showMessage("La raison sociale existe déjà !", 'red');
                return;
            }
            const token = localStorage.getItem('api_token');
            const res = await fetch('/api/admin/api-keys/generate', {
                method: "POST",
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    raison_sociale: raison
                })
            });
            if (res.ok) {
                showMessage("Clé générée avec succès !", 'green');
                document.getElementById('generate-form').reset();
                await loadApiKeys();
            } else {
                let msg = "Erreur lors de la génération.";
                if (res.status === 422) msg = "La raison sociale existe déjà !";
                showMessage(msg, 'red');
            }
        }

        // AFFICHE UN MESSAGE TEMPORAIRE EN FONCTION DU CONTEXTE
        function showMessage(msg, color = 'green') {
            const messageDiv = document.getElementById('message');
            messageDiv.innerText = msg;
            messageDiv.className = "mb-6 p-4 rounded-lg font-semibold bg-" + (color === 'green' ?
                'green-50 text-green-800' : (color === 'red' ? 'red-50 text-red-800' : 'blue-50 text-blue-800'));
            messageDiv.classList.remove('hidden');
            setTimeout(() => messageDiv.classList.add('hidden'), 2000);
        }

        window.onload = loadApiKeys;
    </script>
@endsection
