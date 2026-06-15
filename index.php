<?php
/**
 * Página de Inicio - Hero Slider y secciones principales
 * Santiago Urquieta - Sitio Web Profesional
 */

require_once __DIR__ . '/../models/Articulo.php';
require_once __DIR__ . '/../models/Portafolio.php';

$articuloModel = new Articulo();
$portafolioModel = new Portafolio();

// Obtener artículos destacados
$articulosDestacados = $articuloModel->getDestacados(3);

// Obtener proyectos destacados
$proyectosDestacados = $portafolioModel->getDestacados(6);

$pageTitle = 'Inicio';
$pageDescription = getConfig('site_subtitle');

ob_start();
?>

<!-- Hero Section con Slider -->
<section class="hero-slider">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="4"></button>
        </div>
        
        <div class="carousel-inner">
            <!-- Slide 1: Inteligencia Artificial -->
            <div class="carousel-item active" style="background-image: url('<?= getConfig('site_url') ?>/assets/images/slide-ai.jpg');">
                <div class="carousel-overlay"></div>
                <div class="container">
                    <div class="carousel-caption">
                        <h1 class="display-3 fw-bold animate__animated animate__fadeInUp">Inteligencia Artificial</h1>
                        <p class="lead animate__animated animate__fadeInUp animate__delay-1s">Soluciones innovadoras basadas en machine learning y automatización inteligente</p>
                        <a href="<?= getConfig('site_url') ?>/especialidades#ia" class="btn btn-primary btn-lg animate__animated animate__fadeInUp animate__delay-2s">Conocer más</a>
                    </div>
                </div>
            </div>
            
            <!-- Slide 2: Ciberseguridad -->
            <div class="carousel-item" style="background-image: url('<?= getConfig('site_url') ?>/assets/images/slide-security.jpg');">
                <div class="carousel-overlay"></div>
                <div class="container">
                    <div class="carousel-caption">
                        <h1 class="display-3 fw-bold animate__animated animate__fadeInUp">Ciberseguridad</h1>
                        <p class="lead animate__animated animate__fadeInUp animate__delay-1s">Protección integral de sistemas y datos con las últimas tecnologías</p>
                        <a href="<?= getConfig('site_url') ?>/especialidades#ciberseguridad" class="btn btn-primary btn-lg animate__animated animate__fadeInUp animate__delay-2s">Conocer más</a>
                    </div>
                </div>
            </div>
            
            <!-- Slide 3: Marketing Digital -->
            <div class="carousel-item" style="background-image: url('<?= getConfig('site_url') ?>/assets/images/slide-marketing.jpg');">
                <div class="carousel-overlay"></div>
                <div class="container">
                    <div class="carousel-caption">
                        <h1 class="display-3 fw-bold animate__animated animate__fadeInUp">Marketing Digital</h1>
                        <p class="lead animate__animated animate__fadeInUp animate__delay-1s">Estrategias digitales que impulsan el crecimiento de tu negocio</p>
                        <a href="<?= getConfig('site_url') ?>/especialidades#marketing" class="btn btn-primary btn-lg animate__animated animate__fadeInUp animate__delay-2s">Conocer más</a>
                    </div>
                </div>
            </div>
            
            <!-- Slide 4: Desarrollo de Software -->
            <div class="carousel-item" style="background-image: url('<?= getConfig('site_url') ?>/assets/images/slide-dev.jpg');">
                <div class="carousel-overlay"></div>
                <div class="container">
                    <div class="carousel-caption">
                        <h1 class="display-3 fw-bold animate__animated animate__fadeInUp">Desarrollo de Software</h1>
                        <p class="lead animate__animated animate__fadeInUp animate__delay-1s">Aplicaciones web y móviles de alto rendimiento y escalabilidad</p>
                        <a href="<?= getConfig('site_url') ?>/especialidades#desarrollo" class="btn btn-primary btn-lg animate__animated animate__fadeInUp animate__delay-2s">Conocer más</a>
                    </div>
                </div>
            </div>
            
            <!-- Slide 5: Analítica de Datos -->
            <div class="carousel-item" style="background-image: url('<?= getConfig('site_url') ?>/assets/images/slide-analytics.jpg');">
                <div class="carousel-overlay"></div>
                <div class="container">
                    <div class="carousel-caption">
                        <h1 class="display-3 fw-bold animate__animated animate__fadeInUp">Analítica de Datos</h1>
                        <p class="lead animate__animated animate__fadeInUp animate__delay-1s">Transformamos datos en insights accionables para tu negocio</p>
                        <a href="<?= getConfig('site_url') ?>/especialidades#analitica" class="btn btn-primary btn-lg animate__animated animate__fadeInUp animate__delay-2s">Conocer más</a>
                    </div>
                </div>
            </div>
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

