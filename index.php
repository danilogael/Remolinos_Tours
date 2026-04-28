<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Remolinos Tours - Inicio</title>
  <link rel="stylesheet" href="style.css">
  <!-- Font Awesome y Google Fonts ya los carga header.php automáticamente -->
</head>
<body>

<!-- ══ HEADER ══ -->
<?php include __DIR__ . "/componentes/header/header.php"; ?>

<!-- ══ HERO ══ -->
<section class="hero" id="home">
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <h1>Descubre el Mundo con<br><span>REMOLINOS TOURS</span></h1>
    <p>Experiencias únicas que transforman viajes en recuerdos inolvidables.<br>Más de 5,000 viajeros felices nos respaldan.</p>
    <div class="hero-btns">
      <a href="/Agencia_Remolinos/componentes/Destinos/destinos.php" class="btn-primary">Explorar Destinos <i class="fas fa-arrow-right"></i></a>
      <a href="/Agencia_Remolinos/componentes/Reserva/reserva.php" class="btn-ghost">Reservar Ahora</a>
    </div>
  </div>
  <div class="hero-stats">
    <div class="stat"><strong>5,000+</strong><span>Viajeros</span></div>
    <div class="stat"><strong>50+</strong><span>Destinos</span></div>
    <div class="stat"><strong>15 Años</strong><span>Experiencia</span></div>
  </div>
  <div class="scroll-indicator"><i class="fas fa-chevron-down"></i></div>
</section>

<!-- ══ DESTINOS ══ -->
<section class="destinos" id="destinos">
  <div class="destinos-header">
    <div class="header-content">
      <span class="sub-label">Nuestros Destinos</span>
      <h2>Lugares que te Dejarán<br>Sin Aliento</h2>
    </div>
    <a href="componentes/Destinos/destinos.php" class="ver-todos">Ver todos los destinos <i class="fas fa-arrow-right"></i></a>
  </div>

  <div class="destinos-grid">

    <div class="dest-card card-featured">
      <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800" alt="Caribe Mexicano">
      <div class="dest-overlay">
        <span class="badge-popular">POPULAR</span>
        <h3>Caribe Mexicano Mágico</h3>
        <p>Cancún, México</p>
        <div class="dest-footer">
          <span class="precio">Desde<br><strong>$1299</strong></span>
          <a href="#" class="btn-detalle">Ver Detalles</a>
        </div>
      </div>
    </div>

    <div class="dest-card">
      <img src="https://images.unsplash.com/photo-1526392060635-9d6019884377?w=600" alt="Perú">
      <div class="dest-overlay">
        <h3>Perú Místico</h3>
        <p>Cusco & Machu Picchu, Perú</p>
        <div class="dest-footer">
          <strong class="precio">$1850</strong>
          <a href="#" class="btn-detalle">Ver Detalles</a>
        </div>
      </div>
    </div>

    <div class="dest-card">
      <img src="https://images.unsplash.com/photo-1501854140801-50d01698950b?w=600" alt="Patagonia">
      <div class="dest-overlay">
        <h3>Patagonia Salvaje</h3>
        <p>Torres del Paine, Chile</p>
        <div class="dest-footer">
          <strong class="precio">$2200</strong>
          <a href="#" class="btn-detalle">Ver Detalles</a>
        </div>
      </div>
    </div>

    <div class="dest-card-cta">
      <div class="cta-icon"><i class="fas fa-compass"></i></div>
      <h4>¿No encuentras tu destino?</h4>
      <p>Creamos paquetes personalizados para ti</p>
      <a href="/componentes/Contacto/contacto.php" class="btn-cta-blanco">Ver Todos</a>
    </div>

  </div>
</section>

