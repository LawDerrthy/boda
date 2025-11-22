<?php
/**
 * Script para manejar la subida de archivos y guardar información en MySQL
 */

// Incluir configuración
require_once 'config.php';

// Crear directorio de uploads si no existe
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}

// Respuesta JSON
header('Content-Type: application/json; charset=utf-8');

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido. Solo se acepta POST.'
    ]);
    exit;
}

// Verificar que se haya subido un archivo
if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
    $errorMsg = 'No se recibió ningún archivo.';
    
    if (isset($_FILES['archivo']['error'])) {
        switch ($_FILES['archivo']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $errorMsg = 'El archivo excede el tamaño máximo permitido.';
                break;
            case UPLOAD_ERR_PARTIAL:
                $errorMsg = 'El archivo se subió parcialmente.';
                break;
            case UPLOAD_ERR_NO_FILE:
                $errorMsg = 'No se seleccionó ningún archivo.';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $errorMsg = 'Falta la carpeta temporal.';
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $errorMsg = 'Error al escribir el archivo en disco.';
                break;
            case UPLOAD_ERR_EXTENSION:
                $errorMsg = 'Una extensión de PHP detuvo la subida del archivo.';
                break;
        }
    }
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $errorMsg
    ]);
    exit;
}

$archivo = $_FILES['archivo'];
$nombreOriginal = $archivo['name'];
$tipoArchivo = $archivo['type'];
$tamanoArchivo = $archivo['size'];
$archivoTemporal = $archivo['tmp_name'];

// Validar tamaño del archivo
if ($tamanoArchivo > MAX_FILE_SIZE) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El archivo excede el tamaño máximo permitido de ' . (MAX_FILE_SIZE / 1024 / 1024) . ' MB.'
    ]);
    exit;
}

// Validar tipo de archivo
if (!esTipoPermitido($tipoArchivo)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Tipo de archivo no permitido. Solo se permiten imágenes y videos.'
    ]);
    exit;
}

// Generar nombre único para el archivo
$extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
$nombreGuardado = uniqid('archivo_', true) . '.' . $extension;
$rutaArchivo = UPLOAD_DIR . $nombreGuardado;

// Mover archivo del directorio temporal al directorio de destino
if (!move_uploaded_file($archivoTemporal, $rutaArchivo)) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al guardar el archivo en el servidor.'
    ]);
    exit;
}

// Conectar a MySQL y guardar información en la base de datos
try {
    $conn = conectarMySQL();
    
    // Preparar consulta SQL
    $stmt = $conn->prepare("INSERT INTO `fotos y videos` (nombre_original, nombre_guardado, ruta_archivo, tipo_archivo, tamano_archivo) VALUES (?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conn->error);
    }
    
    // Vincular parámetros
    $stmt->bind_param("ssssi", $nombreOriginal, $nombreGuardado, $rutaArchivo, $tipoArchivo, $tamanoArchivo);
    
    // Ejecutar consulta
    if (!$stmt->execute()) {
        // Si falla la inserción en BD, eliminar el archivo subido
        unlink($rutaArchivo);
        throw new Exception("Error al guardar en la base de datos: " . $stmt->error);
    }
    
    $idInsertado = $conn->insert_id;
    
    // Cerrar conexiones
    $stmt->close();
    $conn->close();
    
    // Respuesta exitosa
    echo json_encode([
        'success' => true,
        'message' => 'Archivo subido exitosamente.',
        'data' => [
            'id' => $idInsertado,
            'nombre_original' => $nombreOriginal,
            'nombre_guardado' => $nombreGuardado,
            'ruta_archivo' => $rutaArchivo,
            'tipo_archivo' => $tipoArchivo,
            'tamano_archivo' => $tamanoArchivo
        ]
    ]);
    
} catch (Exception $e) {
    // Eliminar archivo si existe
    if (file_exists($rutaArchivo)) {
        unlink($rutaArchivo);
    }
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al procesar el archivo: ' . $e->getMessage()
    ]);
}

?>

