<?php
/**
 * Galería de imágenes y videos (requiere autenticación)
 */
require_once 'session.php';
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería - Mi Boda</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="galeria.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <a href="index.html" class="logo">
                <i class="fas fa-heart"></i> Mi Boda
            </a>
            <div class="header-actions">
                <span class="user-info">
                    <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                </span>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </header>

    <!-- Contenedor Principal -->
    <div class="container">
        <div class="galeria-header">
            <h1 class="galeria-title">
                <i class="fas fa-images"></i> Galería de Recuerdos
            </h1>
            <p class="galeria-subtitle">
                Visualiza todas las fotos y videos compartidos
            </p>
        </div>

        <!-- Filtros -->
        <div class="filtros">
            <button class="filtro-btn active" data-tipo="todos">
                <i class="fas fa-th"></i> Todos
            </button>
            <button class="filtro-btn" data-tipo="imagen">
                <i class="fas fa-image"></i> Imágenes
            </button>
            <button class="filtro-btn" data-tipo="video">
                <i class="fas fa-video"></i> Videos
            </button>
        </div>

        <!-- Estadísticas -->
        <div class="estadisticas" id="estadisticas"></div>

        <!-- Galería -->
        <div class="galeria-grid" id="galeriaGrid">
            <div class="loading">
                <i class="fas fa-spinner fa-spin"></i> Cargando archivos...
            </div>
        </div>

        <!-- Modal para vista ampliada -->
        <div class="modal" id="modal">
            <div class="modal-content">
                <span class="modal-close" id="modalClose">
                    <i class="fas fa-times"></i>
                </span>
                <div class="modal-body" id="modalBody"></div>
                <div class="modal-info" id="modalInfo"></div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>Galería de recuerdos compartidos ❤️</p>
    </footer>

    <script src="galeria.js"></script>
</body>
</html>