<!-- ══ STATS BAR ══ -->
<section class="stats-bar">
  <div class="stat-item">
    <i class="fas fa-smile"></i>
    <strong>5,000+</strong>
    <span>Viajeros Felices</span>
  </div>
  <div class="stat-item">
    <i class="fas fa-map"></i>
    <strong>50+</strong>
    <span>Destinos Disponibles</span>
  </div>
  <div class="stat-item">
    <i class="fas fa-heart"></i>
    <strong>98%</strong>
    <span>Satisfacción del Cliente</span>
  </div>
  <div class="stat-item">
    <i class="fas fa-award"></i>
    <strong>15</strong>
    <span>Años de Experiencia</span>
  </div>
</section>

<!-- ══ POR QUÉ ELEGIRNOS ══ -->
<section class="porque" id="about">
  <p class="label-sup centrado">POR QUÉ ELEGIRNOS</p>
  <h2 class="porque-titulo">Tu Viaje, Nuestra Pasión</h2>
  <p class="porque-sub">Llevamos más de 15 años convirtiendo sueños en aventuras reales.<br>Cada detalle importa, cada momento cuenta.</p>
  <div class="porque-grid">
    <div class="porque-card destacado">
      <div class="ico-box activo"><i class="fas fa-headset"></i></div>
      <h4>Atención 24/7</h4>
      <p>Nuestro equipo está disponible en todo momento para resolver cualquier inconveniente durante tu viaje.</p>
    </div>
    <div class="porque-card">
      <div class="ico-box"><i class="fas fa-shield-alt"></i></div>
      <h4>Viajes 100% Seguros</h4>
      <p>Todos nuestros paquetes incluyen seguro de viaje completo y asistencia médica internacional 24/7.</p>
    </div>
    <div class="porque-card">
      <div class="ico-box"><i class="fas fa-star"></i></div>
      <h4>Experiencias Únicas</h4>
      <p>Diseñamos itinerarios exclusivos que van más allá del turismo convencional para crear memorias únicas.</p>
    </div>
    <div class="porque-card">
      <div class="ico-box"><i class="fas fa-tag"></i></div>
      <h4>Mejor Precio Garantizado</h4>
      <p>Si encuentras el mismo paquete más barato, igualamos el precio y te damos un descuento adicional.</p>
    </div>
    <div class="porque-card">
      <div class="ico-box"><i class="fas fa-map-marker-alt"></i></div>
      <h4>Guías Expertos Locales</h4>
      <p>Nuestros guías son nativos del destino con profundo conocimiento cultural e histórico.</p>
    </div>
    <div class="porque-card">
      <div class="ico-box"><i class="fas fa-thumbs-up"></i></div>
      <h4>98% Satisfacción</h4>
      <p>Más de 5,000 viajeros satisfechos avalan nuestra calidad y compromiso con cada experiencia.</p>
    </div>
  </div>
</section>

<!-- ══ RESEÑAS ══ -->
<section class="reviews" id="reviews">
  <div class="reviews-inner">
    <div class="reviews-img-wrap">
      <img src="assets/imagenes/persona1.jpg" onerror="this.src='https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?w=500'" alt="María González">
      <div class="rev-badge">
        <span class="stars-rev">★★★★★</span>
        <strong>Caribe Mexicano Mágico</strong>
      </div>
    </div>
    <div class="reviews-text">
      <p class="label-sup">(EXPERIENCIAS REALES)</p>
      <h2>Lo Que Dicen<br><span>Nuestros Viajeros</span></h2>
      <blockquote>
        <i class="fas fa-quote-left"></i>
        REMOLINOS TOURS superó todas mis expectativas. El hotel era espectacular, los guías increíblemente conocedores y cada detalle estaba perfectamente organizado. Fue el viaje de mi vida y definitivamente volveré a reservar con ellos.
      </blockquote>
      <div class="rev-autor">
        <img src="assets/imagenes/persona1.jpg"
             onerror="this.src='https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?w=800'"
             alt="María González">
        <div>
          <strong>— María González</strong>
          <span>Ciudad de México</span>
        </div>
      </div>
      <div class="rev-nav">
        <button class="rev-btn"><i class="fas fa-arrow-left"></i></button>
        <button class="rev-btn activo"><i class="fas fa-arrow-right"></i></button>
        <div class="rev-dots">
          <span class="dot activo"></span>
          <span class="dot"></span>
          <span class="dot"></span>
          <span class="dot"></span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══ CTA BANNER ══ -->
