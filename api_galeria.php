<?php
/**
 * API para obtener archivos de la galería
 * Requiere autenticación
 */
require_once 'session.php';
require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $conn = conectarMySQL();
    
    // Obtener tipo de filtro
    $tipoFiltro = $_GET['tipo'] ?? 'todos';
    
    // Construir consulta
    if ($tipoFiltro === 'imagen') {
        $sql = "SELECT * FROM `fotos y videos` WHERE tipo_archivo LIKE 'image/%' ORDER BY fecha_subida DESC";
    } elseif ($tipoFiltro === 'video') {
        $sql = "SELECT * FROM `fotos y videos` WHERE tipo_archivo LIKE 'video/%' ORDER BY fecha_subida DESC";
    } else {
        $sql = "SELECT * FROM `fotos y videos` ORDER BY fecha_subida DESC";
    }
    
    $result = $conn->query($sql);
    
    $archivos = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Verificar si el archivo existe físicamente
            $archivoExiste = file_exists($row['ruta_archivo']);
            
            $archivos[] = [
                'id' => $row['id'],
                'nombre_original' => $row['nombre_original'],
                'nombre_guardado' => $row['nombre_guardado'],
                'ruta_archivo' => $row['ruta_archivo'],
                'tipo_archivo' => $row['tipo_archivo'],
                'tamano_archivo' => $row['tamano_archivo'],
                'fecha_subida' => $row['fecha_subida'],
                'existe' => $archivoExiste,
                'es_imagen' => strpos($row['tipo_archivo'], 'image/') === 0,
                'es_video' => strpos($row['tipo_archivo'], 'video/') === 0
            ];
        }
    }
    
    // Obtener estadísticas
    $stats = [];
    $stats['total'] = count($archivos);
    $stats['imagenes'] = count(array_filter($archivos, fn($a) => $a['es_imagen']));
    $stats['videos'] = count(array_filter($archivos, fn($a) => $a['es_video']));
    
    echo json_encode([
        'success' => true,
        'archivos' => $archivos,
        'estadisticas' => $stats
    ]);
    
    $conn->close();
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener archivos: ' . $e->getMessage()
    ]);
}
?>

