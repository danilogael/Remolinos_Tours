<?php
session_start();
$nombre = $_SESSION['nombre'] ?? 'Viajero';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remolinos Tours - Cotización Moderna</title>
  
    <script src="https://cdn.tailwindcss.com"></script>
 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="planea.css">
    <link rel="stylesheet" href="/viaje/viaje/Viaje-APP/componentes/estilos/estilos_footer.css">
    <link rel="stylesheet" href="/viaje/viaje/Viaje-APP/componentes/estilos/header.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-indigo': '#3730a3',
                        'accent-emerald': '#10b981',
                        'input-border': '#e5e7eb',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
       body { font-family: 'Inter', sans-serif; min-height: 100vh; background: #f5f5f5; margin: 0; padding-top: 6rem; }
       header { position: fixed; top: 0; left: 0; width: 100%; z-index: 50; }
       .main-card-shadow { box-shadow: 0 8px 12px -3px rgba(0,0,0,0.08), 0 4px 6px -2px rgba(0,0,0,0.04); transition: box-shadow 0.3s ease; margin-top: 2rem; position: relative; z-index: 10; padding: 2rem; }
       input:focus, select:focus, textarea:focus { border-color: #10b981 !important; box-shadow: 0 0 0 1px #10b981 !important; }
       .custom-checkbox input[type="checkbox"], .custom-checkbox input[type="radio"] { appearance: none; -webkit-appearance: none; width: 1.15rem; height: 1.15rem; border: 2px solid #9ca3af; border-radius: 0.25rem; cursor: pointer; position: relative; }
       .custom-checkbox input[type="radio"] { border-radius: 50%; }
       .custom-checkbox input:checked { background-color: #10b981; border-color: #10b981; }
       .custom-checkbox input[type="checkbox"]:checked::after { content: '\2713'; color: white; font-size: 0.7rem; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); }
       .custom-checkbox input[type="radio"]:checked::after { content: ''; width: 0.4rem; height: 0.4rem; border-radius: 50%; background-color: white; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); }
       #contenedor_transporte, #contenedor_alojamiento, #contenedor_coche { transition: max-height 0.3s ease, opacity 0.3s ease; overflow: hidden; position: relative; }
       .section-title { margin-top: 2rem; margin-bottom: 1rem; font-weight: 800; color: #1f2937; border-bottom: 2px solid #e5e7eb; padding-bottom: 0.5rem; }
       #mensaje_estado { padding: 0.75rem; font-weight: 600; transition: all 0.3s ease; border-width: 2px; }
    </style>
</head>

<body class="flex flex-col min-h-screen font-sans bg-gray-100 pt-24"> 
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/viaje/viaje/Viaje-APP/componentes/header/header.php'); ?>
    <center>
    <div class="bg-white p-6 sm:p-12 rounded-xl w-full max-w-4xl border border-gray-200 main-card-shadow">
        
        <h1 class="text-4xl font-extrabold text-primary-indigo mb-2 tracking-tight">
            <i class="fas fa-plane"></i> Planea tu Próxima Aventura
        </h1>
        <p class="text-gray-500 mb-10 text-lg">Cuéntanos sobre el viaje que sueñas. Nuestro equipo te enviará la cotización ideal.</p>
        
        <div id="mensaje_estado" class="p-4 mb-8 rounded-lg text-sm font-semibold hidden transition-all duration-300 border" aria-live="polite"></div>

        <form id="cotizacion-viaje" class="space-y-8">
            
            <h2 class="text-2xl section-title">I. Información Clave del Viaje</h2>
            
            <div>
                <label for="destino" class="block text-sm font-medium text-gray-700 mb-1">Destino Principal <span class="text-red-500">*</span>:</label>
                <input type="text" id="destino" name="destino" required placeholder="Ej: Islas Griegas, Tokio y Osaka, Tour por la Toscana"
                       class="w-full p-3 border border-input-border rounded-lg transition duration-200">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="fecha_salida" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Salida <span class="text-red-500">*</span>:</label>
                    <input type="date" id="fecha_salida" name="fecha_salida" required
                           class="w-full p-3 border border-input-border rounded-lg transition duration-200">
                </div>
                <div>
                    <label for="fecha_regreso" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Regreso <span class="text-red-500">*</span>:</label>
                    <input type="date" id="fecha_regreso" name="fecha_regreso" required
                           class="w-full p-3 border border-input-border rounded-lg transition duration-200">
                </div>
                <div class="flex items-end">
                    <p id="duracion_calculada" class="text-sm text-gray-500 font-medium pb-1 w-full border-b border-dashed border-gray-300">Duración: 0 noches</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="num_adultos" class="block text-sm font-medium text-gray-700 mb-1">Adultos (Mayores de 12) <span class="text-red-500">*</span>:</label>
                    <input type="number" id="num_adultos" name="num_adultos" min="1" value="1" required
                           class="w-full p-3 border border-input-border rounded-lg transition duration-200">
                </div>
                <div>
                    <label for="num_ninos" class="block text-sm font-medium text-gray-700 mb-1">Niños (0-12 años):</label>
                    <input type="number" id="num_ninos" name="num_ninos" min="0" value="0"
                           class="w-full p-3 border border-input-border rounded-lg transition duration-200">
                </div>
            </div>

            <h2 class="text-2xl section-title">II. Servicios que Necesitas</h2>
            
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 custom-checkbox pt-2">
                <div class="flex items-center space-x-2 p-3 bg-white rounded-lg border border-gray-200 shadow-sm hover:border-accent-emerald transition duration-150">
                    <input type="checkbox" id="check_transporte" name="servicios[]" value="transporte" class="text-primary-indigo"> 
                    <label for="check_transporte" class="text-sm text-gray-700"><i class="fas fa-plane-departure mr-1 text-primary-indigo/80"></i> Transporte</label>
                </div>
                <div class="flex items-center space-x-2 p-3 bg-white rounded-lg border border-gray-200 shadow-sm hover:border-accent-emerald transition duration-150">
                    <input type="checkbox" id="check_alojamiento" name="servicios[]" value="alojamiento" class="text-primary-indigo"> 
                    <label for="check_alojamiento" class="text-sm text-gray-700"><i class="fas fa-bed mr-1 text-primary-indigo/80"></i> Alojamiento</label>
                </div>
                <div class="flex items-center space-x-2 p-3 bg-white rounded-lg border border-gray-200 shadow-sm hover:border-accent-emerald transition duration-150">
                    <input type="checkbox" id="check_coche" name="servicios[]" value="coche" class="text-primary-indigo"> 
                    <label for="check_coche" class="text-sm text-gray-700"><i class="fas fa-car-side mr-1 text-primary-indigo/80"></i> Coche</label>
                </div>
                <div class="flex items-center space-x-2 p-3 bg-white rounded-lg border border-gray-200 shadow-sm hover:border-accent-emerald transition duration-150">
                    <input type="checkbox" id="check_seguro" name="servicios[]" value="seguro" class="text-primary-indigo"> 
                    <label for="check_seguro" class="text-sm text-gray-700"><i class="fas fa-user-shield mr-1 text-primary-indigo/80"></i> Seguro</label>
                </div>
            </div>
            
            <div id="contenedor_transporte" class="p-6 bg-indigo-50 border-l-4 border-primary-indigo rounded-lg transition duration-300 hidden">
                <h3 class="text-lg font-bold mb-4 text-primary-indigo"><i class="fas fa-tags mr-2"></i> Opciones de Traslado</h3>
                <div class="flex flex-wrap gap-x-6 gap-y-3 mb-4 custom-checkbox">
                    <div class="flex items-center">
                        <input type="radio" id="transporte_avion" name="tipo_transporte" value="avion" checked class="text-primary-indigo">
                        <label for="transporte_avion" class="ml-2 text-sm text-gray-700">Vuelo Comercial</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="transporte_bus_privado" name="tipo_transporte" value="bus_privado" class="text-primary-indigo">
                        <label for="transporte_bus_privado" class="ml-2 text-sm text-gray-700">Autobús o Charter</label>
                    </div>
                </div>
                <div id="contenedor_clase_vuelo">
                    <label for="clase_vuelo" class="block text-sm font-medium text-gray-700 mb-1">Clase/Categoría:</label>
                    <select id="clase_vuelo" name="clase_vuelo" class="w-full p-2 border border-input-border rounded-lg">
                        <option value="economica">Económica</option>
                        <option value="premium">Premium Economy</option>
                        <option value="business">Business</option>
                        <option value="primera">Primera Clase</option>
                    </select>
                </div>
            </div>
            
            <div id="contenedor_alojamiento" class="p-6 bg-indigo-50 border-l-4 border-primary-indigo rounded-lg transition duration-300 hidden">
                <h3 class="text-lg font-bold mb-4 text-primary-indigo"><i class="fas fa-home mr-2"></i> Preferencias de Estancia</h3>
                <label for="tipo_alojamiento" class="block text-sm font-medium text-gray-700 mb-1">Tipo:</label>
                <select id="tipo_alojamiento" name="tipo_alojamiento" class="w-full p-2 border border-input-border rounded-lg">
                    <option value="">Selecciona...</option>
                    <option value="hotel">Hotel de 4-5 Estrellas</option>
                    <option value="resort">Resort / Todo Incluido</option>
                    <option value="apartamento">Apartamento / Airbnb</option>
                    <option value="otro">Boutique / Otro</option>
                </select>
                <div id="contenedor_nombre_hotel" class="mt-4 hidden">
                    <label for="nombre_hotel" class="block text-sm font-medium text-gray-700 mb-1">Nombre Deseado:</label>
                    <input type="text" id="nombre_hotel" name="nombre_hotel" placeholder="Ej: Hyatt, Hilton..."
                           class="w-full p-2 border border-input-border rounded-lg">
                </div>
            </div>
            
            <div id="contenedor_coche" class="p-6 bg-indigo-50 border-l-4 border-primary-indigo rounded-lg transition duration-300 hidden">
                <h3 class="text-lg font-bold mb-4 text-primary-indigo"><i class="fas fa-tachometer-alt mr-2"></i> Movilidad</h3>
                <label for="coche_personas" class="block text-sm font-medium text-gray-700 mb-1">Pasajeros:</label>
                <input type="number" id="coche_personas" name="coche_personas" min="1" value="1"
                       class="w-full p-2 border border-input-border rounded-lg">
                <label for="coche_modelo" class="block text-sm font-medium text-gray-700 mt-4 mb-1">Modelo:</label>
                <input type="text" id="coche_modelo" name="coche_modelo" placeholder="Ej: Económico, SUV"
                       class="w-full p-2 border border-input-border rounded-lg">
            </div>

            <h2 class="text-2xl section-title">III. Presupuesto y Detalles Adicionales</h2>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Presupuesto Estimado por el Viaje <span class="text-red-500">*</span>:</label>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex bg-gray-100 p-1 rounded-lg shrink-0 w-fit">
                        <label class="cursor-pointer">
                            <input type="radio" name="moneda" value="MXN" class="peer sr-only" checked>
                            <span class="block px-6 py-3 rounded-md text-sm font-bold text-gray-500 hover:text-gray-700 peer-checked:bg-white peer-checked:text-emerald-600 peer-checked:shadow-sm transition-all duration-200">
                                <i class="fas fa-dollar-sign mr-1"></i> MXN
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="moneda" value="USD" class="peer sr-only">
                            <span class="block px-6 py-3 rounded-md text-sm font-bold text-gray-500 hover:text-gray-700 peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm transition-all duration-200">
                                <i class="fas fa-dollar-sign mr-1"></i> USD
                            </span>
                        </label>
                    </div>

                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-money-bill-wave text-gray-400"></i>
                        </div>
                        <input type="number" id="presupuesto_cantidad" name="presupuesto_cantidad" required min="1" step="any"
                               class="w-full pl-10 p-3 border border-input-border rounded-lg transition duration-200 focus:ring-2 focus:ring-primary-indigo focus:border-transparent" 
                               placeholder="Ingresa la cantidad (Ej: 25000)">
                    </div>
                </div>
            </div>
            
            <div>
                <label for="comentarios_especiales" class="block text-sm font-medium text-gray-700 mb-1">Notas y Solicitudes Especiales:</label>
                <textarea id="comentarios_especiales" name="comentarios_especiales" rows="4" placeholder="Indica tours de interés, restricciones dietéticas, solicitudes de asientos..."
                          class="w-full p-3 border border-input-border rounded-lg transition duration-200"></textarea>
            </div>

            <button type="submit" id="enviar-cotizacion" 
                    class="w-full text-lg text-white font-extrabold py-3 px-4 rounded-lg transition duration-300 mt-8 
                           bg-primary-indigo hover:bg-indigo-800 shadow-lg shadow-indigo-300/50 hover:shadow-xl transform hover:scale-[1.005]">
                <i class="fas fa-magic mr-2"></i> Solicitar mi Viaje a la Medida
            </button>

        </form>

    </div>
    </center>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- VARIABLES ---
        const checkTransporte = document.getElementById('check_transporte');
        const checkAlojamiento = document.getElementById('check_alojamiento');
        const checkCoche = document.getElementById('check_coche');
        const contTransporte = document.getElementById('contenedor_transporte');
        const contAlojamiento = document.getElementById('contenedor_alojamiento');
        const contCoche = document.getElementById('contenedor_coche');
        const tipoTransporte = document.querySelectorAll('input[name="tipo_transporte"]');
        const contClaseVuelo = document.getElementById('contenedor_clase_vuelo');
        const tipoAlojamiento = document.getElementById('tipo_alojamiento');
        const contNombreHotel = document.getElementById('contenedor_nombre_hotel');
        const fechaSalida = document.getElementById('fecha_salida');
        const fechaRegreso = document.getElementById('fecha_regreso');
        const duracionParrafo = document.getElementById('duracion_calculada');
        const form = document.getElementById('cotizacion-viaje');
        const mensajeEstado = document.getElementById('mensaje_estado');

        // --- 0. BLOQUEO DE FECHAS (VISUAL + VALIDACIÓN AGRESIVA) ---
        const hoy = new Date();
        const yyyy = hoy.getFullYear();
        const mm = String(hoy.getMonth() + 1).padStart(2, '0');
        const dd = String(hoy.getDate()).padStart(2, '0');
        const fechaMinima = `${yyyy}-${mm}-${dd}`;

        // 1. Bloqueo visual (Calendario)
        fechaSalida.setAttribute('min', fechaMinima);
        fechaRegreso.setAttribute('min', fechaMinima);

        // 2. Validación Agresiva (Por si escriben manualmente)
        function validarNoPasado(input) {
            if (!input.value) return; // Si está vacío no hacemos nada

            const fechaSeleccionada = new Date(input.value + 'T00:00:00'); // Ajuste de zona horaria
            const fechaHoy = new Date();
            fechaHoy.setHours(0,0,0,0); // Quitamos la hora para comparar solo días

            if (fechaSeleccionada < fechaHoy) {
                Swal.fire({
                    icon: 'error',
                    title: '¡Fecha Incorrecta!',
                    text: 'No puedes planear viajes al pasado. Por favor selecciona una fecha futura.',
                    confirmButtonColor: '#3730a3'
                });
                input.value = ''; // BORRAMOS EL DATO MALO
                calcularDuracion();
            }
        }

        // Ejecutar validación cuando el usuario cambia el dato o sale del campo
        fechaSalida.addEventListener('change', function() {
            validarNoPasado(this);
            // Ajustar el mínimo del regreso
            if (this.value) {
                fechaRegreso.setAttribute('min', this.value);
                if(fechaRegreso.value && fechaRegreso.value < this.value) {
                    fechaRegreso.value = this.value;
                }
            }
            calcularDuracion();
        });

        fechaRegreso.addEventListener('change', function() {
            validarNoPasado(this);
            // Validación extra: Regreso no puede ser antes de Salida
            if (fechaSalida.value && this.value < fechaSalida.value) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error en fechas',
                    text: 'La fecha de regreso no puede ser antes de la salida.',
                });
                this.value = '';
            }
            calcularDuracion();
        });


        // --- 1. LÓGICA DE RECUPERACIÓN (EL TRUCO) ---
        const datosGuardados = localStorage.getItem('borrador_cotizacion');
        const urlParams = new URLSearchParams(window.location.search);
        
        if (urlParams.get('reserva_pendiente') === 'si') {
            Swal.fire({
                icon: 'success',
                title: '¡Sesión Iniciada!',
                text: `Hola ${"<?php echo $nombre; ?>"}, hemos recuperado tus datos. Ya puedes enviar la cotización.`,
                confirmButtonText: 'Entendido'
            });

            if (datosGuardados) {
                const datos = JSON.parse(datosGuardados);
                document.getElementById('destino').value = datos.destino || '';
                document.getElementById('fecha_salida').value = datos.fecha_salida || '';
                
                // Aplicar restricción al recuperar
                if(datos.fecha_salida) {
                    fechaRegreso.setAttribute('min', datos.fecha_salida);
                }

                document.getElementById('fecha_regreso').value = datos.fecha_regreso || '';
                document.getElementById('num_adultos').value = datos.num_adultos || 1;
                document.getElementById('comentarios_especiales').value = datos.comentarios_especiales || '';
                
                if (datos.presupuesto_cantidad) {
                    document.getElementById('presupuesto_cantidad').value = datos.presupuesto_cantidad;
                }
                if (datos.moneda) {
                    const radioMoneda = document.querySelector(`input[name="moneda"][value="${datos.moneda}"]`);
                    if (radioMoneda) radioMoneda.checked = true;
                }

                calcularDuracion();
            }
        }

        // --- FUNCIONES VISUALES ---
        function toggleContainer(checkbox, container) {
            container.classList.toggle('hidden', !checkbox.checked);
        }

        checkTransporte.addEventListener('change', () => {
            toggleContainer(checkTransporte, contTransporte);
            if (checkTransporte.checked) {
                const isAvion = document.querySelector('input[name="tipo_transporte"][value="avion"]').checked;
                contClaseVuelo.classList.toggle('hidden', !isAvion);
            } else {
                contClaseVuelo.classList.add('hidden');
            }
        });

        checkAlojamiento.addEventListener('change', () => {
            toggleContainer(checkAlojamiento, contAlojamiento);
            if (!checkAlojamiento.checked) contNombreHotel.classList.add('hidden');
        });

        checkCoche.addEventListener('change', () => toggleContainer(checkCoche, contCoche));

        tipoTransporte.forEach(radio => {
            radio.addEventListener('change', function() {
                contClaseVuelo.classList.toggle('hidden', this.value !== 'avion');
            });
        });

        tipoAlojamiento.addEventListener('change', function() {
            contNombreHotel.classList.toggle('hidden', this.value !== 'hotel');
        });

        function calcularDuracion() {
            const salida = fechaSalida.value;
            const regreso = fechaRegreso.value;

            if (salida && regreso) {
                const date1 = new Date(salida);
                const date2 = new Date(regreso);
                const diffTime = date2 - date1;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                
                let texto = "Duración: ";
                if (diffDays > 0 && date2 >= date1) {
                    const nights = diffDays - 1; 
                    texto += `${diffDays} días (${nights} noches)`; 
                    duracionParrafo.classList.remove('text-red-500');
                    duracionParrafo.classList.add('text-gray-500');
                } else if (diffDays === 0 && date2.toDateString() === date1.toDateString()) {
                    texto += `1 día (Ida y vuelta)`;
                    duracionParrafo.classList.remove('text-red-500');
                    duracionParrafo.classList.add('text-gray-500');
                } else {
                    texto += `Fechas Inválidas`;
                    duracionParrafo.classList.add('text-red-500');
                    duracionParrafo.classList.remove('text-gray-500');
                }
                duracionParrafo.textContent = texto;
            } else {
                duracionParrafo.textContent = "Duración: 0 noches";
                duracionParrafo.classList.remove('text-red-500');
                duracionParrafo.classList.add('text-gray-500');
            }
        }

        // --- ENVÍO A API ---
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // ULTIMA VERIFICACIÓN DE FECHAS AL ENVIAR
            const fSalida = new Date(fechaSalida.value + 'T00:00:00');
            const fHoy = new Date();
            fHoy.setHours(0,0,0,0);
            
            if (fSalida < fHoy) {
                 Swal.fire({icon: 'error', title: 'Fecha Inválida', text: 'La fecha de salida es del pasado.'});
                 return;
            }

            // 1. Verificación de sesión PHP
            const usuarioLogueado = <?= isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
            
            if (!usuarioLogueado) {
                const datosBorrador = {
                    destino: document.getElementById('destino').value,
                    fecha_salida: document.getElementById('fecha_salida').value,
                    fecha_regreso: document.getElementById('fecha_regreso').value,
                    num_adultos: document.getElementById('num_adultos').value,
                    comentarios_especiales: document.getElementById('comentarios_especiales').value,
                    presupuesto_cantidad: document.getElementById('presupuesto_cantidad').value,
                    moneda: document.querySelector('input[name="moneda"]:checked').value
                };
                localStorage.setItem('borrador_cotizacion', JSON.stringify(datosBorrador));

                Swal.fire({
                    icon: 'warning',
                    title: 'Inicia sesión para cotizar',
                    text: 'Necesitamos tu cuenta para enviarte la propuesta. Tus datos se guardarán.',
                    showCancelButton: true,
                    confirmButtonText: 'Iniciar Sesión',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "/viaje/viaje/Viaje-APP/componentes/iniciarsesion/sign.php?origen=cotizacion";
                    }
                });
                return;
            }

            localStorage.removeItem('borrador_cotizacion');
            
            const apiURL = '/viaje/viaje/LoginAPI/planea_cotizacion.php';
            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => {
                if (key.endsWith('[]')) {
                    const baseKey = key.slice(0, -2);
                    if (!data[baseKey]) data[baseKey] = [];
                    data[baseKey].push(value);
                } else {
                    data[key] = value;
                }
            });

            try {
                mostrarMensaje('⏳ Procesando tu cotización...', 'info');
                const respuesta = await fetch(apiURL, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(data)
                });
                const resultado = await respuesta.json();

                if (respuesta.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Enviado!',
                        text: resultado.mensaje || 'Tu cotización ha sido enviada correctamente y en seguida recibirás varias propuestas personalizadas.',
                    });
                    mensajeEstado.classList.add('hidden');
                    form.reset();
                    // Resetear validaciones visuales
                    fechaSalida.setAttribute('min', fechaMinima);
                    fechaRegreso.setAttribute('min', fechaMinima);
                    calcularDuracion();
                    contTransporte.classList.add('hidden');
                    contAlojamiento.classList.add('hidden');
                    contCoche.classList.add('hidden');
                } else {
                    mostrarMensaje(`❌ Error: ${resultado.mensaje}`, 'error');
                }
            } catch (error) {
                console.error(error);
                mostrarMensaje('❌ Error de conexión al servidor.', 'error');
            }
        });

        function mostrarMensaje(texto, tipo) {
            mensajeEstado.textContent = texto;
            mensajeEstado.classList.remove('hidden', 'bg-red-50','text-red-800','border-red-400','bg-green-50','text-green-800','border-green-400','bg-indigo-50','text-indigo-800','border-indigo-400');
            if (tipo === 'error') {
                mensajeEstado.classList.add('bg-red-50','text-red-800','border-red-400','font-bold');
            } else if (tipo === 'success') {
                mensajeEstado.classList.add('bg-green-50','text-green-800','border-green-400','font-bold');
            } else {
                mensajeEstado.classList.add('bg-indigo-50','text-indigo-800','border-indigo-400','font-bold');
            }
        }
    });
    </script>
    
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/viaje/viaje/Viaje-APP/componentes/footer/footer.php"); ?>

</body>
</html>