<section class="cta-banner">
  <div class="cta-overlay"></div>
  <div class="cta-content">
    <span class="cta-label">OFERTA ESPECIAL</span>
    <h2>COMIENZA TU AVENTURA<br>HOY</h2>
    <p>Reserva ahora y obtén <strong>10% de descuento</strong><br>en tu primer viaje con nosotros</p>
    <a href="/Agencia_Remolinos/componentes/Reserva/reserva.php" class="btn-cta-main"><i class="fas fa-shopping-bag"></i> RESERVAR AHORA <i class="fas fa-external-link-alt"></i></a>
  </div>
</section>

<!-- ══ CONTACTO ══ -->
<section class="contact" id="contact">
  <p class="label-sup centrado">CONTÁCTANOS</p>
  <h2 class="contact-titulo">Planifica Tu Próxima Aventura</h2>
  <p class="contact-sub">Nuestros expertos en viajes están listos para ayudarte a crear la experiencia perfecta.</p>
  <div class="contact-grid">
    <div class="contact-form-wrap">
      <form id="formContacto">
        <div class="form-row">
          <div class="form-group">
            <label>Nombre completo</label>
            <input type="text" placeholder="Tu nombre" class="finput">
          </div>
          <div class="form-group">
            <label>Correo electrónico</label>
            <input type="email" placeholder="tu@email.com" class="finput">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Teléfono</label>
            <input type="tel" placeholder="+52 55 0000 0000" class="finput">
          </div>
          <div class="form-group">
            <label>Destino de interés</label>
            <select class="finput">
              <option>Seleccionar destino</option>
              <option>Caribe Mexicano</option>
              <option>Perú</option>
              <option>Patagonia</option>
              <option>Otro</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label>Fecha preferida de viaje</label>
          <input type="date" class="finput">
        </div>
        <div class="form-group">
          <label>Mensaje <span class="char-count">(0/500)</span></label>
          <textarea class="finput" rows="4" placeholder="Cuéntanos sobre tu viaje ideal..." maxlength="500"
            oninput="this.previousElementSibling.querySelector('.char-count').textContent='('+this.value.length+'/500)'"></textarea>
        </div>
        <button type="submit" class="btn-enviar"><i class="fas fa-paper-plane"></i> Enviar Consulta</button>
      </form>
    </div>
    <div class="contact-info-wrap">
      <div class="mapa-placeholder">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3762.661235874!2d-102.2915!3d21.8818!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjHCsDUyJzU0LjUiTiAxMDLCsDE3JzI5LjQiVw!5e0!3m2!1ses!2smx!4v1680000000000!5m2!1ses!2smx"
          width="100%" height="240" style="border:0;border-radius:1.2rem;" allowfullscreen loading="lazy">
        </iframe>
      </div>
      <div class="contact-datos">
        <div class="dato"><span class="dato-ico"><i class="fas fa-map-marker-alt"></i></span><div><small>Dirección</small><p>Aguascalientes, Ags. México</p></div></div>
        <div class="dato"><span class="dato-ico"><i class="fas fa-phone"></i></span><div><small>Teléfono</small><p>+52 449 000 0000</p></div></div>
        <div class="dato"><span class="dato-ico"><i class="fas fa-envelope"></i></span><div><small>Email</small><p>info@remolinostours.com</p></div></div>
        <div class="dato"><span class="dato-ico"><i class="fas fa-clock"></i></span><div><small>Horario</small><p>Lun–Vie 9:00–18:00 | Sáb 10:00–14:00</p></div></div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . "/componentes/footer/footer.php"; ?>

<script src="index.js"></script>
</body>
</html>
