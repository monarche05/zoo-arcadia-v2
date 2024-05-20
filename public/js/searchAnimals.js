document.addEventListener("DOMContentLoaded", function() {
  const selectHabitats = document.getElementById("SelectHabitats");

  // Ajouter un écouteur d'événements pour les changements de sélection d'habitat
  selectHabitats.addEventListener("change", function() {
    const selectedHabitatId = selectHabitats.options[selectHabitats.selectedIndex].getAttribute("data-habitat-id");
    // Envoyer une requête pour récupérer les animaux de cet habitat
    fetchAnimals(selectedHabitatId);
  });

  // Fonction pour envoyer une requête AJAX pour récupérer les animaux en fonction de l'habitat et de l'espèce sélectionnés
  function fetchAnimals(habitatId) {
    // Construir l'URL de la requête en fonction des paramètres d'habitat et d'espèce
    let url = '/animaux/filtrer';
    if (habitatId) {
      url += '?habitat=' + habitatId;
    }
    // Envoyer une requête GET à l'URL construite
    fetch(url)
      .then(response => response.json())
      .then(data => {
        // Mettre à jour l'affichage avec les animaux récupérés
        const animalSection = document.querySelector('.animal-section');
        // Effacer le contenu précédent de la section des animaux
        animalSection.innerHTML = '';

        if (Array.isArray(data.animals)) {
          // Ajouter les cartes d'animaux à la section des animaux
          data.animals.forEach(animal => {
            const animalCard = document.createElement('div');
            animalCard.classList.add('card', 'secondary-cust', 'm-4', 'text-white', 'scale');
            animalCard.style.width = '18rem';
            animalCard.setAttribute('data-bs-toggle', 'modal');
            animalCard.setAttribute('data-bs-target', '#' + animal.name + 'Modal');

            // Trouver le rapport qui correspond à l'animal actuel
            const rapAnimal = data.rapAnimal.find(rap => rap.animalId === animal.id);

            if (rapAnimal) {
              // Vérifier si l'animal a un habitat défini
              const habitat = animal.habitatName ? animal.habitatName : '';
              // Construction de la carte de l'animal avec ses détails et le rapport
              animalCard.innerHTML = `
                <img src="img/${animal.img[0]}" class="card-img-top" alt="${animal.name}">
                <div class="card-body">
                  <h5 class="card-title link-cust">${animal.name}</h5>
                </div>
                <div class="card-body bg-ligth-green text-black rounded-bottom">
                  <p class="m-0" style="font-weight: bold;">État</p>
                  <p class="m-0">${rapAnimal.detail}</p>
                  <p class="m-0" style="font-weight: bold;">Habitat</p>
                  <p class="mb-1">${habitat}</p>
                  <p class="m-0" style="font-weight: bold;">Espèce</p>
                  <p class="m-0">${animal.species}</p>
                </div>
              `;

              // Ajouter la carte d'animal à la section des animaux
              animalSection.appendChild(animalCard);
            }
          });

          // Ajouter les modal d'animaux à la section des animaux
          data.animals.forEach(animal => {
            // Création de la chaîne de caractères HTML de la modale
            const modal = createAnimalModal(animal, data);

            // Ajouter la modale d'animal à la section des animaux
            animalSection.innerHTML += modal;
          });
        } else {
          console.error('Les données reçues ne sont pas au format attendu.');
        }
      })
      .catch(error => console.error('Erreur lors de la récupération des animaux:', error));
  }

  // Fonction pour créer la chaîne de caractères HTML de la modale d'un animal
  function createAnimalModal(animal ,data) {
    // Définir rapAnimal avant la boucle for
    const rapAnimal = data.rapAnimal.find(rap => rap.animalId === animal.id);

    // Création de la chaîne de caractères HTML de la modale
    let modal = `
      <div class="modal fade p-0" id="${animal.name}Modal" tabindex="-1" aria-labelledby="${animal.name}ModalLabel" aria-hidden="true" data-bs-animal="${animal.name}">
        <div class="modal-dialog modal-size my-3 mx-auto" style="max-width:none;">
          <div class="modal-content secondary-cust">
            <div class="modal-header d-flex justify-content-center">
              <h3 class="modal-title link-cust" id="${animal.name}ModalLabel">${animal.name}</h3>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-ligth-green text-black">
              <div class="d-flex flex-column justify-content-start">
                <div>
                  <div class="container d-flex flex-column align-items-center">
                    <div id="carousel-${animal.id}" class="carousel slide col-12 " data-bs-ride="carousel">
                      <div class="carousel-indicators">
    `;

    // Ajout des indicateurs du carousel
    let imageCounter = 0;
    for (const img of animal.img) {
      imageCounter++;
    }
    if (imageCounter > 1) {
      for (let i = 0; i < imageCounter; i++) {
        modal += `
          <button type="button"
                  data-bs-target="#carousel-${animal.id}"
                  data-bs-slide-to="${i}"
                  ${i === 0 ? 'class="active"' : ''}
                  aria-current="true"
                  aria-label="Slide ${i + 1}">
          </button>
        `;
      }
    }

    // Ajout des images du carousel
    modal += `
          </div>
          <div class="carousel-inner">
      `;
    let first = true;
    for (const img of animal.img) {
      modal += `
          <div class="carousel-item ${first ? 'active' : ''}">
              <img src="/img/${img}" class="d-block img-size3 col-12 rounded p-0" alt="${animal.name}">
          </div>
      `;
      first = false;
    }

    // Ajout des boutons de navigation du carousel
    if (imageCounter > 1) {
      modal += `
        <button class="carousel-control-prev" type="button" data-bs-target="#carousel-${animal.id}" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carousel-${animal.id}" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
      `;
    }

    // Ajout des détails de l'animal
    modal += `
              </div>
            </div>
          </div>
        </div>
        <div class="my-3">
          <div class="d-flex justify-content-between">
            <div class="m-3">
              <p class="fs-4 fw-bold m-0">Éspèce</p>
              <p class="fs-5 m-0">${animal.species}</p>
            </div>
            <div class="m-3 text-end">
              <p class="fs-4 fw-bold m-0">Habitat</p>
              <p class="fs-5 m-0">${animal.habitatName}</p>
            </div>
          </div>
          <div class="m-3">
            <p class="fs-4 fw-bold m-0">Description</p>
            <p class="fs-5 m-0">${animal.description}</p>
          </div>
    `;

    // Ajout du rapport de l'animal
    if (rapAnimal) {
      modal += `
        <div class="m-3">
          <p class="fs-4 fw-bold m-0">État</p>
          <p class="fs-5 m-0">${rapAnimal.detail}</p>
        </div>
      `;
    }

    // Fin de la chaîne de caractères HTML de la modale
    modal += `
          </div>
        </div>
        <div class="modal-footer justify-content-center ">
          <button type="button" class="cta m-4" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>
  `;
    return modal;
  }
});
