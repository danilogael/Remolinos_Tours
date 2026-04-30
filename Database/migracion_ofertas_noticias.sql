-- Mejoras para paquetes, ofertas y noticias.
-- Ejecutar en phpMyAdmin o con mysql sobre la base remolino_tours.

ALTER TABLE destinos
    ADD COLUMN IF NOT EXISTS es_oferta TINYINT(1) NOT NULL DEFAULT 0 AFTER activo,
    ADD COLUMN IF NOT EXISTS oferta_titulo VARCHAR(120) NULL AFTER es_oferta,
    ADD COLUMN IF NOT EXISTS precio_oferta DECIMAL(10,2) NULL AFTER oferta_titulo,
    ADD COLUMN IF NOT EXISTS oferta_inicio DATE NULL AFTER precio_oferta,
    ADD COLUMN IF NOT EXISTS oferta_fin DATE NULL AFTER oferta_inicio,
    ADD COLUMN IF NOT EXISTS permite_ninos TINYINT(1) NOT NULL DEFAULT 1 AFTER oferta_fin,
    ADD COLUMN IF NOT EXISTS min_adultos INT NOT NULL DEFAULT 1 AFTER permite_ninos,
    ADD COLUMN IF NOT EXISTS max_adultos INT NOT NULL DEFAULT 10 AFTER min_adultos,
    ADD COLUMN IF NOT EXISTS max_ninos INT NOT NULL DEFAULT 6 AFTER max_adultos,
    ADD COLUMN IF NOT EXISTS tipo_cupo ENUM('flexible','fijo') NOT NULL DEFAULT 'flexible' AFTER max_ninos;

CREATE TABLE IF NOT EXISTS noticias (
    id INT NOT NULL AUTO_INCREMENT,
    titulo VARCHAR(160) NOT NULL,
    resumen VARCHAR(255) DEFAULT NULL,
    contenido TEXT DEFAULT NULL,
    imagen VARCHAR(255) DEFAULT 'default.png',
    categoria ENUM('aviso','promocion','recomendacion','destino','comunicado') NOT NULL DEFAULT 'aviso',
    estado ENUM('borrador','publicado') NOT NULL DEFAULT 'borrador',
    fecha_publicacion DATE DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