<!-- Sección Sobre Mí Preview -->
<section class="section about-preview">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="about-image">
                    <img src="<?= getConfig('site_url') ?>/assets/images/profile.jpg" alt="Santiago Urquieta" class="img-fluid rounded shadow">
                </div>
            </div>
            <div class="col-lg-6">
                <h2 class="section-title">Santiago Urquieta</h2>
                <h4 class="text-primary mb-4">Ingeniero en Informática</h4>
                <p class="lead">Profesional especializado en Inteligencia Artificial, Ciberseguridad y Marketing Digital con enfoque en innovación y resultados.</p>
                <p>Mi experiencia abarca desde el desarrollo de software hasta la implementación de estrategias digitales avanzadas, siempre buscando optimizar procesos y maximizar el valor tecnológico.</p>
                <ul class="list-unstyled about-skills">
                    <li><i class="fas fa-check-circle text-primary"></i> Inteligencia Artificial & Machine Learning</li>
                    <li><i class="fas fa-check-circle text-primary"></i> Ciberseguridad & Protección de Datos</li>
                    <li><i class="fas fa-check-circle text-primary"></i> Marketing Digital & Publicidad Online</li>
                    <li><i class="fas fa-check-circle text-primary"></i> Desarrollo Web & Automatización</li>
                </ul>
                <a href="<?= getConfig('site_url') ?>/sobre-mi" class="btn btn-outline-primary">Ver perfil completo</a>
            </div>
        </div>
    </div>
</section>

<!-- Sección Especialidades Preview -->
<section class="section specialties-preview bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Especialidades</h2>
            <p class="text-muted">Áreas de expertise profesional</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="specialty-card card h-100">
                    <div class="card-body text-center">
                        <div class="specialty-icon">
                            <i class="fas fa-brain"></i>
                        </div>
                        <h5 class="card-title">Inteligencia Artificial</h5>
                        <p class="card-text">Implementación de soluciones IA, machine learning y procesamiento de lenguaje natural.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="specialty-card card h-100">
                    <div class="card-body text-center">
                        <div class="specialty-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5 class="card-title">Ciberseguridad</h5>
                        <p class="card-text">Auditoría de seguridad, protección de infraestructuras y hacking ético.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="specialty-card card h-100">
                    <div class="card-body text-center">
                        <div class="specialty-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h5 class="card-title">Marketing Digital</h5>
                        <p class="card-text">Estrategias SEO/SEM, gestión de redes sociales y campañas publicitarias.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-5">
            <a href="<?= getConfig('site_url') ?>/especialidades" class="btn btn-primary">Ver todas las especialidades</a>
        </div>
    </div>
</section>

<!-- Sección Portafolio Preview -->
<?php if (!empty($proyectosDestacados)): ?>
<section class="section portfolio-preview">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Portafolio Destacado</h2>
            <p class="text-muted">Proyectos recientes</p>
        </div>
        <div class="row g-4">
            <?php foreach ($proyectosDestacados as $proyecto): ?>
            <div class="col-md-4">
                <div class="portfolio-card card h-100">
                    <img src="<?= getConfig('uploads_url') . $proyecto['imagen'] ?>" 
                         class="card-img-top" alt="<?= sanitize($proyecto['titulo']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= sanitize($proyecto['titulo']) ?></h5>
                        <p class="card-text"><?= truncateText($proyecto['descripcion'], 100) ?></p>
                        <?php if ($proyecto['tecnologias']): ?>
                        <div class="technologies">
                            <?php 
                            $tecnologias = explode(',', $proyecto['tecnologias']);
                            foreach (array_slice($tecnologias, 0, 3) as $tech): 
                            ?>
                            <span class="badge bg-secondary"><?= sanitize(trim($tech)) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3">
                        <a href="<?= getConfig('site_url') ?>/portafolio/<?= $proyecto['slug'] ?>" class="btn btn-outline-primary btn-sm">Ver proyecto</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="<?= getConfig('site_url') ?>/portafolio" class="btn btn-primary">Ver todo el portafolio</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Sección Blog Preview -->
<?php if (!empty($articulosDestacados)): ?>
<section class="section blog-preview bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Últimos Artículos</h2>
            <p class="text-muted">Contenido especializado</p>
        </div>
        <div class="row g-4">
            <?php foreach ($articulosDestacados as $articulo): ?>
            <div class="col-md-4">
                <article class="blog-card card h-100">
                    <img src="<?= getConfig('uploads_url') . $articulo['imagen_destacada'] ?>" 
                         class="card-img-top" alt="<?= sanitize($articulo['titulo']) ?>">
                    <div class="card-body">
                        <?php if ($articulo['categoria_nombre']): ?>
                        <span class="badge bg-primary mb-2"><?= sanitize($articulo['categoria_nombre']) ?></span>
                        <?php endif; ?>
                        <h5 class="card-title"><?= sanitize($articulo['titulo']) ?></h5>
                        <p class="card-text"><?= truncateText($articulo['extracto'], 120) ?></p>
                        <small class="text-muted">
                            <i class="far fa-calendar"></i> <?= formatDateLong($articulo['published_at']) ?>
                        </small>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3">
                        <a href="<?= getConfig('site_url') ?>/blog/<?= $articulo['slug'] ?>" class="btn btn-outline-primary btn-sm">Leer más</a>
                    </div>
                </article>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="<?= getConfig('site_url') ?>/blog" class="btn btn-primary">Ver todos los artículos</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Call to Action -->
<section class="section cta-section">
    <div class="container text-center">
        <h2 class="mb-4">¿Listo para trabajar juntos?</h2>
        <p class="lead mb-4">Transformemos tus ideas en realidad con tecnología de vanguardia</p>
        <a href="<?= getConfig('site_url') ?>/contacto" class="btn btn-primary btn-lg">Contactar ahora</a>
    </div>
</section>

<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/main.php';
?>
