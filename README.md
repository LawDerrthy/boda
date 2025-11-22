# Sistema de Subida de Archivos - Mi Boda

Sistema básico para subir archivos (imágenes y videos) con almacenamiento en MySQL.

## 📋 Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

1. **Servidor Web con PHP**
   - XAMPP (recomendado para Windows): [Descargar XAMPP](https://www.apachefriends.org/)
   - WAMP (Windows)
   - LAMP (Linux)
   - MAMP (Mac)

2. **MySQL**
   - Generalmente incluido en XAMPP/WAMP/MAMP
   - Versión 5.7 o superior recomendada

3. **PHP**
   - Versión 7.4 o superior
   - Extensiones requeridas:
     - `mysqli` (para conexión MySQL)
     - `gd` (para manipulación de imágenes - opcional)

## 🚀 Instalación y Configuración

### Paso 1: Instalar XAMPP (si no lo tienes)

1. Descarga XAMPP desde [apachefriends.org](https://www.apachefriends.org/)
2. Instala XAMPP en tu sistema (por defecto en `C:\xampp` en Windows)
3. Inicia el Panel de Control de XAMPP
4. Inicia los servicios:
   - **Apache** (servidor web)
   - **MySQL** (base de datos)

### Paso 2: Configurar el Proyecto

1. **Copia el proyecto** en el directorio de XAMPP:
   ```
   C:\xampp\htdocs\Mi-boda
   ```

   O si usas otro servidor:
   - WAMP: `C:\wamp64\www\Mi-boda`
   - MAMP: `/Applications/MAMP/htdocs/Mi-boda`

2. **Asegúrate** de que el directorio `uploads/` tenga permisos de escritura:
   - En Windows, generalmente funciona automáticamente
   - En Linux/Mac, ejecuta: `chmod 777 uploads/`

### Paso 3: Crear la Base de Datos MySQL

1. **Abre phpMyAdmin**:
   - Abre tu navegador
   - Ve a: `http://localhost/phpmyadmin`

2. **Importa el script SQL**:
   - Haz clic en "Nueva" o "New" en el menú lateral
   - O selecciona la pestaña "Importar" / "Import"
   - Haz clic en "Seleccionar archivo" / "Choose File"
   - Selecciona el archivo `database.sql` del proyecto
   - Haz clic en "Continuar" / "Go"

   **O ejecuta manualmente**:
   - Haz clic en la pestaña "SQL"
   - Copia y pega el contenido de `database.sql`
   - Haz clic en "Continuar" / "Go"

3. **Verifica la creación**:
   - Deberías ver una base de datos llamada `mi_boda`
   - Dentro debería haber una tabla llamada `fotos y videos`

### Paso 4: Configurar la Conexión a MySQL

1. **Edita el archivo `config.php`** con un editor de texto o editor de código

2. **Modifica estas líneas** según tu configuración:

```php
define('DB_HOST', 'localhost');        // Generalmente 'localhost'
define('DB_USER', 'root');              // Usuario de MySQL (por defecto 'root' en XAMPP)
define('DB_PASS', '');                  // Contraseña (vacía por defecto en XAMPP)
define('DB_NAME', 'mi_boda');  // Nombre de la base de datos
```

**Si tu MySQL tiene contraseña**, cambia `DB_PASS`:
```php
define('DB_PASS', 'tu_contraseña_aqui');
```

**Si usas un puerto diferente** (no es común):
```php
define('DB_HOST', 'localhost:3307');  // Ejemplo si MySQL está en puerto 3307
```

### Paso 5: Verificar la Configuración

1. **Abre en el navegador**:
   ```
   http://localhost/Mi-boda/test_connection.php
   ```

2. **Deberías ver**:
   - ✓ Conexión a MySQL exitosa
   - ✓ La tabla 'fotos y videos' existe
   - Estructura de la tabla
   - Total de archivos (inicialmente 0)

3. **Si hay errores**, revisa:
   - Que Apache y MySQL estén corriendo en XAMPP
   - Que los datos en `config.php` sean correctos
   - Que la base de datos y tabla existan

### Paso 6: Probar la Subida de Archivos

1. **Abre en el navegador**:
   ```
   http://localhost/Mi-boda/index.html
   ```

2. **Sube un archivo**:
   - Haz clic en "Seleccionar archivo"
   - Elige una imagen (JPG, PNG, GIF, WEBP) o video (MP4, MOV, AVI, WEBM)
   - Haz clic en "Subir Archivo"

3. **Verifica el resultado**:
   - Deberías ver un mensaje de éxito
   - El archivo se guardará en la carpeta `uploads/`
   - La información se guardará en la base de datos

4. **Verifica en MySQL**:
   - Ve a phpMyAdmin: `http://localhost/phpmyadmin`
   - Selecciona la base de datos `mi_boda`
   - Haz clic en la tabla `fotos y videos`
   - Deberías ver el registro del archivo subido

## 📁 Estructura del Proyecto

```
Mi-boda/
├── index.html              # Página principal para subir archivos
├── styles.css              # Estilos CSS principales
├── script.js               # JavaScript para subida de archivos
├── login.php               # Página de inicio de sesión
├── login.css               # Estilos para la página de login
├── login.js                # JavaScript para el login
├── session.php             # Verificación de sesión de usuario
├── logout.php              # Cerrar sesión
├── galeria.php             # Galería de imágenes (requiere login)
├── galeria.css             # Estilos para la galería
├── galeria.js              # JavaScript para la galería
├── api_galeria.php         # API para obtener archivos de la galería
├── upload.php              # Script PHP que maneja la subida y guarda en MySQL
├── config.php              # Configuración de conexión MySQL
├── test_connection.php     # Script para probar la conexión MySQL
├── fix_table.php           # Script para corregir estructura de tabla
├── database.sql            # Script SQL para crear base de datos y tabla
├── .gitignore             # Archivos a ignorar en Git
├── README.md              # Este archivo
└── uploads/               # Directorio donde se guardan los archivos (se crea automáticamente)
```

## 🔧 Configuración Detallada

### Configuración de PHP

Si tienes problemas con el tamaño máximo de archivo, edita `php.ini`:

**Ubicación de php.ini**:
- XAMPP: `C:\xampp\php\php.ini`
- WAMP: `C:\wamp64\bin\php\php7.x.x\php.ini`

**Ajusta estos valores** (si es necesario):
```ini
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
memory_limit = 256M
```

**Reinicia Apache** después de modificar `php.ini`.

### Tipos de Archivos Permitidos

Los tipos permitidos están configurados en `config.php`:

**Imágenes:**
- JPEG/JPG
- PNG
- GIF
- WEBP

**Videos:**
- MP4
- MPEG
- QuickTime (MOV)
- AVI
- WEBM

### Tamaño Máximo de Archivo

Por defecto: **100 MB**

Para cambiar, modifica en `config.php`:
```php
define('MAX_FILE_SIZE', 100 * 1024 * 1024); // 100 MB
```

## 🔐 Sistema de Inicio de Sesión

El sistema incluye un sistema de autenticación simple para acceder a la galería de imágenes:

### Credenciales de Acceso
- **Usuario:** `admin`
- **Contraseña:** `password`

### Características del Login
- Página de inicio de sesión con diseño moderno
- Validación de credenciales
- Sesiones PHP para mantener el usuario logueado
- Protección de la galería (solo usuarios autenticados pueden ver las imágenes)
- Opción de cerrar sesión

### Páginas del Sistema
1. **`index.html`** - Página pública para subir archivos
2. **`login.php`** - Página de inicio de sesión
3. **`galeria.php`** - Galería de imágenes (requiere autenticación)

### Uso del Sistema de Login
1. Haz clic en el botón "Iniciar Sesión" en la página principal
2. Ingresa las credenciales: usuario `admin` y contraseña `password`
3. Serás redirigido a la galería donde podrás ver todas las fotos y videos subidos
4. Puedes filtrar por tipo (Todos, Imágenes, Videos)
5. Haz clic en cualquier archivo para verlo en tamaño completo
6. Usa el botón "Cerrar Sesión" para salir

## 🧪 Pruebas

### Prueba 1: Conexión MySQL
```
http://localhost/Mi-boda/test_connection.php
```
- Verifica que la conexión funcione
- Confirma que la tabla existe
- Revisa la estructura de la tabla

### Prueba 2: Subida de Archivo
```
http://localhost/Mi-boda/index.html
```
- Sube una imagen pequeña (< 1 MB)
- Verifica mensaje de éxito
- Comprueba que el archivo esté en `uploads/`
- Verifica el registro en MySQL

### Prueba 3: Verificación en MySQL
1. Abre phpMyAdmin
2. Selecciona `mi_boda`
3. Tabla `fotos y videos`
4. Verifica que haya registros con:
   - Nombre original
   - Nombre guardado
   - Ruta del archivo
   - Tipo de archivo
   - Tamaño
   - Fecha de subida

## ❗ Solución de Problemas

### Error: "Error de conexión: Access denied"
- **Problema**: Usuario o contraseña incorrectos
- **Solución**: Verifica `DB_USER` y `DB_PASS` en `config.php`

### Error: "Unknown database 'mi_boda'"
- **Problema**: La base de datos no existe
- **Solución**: Ejecuta `database.sql` en phpMyAdmin

### Error: "Table 'fotos y videos' doesn't exist"
- **Problema**: La tabla no existe
- **Solución**: Ejecuta `database.sql` en phpMyAdmin

### Error: "The file exceeds the maximum size"
- **Problema**: El archivo es muy grande o límite de PHP muy bajo
- **Solución**: 
  - Reduce el tamaño del archivo
  - O aumenta `upload_max_filesize` y `post_max_size` en `php.ini`

### Error: "Failed to open stream: Permission denied"
- **Problema**: Permisos insuficientes en la carpeta `uploads/`
- **Solución**: 
  - Windows: Verifica que la carpeta exista
  - Linux/Mac: `chmod 777 uploads/`

### Error: "Call to undefined function conectarMySQL()"
- **Problema**: `config.php` no está incluido correctamente
- **Solución**: Verifica que `require_once 'config.php';` esté en `upload.php`

### Los archivos no se suben
- **Verifica**:
  1. Que Apache esté corriendo
  2. Que `uploads/` tenga permisos de escritura
  3. Que no haya errores en la consola del navegador (F12)
  4. Revisa los logs de Apache: `C:\xampp\apache\logs\error.log`

## 📝 Notas Importantes

1. **Seguridad**: Esta es una versión básica para pruebas. Para producción, necesitarás:
   - Validación más estricta de archivos
   - Sanitización de nombres de archivo
   - Límites de tamaño por usuario
   - Autenticación de usuarios
   - Protección contra CSRF

2. **Rutas**: Si cambias la ubicación del proyecto, ajusta las rutas relativas en el código.

3. **Base de Datos**: Los datos están en la base de datos MySQL, pero los archivos físicos están en `uploads/`. Si eliminas archivos de `uploads/`, los registros en MySQL quedarán huérfanos.

## 🔄 Próximos Pasos

Después de verificar que todo funciona:

1. Implementar diseño visual
2. Agregar vista de archivos subidos
3. Implementar eliminación de archivos
4. Agregar autenticación de usuarios
5. Implementar galería de imágenes
6. Agregar reproductor de videos

## 📞 Soporte

Si encuentras problemas:

1. Revisa la sección "Solución de Problemas" arriba
2. Verifica los logs de errores:
   - PHP: `C:\xampp\php\logs\php_error_log`
   - Apache: `C:\xampp\apache\logs\error.log`
3. Asegúrate de que todos los servicios estén corriendo

---

**Versión**: 1.0  
**Fecha**: 2024  
**Autor**: Sistema Mi Boda

