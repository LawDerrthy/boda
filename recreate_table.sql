-- Script para eliminar y recrear la tabla 'fotos y videos' con la estructura correcta
-- CUIDADO: Esto eliminará todos los datos de la tabla
-- Ejecuta este script en phpMyAdmin en la pestaña SQL

-- Eliminar la tabla existente
DROP TABLE IF EXISTS `fotos y videos`;

-- Crear la tabla con la estructura correcta
CREATE TABLE `fotos y videos` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_original VARCHAR(255) NOT NULL,
    nombre_guardado VARCHAR(255) NOT NULL,
    ruta_archivo VARCHAR(500) NOT NULL,
    tipo_archivo VARCHAR(100) NOT NULL,
    tamano_archivo BIGINT NOT NULL,
    fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_tipo (tipo_archivo),
    INDEX idx_fecha (fecha_subida)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

