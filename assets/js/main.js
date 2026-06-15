/**
 * JavaScript Principal - Santiago Urquieta
 * Sitio Web Profesional
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // NAVBAR SCROLL EFFECT
    // ============================================
    const navbar = document.querySelector('.navbar');
    
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }
    
    // ============================================
    // SMOOTH SCROLL PARA ANCHOR LINKS
    // ============================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            if (href !== '#' && document.querySelector(href)) {
                e.preventDefault();
                
                const target = document.querySelector(href);
                const offsetTop = target.offsetTop - 76;
                
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // ============================================
    // ANIMACIONES AL HACER SCROLL
    // ============================================
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__fadeInUp');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.specialty-card, .portfolio-card, .blog-card').forEach(card => {
        card.style.opacity = '0';
        observer.observe(card);
    });
    
    // ============================================
    // HERO CAROUSEL AUTOPLAY
    // ============================================
    const heroCarousel = document.getElementById('heroCarousel');
    
    if (heroCarousel) {
        const carousel = new bootstrap.Carousel(heroCarousel, {
            interval: 5000,
            wrap: true,
            pause: 'hover'
        });
    }
    
    // ============================================
    // FORMULARIO DE CONTACTO - VALIDACIÓN
    // ============================================
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Validación básica
            const nombre = formData.get('nombre');
            const email = formData.get('email');
            const mensaje = formData.get('mensaje');
            
            if (!nombre || !email || !mensaje) {
                showAlert('Por favor completa todos los campos requeridos', 'danger');
                return;
            }
            
            if (!isValidEmail(email)) {
                showAlert('Por favor ingresa un email válido', 'danger');
                return;
            }
            
            // Deshabilitar botón
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
            
            // Enviar formulario
            fetch('process-contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('¡Mensaje enviado correctamente! Te contactaré pronto.', 'success');
                    contactForm.reset();
                } else {
                    showAlert(data.message || 'Error al enviar el mensaje. Intente nuevamente.', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error de conexión. Intente nuevamente.', 'danger');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
    
    // ============================================
    // VALIDACIÓN DE EMAIL
    // ============================================
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    // ============================================
    // MOSTRAR ALERTAS
    // ============================================
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.container');
        if (container) {
            container.insertBefore(alertDiv, container.firstChild);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    }
    
    // ============================================
    // CONTADOR DE VISITAS (Opcional)
    // ============================================
    function trackVisit(tipo, elementoId = null) {
        fetch('track-visit.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                tipo: tipo,
                elemento_id: elementoId
            })
        }).catch(error => console.error('Error tracking visit:', error));
    }
    
    // ============================================
    // LAZY LOADING PARA IMÁGENES
    // ============================================
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.add('loaded');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // ============================================
    // TOOLTips DE BOOTSTRAP
    // ============================================
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // ============================================
    // POPOVERS DE BOOTSTRAP
    // ============================================
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // ============================================
    // BACK TO TOP BUTTON
    // ============================================
    const backToTopButton = document.createElement('button');
    backToTopButton.className = 'btn btn-primary back-to-top';
    backToTopButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
    backToTopButton.style.cssText = `
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: none;
        z-index: 999;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    `;
    
    document.body.appendChild(backToTopButton);
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTopButton.style.display = 'block';
        } else {
            backToTopButton.style.display = 'none';
        }
    });
    
    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // ============================================
    // PRELOADER (Opcional)
    // ============================================
    window.addEventListener('load', function() {
        const preloader = document.querySelector('.preloader');
        if (preloader) {
            preloader.classList.add('fade-out');
            setTimeout(() => {
                preloader.remove();
            }, 500);
        }
    });
    
});

// ============================================
// UTILIDADES GLOBALES
// ============================================

/**
 * Formatear número con separador de miles
 */
function formatNumber(number) {
    return new Intl.NumberFormat('es-ES').format(number);
}

/**
 * Obtener parámetro de la URL
 */
function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    const results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

/**
 * Copy to clipboard
 */
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showAlert('Texto copiado al portapapeles', 'success');
    }).catch(err => {
        console.error('Error al copiar:', err);
    });
}
