<?php
/**
 * Configuración de conexión a MySQL
 * IMPORTANTE: Modifica estos valores según tu configuración local
 */

// Configuración de la base de datos
define('DB_HOST', 'localhost');        // Host de MySQL (generalmente 'localhost')
define('DB_USER', 'root');              // Usuario de MySQL
define('DB_PASS', '');                  // Contraseña de MySQL (vacía por defecto en XAMPP/WAMP)
define('DB_NAME', 'mi_boda');  // Nombre de la base de datos

// Configuración de subida de archivos
define('UPLOAD_DIR', 'uploads/');      // Directorio donde se guardarán los archivos
define('MAX_FILE_SIZE', 100 * 1024 * 1024); // Tamaño máximo: 100 MB

// Tipos de archivos permitidos
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp']);
define('ALLOWED_VIDEO_TYPES', ['video/mp4', 'video/mpeg', 'video/quicktime', 'video/avi', 'video/webm']);

/**
 * Función para establecer conexión con MySQL
 * @return mysqli|false Retorna el objeto de conexión o false en caso de error
 */
function conectarMySQL() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Verificar conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    
    // Establecer charset UTF-8
    $conn->set_charset("utf8mb4");
    
    return $conn;
}

/**
 * Verificar si un tipo de archivo está permitido
 * @param string $tipo Tipo MIME del archivo
 * @return bool
 */
function esTipoPermitido($tipo) {
    $tiposPermitidos = array_merge(ALLOWED_IMAGE_TYPES, ALLOWED_VIDEO_TYPES);
    return in_array($tipo, $tiposPermitidos);
}

?>

