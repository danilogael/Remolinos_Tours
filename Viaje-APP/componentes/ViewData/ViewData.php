<?php
session_start();

// 1. VERIFICACIÓN: Si no está logueado, muestra SweetAlert y sale. (Mantenemos tu lógica original)
if (!isset($_SESSION['user_id'])) {
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
  <script>
    Swal.fire({
      icon: 'warning',
      title: 'Acceso denegado',
      text: 'Debes iniciar sesión primero',
      timer: 2000,
      showConfirmButton: false
    }).then(() => {
      window.location.href = '/viaje/viaje/Viaje-APP/componentes/iniciarsesion/sign.php';
    });
  </script>";
  exit;
}

// 2. 🛡️ VERIFICACIÓN DE ROL Y REDIRECCIÓN DEL ADMINISTRADOR (¡NUEVO BLOQUE!)
// Si el rol es 'admin', lo enviamos al panel de administración.
if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
    
    // Ruta que confirmaste es la correcta en tu servidor XAMPP:
    $admin_url = '/viaje/viaje/Viaje-APP/componentes/admin/admin_panel.php'; 
    
    // Usamos la redirección por cabeceras (la más rápida y segura)
    header("Location: " . $admin_url);
    exit(); // Detiene la ejecución para asegurar la redirección
}

// Si llega aquí, significa que es un usuario logueado Y NO ES admin (es cliente).
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Mi cuenta — Remolinos Tours</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="ViewData.css">
  <link rel="stylesheet" href="/viaje/viaje/Viaje-APP/componentes/estilos/header.css">
  <link rel="stylesheet" href="/viaje/viaje/Viaje-APP/componentes/estilos/estilos_footer.css">
  <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/viaje/viaje/Viaje-APP/componentes/header/header.php"); ?>

<button id="darkModeBtn" class="dark-toggle" title="Modo oscuro"><i class="fa-solid fa-moon"></i></button>

<div class="container">
  <aside class="sidebar">
    <ul class="menu">
  <li><button class="menu-btn active" data-section="info"><i class="fa-solid fa-user"></i> Información personal</button></li>
  <li><button class="menu-btn" data-section="seguridad"><i class="fa-solid fa-lock"></i> Seguridad</button></li>
  <li><button class="menu-btn" data-section="preferencias"><i class="fa-solid fa-magnifying-glass"></i> Preferencias</button></li>
  <li><button class="menu-btn" data-section="notificaciones"><i class="fa-solid fa-bell"></i> Notificaciones</button></li>
</ul>
  </aside>

  <main class="content">
    <section id="info" class="card">
      <h2>Información personal</h2>
      <p class="subtitle">Revisa o modifica tu información</p>
      <div id="userData"><p>Cargando...</p></div>

      <div class="actions">
        <button class="btn-edit" onclick="editarCampo('correo')">Editar correo</button>
        <button class="btn-edit" onclick="editarCampo('telefono')">Editar teléfono</button>
      </div>
      <div style="margin-top:18px;">
        <button id="logoutBtn" class="btn-danger">Cerrar sesión</button>
      </div>
      
    </section>

    
    <section id="seguridad" class="card hidden">
      <h2>Seguridad de la cuenta</h2>
      <p class="subtitle">Opciones de acceso y seguridad.</p>
      <button class="btn-edit" onclick="cambiarPassword()">Cambiar contraseña</button>
    </section>


 
    <section id="preferencias" class="card hidden">
      <h2>Preferencias</h2>
      <p class="subtitle">Tus preferencias aparecerán aquí.</p>
      <div id="resList">No hay preferencias.</div>
    </section>
    
    <section id="notificaciones" class="card hidden">
      <h2>Notificaciones</h2>
      <p class="subtitle">Tus notificaciones aparecerán aquí.</p>
      <div id="resList">No hay notificaciones.</div>
    </section>
    
   
    </section>

  </main>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/viaje/viaje/Viaje-APP/componentes/footer/footer.php"); ?>
<script>
  const GET_USER_URL = '/viaje/viaje/LoginAPI/login/getUser.php';
const UPDATE_USER_URL = '/viaje/viaje/LoginAPI/login/update_user.php';
const CHANGE_PASS_URL = '/viaje/viaje/LoginAPI/login/changePassword.php';
const LOGOUT_URL = '/viaje/viaje/LoginAPI/login/logOut.php';

/* MODO OSCURO */
document.getElementById('darkModeBtn').addEventListener('click',()=>{
  document.body.classList.toggle('dark');
  localStorage.setItem('darkMode', document.body.classList.contains('dark'));
});
if(localStorage.getItem('darkMode')==='true'){document.body.classList.add('dark');}

