@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">Ma Bibliothèque d'Avatars</h1>

        <div id="avatars-container" class="mb-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <!-- LES AVATARS SONT INSÉRÉS EN JAVASCRIPT -->
        </div>
    </div>

    <!-- BOUTONS VISUALISER, TELECHARGER, SUPPRIMER -->
    <div id="action-bar" class="fixed bottom-4 left-1/2 transform -translate-x-1/2 z-40 flex space-x-4">
        <button onclick="handleView()"
            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-8 rounded-full border-2 border-black flex items-center gap-2">
            Voir <img src="{{ asset('images/visualiser.svg') }}" alt="Voir" class="w-5 h-5">
        </button>
        <button onclick="handleDownload()"
            class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-full border-2 border-black flex items-center gap-2">
            Télécharger <img src="{{ asset('images/telecharger.svg') }}" alt="Télécharger" class="w-8 h-8">
        </button>
        <button onclick="handleDelete()"
            class="bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 px-4 rounded-full border-2 border-black flex items-center gap-2">
            Supprimer <img src="{{ asset('images/supprimer.svg') }}" alt="Supprimer" class="w-7 h-7">
        </button>
    </div>

    <!-- AFFICHER L'AVATAR -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
            <div class="text-center">
                <div id="modal-avatar"
                    class="pt-6 w-64 h-64 mx-auto mb-4 overflow-hidden rounded-lg bg-gray-100 flex items-center justify-center">
                    <div class="w-full h-full flex items-center justify-center"></div>
                </div>
                <p id="modal-name" class="font-bold"></p>
                <button onclick="closeModal()" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded">Fermer</button>
            </div>
        </div>
    </div>

    <script>
        let avatars = [];
        let selectedAvatarId = null;

        // CHARGE LES AVATARS DE L'UTILISATEUR VIA L'API
        async function loadAvatars() {
            const token = localStorage.getItem('api_token');
            if (!token) {
                alert('Connectez-vous d\'abord');
                window.location.href = '/login';
                return;
            }
            try {
                const response = await fetch('http://localhost:8000/api/bibliotheque', {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                avatars = await response.json();
                displayAvatars();
            } catch (error) {
                alert('Erreur de chargement');
            }
        }

        // AFFICHE LES AVATARS DANS LA GRILLE
        function displayAvatars() {
            const container = document.getElementById('avatars-container');
            if (avatars.length === 0) {
                container.innerHTML = '<p class="text-center text-gray-500 col-span-full">Aucun avatar</p>';
                return;
            }
            container.innerHTML = avatars.map(avatar => `
            <div onclick="selectAvatar('${avatar.avatar_id}', this)"
                 class="bg-white rounded-lg shadow-md p-4 text-center cursor-pointer transition ring-offset-2">
                <div class="w-64 h-64 mx-auto bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                    <div class="w-full h-full flex items-center justify-center pt-6">
                        ${avatar.avatar_svg}
                    </div>
                </div>
                <p class="font-semibold mb-3">${avatar.avatar_name || 'Sans nom'}</p>
            </div>
        `).join('');
        }

        // SÉLECTIONNE UN AVATAR + FOCUS
        function selectAvatar(id, element) {
            selectedAvatarId = id;
            const all = document.querySelectorAll('#avatars-container > div');
            all.forEach(div => div.classList.remove('ring-2', 'ring-blue-500'));
            element.classList.add('ring-2', 'ring-blue-500');
        }

        // UTILISATION DE LA FONCTION viewAvatar AVEC GESTION D'ERREUR
        function handleView() {
            if (!selectedAvatarId) return alert('Sélectionnez un avatar');
            viewAvatar(selectedAvatarId);
        }

        // // UTILISATION DE LA FONCTION downloadAvatar AVEC GESTION D'ERREUR
        function handleDownload() {
            if (!selectedAvatarId) return alert('Sélectionnez un avatar');
            downloadAvatar(selectedAvatarId);
        }

        // // UTILISATION DE LA FONCTION deleteAvatar AVEC GESTION D'ERREUR
        function handleDelete() {
            if (!selectedAvatarId) return alert('Sélectionnez un avatar');
            deleteAvatar(selectedAvatarId);
        }

        // AFFICHE L'AVATAR DANS LE MODAL
        function viewAvatar(id) {
            const avatar = avatars.find(a => a.avatar_id === id);
            const modalContainer = document.getElementById('modal-avatar');
            modalContainer.querySelector('div').innerHTML = avatar.avatar_svg;
            document.getElementById('modal-name').textContent = avatar.avatar_name || 'Sans nom';
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('modal').classList.add('flex');
        }

        // FERME LE MODAL
        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
            document.getElementById('modal').classList.remove('flex');
        }

        // TÉLÉCHARGE L'AVATAR SÉLECTIONNÉ EN SVG
        function downloadAvatar(id) {
            const avatar = avatars.find(a => a.avatar_id === id);
            const blob = new Blob([avatar.avatar_svg], {
                type: 'image/svg+xml'
            });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `${avatar.avatar_name || 'avatar'}.svg`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // SUPPRIME L'AVATAR SÉLECTIONNÉ VIA L'API
        async function deleteAvatar(id) {
            const avatar = avatars.find(a => a.avatar_id === id);
            if (!confirm(`Supprimer "${avatar.avatar_name || 'cet avatar'}" ?`)) return;

            const token = localStorage.getItem('api_token');
            try {
                const response = await fetch(`http://localhost:8000/api/avatars/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Erreur inconnue');
                }

                alert('Avatar supprimé avec succès');
                loadAvatars();
                selectedAvatarId = null;
            } catch (error) {
                alert('Erreur de suppression : ' + error.message);
            }
        }

        // CHARGE LES AVATARS AU CHARGEMENT DE LA PAGE
        document.addEventListener('DOMContentLoaded', loadAvatars);
    </script>
@endsection
