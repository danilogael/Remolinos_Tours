(() => { // Bloque autoejecutable para evitar conflictos globales
  // Seleccionamos los elementos solo si existen
  const userIcon = document.querySelector('.user-icon');
  const dropdown = document.querySelector('.dropdown');

  if (userIcon && dropdown) {
    userIcon.addEventListener('click', (e) => {
      e.stopPropagation();
      dropdown.classList.toggle('active');
    });

    document.addEventListener('click', () => {
      dropdown.classList.remove('active');
    });

    window.addEventListener('resize', () => {
      dropdown.classList.remove('active');
    });
  }

  // SweetAlert 10% descuento
  document.addEventListener("DOMContentLoaded", () => {
    fetch("/viaje/viaje/LoginAPI/paquetes10_api.php") // tu API separada
      .then(res => res.json())
      .then(data => {
        if (data.show) {
          Swal.fire({
            icon: 'success',
            title: '¡Felicidades!',
            text: 'Recibirás un 10% de descuento en tu primer paquete',
            confirmButtonText: '¡Genial!'
          });
        }
      })
      .catch(err => console.error("Error al verificar descuento:", err));
  });
})();
