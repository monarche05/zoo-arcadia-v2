document.addEventListener('DOMContentLoaded', () => {
    const slideElements = document.querySelectorAll('.slide-container');
    
    const observer = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.querySelector('.slide-element').classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.5
    });
    
    slideElements.forEach(slideElement => {
      observer.observe(slideElement);
    });
   
  });