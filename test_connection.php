<?php
/**
 * Script de prueba para verificar la conexión con MySQL
 * Ejecuta este archivo en el navegador para comprobar que la conexión funcione
 */

require_once 'config.php';

echo "<h2>Prueba de Conexión MySQL</h2>";

// Probar conexión
try {
    $conn = conectarMySQL();
    
    echo "<p style='color: green;'>✓ Conexión a MySQL exitosa</p>";
    echo "<p><strong>Servidor:</strong> " . DB_HOST . "</p>";
    echo "<p><strong>Base de datos:</strong> " . DB_NAME . "</p>";
    echo "<p><strong>Versión MySQL:</strong> " . $conn->server_info . "</p>";
    
    // Verificar si la tabla existe
    $result = $conn->query("SHOW TABLES LIKE 'fotos y videos'");
    
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✓ La tabla 'fotos y videos' existe</p>";
        
        // Mostrar estructura de la tabla
        echo "<h3>Estructura de la tabla 'fotos y videos':</h3>";
        $result = $conn->query("DESCRIBE `fotos y videos`");
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Contar archivos en la tabla
        $result = $conn->query("SELECT COUNT(*) as total FROM `fotos y videos`");
        $row = $result->fetch_assoc();
        echo "<p><strong>Total de archivos en la base de datos:</strong> " . $row['total'] . "</p>";
        
        // Mostrar últimos 5 archivos
        $result = $conn->query("SELECT * FROM `fotos y videos` ORDER BY fecha_subida DESC LIMIT 5");
        if ($result->num_rows > 0) {
            echo "<h3>Últimos 5 archivos subidos:</h3>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Nombre Original</th><th>Tipo</th><th>Tamaño</th><th>Fecha</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['nombre_original']) . "</td>";
                echo "<td>" . htmlspecialchars($row['tipo_archivo']) . "</td>";
                echo "<td>" . round($row['tamano_archivo'] / 1024 / 1024, 2) . " MB</td>";
                echo "<td>" . $row['fecha_subida'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } else {
        echo "<p style='color: red;'>✗ La tabla 'fotos y videos' no existe. Ejecuta el script database.sql primero.</p>";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error de conexión: " . $e->getMessage() . "</p>";
    echo "<p>Verifica la configuración en config.php</p>";
}

?>

