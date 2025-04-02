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
