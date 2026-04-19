document.addEventListener('DOMContentLoaded', () => {

    /* --- Funcionalidad de Despliegue de Detalles (Acordeón) --- */
    const detailButtons = document.querySelectorAll('.details-btn');

    detailButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            // Sube al contenedor principal (.content) y busca los detalles
            const content = this.closest('.content');
            const extraDetails = content.querySelector('.extra-details');

            // 1. Alternar la clase 'active' para desplegar/ocultar
            extraDetails.classList.toggle('active');

            // 2. Cambiar el texto del botón
            if (extraDetails.classList.contains('active')) {
                this.textContent = 'Ver menos detalles';
            } else {
                this.textContent = 'Ver más detalles';
            }
        });
    });

    /* --- Funcionalidad del Carrusel de Imágenes --- */

    let slideIndex = {}; // Almacena el índice actual para cada carrusel
    const carruselWrappers = document.querySelectorAll('.carrusel-slide-wrapper');

    // Inicializar el índice para cada carrusel
    carruselWrappers.forEach(wrapper => {
        const id = wrapper.dataset.carruselId;
        slideIndex[id] = 0;
    });

    // Función principal para mostrar la diapositiva
    function showSlide(id, n) {
        const wrapper = document.querySelector(`.carrusel-slide-wrapper[data-carrusel-id="${id}"]`);
        const slides = wrapper.querySelectorAll('img');
        
        if (slides.length === 0) return;

        // Bucle para movernos al principio o al final
        if (n >= slides.length) {
            slideIndex[id] = 0;
        } else if (n < 0) {
            slideIndex[id] = slides.length - 1;
        }

        // Mover el contenedor de diapositivas usando translateX
        const offset = -slideIndex[id] * 100;
        wrapper.style.transform = `translateX(${offset}%)`;
    }

    // Manejar el clic en los botones
    document.querySelectorAll('.carrusel-btn').forEach(button => {
        button.addEventListener('click', function() {
            const carruselId = this.dataset.carruselTarget;
            const direction = this.classList.contains('next') ? 1 : -1;
            
            slideIndex[carruselId] += direction;
            showSlide(carruselId, slideIndex[carruselId]);
        });
    });

    // Mostrar la primera diapositiva al cargar
    carruselWrappers.forEach(wrapper => {
        showSlide(wrapper.dataset.carruselId, 0);
    });
});