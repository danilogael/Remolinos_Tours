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
 
 <link rel="stylesheet" href="/viaje/viaje/Viaje-APP/componentes/ofertas/ofertas.css">
 <link rel="stylesheet" href="/viaje/viaje/Viaje-APP/componentes/estilos/header.css">
 <link rel="stylesheet" href="/viaje/viaje/Viaje-APP/componentes/estilos/estilos_footer.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/viaje/viaje/Viaje-APP/componentes/header/header.php"); ?>

<section class="products" id="products">
  <h1 class="titulo-ofertas">Descubre nuestras <span>Ofertas</span></h1>
  <div class="box-container">

        <div class="box">
      <span class="discount">-10%</span>

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cancun">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun.png" alt="Vista principal de Cancún">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-2.jpg" alt="Playa de Cancún">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-3.jpg" alt="Hotel de Cancún">
        </div>
        
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cancun" aria-label="Imagen anterior"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cancun" aria-label="Imagen siguiente"></button>
        <div class="icons">
          <a href="#" class="fas fa-heart" aria-label="Agregar a favoritos"></a>
          <a href="#" class="fas fa-share" aria-label="Compartir"></a>
        </div>
      </div>

      <div class="content">
        <h3>Cancún</h3>
        <span class="ubicacion"><i class="fas fa-map-marker-alt"></i> Quintana Roo</span>
        <p class="duracion">3 días · 2 noches</p>
        <p class="categoria">All Inclusive</p>
        <div class="rating">★★★★☆</div>
        <div class="price">$1200 MXN <span>$1700 MXN</span></div>
        
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
      <span class="discount">-15%</span>

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="vallarta">
          <img src="/viaje/viaje/Viaje-APP/imagenes/puerto_vallarta.jpg" alt="Vista principal de Puerto Vallarta">
          <img src="/viaje/viaje/Viaje-APP/imagenes/vallarta-2.jpg" alt="Playa de Puerto Vallarta">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="vallarta" aria-label="Imagen anterior"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="vallarta" aria-label="Imagen siguiente"></button>
        <div class="icons">
          <a href="#" class="fas fa-heart" aria-label="Agregar a favoritos"></a>
          <a href="#" class="fas fa-share" aria-label="Compartir"></a>
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
      <span class="discount">-20%</span>

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cabos">
          <img src="/viaje/viaje/Viaje-APP/imagenes/los_cabos.jpg">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun.png">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-2.jpg">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cabos"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cabos"></button>
        <div class="icons">
          <a href="#" class="fas fa-heart"></a>
          <a href="#" class="fas fa-share"></a>
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
      <span class="discount">-20%</span>

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cabos">
          <img src="/viaje/viaje/Viaje-APP/imagenes/los_cabos.jpg">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun.png">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-2.jpg">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cabos"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cabos"></button>
        <div class="icons">
          <a href="#" class="fas fa-heart"></a>
          <a href="#" class="fas fa-share"></a>
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
      <span class="discount">-20%</span>

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cabos">
          <img src="/viaje/viaje/Viaje-APP/imagenes/los_cabos.jpg">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun.png">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-2.jpg">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cabos"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cabos"></button>
        <div class="icons">
          <a href="#" class="fas fa-heart"></a>
          <a href="#" class="fas fa-share"></a>
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
      <span class="discount">-20%</span>

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cabos">
          <img src="/viaje/viaje/Viaje-APP/imagenes/los_cabos.jpg">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun.png">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-2.jpg">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cabos"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cabos"></button>
        <div class="icons">
          <a href="#" class="fas fa-heart"></a>
          <a href="#" class="fas fa-share"></a>
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
      <span class="discount">-20%</span>

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cabos">
          <img src="/viaje/viaje/Viaje-APP/imagenes/los_cabos.jpg">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun.png">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-2.jpg">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cabos"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cabos"></button>
        <div class="icons">
          <a href="#" class="fas fa-heart"></a>
          <a href="#" class="fas fa-share"></a>
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
      <span class="discount">-20%</span>

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cabos">
          <img src="/viaje/viaje/Viaje-APP/imagenes/los_cabos.jpg">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun.png">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-2.jpg">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cabos"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cabos"></button>
        <div class="icons">
          <a href="#" class="fas fa-heart"></a>
          <a href="#" class="fas fa-share"></a>
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
      <span class="discount">-20%</span>

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cabos">
          <img src="/viaje/viaje/Viaje-APP/imagenes/los_cabos.jpg">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun.png">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-2.jpg">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cabos"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cabos"></button>
        <div class="icons">
          <a href="#" class="fas fa-heart"></a>
          <a href="#" class="fas fa-share"></a>
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
      <span class="discount">-20%</span>

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cabos">
          <img src="/viaje/viaje/Viaje-APP/imagenes/los_cabos.jpg">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun.png">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-2.jpg">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cabos"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cabos"></button>
        <div class="icons">
          <a href="#" class="fas fa-heart"></a>
          <a href="#" class="fas fa-share"></a>
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
      <span class="discount">-20%</span>

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cabos">
          <img src="/viaje/viaje/Viaje-APP/imagenes/los_cabos.jpg">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun.png">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-2.jpg">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cabos"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cabos"></button>
        <div class="icons">
          <a href="#" class="fas fa-heart"></a>
          <a href="#" class="fas fa-share"></a>
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
      <span class="discount">-20%</span>

      <div class="img carrusel">
        <div class="carrusel-slide-wrapper" data-carrusel-id="cabos">
          <img src="/viaje/viaje/Viaje-APP/imagenes/los_cabos.jpg">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun.png">
          <img src="/viaje/viaje/Viaje-APP/imagenes/cancun-2.jpg">
        </div>
        <button class="carrusel-btn prev fas fa-chevron-left" data-carrusel-target="cabos"></button>
        <button class="carrusel-btn next fas fa-chevron-right" data-carrusel-target="cabos"></button>
        <div class="icons">
          <a href="#" class="fas fa-heart"></a>
          <a href="#" class="fas fa-share"></a>
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

    // 1. DETECTAR SI VENIMOS DE UN LOGIN EXITOSO
    // Si la URL tiene ?reserva_pendiente=si, mostramos la alerta de gracias
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('reserva_pendiente') === 'si') {
        Swal.fire({
            icon: 'success',
            title: '¡Reserva en proceso!',
            html: 'Gracias <b><?php echo $_SESSION["nombre"] ?? "Viajero"; ?></b>,<br> hemos recibido tu solicitud de oferta. Te contactaremos pronto.',
            confirmButtonText: 'Genial'
        }).then(() => {
            // Limpiamos la URL para que no salga la alerta al recargar
            window.history.replaceState({}, document.title, window.location.pathname);
        });
    }

    // 2. LÓGICA DE LOS BOTONES "RESERVAR"
    const botonesReserva = document.querySelectorAll('.reserve-btn'); // Asegúrate que tus botones tengan esta clase

    botonesReserva.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            <?php if (!isset($_SESSION['user_id'])) { ?>
                
                // CASO A: NO ESTÁ LOGUEADO
                Swal.fire({
                    icon: 'warning',
                    title: 'Inicia sesión para aprovechar la oferta',
                    text: 'Para reservar este precio especial, necesitas identificarte.',
                    showCancelButton: true,
                    confirmButtonText: 'Ir a Login',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // AQUÍ ESTÁ LA CLAVE: Le decimos ?origen=ofertas
                        window.location.href = "/viaje/viaje/Viaje-APP/componentes/iniciarsesion/sign.php?origen=ofertas";
                    }
                });

            <?php } else { ?>

                // CASO B: YA ESTÁ LOGUEADO (Reserva directa)
                Swal.fire({
                    icon: 'success',
                    title: '¡Solicitud enviada!',
                    html: 'Gracias <b><?php echo $_SESSION["nombre"]; ?></b>, estamos procesando tu oferta en seguida nos contactaremos contigo para darte seguimiento.',
                    confirmButtonText: 'Aceptar'
                });

            <?php } ?>
        });
    });
});
</script>


<?php include($_SERVER['DOCUMENT_ROOT'] . "/viaje/viaje/Viaje-APP/componentes/footer/footer.php"); ?>
<script src="/viaje/viaje/Viaje-APP/componentes/js/header.js"></script>
<script src="/viaje/viaje/Viaje-APP/componentes/ofertas/ofertas.js"></script>
</body>
</html>