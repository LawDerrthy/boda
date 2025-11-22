<?php
/**
 * Script para verificar y corregir la estructura de la tabla 'fotos y videos'
 * Ejecuta este archivo en el navegador para verificar y corregir la tabla
 */

require_once 'config.php';

echo "<h2>Verificación y Corrección de la Tabla 'fotos y videos'</h2>";

try {
    $conn = conectarMySQL();
    
    // Verificar si la tabla existe
    $result = $conn->query("SHOW TABLES LIKE 'fotos y videos'");
    
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✓ La tabla 'fotos y videos' existe</p>";
        
        // Obtener estructura actual de la tabla
        echo "<h3>Estructura actual de la tabla:</h3>";
        $result = $conn->query("DESCRIBE `fotos y videos`");
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        $columnasExistentes = [];
        while ($row = $result->fetch_assoc()) {
            $columnasExistentes[] = $row['Field'];
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Columnas requeridas
        $columnasRequeridas = [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'nombre_original' => 'VARCHAR(255) NOT NULL',
            'nombre_guardado' => 'VARCHAR(255) NOT NULL',
            'ruta_archivo' => 'VARCHAR(500) NOT NULL',
            'tipo_archivo' => 'VARCHAR(100) NOT NULL',
            'tamano_archivo' => 'BIGINT NOT NULL',
            'fecha_subida' => 'DATETIME DEFAULT CURRENT_TIMESTAMP'
        ];
        
        echo "<h3>Verificando columnas requeridas:</h3>";
        $faltanColumnas = false;
        
        foreach ($columnasRequeridas as $columna => $definicion) {
            if (in_array($columna, $columnasExistentes)) {
                echo "<p style='color: green;'>✓ Columna '$columna' existe</p>";
            } else {
                echo "<p style='color: red;'>✗ Columna '$columna' NO existe</p>";
                $faltanColumnas = true;
            }
        }
        
        if ($faltanColumnas) {
            echo "<h3>Opciones para corregir:</h3>";
            echo "<p><strong>Opción 1:</strong> Eliminar y recrear la tabla (se perderán todos los datos)</p>";
            echo "<p><strong>Opción 2:</strong> Agregar las columnas faltantes manualmente</p>";
            echo "<hr>";
            
            // Opción para eliminar y recrear
            if (isset($_GET['recrear']) && $_GET['recrear'] == 'si') {
                echo "<h3>Eliminando y recreando la tabla...</h3>";
                
                // Eliminar tabla
                $conn->query("DROP TABLE IF EXISTS `fotos y videos`");
                echo "<p style='color: orange;'>Tabla eliminada</p>";
                
                // Crear tabla con estructura correcta
                $sql = "CREATE TABLE `fotos y videos` (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    nombre_original VARCHAR(255) NOT NULL,
                    nombre_guardado VARCHAR(255) NOT NULL,
                    ruta_archivo VARCHAR(500) NOT NULL,
                    tipo_archivo VARCHAR(100) NOT NULL,
                    tamano_archivo BIGINT NOT NULL,
                    fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_tipo (tipo_archivo),
                    INDEX idx_fecha (fecha_subida)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                
                if ($conn->query($sql)) {
                    echo "<p style='color: green; font-weight: bold;'>✓ Tabla recreada correctamente con la estructura correcta</p>";
                    echo "<p>Ahora puedes intentar subir archivos nuevamente.</p>";
                } else {
                    echo "<p style='color: red;'>✗ Error al recrear la tabla: " . $conn->error . "</p>";
                }
            } else {
                echo "<p><a href='?recrear=si' style='background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>ELIMINAR Y RECREAR TABLA (CUIDADO: Se perderán todos los datos)</a></p>";
            }
        } else {
            echo "<p style='color: green; font-weight: bold;'>✓ La tabla tiene todas las columnas requeridas</p>";
            echo "<p>Si sigues teniendo problemas, intenta eliminar y recrear la tabla.</p>";
        }
        
    } else {
        echo "<p style='color: red;'>✗ La tabla 'fotos y videos' NO existe</p>";
        echo "<p>Creando la tabla...</p>";
        
        // Crear tabla
        $sql = "CREATE TABLE `fotos y videos` (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre_original VARCHAR(255) NOT NULL,
            nombre_guardado VARCHAR(255) NOT NULL,
            ruta_archivo VARCHAR(500) NOT NULL,
            tipo_archivo VARCHAR(100) NOT NULL,
            tamano_archivo BIGINT NOT NULL,
            fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_tipo (tipo_archivo),
            INDEX idx_fecha (fecha_subida)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        if ($conn->query($sql)) {
            echo "<p style='color: green; font-weight: bold;'>✓ Tabla creada correctamente</p>";
        } else {
            echo "<p style='color: red;'>✗ Error al crear la tabla: " . $conn->error . "</p>";
        }
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}

?>

