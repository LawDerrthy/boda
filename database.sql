-- Script SQL para crear la base de datos y tabla de archivos
-- Ejecuta este script en tu servidor MySQL antes de usar la aplicación

-- Crear base de datos (ajusta el nombre si lo deseas)
CREATE DATABASE IF NOT EXISTS mi_boda CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Usar la base de datos
USE mi_boda;

-- Crear tabla para almacenar información de archivos
CREATE TABLE IF NOT EXISTS `fotos y videos` (
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

