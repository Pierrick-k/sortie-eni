var selectLieu = document.getElementById('sortie_lieu');

if (selectLieu) {
    selectLieu.addEventListener('change', function () {
        var lieuInfos = document.getElementById('lieuInfos');
        lieuInfos.removeAttribute('hidden');
        const lieuId = this.value;
        if (lieuId) {
            fetch(`/api/lieu/${lieuId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('lieuRue').textContent = data.rue;
                    document.getElementById('lieuCp').textContent = data.ville.codePostal;
                    document.getElementById('lieuCoord').textContent = data.latitude + " / " + data.longitude;
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des données:', error);
                });
        }
    });
}

var selectVille = document.getElementById('sortie_ville');

if (selectVille) {
    selectVille.addEventListener('change', function() {
        const villeId = this.value;
        selectLieu.innerHTML = '<option value="">--Choisissez un lieu--</option>';

        if (villeId) {
            fetch(`/api/lieu/par-ville/${villeId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(lieu => {
                        const option = document.createElement('option');
                        option.value = lieu.id;
                        option.textContent = lieu.nom;
                        selectLieu.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des lieux :', error);
                });
        }
    });
}

