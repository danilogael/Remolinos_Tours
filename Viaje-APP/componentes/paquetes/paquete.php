<?php
session_start();
$nombre = $_SESSION['nombre'] ?? 'Usuario';
?>


<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Ofertas de Fin de Año - Remolinos Tours</title>
 
 <link rel="stylesheet" href="/viaje/viaje/Viaje-APP/componentes/paquetes/paquete.css">
 <link rel="stylesheet" href="/viaje/viaje/Viaje-APP/componentes/estilos/header.css">
 <link rel="stylesheet" href="/viaje/viaje/Viaje-APP/componentes/estilos/estilos_footer.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/viaje/viaje/Viaje-APP/componentes/header/header.php"); ?>

<section class="products" id="products">
  <h1 class="titulo-ofertas">Descubre nuestros <span>Paquetes</span></h1>
  <div class="box-container">

        <div class="box">
     

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cancun">
          <img src="/viaje/viaje/Viaje-APP/imagenes/viajes internacionales/Alemania/Al1.jpg" alt="Vista principal de Alemania">
          <img src="/viaje/viaje/Viaje-APP/imagenes/viajes internacionales/Alemania/Al2.jpg" alt="alemania">
          <img src="/viaje/viaje/Viaje-APP/imagenes/viajes internacionales/Alemania/Al3.jpg" alt="alemania">
        </div>
        
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cancun" aria-label="Imagen anterior"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cancun" aria-label="Imagen siguiente"></button>
        <div class="icons">
        </div>
      </div>

      <div class="content">
        <h3>Alemania</h3>
        <span class="ubicacion"><i class="fas fa-map-marker-alt"></i> Berlin</span>
        <p class="duracion">7 días · 6 noches</p>
        <p class="categoria">All Inclusive</p>
        <div class="rating">★★★★☆</div>
        <div class="price">$23,000 MXN </div>
        
                <div class="extra-details">
          <ul class="details-list">
            <li><i class="fas fa-user"></i> Paquete para 2 personas.</li>
            <li><i class="fas fa-plane"></i> Vuelos redondos incluidos.</li>
            <li><i class="fas fa-hotel"></i> Hospedaje 5 estrellas (Todo Incluido).</li>
            <li><i class="fas fa-cocktail"></i> Barra libre y alimentos gourmet.</li>
            <li><i class="fas fa-bus-alt"></i> Traslados aeropuerto-hotel y viceversa.</li>
            <li><i class="fas fa-user"></i> Paquete para 2 personas.</li>
          </ul>
          <p class="details-note">
            <i class="fas fa-exclamation-circle"></i> **Importante:** No incluye tours opcionales ni propinas.
          </p>
        </div>
        
        <div class="box-actions">
          <a href="#" class="btn details-btn">Ver más detalles</a>
          <a href="#" class="btn reserve-btn">¡Reservar ahora!</a>
        </div>
      </div>
    </div>

        <div class="box">
     

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="vallarta">
          <img src="/viaje/viaje/Viaje-APP/imagenes/viajes internacionales/España/Es1.jpg" alt="España ciudad">
          <img src="/viaje/viaje/Viaje-APP/imagenes/viajes internacionales/España/Es2.jpg" alt="España ">
          <img src="/viaje/viaje/Viaje-APP/imagenes/viajes internacionales/España/Es3.jpg" alt="España ciudad">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="vallarta" aria-label="Imagen anterior"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="vallarta" aria-label="Imagen siguiente"></button>
        <div class="icons">
         
        </div>
      </div>

      <div class="content">
        <h3>Puerto Vallarta</h3>
        <span class="ubicacion"><i class="fas fa-map-marker-alt"></i> Jalisco</span>
        <p class="duracion">4 días · 3 noches</p>
        <p class="categoria">Hotel 4★</p>
        <div class="rating">★★★★</div>
        <div class="price">$2400 MXN <span>$2800 MXN</span></div>
        
                <div class="extra-details">
          <ul class="details-list">
            <li><i class="fas fa-mug-hot"></i> Desayuno buffet diario.</li>
            <li><i class="fas fa-car"></i> Traslado gratuito al malecón.</li>
            <li><i class="fas fa-utensils"></i> Una cena especial en restaurante del hotel.</li>
          </ul>
          <p class="details-note">
            <i class="fas fa-exclamation-circle"></i> **Importante:** Boletos de avión no incluidos.
          </p>
        </div>
        
        <div class="box-actions">
          <a href="#" class="btn details-btn">Ver más detalles</a>
          <a href="#" class="btn reserve-btn">¡Reservar ahora!</a>
        </div>
      </div>
    </div>
    <div class="box">
     
      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cabos">
          <img src="/viaje/viaje/Viaje-APP/imagenes/los_cabos.jpg">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun.png">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-2.jpg">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cabos"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cabos"></button>
        <div class="icons">
         
        
        </div>
      </div>

      <div class="content">
        <h3>Los Cabos</h3>
        <span class="ubicacion"><i class="fas fa-map-marker-alt"></i> Baja California Sur</span>
        <p class="duracion">3 días · 2 noches</p>
        <p class="categoria">All Inclusive</p>
        <div class="rating">★★★★☆</div>
        <div class="price">$2100 MXN <span>$2600 MXN</span></div>

        <div class="extra-details">
          <ul class="details-list">
            <li><i class="fas fa-plane"></i> Vuelos redondos incluidos.</li>
            <li><i class="fas fa-hotel"></i> Hospedaje 5 estrellas (Todo Incluido).</li>
            <li><i class="fas fa-cocktail"></i> Barra libre y alimentos gourmet.</li>
            <li><i class="fas fa-bus-alt"></i> Traslados aeropuerto-hotel y viceversa.</li>
          </ul>
          <p class="details-note">
            <i class="fas fa-exclamation-circle"></i> **Importante:** No incluye tours opcionales ni propinas.
          </p>
        </div>

        <div class="box-actions">
          <a href="#" class="btn details-btn">Ver más detalles</a>
          <a href="#" class="btn reserve-btn">¡Reservar ahora!</a>
        </div>
      </div>
    </div>
    <div class="box">
     
      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cabos">
          <img src="/viaje/viaje/Viaje-APP/imagenes/los_cabos.jpg">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun.png">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-2.jpg">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cabos"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cabos"></button>
        <div class="icons">
         
        
        </div>
      </div>

      <div class="content">
        <h3>Los Cabos</h3>
        <span class="ubicacion"><i class="fas fa-map-marker-alt"></i> Baja California Sur</span>
        <p class="duracion">3 días · 2 noches</p>
        <p class="categoria">All Inclusive</p>
        <div class="rating">★★★★☆</div>
        <div class="price">$2100 MXN <span>$2600 MXN</span></div>

        <div class="extra-details">
          <ul class="details-list">
            <li><i class="fas fa-plane"></i> Vuelos redondos incluidos.</li>
            <li><i class="fas fa-hotel"></i> Hospedaje 5 estrellas (Todo Incluido).</li>
            <li><i class="fas fa-cocktail"></i> Barra libre y alimentos gourmet.</li>
            <li><i class="fas fa-bus-alt"></i> Traslados aeropuerto-hotel y viceversa.</li>
          </ul>
          <p class="details-note">
            <i class="fas fa-exclamation-circle"></i> **Importante:** No incluye tours opcionales ni propinas.
          </p>
        </div>

        <div class="box-actions">
          <a href="#" class="btn details-btn">Ver más detalles</a>
          <a href="#" class="btn reserve-btn">¡Reservar ahora!</a>
        </div>
      </div>
    </div>
    <div class="box">
      

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cabos">
          <img src="/viaje/viaje/Viaje-APP/imagenes/los_cabos.jpg">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun.png">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-2.jpg">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cabos"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cabos"></button>
        <div class="icons">
         
        
        </div>
      </div>

      <div class="content">
        <h3>Los Cabos</h3>
        <span class="ubicacion"><i class="fas fa-map-marker-alt"></i> Baja California Sur</span>
        <p class="duracion">3 días · 2 noches</p>
        <p class="categoria">All Inclusive</p>
        <div class="rating">★★★★☆</div>
        <div class="price">$2100 MXN <span>$2600 MXN</span></div>

        <div class="extra-details">
          <ul class="details-list">
            <li><i class="fas fa-plane"></i> Vuelos redondos incluidos.</li>
            <li><i class="fas fa-hotel"></i> Hospedaje 5 estrellas (Todo Incluido).</li>
            <li><i class="fas fa-cocktail"></i> Barra libre y alimentos gourmet.</li>
            <li><i class="fas fa-bus-alt"></i> Traslados aeropuerto-hotel y viceversa.</li>
          </ul>
          <p class="details-note">
            <i class="fas fa-exclamation-circle"></i> **Importante:** No incluye tours opcionales ni propinas.
          </p>
        </div>

        <div class="box-actions">
          <a href="#" class="btn details-btn">Ver más detalles</a>
          <a href="#" class="btn reserve-btn">¡Reservar ahora!</a>
        </div>
      </div>
    </div>
    <div class="box">
      

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cabos">
          <img src="/viaje/viaje/Viaje-APP/imagenes/los_cabos.jpg">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun.png">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-2.jpg">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cabos"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cabos"></button>
        <div class="icons">
         
        
        </div>
      </div>

      <div class="content">
        <h3>Los Cabos</h3>
        <span class="ubicacion"><i class="fas fa-map-marker-alt"></i> Baja California Sur</span>
        <p class="duracion">3 días · 2 noches</p>
        <p class="categoria">All Inclusive</p>
        <div class="rating">★★★★☆</div>
        <div class="price">$2100 MXN <span>$2600 MXN</span></div>

        <div class="extra-details">
          <ul class="details-list">
            <li><i class="fas fa-plane"></i> Vuelos redondos incluidos.</li>
            <li><i class="fas fa-hotel"></i> Hospedaje 5 estrellas (Todo Incluido).</li>
            <li><i class="fas fa-cocktail"></i> Barra libre y alimentos gourmet.</li>
            <li><i class="fas fa-bus-alt"></i> Traslados aeropuerto-hotel y viceversa.</li>
          </ul>
          <p class="details-note">
            <i class="fas fa-exclamation-circle"></i> **Importante:** No incluye tours opcionales ni propinas.
          </p>
        </div>

        <div class="box-actions">
          <a href="#" class="btn details-btn">Ver más detalles</a>
          <a href="#" class="btn reserve-btn">¡Reservar ahora!</a>
        </div>
      </div>
    </div>
    <div class="box">
     

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cabos">
          <img src="/viaje/viaje/Viaje-APP/imagenes/los_cabos.jpg">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun.png">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-2.jpg">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cabos"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cabos"></button>
        <div class="icons">
         
        
        </div>
      </div>

      <div class="content">
        <h3>Los Cabos</h3>
        <span class="ubicacion"><i class="fas fa-map-marker-alt"></i> Baja California Sur</span>
        <p class="duracion">3 días · 2 noches</p>
        <p class="categoria">All Inclusive</p>
        <div class="rating">★★★★☆</div>
        <div class="price">$2100 MXN <span>$2600 MXN</span></div>

        <div class="extra-details">
          <ul class="details-list">
            <li><i class="fas fa-plane"></i> Vuelos redondos incluidos.</li>
            <li><i class="fas fa-hotel"></i> Hospedaje 5 estrellas (Todo Incluido).</li>
            <li><i class="fas fa-cocktail"></i> Barra libre y alimentos gourmet.</li>
            <li><i class="fas fa-bus-alt"></i> Traslados aeropuerto-hotel y viceversa.</li>
          </ul>
          <p class="details-note">
            <i class="fas fa-exclamation-circle"></i> **Importante:** No incluye tours opcionales ni propinas.
          </p>
        </div>

        <div class="box-actions">
          <a href="#" class="btn details-btn">Ver más detalles</a>
          <a href="#" class="btn reserve-btn">¡Reservar ahora!</a>
        </div>
      </div>
    </div>
    <div class="box">
  

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cabos">
          <img src="/viaje/viaje/Viaje-APP/imagenes/los_cabos.jpg">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun.png">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-2.jpg">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cabos"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cabos"></button>
        <div class="icons">
         
        
        </div>
      </div>

      <div class="content">
        <h3>Los Cabos</h3>
        <span class="ubicacion"><i class="fas fa-map-marker-alt"></i> Baja California Sur</span>
        <p class="duracion">3 días · 2 noches</p>
        <p class="categoria">All Inclusive</p>
        <div class="rating">★★★★☆</div>
        <div class="price">$2100 MXN <span>$2600 MXN</span></div>

        <div class="extra-details">
          <ul class="details-list">
            <li><i class="fas fa-plane"></i> Vuelos redondos incluidos.</li>
            <li><i class="fas fa-hotel"></i> Hospedaje 5 estrellas (Todo Incluido).</li>
            <li><i class="fas fa-cocktail"></i> Barra libre y alimentos gourmet.</li>
            <li><i class="fas fa-bus-alt"></i> Traslados aeropuerto-hotel y viceversa.</li>
          </ul>
          <p class="details-note">
            <i class="fas fa-exclamation-circle"></i> **Importante:** No incluye tours opcionales ni propinas.
          </p>
        </div>

        <div class="box-actions">
          <a href="#" class="btn details-btn">Ver más detalles</a>
          <a href="#" class="btn reserve-btn">¡Reservar ahora!</a>
        </div>
      </div>
    </div>
    <div class="box">
     

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cabos">
          <img src="/viaje/viaje/Viaje-APP/imagenes/los_cabos.jpg">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun.png">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-2.jpg">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cabos"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cabos"></button>
        <div class="icons">
         
        
        </div>
      </div>

      <div class="content">
        <h3>Los Cabos</h3>
        <span class="ubicacion"><i class="fas fa-map-marker-alt"></i> Baja California Sur</span>
        <p class="duracion">3 días · 2 noches</p>
        <p class="categoria">All Inclusive</p>
        <div class="rating">★★★★☆</div>
        <div class="price">$2100 MXN <span>$2600 MXN</span></div>

        <div class="extra-details">
          <ul class="details-list">
            <li><i class="fas fa-plane"></i> Vuelos redondos incluidos.</li>
            <li><i class="fas fa-hotel"></i> Hospedaje 5 estrellas (Todo Incluido).</li>
            <li><i class="fas fa-cocktail"></i> Barra libre y alimentos gourmet.</li>
            <li><i class="fas fa-bus-alt"></i> Traslados aeropuerto-hotel y viceversa.</li>
          </ul>
          <p class="details-note">
            <i class="fas fa-exclamation-circle"></i> **Importante:** No incluye tours opcionales ni propinas.
          </p>
        </div>

        <div class="box-actions">
          <a href="#" class="btn details-btn">Ver más detalles</a>
          <a href="#" class="btn reserve-btn">¡Reservar ahora!</a>
        </div>
      </div>
    </div>

    <div class="box">
      

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cabos">
          <img src="/viaje/viaje/Viaje-APP/imagenes/los_cabos.jpg">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun.png">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-2.jpg">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cabos"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cabos"></button>
        <div class="icons">
         
        
        </div>
      </div>

      <div class="content">
        <h3>Los Cabos</h3>
        <span class="ubicacion"><i class="fas fa-map-marker-alt"></i> Baja California Sur</span>
        <p class="duracion">3 días · 2 noches</p>
        <p class="categoria">All Inclusive</p>
        <div class="rating">★★★★☆</div>
        <div class="price">$2100 MXN <span>$2600 MXN</span></div>

        <div class="extra-details">
          <ul class="details-list">
            <li><i class="fas fa-plane"></i> Vuelos redondos incluidos.</li>
            <li><i class="fas fa-hotel"></i> Hospedaje 5 estrellas (Todo Incluido).</li>
            <li><i class="fas fa-cocktail"></i> Barra libre y alimentos gourmet.</li>
            <li><i class="fas fa-bus-alt"></i> Traslados aeropuerto-hotel y viceversa.</li>
          </ul>
          <p class="details-note">
            <i class="fas fa-exclamation-circle"></i> **Importante:** No incluye tours opcionales ni propinas.
          </p>
        </div>

        <div class="box-actions">
          <a href="#" class="btn details-btn">Ver más detalles</a>
          <a href="#" class="btn reserve-btn">¡Reservar ahora!</a>
        </div>
      </div>
    </div>
    <div class="box">
      

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cabos">
          <img src="/viaje/viaje/Viaje-APP/imagenes/los_cabos.jpg">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun.png">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-2.jpg">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cabos"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cabos"></button>
        <div class="icons">
         
        
        </div>
      </div>

      <div class="content">
        <h3>Los Cabos</h3>
        <span class="ubicacion"><i class="fas fa-map-marker-alt"></i> Baja California Sur</span>
        <p class="duracion">3 días · 2 noches</p>
        <p class="categoria">All Inclusive</p>
        <div class="rating">★★★★☆</div>
        <div class="price">$2100 MXN <span>$2600 MXN</span></div>

        <div class="extra-details">
          <ul class="details-list">
            <li><i class="fas fa-plane"></i> Vuelos redondos incluidos.</li>
            <li><i class="fas fa-hotel"></i> Hospedaje 5 estrellas (Todo Incluido).</li>
            <li><i class="fas fa-cocktail"></i> Barra libre y alimentos gourmet.</li>
            <li><i class="fas fa-bus-alt"></i> Traslados aeropuerto-hotel y viceversa.</li>
          </ul>
          <p class="details-note">
            <i class="fas fa-exclamation-circle"></i> **Importante:** No incluye tours opcionales ni propinas.
          </p>
        </div>

        <div class="box-actions">
          <a href="#" class="btn details-btn">Ver más detalles</a>
          <a href="#" class="btn reserve-btn">¡Reservar ahora!</a>
        </div>
      </div>
    </div>
    <div class="box">
     

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cabos">
          <img src="/viaje/viaje/Viaje-APP/imagenes/los_cabos.jpg">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun.png">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-2.jpg">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cabos"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cabos"></button>
        <div class="icons">
         
        
        </div>
      </div>

      <div class="content">
        <h3>Los Cabos</h3>
        <span class="ubicacion"><i class="fas fa-map-marker-alt"></i> Baja California Sur</span>
        <p class="duracion">3 días · 2 noches</p>
        <p class="categoria">All Inclusive</p>
        <div class="rating">★★★★☆</div>
        <div class="price">$2100 MXN <span>$2600 MXN</span></div>

        <div class="extra-details">
          <ul class="details-list">
            <li><i class="fas fa-plane"></i> Vuelos redondos incluidos.</li>
            <li><i class="fas fa-hotel"></i> Hospedaje 5 estrellas (Todo Incluido).</li>
            <li><i class="fas fa-cocktail"></i> Barra libre y alimentos gourmet.</li>
            <li><i class="fas fa-bus-alt"></i> Traslados aeropuerto-hotel y viceversa.</li>
          </ul>
          <p class="details-note">
            <i class="fas fa-exclamation-circle"></i> **Importante:** No incluye tours opcionales ni propinas.
          </p>
        </div>

        <div class="box-actions">
          <a href="#" class="btn details-btn">Ver más detalles</a>
          <a href="#" class="btn reserve-btn">¡Reservar ahora!</a>
        </div>
      </div>
    </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Lógica de los botones manuales (tu código actual mejorado)
    document.querySelectorAll('.reserve-btn').forEach(btn => {
      btn.addEventListener('click', function(e) {
          e.preventDefault();

          <?php if (!isset($_SESSION['user_id'])) { ?>
                // Si NO está logueado: Lo mandamos al login pero con un "aviso" en la URL
                Swal.fire({
                  icon: 'warning',
                  title: 'Debes iniciar sesión',
                  text: 'Para reservar, primero inicia sesión.',
                  confirmButtonText: 'Ir al Login'
                }).then(()=> {
                    // OJO AQUÍ: Agregamos ?origen=reserva a la URL
                    window.location.href="/viaje/viaje/Viaje-APP/componentes/iniciarsesion/sign.php?origen=reserva";
                });

          <?php } else { ?>
                // Si YA está logueado: Muestra el éxito
                mostrarAlertaExito();
          <?php } ?>
      });
    });

    // 2. Lógica AUTOMÁTICA (El truco)
    // Verifica si en la URL hay una variable "reserva_pendiente=si"
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('reserva_pendiente') === 'si') {
        mostrarAlertaExito();
    }

    // Función para no repetir código
    function mostrarAlertaExito() {
        Swal.fire({
          icon: 'success',
          title: '¡Solicitud recibida!',
          html: 'Gracias <b><?php echo $_SESSION["nombre"] ?? ""; ?></b>,<br> en un momento nuestro equipo te contactará para darle seguimiento a tu reserva.',
          confirmButtonText: 'Aceptar'
        }).then(() => {
            // Limpiamos la URL para que no salga la alerta si recarga la página
            window.history.replaceState({}, document.title, window.location.pathname);
        });
    }
});
</script>


<?php include($_SERVER['DOCUMENT_ROOT'] . "/viaje/viaje/Viaje-APP/componentes/footer/footer.php"); ?>
<script src="/viaje/viaje/Viaje-APP/componentes/js/header.js"></script>
<script src="/viaje/viaje/Viaje-APP/componentes/paquetes/paquete.js"></script>
</body>
</html>
