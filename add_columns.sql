-- Script para agregar las columnas necesarias a la tabla 'fotos y videos'
-- Ejecuta este script en phpMyAdmin en la pestaña SQL

-- Primero, agregar la columna id como PRIMARY KEY
ALTER TABLE `fotos y videos` 
ADD COLUMN id INT AUTO_INCREMENT PRIMARY KEY FIRST;

-- Agregar las demás columnas después de id
ALTER TABLE `fotos y videos` 
ADD COLUMN nombre_original VARCHAR(255) NOT NULL AFTER id,
ADD COLUMN nombre_guardado VARCHAR(255) NOT NULL AFTER nombre_original,
ADD COLUMN ruta_archivo VARCHAR(500) NOT NULL AFTER nombre_guardado,
ADD COLUMN tipo_archivo VARCHAR(100) NOT NULL AFTER ruta_archivo,
ADD COLUMN tamano_archivo BIGINT NOT NULL AFTER tipo_archivo,
ADD COLUMN fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP AFTER tamano_archivo;

-- Agregar índices para mejorar el rendimiento
ALTER TABLE `fotos y videos` 
ADD INDEX idx_tipo (tipo_archivo),
ADD INDEX idx_fecha (fecha_subida);

-- Si quieres mantener la columna foto_boda, déjala
-- Si NO la necesitas, puedes eliminarla con esta línea (descomenta si quieres):
-- ALTER TABLE `fotos y videos` DROP COLUMN foto_boda;

