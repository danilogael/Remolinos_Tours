
/* destinos.js — Remolinos Tours
   Búsqueda en vivo con debounce al escribir
   ----------------------------------------- */
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('.dest-search-form input[name="busqueda"]');
    const form        = document.querySelector('.dest-search-form');
    if (!searchInput || !form) return;

    let timer;

    searchInput.addEventListener('input', () => {
        clearTimeout(timer);
        // Espera 450ms después de que el usuario deja de escribir para enviar
        timer = setTimeout(() => {
            form.submit();
        }, 450);
    });

    // Animación de entrada para las cards
    const cards = document.querySelectorAll('.dest-card');
    cards.forEach((card, i) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 60 + i * 60);
    });
});
