const registerForm = document.getElementById('registerForm');
const passwordInput = document.querySelector('input[name="contraseña"]');

// Checklist visual de contraseña
const checklist = {
  charLength: document.getElementById('charLength'),
  uppercase: document.getElementById('uppercase'),
  lowercase: document.getElementById('lowercase'),
  number: document.getElementById('number'),
  special: document.getElementById('special')
};

document.querySelectorAll('.toggle-password').forEach(eye => {
  eye.addEventListener('click', () => {
    const input = eye.parentElement.querySelector('input');
    if (input.type === 'password') {
      input.type = 'text';
      eye.classList.add('fa-eye-slash');
    } else {
      input.type = 'password';
      eye.classList.remove('fa-eye-slash');
    }
  });
});


// Validación en tiempo real de la contraseña
passwordInput.addEventListener('input', () => {
  const val = passwordInput.value;
  checklist.charLength.style.color = val.length >= 6 ? '#1abc9c' : '#e74c3c';
  checklist.uppercase.style.color = /[A-Z]/.test(val) ? '#1abc9c' : '#e74c3c';
  checklist.lowercase.style.color = /[a-z]/.test(val) ? '#1abc9c' : '#e74c3c';
  checklist.number.style.color = /\d/.test(val) ? '#1abc9c' : '#e74c3c';
  checklist.special.style.color = /[\W_]/.test(val) ? '#1abc9c' : '#e74c3c';
});

// Inicializar intl-tel-input
const input = document.querySelector("#telefono");
const iti = window.intlTelInput(input, {
  initialCountry: "auto",
  geoIpLookup: function(callback) {
    fetch("https://ipapi.co/json")
      .then(res => res.json())
      .then(data => callback(data.country_code))
      .catch(() => callback("MX"));
  },
  utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
});

// Enviar formulario
registerForm.addEventListener('submit', async e => {
  e.preventDefault();

  // Validación teléfono
  if (!iti.isValidNumber()) {
    Swal.fire({
      icon: 'warning',
      title: 'Teléfono inválido',
      text: 'El número no es válido para el país seleccionado.'
    });
    return;
  }

  // Validación contraseña
  const val = passwordInput.value;
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/;
  if (!regex.test(val)) {
    Swal.fire({
      icon: 'warning',
      title: 'Contraseña inválida',
      html: 'Debe cumplir todos los requisitos de la línea debajo del campo.'
    });
    return;
  }

  const formData = new FormData(registerForm);

  try {
    const res = await fetch('/viaje/viaje/LoginAPI/login/register.php', {
      method: 'POST',
      body: formData
    });
    const data = await res.json();

    if (data.success) {
      Swal.fire({
        icon: 'success',
        title: '¡Registro exitoso!',
        text: 'Ahora puedes iniciar sesión',
        timer: 1500,
        showConfirmButton: false
      }).then(() => {
        window.location.href = "/viaje/viaje/Viaje-APP/componentes/iniciarsesion/sign.php";
      });
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: data.message || 'Error al registrarse'
      });
    }
  } catch (err) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'No se pudo conectar con el servidor'
    });
  }
});

