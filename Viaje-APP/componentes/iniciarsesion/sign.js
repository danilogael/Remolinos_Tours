const loginForm = document.getElementById('loginForm');
const submitBtn = document.querySelector(".next-btn");

const MAX_ATTEMPTS = 3;
const BLOCK_TIME = 60 * 1000; 

// --- RUTAS (Configura aquí los nombres de tus archivos) ---
const URL_ADMIN      = "/viaje/viaje/Viaje-APP/componentes/admin/admin_panel.php";
const URL_PERFIL     = "/viaje/viaje/Viaje-APP/componentes/ViewData/ViewData.php";
const URL_PAQUETES   = "/viaje/viaje/Viaje-APP/componentes/paquetes/paquete.php";
const URL_OFERTAS    = "/viaje/viaje/Viaje-APP/componentes/ofertas/ofertas.php"; 
const URL_COTIZACION = "/viaje/viaje/Viaje-APP/componentes/planea/planea.php"; 

// Función de bloqueo
function checkBlocked() {
    const blockedUntil = localStorage.getItem('blockedUntil');
    if (blockedUntil) {
        const now = Date.now();
        if (now < blockedUntil) {
            const remaining = Math.ceil((blockedUntil - now) / 1000);
            submitBtn.disabled = true;
            Swal.fire({ icon: 'warning', title: 'Cuenta bloqueada', text: `Espera ${remaining}s`, showConfirmButton: false, timer: 2000 });
            setTimeout(() => submitBtn.disabled = false, blockedUntil - now);
            return true;
        } else {
            localStorage.removeItem('attempts'); localStorage.removeItem('blockedUntil');
        }
    }
    return false;
}
checkBlocked();

// --- LOGICA DE LOGIN ---
loginForm.addEventListener('submit', async e => {
    e.preventDefault();
    if (checkBlocked()) return;

    const formData = new FormData(loginForm);

    try {
        const res = await fetch("/viaje/viaje/LoginAPI/login/login.php", { method: "POST", body: formData });
        const data = await res.json(); 

        if (data.success) {
            localStorage.removeItem('attempts');

            // --- REDIRECCIÓN INTELIGENTE ---
            const urlParams = new URLSearchParams(window.location.search);
            const origen = urlParams.get('origen');

            let redirectUrl = URL_PERFIL; // Destino por defecto

            if (origen === 'reserva') {
                // Caso 1: Venía de Paquetes
                redirectUrl = `${URL_PAQUETES}?reserva_pendiente=si`;
            
            } else if (origen === 'ofertas') {
                // Caso 2: Venía de Ofertas
                redirectUrl = `${URL_OFERTAS}?reserva_pendiente=si`;

            } else if (origen === 'cotizacion') {
                // Caso 3: Venía de Cotización (AGREGADO)
                redirectUrl = `${URL_COTIZACION}?reserva_pendiente=si`;

            } else if (data.user.rol === 'admin') { 
                // Caso 4: Es Admin y entró normal
                redirectUrl = URL_ADMIN; 
            }
            // -------------------------------

            Swal.fire({
                icon: 'success',
                title: '¡Bienvenido!',
                text: `Hola ${data.user.nombre}`,
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.href = redirectUrl;
            });

        } else {
            // Manejo de errores
            let attempts = parseInt(localStorage.getItem('attempts') || "0") + 1;
            localStorage.setItem('attempts', attempts);
            if (attempts >= MAX_ATTEMPTS) {
                const blockUntil = Date.now() + BLOCK_TIME;
                localStorage.setItem('blockedUntil', blockUntil);
                submitBtn.disabled = true;
                Swal.fire({ icon: 'error', title: 'Cuenta bloqueada', text: 'Demasiados intentos.' });
                setTimeout(() => { submitBtn.disabled = false; localStorage.removeItem('attempts'); localStorage.removeItem('blockedUntil'); }, BLOCK_TIME);
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Credenciales incorrectas' });
            }
        }
    } catch (err) {
        console.error(err);
        Swal.fire({ icon: 'error', title: 'Error', text: 'Fallo de conexión' });
    }
});

// Mostrar contraseña
const togglePassword = document.querySelector(".toggle-password");
const passwordInput = document.getElementById("password");
if(togglePassword && passwordInput) {
    togglePassword.addEventListener("click", () => {
        const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
        passwordInput.setAttribute("type", type);
        togglePassword.classList.toggle("fa-eye");
        togglePassword.classList.toggle("fa-eye-slash");
    });
}