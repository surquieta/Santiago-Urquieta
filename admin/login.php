<?php
/**
 * Panel de Administración - Login
 * Santiago Urquieta - Sitio Web Profesional
 */

session_start();

// Redireccionar si ya está logueado
if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../models/Usuario.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Verificar CSRF
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
        $error = 'Token de seguridad inválido';
    } elseif (empty($email) || empty($password)) {
        $error = 'Por favor complete todos los campos';
    } else {
        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->authenticate($email, $password);
        
        if ($usuario) {
            // Regenerar ID de sesión para seguridad
            session_regenerate_id(true);
            
            $_SESSION['admin_id'] = $usuario['id'];
            $_SESSION['admin_email'] = $usuario['email'];
            $_SESSION['admin_nombre'] = $usuario['nombre'];
            $_SESSION['admin_rol'] = $usuario['rol'];
            $_SESSION['last_activity'] = time();
            
            header('Location: index.php');
            exit;
        } else {
            $error = 'Email o contraseña incorrectos';
        }
    }
}

// Generar nuevo token CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Panel Administrativo</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header i {
            font-size: 3rem;
            color: #0d6efd;
            margin-bottom: 1rem;
        }
        
        .login-header h2 {
            font-weight: 600;
            color: #212529;
        }
        
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            border: none;
            padding: 0.75rem;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-shield-alt"></i>
                <h2>Panel Administrativo</h2>
                <p class="text-muted">Santiago Urquieta</p>
            </div>
            
            <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-2"></i>Email
                    </label>
                    <input type="email" 
                           class="form-control" 
                           id="email" 
                           name="email" 
                           placeholder="tu@email.com"
                           required
                           autofocus>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-2"></i>Contraseña
                    </label>
                    <input type="password" 
                           class="form-control" 
                           id="password" 
                           name="password" 
                           placeholder="••••••••"
                           required>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt me-2"></i>Ingresar
                    </button>
                </div>
                
                <div class="text-center mt-3">
                    <a href="../" class="text-muted">
                        <i class="fas fa-arrow-left me-1"></i>Volver al sitio
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
