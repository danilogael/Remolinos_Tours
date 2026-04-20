window.addEventListener('scroll', function() {
  const header = document.getElementById('site-header');
  // Si bajamos más de 150px, añade la clase 'scrolled', si no, la quita
  if (window.scrollY > 150) {
    header.classList.add('scrolled');
  } else {
    header.classList.remove('scrolled');
  }
});

document.addEventListener('DOMContentLoaded', () => {
    // 1. Datos de los testimonios (puedes añadir más aquí)
    const testimonios = [
        {
            nombre: "María González",
            ciudad: "Ciudad de México",
            texto: "REMOLINOS TOURS superó todas mis expectativas. El hotel era espectacular, los guías increíblemente conocedores y cada detalle estaba perfectamente organizado. Fue el viaje de mi vida.",
            destino: "Caribe Mexicano Mágico",
            foto: "assets/imagenes/persona1.jpg"
        },
        {
            nombre: "Juan Pérez",
            ciudad: "Monterrey",
            texto: "Increíble atención desde el primer momento. El paquete a las Barrancas del Cobre fue una experiencia que jamás olvidaré. ¡Gracias Remolinos Tours!",
            destino: "Barrancas del Cobre",
            foto: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=800"
        },
        {
            nombre: "Sofía Aguilar",
            ciudad: "Aguascalientes",
            texto: "La mejor agencia de la región. El viaje a Europa estuvo súper bien planeado, hoteles céntricos y traslados cómodos. Volveré a viajar con ellos.",
            destino: "EuroTour Clásico",
            foto: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=800"
        }
    ];

    let index = 0;

    // 2. Elementos del DOM
    const imgPrincipal = document.querySelector('.reviews-img-wrap > img');
    const badgeDestino = document.querySelector('.rev-badge strong');
    const textoCita = document.querySelector('blockquote');
    const autorFoto = document.querySelector('.rev-autor img');
    const autorNombre = document.querySelector('.rev-autor strong');
    const autorCiudad = document.querySelector('.rev-autor span');
    const dots = document.querySelectorAll('.dot');
    const btnPrev = document.querySelector('.rev-btn:first-child');
    const btnNext = document.querySelector('.rev-btn.activo');

    // 3. Función para actualizar la interfaz
    function renderTestimonio(i) {
        const t = testimonios[i];
        
        // Transición suave: quitamos y ponemos clase de opacidad
        document.querySelector('.reviews-inner').style.opacity = 0;
        
        setTimeout(() => {
            imgPrincipal.src = t.foto;
            badgeDestino.textContent = t.destino;
            textoCita.innerHTML = `<i class="fas fa-quote-left"></i> ${t.texto}`;
            autorFoto.src = t.foto;
            autorNombre.textContent = `— ${t.nombre}`;
            autorCiudad.textContent = t.ciudad;

            // Actualizar Dots
            dots.forEach(dot => dot.classList.remove('activo'));
            dots[i].classList.add('activo');
            
            document.querySelector('.reviews-inner').style.opacity = 1;
        }, 300);
    }

    // 4. Eventos de las flechas
    btnNext.addEventListener('click', () => {
        index = (index + 1) % testimonios.length;
        renderTestimonio(index);
    });

    btnPrev.addEventListener('click', () => {
        index = (index - 1 + testimonios.length) % testimonios.length;
        renderTestimonio(index);
    });
});