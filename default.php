<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Remolinos Tours - Inicio</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="/viaje/viaje/Viaje-APP/componentes/estilos/header.css">
  <link rel="stylesheet" href="/viaje/viaje/Viaje-APP/componentes/estilos/estilos_footer.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="index.js"></script>
</head>
<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/viaje/viaje/Viaje-APP/componentes/header/header.php"); ?>

<section class="home" id="home">
    <div class="content">
        <h3><span>Remolinos</span> Tours</h3>
        <span>Agencia de Viajes</span>
        <p>Agencia de viajes hidrocalida que se interesa por hacer de tus viajes una experiencia inolvidable, brindando seguridad en tu compra!
contamos con Registro nacional de turismo vigente!.</p>
        <a href="/viaje/viaje/Viaje-APP/componentes/paquetes/paquete.php" class="btn">Descubre </a>
    </div>
</section>
    
<section class="about" id="about">
    <h1 class="heading"><span>Conocenos</span></h1>
<div class="row">
    <div class="video-container">
        <img src="/viaje/viaje/Viaje-APP/imagenes/barco.jpg" alt="Barco">
        <h3>Remolinos Tours</h3>
    </div>
    <div class="content">
        <h3>Por qué elegirnos?</h3>
        <p>En Remolinos Tours buscamos que cada viaje se sienta ligero y sin complicaciones. Ofrecemos opciones accesibles, información clara y acompañamiento en todo momento, para que las personas disfruten más y se preocupen menos. Nuestro objetivo es que cada experiencia se convierta en un buen recuerdo y no solo en un simple traslado..</p>
        
        <a href="#" class="btn">leer más </a>
</div>
</section>
<section class="icons-container">
    <div class="icons">
       <img src="/viaje/viaje/Viaje-APP/imagenes/seguro_viaje.png" alt="">
        <div class="info">
            <h3>Viaje Seguro</h3>
            <span>Contamos con seguro de viaje.</span>
        </div>
    </div>
    <div class="icons">
       <img src="/viaje/viaje/Viaje-APP/imagenes/devolucion.png" alt="">
        <div class="info">
            <h3>Devolucion/Garantia  </h3>
            <span>Cancelacion flexible, Garantia de reembolso</span>
        </div>
    </div>
    <div class="icons">
       <img src="/viaje/viaje/Viaje-APP/imagenes/support-removebg-preview.png" alt="">
        <div class="info">
            <h3> Soporte 24/7</h3>
            <span>Estamos aquí para ayudarte</span>
        </div>
</div>
<div class="icons">
       <img src="/viaje/viaje/Viaje-APP/imagenes/metodos_pago.png" alt="">
        <div class="info">
            <h3>Pagos/ Metodos de pago </h3>
            <span>Contamos con diversos métodos de pago seguros</span>
        </div>
</div>
    </div>
</section>

<section class="products" id="products">
    <h1 class="heading"><span>Ofertas</span></h1>

    <div class="promo-box" style="
        width:90%;
        max-width:900px;
        margin:0 auto;
        padding:0;
        border:1px solid #dcdcdc;
        border-radius:12px;
        background:#fff;
        display:flex;
        justify-content:space-between;
        overflow:hidden;
        margin-top:30px;
    ">
        
        <!-- imagen -->
        <div style="width:50%; height:220px; overflow:hidden;">
            <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e"
            style="width:100%; height:100%; object-fit:cover;">
        </div>

        <!-- texto + botón -->
        <div style="width:50%; padding:25px; text-align:left;">
            
            <h2 style="
                font-size:2rem;
                font-weight:600;
                margin-bottom:10px;
                color:#222;
            ">
                Promociones Especiales
            </h2>

            <p style="
                font-size:1.5rem;
                color:#555;
                margin-bottom:25px;
                line-height:1.5;
            ">
                Descubre destinos con precios únicos.<br>
                Viaja sin complicarte.
            </p>

            <a href="/viaje/viaje/Viaje-APP/componentes/ofertas/ofertas.php"
