<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?><?= getConfig('site_name') ?></title>
    <meta name="description" content="<?= isset($pageDescription) ? $pageDescription : getConfig('site_subtitle') ?>">
    <?php if (isset($pageKeywords)): ?>
    <meta name="keywords" content="<?= sanitize($pageKeywords) ?>">
    <?php endif; ?>
    
    <!-- Open Graph / Facebook -->
    <?php if (isset($ogImage)): ?>
    <meta property="og:image" content="<?= $ogImage ?>">
    <?php endif; ?>
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= getCurrentURL() ?>">
    <meta property="og:title" content="<?= isset($ogTitle) ? $ogTitle : getConfig('site_name') ?>">
    <meta property="og:description" content="<?= isset($ogDescription) ? $ogDescription : getConfig('site_subtitle') ?>">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?= getCurrentURL() ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= getConfig('uploads_url') ?>favicon.png">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= getConfig('site_url') ?>/assets/css/style.css">
    
    <?php if (isset($extraCSS)): ?>
        <?php foreach ($extraCSS as $css): ?>
    <link rel="stylesheet" href="<?= $css ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?= getConfig('site_url') ?>">
                <span class="brand-name"><?= getConfig('site_name') ?></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= isset($currentPage) && $currentPage === 'inicio' ? 'active' : '' ?>" 
                           href="<?= getConfig('site_url') ?>">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isset($currentPage) && $currentPage === 'sobre-mi' ? 'active' : '' ?>" 
                           href="<?= getConfig('site_url') ?>/sobre-mi">Sobre Mí</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isset($currentPage) && $currentPage === 'especialidades' ? 'active' : '' ?>" 
                           href="<?= getConfig('site_url') ?>/especialidades">Especialidades</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isset($currentPage) && $currentPage === 'portafolio' ? 'active' : '' ?>" 
                           href="<?= getConfig('site_url') ?>/portafolio">Portafolio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isset($currentPage) && $currentPage === 'blog' ? 'active' : '' ?>" 
                           href="<?= getConfig('site_url') ?>/blog">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isset($currentPage) && $currentPage === 'contacto' ? 'active' : '' ?>" 
                           href="<?= getConfig('site_url') ?>/contacto">Contacto</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5><?= getConfig('site_name') ?></h5>
                    <p class="text-muted"><?= getConfig('site_subtitle') ?></p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-github"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5>Enlaces Rápidos</h5>
                    <ul class="footer-links">
                        <li><a href="<?= getConfig('site_url') ?>/blog">Blog</a></li>
                        <li><a href="<?= getConfig('site_url') ?>/portafolio">Portafolio</a></li>
                        <li><a href="<?= getConfig('site_url') ?>/contacto">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5>Contacto</h5>
                    <ul class="contact-info">
                        <li><i class="fas fa-envelope"></i> contacto@santiagourquieta.com</li>
                        <li><i class="fas fa-map-marker-alt"></i> Santiago, Chile</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; <?= date('Y') ?> <?= getConfig('site_name') ?>. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="<?= getConfig('site_url') ?>/sitemap.xml">Sitemap</a> | 
                    <a href="<?= getConfig('site_url') ?>/privacidad">Privacidad</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= getConfig('site_url') ?>/assets/js/main.js"></script>
    
    <?php if (isset($extraJS)): ?>
        <?php foreach ($extraJS as $js): ?>
    <script src="<?= $js ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
