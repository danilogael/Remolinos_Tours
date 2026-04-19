<?php
session_start();
require_once __DIR__ . "/../../../LoginAPI/db.php"; 

$usuario_logueado = isset($_SESSION['user_id']);
$nombre_usuario = $_SESSION['nombre'] ?? 'Viajero';
?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reseñas de Clientes | Remolinos Tours</title>

<link rel="stylesheet" href="/viaje/viaje/Viaje-APP/componentes/estilos/header.css">
<link rel="stylesheet" href="/viaje/viaje/Viaje-APP/componentes/estilos/estilos_footer.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
:root {
  --accent-color: #007bff; 
  --star-color: #ffc107; 
  --text-color: #343a40; 
  --light-bg: #f8f9fa; 
  --card-bg: #ffffff;
  --shadow-subtle: 0 4px 6px rgba(0, 0, 0, 0.05); 
  --grey-text: #6c757d;
  --dark-blue: #0056b3;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: var(--light-bg);
  line-height: 1.6;
  color: var(--text-color);
  margin: 0;
  padding: 0;
}

.reviews-section {
  padding: 3rem 5%; 
  max-width: 1300px;
  margin: 0 auto;
  min-height: 70vh;
}

.reviews-section h1 {
  text-align: center;
  color: var(--accent-color);
  font-size: 2.8rem;
  font-weight: 800;
  margin-bottom: 3rem;
  position: relative;
}

.box-container {
  display: flex; 
  overflow-x: auto; 
  scroll-snap-type: x mandatory;
  padding: 1.5rem 0; 
  gap: 2.5rem; 
  -webkit-overflow-scrolling: touch;
  margin: 0 auto; 
  max-width: 100%;
}
.box-container::-webkit-scrollbar { height: 6px; }
.box-container::-webkit-scrollbar-thumb { background: var(--accent-color); border-radius: 10px; }

.box {
  min-width: 320px; 
  width: 350px; 
  flex: 0 0 auto;
  background: var(--card-bg);
  padding: 2.5rem;
  border-radius: 1rem;
  box-shadow: var(--shadow-subtle);
  border: 1px solid #e9ecef;
  transition: transform 0.2s, box-shadow 0.3s;
  scroll-snap-align: start;
  display: flex;
  flex-direction: column;
}

.box:hover {
    transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.box p { font-size: 0.95rem; line-height: 1.7; color: var(--text-color); margin-bottom: 2rem; flex-grow: 1; }

.review-image-wrapper { width: 100%; height: 150px; overflow: hidden; margin-bottom: 1.5rem; border-radius: 0.5rem; }
.review-image-wrapper img { width: 100%; height: 100%; object-fit: cover; display: block; }

.user-wrapper { display: flex; align-items: flex-end; justify-content: space-between; margin-top: auto; padding-top: 1rem; border-top: 1px dashed #ced4da; }
.box .user { display: flex; align-items: center; }
.box .user .user-icon { font-size: 2.5rem; color: var(--accent-color); margin-right: 0.8rem; height: 2.5rem; width: 2.5rem; }
.box .user .user-info h3 { font-size: 1rem; font-weight: 700; margin: 0; }
.box .user .user-info span { font-size: 0.8rem; color: var(--grey-text); display: block; margin-top: 0.1rem; }
.box .fa-quote-right { font-size: 2.5rem; color: rgba(0, 123, 255, 0.1); }

#form-wrapper {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.75); z-index: 9999;
    display: flex; justify-content: center; align-items: center; opacity: 0; visibility: hidden;
    transition: opacity 0.3s ease, visibility 0s linear 0.3s;
}
#form-wrapper.visible { opacity: 1; visibility: visible; transition: opacity 0.3s ease; }

form#ratingForm {
    position: relative; max-width: 600px; margin: 3rem; background: var(--card-bg); padding: 3rem;
    border-radius: 1rem; box-shadow: 0 1rem 3rem rgba(0,0,0,.3); gap: 1.2rem;
    transform: translateY(-50px); transition: transform 0.3s ease; overflow-y: auto; max-height: 90vh; 
    border: none; display: flex; flex-direction: column;
}
#form-wrapper.visible #ratingForm { transform: translateY(0); }

#close-form { position: absolute; top: 15px; right: 15px; font-size: 1.5rem; cursor: pointer; color: var(--grey-text); }
#close-form:hover { color: var(--text-color); }

form#ratingForm textarea { min-height: 120px; padding: 12px; border-radius: .5rem; border: 1px solid #ddd; font-size: 1rem; background: #fff; resize: vertical; }

