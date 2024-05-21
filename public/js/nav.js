document.addEventListener("DOMContentLoaded", function() {
    const navbar = document.querySelector('.navbar');
    const contentNavbar = document.getElementById('navbarSupportedContent');

    window.addEventListener('scroll', function() {
      if (window.scrollY > 50) { // Définissez le seuil de défilement où la transparence est appliquée
        navbar.classList.add('navbar-transparent');
        contentNavbar.classList.add('navbar-transparent-content')
      } else {
        navbar.classList.remove('navbar-transparent');
        contentNavbar.classList.remove('navbar-transparent-content')
      }
    });
    
      // Function to update the active link based on current URL
      function updateActiveLink() {
        const currentPath = window.location.pathname;

        // Remove the active class from all nav links
        document.querySelectorAll('.nav-link').forEach(link => {
          link.classList.remove('active');
        });

        // Add the active class to the current nav link
        if (currentPath.includes("/home")) {
          document.getElementById('nav-home').classList.add('active');
        } else if (currentPath.includes("/animaux")) {
          document.getElementById('nav-animaux').classList.add('active');
        } else if (currentPath.includes("/habitats")) {
          document.getElementById('nav-habitats').classList.add('active');
        } else if (currentPath.includes("/services")) {
          document.getElementById('nav-services').classList.add('active');
        } else if (currentPath.includes("/contact")) {
          document.getElementById('nav-contact').classList.add('active');
        } else if (currentPath.includes("/login")) {
          document.getElementById('nav-login').classList.add('active');
        }
      }

      // Initial call to set the active link
      updateActiveLink();

      // Add scroll listener to change navbar transparency
      window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
          navbar.classList.add('navbar-transparent');
        } else {
          navbar.classList.remove('navbar-transparent');
        }
      });

  });