style="
    display:inline-block;
    padding:14px 38px;
    border-radius:28px;
    background:#066cff;
    color:#fff;
    text-decoration:none;
    font-size:1.05rem;
    font-weight:500;
">
    Ver Ofertas
</a>


        </div>
    </div>
</section>



<section class="reviews" id="reviews">
    <h1 class="heading"><span>Lo que dicen nuestros viajeros</span></h1>
    <div class="box-container">
        
        <div class="box">
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            <p>
                "El paquete a Cancún fue increíble. El hotel 'Todo Incluido' superó nuestras expectativas y los traslados fueron puntuales. La atención del equipo de Remolinos Tours es de 5 estrellas. ¡Volveremos a viajar con ustedes!"
            </p>
            <div class="user">
                <img src="/viaje/viaje/Viaje-APP/imagenes/persona1.jpg" alt="Foto de María P.">
                <div class="user-info"> 
                    <h3>María P.</h3>
                    <span>Viaje a Cancún</span>
                </div>
            </div> 
            <span class="fas fa-quote-right"></span>
        </div>
        
        <div class="box">
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i> </div>
            <p>
                "La ruta por la Toscana que nos armaron fue mágica. Hubo un pequeño retraso con el tren en Florencia, pero el soporte 24/7 lo solucionó al instante. ¡Servicio impecable y destinos soñados!"
            </p>
            <div class="user">
                <img src="/viaje/viaje/Viaje-APP/imagenes/persona2.jpg" alt="Foto de Raúl G.">
                <div class="user-info"> 
                    <h3>Raúl G.</h3>
                    <span>Tour por Italia</span>
                </div>
            </div> 
            <span class="fas fa-quote-right"></span>
        </div>
        
        <div class="box">
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="far fa-star"></i> </div>
            <p>
                "Mi primer viaje a Japón fue inolvidable. El itinerario estaba muy bien organizado, aunque nos hubiera gustado tener más tiempo libre en Kioto. Los guías locales que nos recomendaron eran expertos."
            </p>
            <div class="user">
                <img src="/viaje/viaje/Viaje-APP/imagenes/persona3.jpg" alt="Foto de Susana M.">
                <div class="user-info"> 
                    <h3>Susana M.</h3>
                    <span>Viaje a Japón</span>
                </div>
            </div> 
            <span class="fas fa-quote-right"></span>
        </div>
        
        <div class="box">
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            <p>
                "¡El mejor fin de semana en Puerto Vallarta! El precio de la oferta era inigualable y todo fue transparente. Desde el vuelo hasta el check-out, cero problemas. ¡Muy recomendable Remolinos Tours!"
            </p>
            <div class="user">
                <img src="/viaje/viaje/Viaje-APP/imagenes/persona4.jpg" alt="Foto de Jorge R.">
                <div class="user-info"> 
                    <h3>Jorge R.</h3>
                    <span>Escapada a Vallarta</span>
                </div>
            </div> 
            <span class="fas fa-quote-right"></span>
        </div>
        
    </div>
    <center>
        <a href="/viaje/viaje/Viaje-APP/componentes/reseñas/reseñas.php" class="btn">
            Ver todas las Reseñas
        </a>
    </center>
</section>

<section class="contact" id="contact">

    <h1 class="heading"><span>contactanos</span> </h1>

    <div class="row">

        <form id="formContacto">
            <input type="text" name="nombre" placeholder="nombre completo" class="box">
            <input type="email" name="correo" placeholder="correo electrónico" class="box">
            <input type="tel" name="numero" placeholder="número" class="box">
            <textarea name="mensaje" placeholder="mensaje" class="box" cols="30" rows="10"></textarea>
            <input type="submit" value="enviar mensaje" class="btn">
        </form>

        <div class="image"></div>

    </div>

</section>


<?php include($_SERVER['DOCUMENT_ROOT'] . "/viaje/viaje/Viaje-APP/componentes/footer/footer.php"); ?>
<script src="index.js"></script>
</body>
</html>