.rating { gap: .4rem; font-size: 2.2rem; }
.rating .star { color: #ccc; cursor: pointer;}
.rating .star.selected, .rating .star.hovered { color: var(--star-color); }

form#ratingForm button[type="submit"] { padding: 12px; background: var(--accent-color); color: white; border-radius: .5rem; font-size: 1rem; box-shadow: 0 5px 10px rgba(0, 123, 255, 0.3); border: none; }

#floating-cta { position: fixed; bottom: 30px; right: 30px; z-index: 1000; padding: 15px 25px; background: var(--accent-color); color: white; border: none; border-radius: 50px; font-size: 1rem; font-weight: 600; cursor: pointer; box-shadow: 0 8px 15px rgba(0, 123, 255, 0.3); transition: background 0.2s, transform 0.2s; }
#floating-cta:hover { background: var(--dark-blue); transform: scale(1.05); }

@media (max-width: 768px) {
  .reviews-section { padding: 2rem 0; }
  .box-container { padding: 1rem; }
  form#ratingForm { margin: 1rem; padding: 1.5rem; }
  #floating-cta { bottom: 20px; right: 20px; font-size: 0.9rem; padding: 12px 20px; }
}
</style>

</head>
<body>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/viaje/viaje/Viaje-APP/componentes/header/header.php"); ?>

<div class="reviews-section">
    <h1>Testimonios y Reseñas de Clientes</h1>
    <div class="box-container">
        <div class="no-reseñas" style="color: var(--grey-text); margin: 0 auto;">
            Aún no hay reseñas — ¡sé la primera persona en opinar!
        </div>
    </div>
</div>

<?php if($usuario_logueado): ?>
    <button id="floating-cta"><i class="fas fa-pen mr-2"></i> ¡Dejar mi Reseña!</button>
<?php else: ?>
    <button id="floating-cta" onclick="window.location.href='/viaje/viaje/Viaje-APP/componentes/iniciarsesion/sign.php'"><i class="fas fa-sign-in-alt mr-2"></i> Inicia Sesión para Opinar</button>
<?php endif; ?>

<div id="form-wrapper">
    <form id="ratingForm" enctype="multipart/form-data">
        <i class="fas fa-times" id="close-form" title="Cerrar formulario"></i>
        <h3>Comparte tu experiencia, <?= htmlspecialchars($nombre_usuario) ?></h3>
        <input type="text" name="nombre" placeholder="Tu nombre" value="<?= htmlspecialchars($nombre_usuario) ?>" required>
        <input type="text" name="destino" placeholder="Destino visitado" required>
        <input type="number" name="dias" placeholder="Días de viaje" required>

        <p>Calificación:</p>
        <div class="rating">
            <i class="far fa-star star" data-value="1"></i>
            <i class="far fa-star star" data-value="2"></i>
            <i class="far fa-star star" data-value="3"></i>
            <i class="far fa-star star" data-value="4"></i>
            <i class="far fa-star star" data-value="5"></i>
        </div>
        <input type="hidden" name="rating" id="ratingValue">

        <textarea name="mensaje" placeholder="Cuéntanos tu experiencia..." required></textarea>
        <label>Subir foto (opcional)</label>
        <input type="file" name="foto" accept="image/*">

        <button type="submit">Enviar reseña</button>
    </form>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/viaje/viaje/Viaje-APP/componentes/footer/footer.php"); ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const floatingCta = document.getElementById('floating-cta');
    const formWrapper = document.getElementById('form-wrapper');
    const closeForm = document.getElementById('close-form');
    const ratingForm = document.getElementById('ratingForm');
    const stars = document.querySelectorAll('.star');
    const ratingValueInput = document.getElementById('ratingValue');
    let rating = 0;

    <?php if($usuario_logueado): ?>
        floatingCta.addEventListener('click', () => { formWrapper.classList.add('visible'); });
    <?php endif; ?>

    closeForm.addEventListener('click', () => { formWrapper.classList.remove('visible'); });
    formWrapper.addEventListener('click', (e) => { if(e.target.id==='form-wrapper') formWrapper.classList.remove('visible'); });

    stars.forEach(star => {
        star.addEventListener('mouseover', () => { resetHover(); highlight(star.dataset.value); });
        star.addEventListener('mouseout', () => { resetHover(); highlight(rating, true); });
        star.addEventListener('click', () => { rating=star.dataset.value; resetSelected(); highlight(rating,true); ratingValueInput.value=rating; });
    });

    function highlight(limit, selected=false){
        stars.forEach(star=>{
            star.classList.remove('hovered');
            if(star.dataset.value<=limit){
                star.classList.add(selected?"selected":"hovered");
                star.classList.replace('far','fas');
            }else if(star.classList.contains('selected')||star.classList.contains('hovered')){
                star.classList.replace('fas','far');
            }
        });
    }

    function resetHover(){
        stars.forEach(s=>{ s.classList.remove("hovered"); if(!s.classList.contains('selected')) s.classList.replace('fas','far'); });
    }
    function resetSelected(){
        stars.forEach(s=>{ s.classList.remove("selected"); s.classList.replace('fas','far'); });
    }

    ratingForm.addEventListener('submit', e => {
        e.preventDefault();
        if(ratingValueInput.value==0){ 
            Swal.fire('Atención','Por favor, selecciona una calificación.','warning'); 
            return;
        }

        const formData = new FormData(e.target);
        fetch("/viaje/viaje/LoginAPI/guardar_rating.php",{method:"POST", body:formData})
        .then(r=>r.json())
        .then(res=>{
            Swal.fire({
                icon: res.success?'success':'error',
                title: res.success?'¡Reseña enviada!':'Error',
                text: res.success?'En un momento nuestro equipo se pondrá en contacto con usted':res.message,
                confirmButtonText: 'Aceptar'
            }).then(()=>{
                if(res.success){
                    formWrapper.classList.remove('visible');
                    ratingForm.reset();
                    resetSelected();
                    rating=0;
                    ratingValueInput.value=0;
                    location.reload();
                }
            });
        }).catch(()=>{ Swal.fire('Error','Error de conexión al servidor.','error'); });
    });
});
</script>

</body>
</html>