/* Cargar datos */
async function loadUser(){
  const res = await fetch(GET_USER_URL);
  const data = await res.json();
  if(!data.success) return;
  const u = data.user;
  document.getElementById("userData").innerHTML = `
    <p><strong>Nombre:</strong> ${escapeHtml(u.nombre)}</p>
    <p><strong>Apellido paterno:</strong> ${escapeHtml(u.apellido_paterno)}</p>
    <p><strong>Apellido materno:</strong> ${escapeHtml(u.apellido_materno)}</p>
    <p><strong>Correo:</strong> ${escapeHtml(u.correo)}</p>
    <p><strong>Teléfono:</strong> ${escapeHtml(u.telefono)}</p>
    <p><strong>Rol:</strong> ${escapeHtml(u.rol)}</p>
  `;
}
loadUser();
function escapeHtml(t){return t??''}

async function editarCampo(campo){
  const label = {
    correo:'Correo',
    telefono:'Teléfono'
  };

  const { value: nuevo } = await Swal.fire({
    title: `Editar ${label[campo]}`,
    input: campo === 'telefono' ? 'number' : 'text',
    inputAttributes: campo === 'telefono' ? { min: "0", max: "9999999999" } : {},
    showCancelButton: true,
    inputValidator: (v) => {
      if (v.trim() === "") return "No puede estar vacío";

      if (campo === 'correo' && !/^\S+@\S+\.\S+$/.test(v))
        return "Correo inválido";

      if (campo === 'telefono' && !/^[0-9]{10}$/.test(v))
        return "Debe ser exactamente 10 dígitos";
    }
  });

  if (nuevo === undefined) return;

  const fd = new FormData();
  fd.append('campo', campo);
  fd.append('valor', nuevo);

  const res = await fetch(UPDATE_USER_URL, { method: 'POST', body: fd });
  const data = await res.json();

  Swal.fire(
    data.success ? "Listo" : "Error",
    data.message,
    data.success ? "success" : "error"
  );

  if (data.success) loadUser();
}


async function cambiarPassword(){
  const {value:form} = await Swal.fire({
    title:"Cambiar contraseña",
    html:`
      <div style="position:relative;">
        <input id="currentPass" type="password" class="swal2-input" placeholder="Contraseña actual">
        <i class="fa-solid fa-eye togglePass" data-target="currentPass"
           style="position:absolute; right:25px; top:14px; cursor:pointer;"></i>
      </div>

      <div style="position:relative;">
        <input id="newPass" type="password" class="swal2-input" placeholder="Nueva contraseña">
        <i class="fa-solid fa-eye togglePass" data-target="newPass"
           style="position:absolute; right:25px; top:14px; cursor:pointer;"></i>
      </div>
    `,
    didRender: () => {
      document.querySelectorAll('.togglePass').forEach(icon => {
        icon.addEventListener('click', () => {
          const input = document.getElementById(icon.dataset.target);

          if (input.type === "password") {
              input.type = "text";
              icon.classList.remove("fa-eye");
              icon.classList.add("fa-eye-slash");
          } else {
              input.type = "password";
              icon.classList.remove("fa-eye-slash");
              icon.classList.add("fa-eye");
          }
        });
      });
    },
    preConfirm:()=>{

      const c=document.getElementById("currentPass").value;
      const n=document.getElementById("newPass").value;

      if(!c||!n) return Swal.showValidationMessage("Completa todos los campos");
      if(n.length<6) return Swal.showValidationMessage("Mínimo 6 caracteres");

      return {current:c,newp:n};
    },
    showCancelButton:true
  });

  if(!form) return;

  const fd=new FormData();
  fd.append("current_password",form.current);
  fd.append("new_password",form.newp);

  const req=await fetch(CHANGE_PASS_URL,{method:"POST",body:fd});
  const res=await req.json();
  Swal.fire(res.success?"Hecho":"Error",res.message,res.success?"success":"error");
}

/* Logout */
document.getElementById("logoutBtn").addEventListener("click",async()=>{
  await fetch(LOGOUT_URL);
  window.location.href="/viaje/viaje/Viaje-APP";
});

// ---- SIDEBAR FIX ----
document.querySelectorAll('.menu-btn').forEach(btn => {
    btn.addEventListener('click', () => {

        // Quitar activo a todos
        document.querySelectorAll('.menu-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // Ocultar todas las secciones
        document.querySelectorAll('.card').forEach(sec => sec.classList.add('hidden'));

        // Mostrar la sección seleccionada
        const section = btn.getAttribute('data-section');
        document.getElementById(section).classList.remove('hidden');
    });
});

  </script>
<script src="/viaje/viaje/Viaje-APP/componentes/js/header.js"></script>

</body>
</html>
