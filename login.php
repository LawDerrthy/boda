<?php
/**
 * Página de inicio de sesión
 */
session_start();

// Si ya está logueado, redirigir a la galería
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: galeria.php');
    exit;
}

// Verificar si se está enviando el formulario
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Credenciales válidas
    $valid_username = 'admin';
    $valid_password = 'password';
    
    if ($username === $valid_username && $password === $valid_password) {
        // Iniciar sesión
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header('Location: galeria.php');
        exit;
    } else {
        $error = 'Usuario o contraseña incorrectos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Mi Boda</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h1 class="login-title">Mi Boda</h1>
                <p class="login-subtitle">Administración</p>
            </div>
            
            <?php if ($error): ?>
                <div class="message error show">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" class="login-form" id="loginForm">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i> Usuario
                    </label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="form-input"
                        placeholder="Ingresa tu usuario"
                        required
                        autocomplete="username"
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                    >
                </div>
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Contraseña
                    </label>
                    <div class="password-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input"
                            placeholder="Ingresa tu contraseña"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button" class="password-toggle" id="passwordToggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="login-submit-btn">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </button>
            </form>
            
            <div class="login-footer">
                <a href="index.html" class="back-link">
                    <i class="fas fa-arrow-left"></i> Volver al inicio
                </a>
            </div>
        </div>
    </div>
    
    <script src="login.js"></script>
</body>
</html>

