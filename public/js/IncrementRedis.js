document.addEventListener("DOMContentLoaded", function() {
const modals = document.querySelectorAll('.modal');

modals.forEach((modal) => {
  modal.addEventListener('shown.bs.modal', (event) => {
    // const animalName = event.currentTarget.getAttribute('data-animal');
    const animalName = event.currentTarget.dataset.bsAnimal;
   
    
    if (event.target.matches('.modal')) {
      // Récupérer le nom de l'animal à partir de l'attribut data-animal de la modale
      const animalName = event.target.dataset.animal;
 console.log(animalName);
      // Envoyer une requête Fetch pour incrémenter le compteur de vues de l'animal
      fetch('/animaux/increment', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ animalName })
      })
      .then(response => response.json())
      .then(data => {
        console.log(data);
      })
      .catch(error => {
        console.error(error);
      });
    }
  });
});
})
const observer = new MutationObserver((mutations) => {
  mutations.forEach((mutation) => {
    if (mutation.addedNodes) {
      Array.from(mutation.addedNodes).forEach((node) => {
        if (node.matches && node.matches('.modal')) {
          node.addEventListener('shown.bs.modal', (event) => {
            const animalName = event.currentTarget.dataset.bsAnimal;
            console.log(animalName);

            fetch('/animaux/increment', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({ animalName })
            })
            .then(response => response.json())
            .then(data => {
              console.log(data);
            })
            .catch(error => {
              console.error(error);
            });
          });
        }
      });
    }
  });
});

observer.observe(document.body, { childList: true, subtree: true });