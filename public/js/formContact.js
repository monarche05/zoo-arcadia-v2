document.addEventListener("DOMContentLoaded", function() {
    const contactBtn = document.getElementById("contactBtn");
    const contactTitre = document.getElementById("contactTitre");
    const contactMail = document.getElementById("contactMail");
    const contactText = document.getElementById("contactText");
    
    if (contactTitre && contactMail && contactText) {
        contactBtn.addEventListener("click", function(event) {
            event.preventDefault();
            
            // Sécurisation des données utilisateur
            const titre = escapeHtml(contactTitre.value);
            const mail = escapeHtml(contactMail.value);
            const msg = escapeHtml(contactText.value);

            // stocker les données du formulaire
            const Data = {
                titre: titre,
                mail: mail,
                msg: msg,
            };
            if (!mail || !mail.match(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/) || !msg ) {
                // Afficher un message d'erreur
                if(!mail.match(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/)){
                    const errorElement = document.getElementById('error-message');
                    errorElement.innerText = "Veuillez entrer une adresse e-mail valide";
                    errorElement.style.display = "block";
                    setTimeout(function() {
                        errorElement.style.display = "none"; 
                    }, 2000);
                    return;
                }
                const errorElement = document.getElementById('error-message');
                errorElement.innerText = "Veuillez remplir tous les champs du formulaire.";
                errorElement.style.display = "block";
                setTimeout(function() {
                    errorElement.style.display = "none"; 
                }, 2000);
                return;
            }
            // Envoie des donnée
            fetch('/contact/send', {
                    method: 'POST',
                    headers: {"Content-Type": "application/json"},
                    body: JSON.stringify(Data)
                })
                .then(response => {
                if (!response.ok) {
                        window.location.href = '/page500';
                    }else {
                        return response.json();
                    }
                })
                .then(data => {
                    if (data.success) {
                        window.location.href = '/contact/confirmation';
                    } else {
                        window.location.href = '/page500';
                    }
                })
            
        })
    };
});
