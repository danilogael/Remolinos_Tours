-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-04-2026 a las 20:26:54
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `remolino_tours`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades_extra`
--

CREATE TABLE `actividades_extra` (
  `id` int(11) NOT NULL,
  `id_destino` int(11) NOT NULL,
  `nombre_actividad` varchar(200) NOT NULL,
  `precio_extra` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actividades_extra`
--

INSERT INTO `actividades_extra` (`id`, `id_destino`, `nombre_actividad`, `precio_extra`) VALUES
(1, 1, 'Snorkel en arrecife', 800.00),
(2, 1, 'Tour en catamarán', 1200.00),
(3, 3, 'Whale watching', 1500.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `destinos`
--

CREATE TABLE `destinos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Precio adulto',
  `precio_nino` decimal(10,2) NOT NULL DEFAULT 0.00,
  `id_proveedor` int(11) DEFAULT NULL,
  `foto_portada` varchar(255) DEFAULT 'default.png',
  `tipo_trayecto` varchar(50) NOT NULL DEFAULT 'Redondo',
  `cupo_total` int(11) NOT NULL DEFAULT 20,
  `punto_salida` varchar(100) DEFAULT NULL,
  `maleta_mano_kg` int(11) NOT NULL DEFAULT 10,
  `maleta_documentada_kg` int(11) NOT NULL DEFAULT 25,
  `seguro_basico_incluido` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_salida` date DEFAULT NULL,
  `dias` int(11) NOT NULL DEFAULT 1,
  `noches` int(11) NOT NULL DEFAULT 0,
  `estado` enum('Activo','Inactivo','Agotado') NOT NULL DEFAULT 'Activo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `destinos`
--

INSERT INTO `destinos` (`id`, `nombre`, `descripcion`, `precio`, `precio_nino`, `id_proveedor`, `foto_portada`, `tipo_trayecto`, `cupo_total`, `punto_salida`, `maleta_mano_kg`, `maleta_documentada_kg`, `seguro_basico_incluido`, `fecha_salida`, `dias`, `noches`, `estado`, `created_at`, `activo`) VALUES
(1, 'Cancún All Inclusive', 'Resort 5 estrellas frente al mar Caribe.', 15000.00, 8000.00, 3, 'default.png', 'Redondo', 30, 'Aguascalientes', 10, 25, 0, '2026-07-01', 7, 6, 'Activo', '2026-04-28 20:49:45', 1),
(2, 'Ciudad de México Tour', 'Recorrido histórico y gastronómico por la CDMX.', 4500.00, 2500.00, 1, 'default.png', 'Solo ida', 20, 'Aguascalientes', 10, 25, 0, '2026-06-15', 3, 2, 'Activo', '2026-04-28 20:49:45', 1),
(3, 'Los Cabos Relax', 'Playas exclusivas y deportes acuáticos.', 18000.00, 9000.00, 2, 'default.png', 'Redondo', 25, 'Aguascalientes', 10, 25, 0, '2026-08-10', 5, 4, 'Activo', '2026-04-28 20:49:45', 1),
(4, 'Por hollywood', 'Increible viaje a hollywood', 2000.00, 1200.00, NULL, 'dest_69f18f937da13.jpg', 'Solo ida', 20, 'Guadalajara', 10, 25, 1, '2026-05-15', 4, 5, 'Activo', '2026-04-29 04:56:51', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `itinerarios`
--

CREATE TABLE `itinerarios` (
  `id` int(11) NOT NULL,
  `id_destino` int(11) NOT NULL,
  `dia_numero` int(11) NOT NULL,
  `titulo_actividad` varchar(200) NOT NULL,
  `descripcion_actividad` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `itinerarios`
--

INSERT INTO `itinerarios` (`id`, `id_destino`, `dia_numero`, `titulo_actividad`, `descripcion_actividad`) VALUES
(1, 1, 1, 'Llegada y bienvenida', 'Traslado al hotel, check-in y cena de bienvenida.'),
(2, 1, 2, 'Zona Hotelera', 'Día libre en la playa y actividades acuáticas.'),
(3, 1, 3, 'Xcaret Park', 'Visita al parque eco-arqueológico Xcaret.'),
(4, 2, 1, 'Centro Histórico', 'Recorrido por el Zócalo, Palacio Nacional y Catedral.'),
(5, 2, 2, 'Teotihuacán y Xochimilco', 'Pirámides del Sol y la Luna, trajineras en Xochimilco.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id_proveedor` int(11) NOT NULL,
  `nombre_proveedor` varchar(150) NOT NULL,
  `tipo_proveedor` varchar(100) NOT NULL COMMENT 'Aerolínea, Hotel, Crucero, etc.',
  `contacto` varchar(150) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id_proveedor`, `nombre_proveedor`, `tipo_proveedor`, `contacto`, `telefono`, `email`) VALUES
(1, 'Volaris', 'Aerolínea', 'Ana Torres', '55-1000-2000', 'ana@volaris.com'),
(2, 'Aeromexico', 'Aerolínea', 'Luis Ruiz', '55-3000-4000', 'luis@aeromexico.com'),
(3, 'Marriott Cancún', 'Hotel', 'Sara Vega', '998-100-2000', 'sara@marriott.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `folio` varchar(20) NOT NULL COMMENT 'Ej: RT-2026-00001',
  `id_destino` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre_cliente` varchar(150) DEFAULT NULL COMMENT 'Nombre del viajero principal',
  `telefono_cliente` varchar(30) DEFAULT NULL,
  `adultos` int(11) NOT NULL DEFAULT 1,
  `ninos` int(11) NOT NULL DEFAULT 0,
  `num_adultos` int(11) GENERATED ALWAYS AS (`adultos`) VIRTUAL COMMENT 'Alias para compatibilidad',
  `num_ninos` int(11) GENERATED ALWAYS AS (`ninos`) VIRTUAL COMMENT 'Alias para compatibilidad',
  `fecha_salida` date DEFAULT NULL,
  `fecha_regreso` date DEFAULT NULL,
  `fecha_reserva` timestamp NOT NULL DEFAULT current_timestamp(),
  `solicitudes` text DEFAULT NULL COMMENT 'Peticiones especiales del cliente',
  `precio_por_persona` decimal(10,2) NOT NULL DEFAULT 0.00,
  `descuento_ninos` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_pago` decimal(10,2) NOT NULL DEFAULT 0.00,
  `metodo_contacto` varchar(50) DEFAULT NULL COMMENT 'WhatsApp, Email, Llamada',
  `estado` enum('Pendiente','Confirmada','Cancelada') NOT NULL DEFAULT 'Pendiente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `folio`, `id_destino`, `id_usuario`, `nombre_cliente`, `telefono_cliente`, `adultos`, `ninos`, `fecha_salida`, `fecha_regreso`, `fecha_reserva`, `solicitudes`, `precio_por_persona`, `descuento_ninos`, `total_pago`, `metodo_contacto`, `estado`, `created_at`) VALUES
(1, 'RT-2026-00001', 1, 2, 'Carlos López', NULL, 2, 1, '2026-07-01', NULL, '2026-04-28 20:49:45', NULL, 15000.00, 3500.00, 26500.00, 'WhatsApp', 'Confirmada', '2026-04-28 20:49:45'),
(2, 'RT-2026-00002', 3, 3, 'María González', NULL, 2, 0, '2026-08-10', NULL, '2026-04-28 20:49:45', NULL, 18000.00, 0.00, 36000.00, 'Email', 'Pendiente', '2026-04-28 20:49:45'),
(3, 'RT-2026-00003', 2, 2, 'Carlos López', NULL, 1, 0, '2026-06-15', NULL, '2026-04-28 20:49:45', NULL, 4500.00, 0.00, 4500.00, 'Llamada', 'Cancelada', '2026-04-28 20:49:45'),
(4, 'RT-2026-00004', 2, 4, 'Danilo Gael Muñoz Mejia', '+525584140521', 1, 2, '2026-04-30', '2026-05-07', '2026-04-29 04:52:55', '', 4500.00, 2250.00, 9000.00, 'whatsapp', 'Confirmada', '2026-04-29 04:52:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(150) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','cliente') NOT NULL DEFAULT 'cliente',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_completo`, `fecha_nacimiento`, `email`, `telefono`, `password`, `rol`, `activo`, `fecha_registro`) VALUES
(1, 'Administrador', NULL, 'admin@remolinostours.com', '449-000-0001', '$2y$10$GB9vcZWruAXO2Yt62jVCZ.r8yJ9cgvW05BXHQ090SheXsYjqxm2ny', 'admin', 1, '2026-04-28 20:49:45'),
(2, 'Carlos López', NULL, 'carlos@email.com', '449-111-2233', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente', 1, '2026-04-28 20:49:45'),
(3, 'María González', NULL, 'maria@email.com', '449-444-5566', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente', 1, '2026-04-28 20:49:45'),
(4, 'Danilo Gael Muñoz Mejia', '2008-04-09', 'daniel09061604@gmail.com', '+525584140521', '$2y$10$9/wwlMR3GrEILIwV8o8Ncu6e2jODCcUgsMtpVU7mlhx.ih2iTG6He', 'cliente', 1, '2026-04-28 20:53:13');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades_extra`
--
ALTER TABLE `actividades_extra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_extra_destino` (`id_destino`);

--
-- Indices de la tabla `destinos`
--
ALTER TABLE `destinos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_destino_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `itinerarios`
--
ALTER TABLE `itinerarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_itinerario_destino` (`id_destino`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `folio` (`folio`),
  ADD KEY `fk_reserva_destino` (`id_destino`),
  ADD KEY `fk_reserva_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades_extra`
--
ALTER TABLE `actividades_extra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `destinos`
--
ALTER TABLE `destinos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `itinerarios`
--
ALTER TABLE `itinerarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades_extra`
--
ALTER TABLE `actividades_extra`
  ADD CONSTRAINT `fk_extra_destino` FOREIGN KEY (`id_destino`) REFERENCES `destinos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `destinos`
--
ALTER TABLE `destinos`
  ADD CONSTRAINT `fk_destino_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `itinerarios`
--
ALTER TABLE `itinerarios`
  ADD CONSTRAINT `fk_itinerario_destino` FOREIGN KEY (`id_destino`) REFERENCES `destinos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_reserva_destino` FOREIGN KEY (`id_destino`) REFERENCES `destinos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reserva_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

--
-- Mejoras agregadas: ofertas, limites de viajeros y noticias
--

ALTER TABLE `destinos`
  ADD COLUMN IF NOT EXISTS `es_oferta` tinyint(1) NOT NULL DEFAULT 0 AFTER `activo`,
  ADD COLUMN IF NOT EXISTS `oferta_titulo` varchar(120) DEFAULT NULL AFTER `es_oferta`,
  ADD COLUMN IF NOT EXISTS `precio_oferta` decimal(10,2) DEFAULT NULL AFTER `oferta_titulo`,
  ADD COLUMN IF NOT EXISTS `oferta_inicio` date DEFAULT NULL AFTER `precio_oferta`,
  ADD COLUMN IF NOT EXISTS `oferta_fin` date DEFAULT NULL AFTER `oferta_inicio`,
  ADD COLUMN IF NOT EXISTS `permite_ninos` tinyint(1) NOT NULL DEFAULT 1 AFTER `oferta_fin`,
  ADD COLUMN IF NOT EXISTS `min_adultos` int(11) NOT NULL DEFAULT 1 AFTER `permite_ninos`,
  ADD COLUMN IF NOT EXISTS `max_adultos` int(11) NOT NULL DEFAULT 10 AFTER `min_adultos`,
  ADD COLUMN IF NOT EXISTS `max_ninos` int(11) NOT NULL DEFAULT 6 AFTER `max_adultos`,
  ADD COLUMN IF NOT EXISTS `tipo_cupo` enum('flexible','fijo') NOT NULL DEFAULT 'flexible' AFTER `max_ninos`;

CREATE TABLE IF NOT EXISTS `noticias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(160) NOT NULL,
  `resumen` varchar(255) DEFAULT NULL,
  `contenido` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT 'default.png',
  `categoria` enum('aviso','promocion','recomendacion','destino','comunicado') NOT NULL DEFAULT 'aviso',
  `estado` enum('borrador','publicado') NOT NULL DEFAULT 'borrador',
  `fecha_publicacion` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
