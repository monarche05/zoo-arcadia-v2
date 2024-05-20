
document.addEventListener("DOMContentLoaded", function() {
    const homeBtnModal = document.getElementById("homeBtnModal");
    const homeTextModal = document.getElementById("homeTextModal");
    const homePseudoModal = document.getElementById("homePseudoModal");
    const homeNoteModal = document.getElementById("homeNoteModal");
    
    if (homeBtnModal && homeTextModal && homePseudoModal) {
        homeBtnModal.addEventListener("click", function(event) {
            event.preventDefault();
            
            //Sécurisation des données utilisateur
            const pseudo = escapeHtml(homePseudoModal.value);
            const avis = escapeHtml(homeTextModal.value);
            const note = homeNoteModal.value;

            if (note === "null" ) {
                // Afficher un message d'erreur
                const errorElement = document.getElementById('error-message');
                errorElement.innerText = "Veuillez remplir tous les champs du formulaire et choisir une note.";
                errorElement.style.display = "block";
                return;
            } else {
                
                // stocker les données du formulaire
                const Data = {
                    pseudo: pseudo,
                    avis: avis,
                    note: note,
                };
                // Envoie des donnée
                fetch('/home/send', {
                        method: 'POST',
                        headers: {"Content-Type": "application/json"},
                        body: JSON.stringify(Data)
                    })
                    .then(response => {
                    if (!response.ok) {
                            window.location.href = '/page500'
                        }else {
                            return response.json();
                        }
                    })
                    .then(data => {
                        if (data.success) {
                            window.location.href = '/home/confirmation';
                        } else {
                            window.location.href = '/page500';
                        }
                    })
            };
        })
    };
});


// function escapeHtml(unsafe) {
//     return unsafe
//         .replace(/&/g, "&amp;")
//         .replace(/</g, "&lt;")
//         .replace(/>/g, "&gt;")
//         .replace(/"/g, "&quot;")
// }