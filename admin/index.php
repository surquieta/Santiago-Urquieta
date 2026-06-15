<?php
/**
 * Panel de Administración - Dashboard Principal
 * Santiago Urquieta - Sitio Web Profesional
 */

session_start();

// Verificar autenticación
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Verificar timeout de sesión
if (time() - ($_SESSION['last_activity'] ?? 0) > 3600) {
    session_destroy();
    header('Location: login.php?timeout=1');
    exit;
}
$_SESSION['last_activity'] = time();

require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Articulo.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/Portafolio.php';
require_once __DIR__ . '/../models/Contacto.php';
require_once __DIR__ . '/../includes/functions.php';

$usuarioModel = new Usuario();
$articuloModel = new Articulo();
$categoriaModel = new Categoria();
$portafolioModel = new Portafolio();
$contactoModel = new Contacto();

// Estadísticas
$totalArticulos = $articuloModel->count();
$totalCategorias = $categoriaModel->count();
$totalProyectos = $portafolioModel->count();
$mensajesNoLeidos = $contactoModel->countUnread();

// Últimos artículos
$ultimosArticulos = $articuloModel->getRecientes(5);

$pageTitle = 'Dashboard';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - Panel Administrativo</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 260px;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f6fa;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand h4 {
            font-weight: 600;
            margin: 0;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 1rem 0;
        }
        
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar-menu li a i {
            width: 25px;
            margin-right: 10px;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
        }
        
        .top-bar {
            background: white;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .stat-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .stat-card.blue .icon { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
        .stat-card.green .icon { background: rgba(25, 135, 84, 0.1); color: #198754; }
        .stat-card.orange .icon { background: rgba(255, 193, 7, 0.1); color: #ffc107; }
        .stat-card.red .icon { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
        
        .recent-table {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .recent-table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h4><i class="fas fa-shield-alt me-2"></i>Admin Panel</h4>
            <small class="text-white-50">Santiago Urquieta</small>
        </div>
        
        <ul class="sidebar-menu">
            <li><a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="articulos.php"><i class="fas fa-newspaper"></i> Artículos</a></li>
            <li><a href="categorias.php"><i class="fas fa-folder"></i> Categorías</a></li>
            <li><a href="portafolio.php"><i class="fas fa-briefcase"></i> Portafolio</a></li>
            <li><a href="imagenes.php"><i class="fas fa-images"></i> Imágenes</a></li>
            <li><a href="contactos.php"><i class="fas fa-envelope"></i> Contactos</a></li>
            <li><a href="../" target="_blank"><i class="fas fa-external-link-alt"></i> Ver Sitio</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
        </ul>
    </aside>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div>
                <h4 class="mb-0">Dashboard</h4>
                <small class="text-muted">Bienvenido, <?= htmlspecialchars($_SESSION['admin_nombre']) ?></small>
            </div>
            <div>
                <span class="text-muted"><?= date('d/m/Y H:i') ?></span>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card blue">
                    <div class="icon"><i class="fas fa-newspaper"></i></div>
                    <h3><?= $totalArticulos ?></h3>
                    <p class="text-muted mb-0">Artículos Publicados</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card green">
                    <div class="icon"><i class="fas fa-folder"></i></div>
                    <h3><?= $totalCategorias ?></h3>
                    <p class="text-muted mb-0">Categorías</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card orange">
                    <div class="icon"><i class="fas fa-briefcase"></i></div>
                    <h3><?= $totalProyectos ?></h3>
                    <p class="text-muted mb-0">Proyectos</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card red">
                    <div class="icon"><i class="fas fa-envelope"></i></div>
                    <h3><?= $mensajesNoLeidos ?></h3>
                    <p class="text-muted mb-0">Mensajes Nuevos</p>
                </div>
            </div>
        </div>
        
        <!-- Últimos Artículos -->
        <div class="recent-table">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Últimos Artículos</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Categoría</th>
                            <th>Fecha</th>
                            <th>Vistas</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ultimosArticulos)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No hay artículos publicados</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($ultimosArticulos as $articulo): ?>
                        <tr>
                            <td>
                                <a href="articulo_editar.php?id=<?= $articulo['id'] ?>">
                                    <?= htmlspecialchars($articulo['titulo']) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($articulo['categoria_nombre'] ?? 'Sin categoría') ?></td>
                            <td><?= formatDate($articulo['published_at']) ?></td>
                            <td><?= number_format($articulo['vistas']) ?></td>
                            <td>
                                <?php if ($articulo['activo']): ?>
                                    <span class="badge bg-success">Publicado</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Borrador</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
