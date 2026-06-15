# Santiago Urquieta - Sitio Web Profesional

## Manual de Instalación y Uso

---

## 📋 Requisitos del Sistema

- **PHP**: 8.0 o superior
- **MySQL**: 5.7 o superior / MariaDB 10.2+
- **Servidor Web**: Apache con mod_rewrite o Nginx
- **Extensiones PHP**: PDO, GD, mbstring, json

---

## 🚀 Instalación Paso a Paso

### 1. Subir Archivos al Hosting

Sube todos los archivos del proyecto a tu hosting en la carpeta deseada (ej: `public_html` o una subcarpeta).

### 2. Crear Base de Datos

1. Accede a phpMyAdmin o tu gestor de MySQL
2. Crea una nueva base de datos llamada: `santiago_urquieta`
3. Ejecuta el script SQL ubicado en `install/database.sql`

```sql
-- Opción alternativa desde línea de comandos:
mysql -u usuario -p santiago_urquieta < install/database.sql
```

### 3. Configurar Conexión a la Base de Datos

Edita el archivo `config/database.php`:

```php
return [
    'host' => 'localhost',        // Tu host (usualmente localhost)
    'dbname' => 'santiago_urquieta',  // Nombre de tu base de datos
    'username' => 'tu_usuario',   // Tu usuario de MySQL
    'password' => 'tu_password',  // Tu contraseña de MySQL
    'charset' => 'utf8mb4',
    // ... resto de configuración
];
```

### 4. Configurar URL del Sitio

Edita el archivo `config/config.php`:

```php
return [
    'site_url' => 'https://tudominio.com',           // URL de tu sitio
    'admin_url' => 'https://tudominio.com/admin',    // URL del admin
    'uploads_dir' => __DIR__ . '/../uploads/',
    'uploads_url' => '/uploads/',
    // ... resto de configuración
];
```

### 5. Configurar Permisos de Carpetas

Asegúrate de que las carpetas de uploads tengan permisos de escritura:

```bash
chmod 755 uploads/
chmod 755 uploads/images/
chmod 755 uploads/documents/
```

### 6. Configurar .htaccess (Apache)

Si usas Apache, crea un archivo `.htaccess` en la raíz:

```apache
RewriteEngine On
RewriteBase /

# Redireccionar a HTTPS (opcional)
# RewriteCond %{HTTPS} off
# RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# URLs amigables para artículos
RewriteRule ^blog/([a-zA-Z0-9-]+)$ blog-single.php?slug=$1 [QSA,L]

# URLs amigables para portafolio
RewriteRule ^portafolio/([a-zA-Z0-9-]+)$ portafolio-single.php?slug=$1 [QSA,L]

# Otras páginas
RewriteRule ^sobre-mi$ sobre-mi.php [QSA,L]
RewriteRule ^especialidades$ especialidades.php [QSA,L]
RewriteRule ^contacto$ contacto.php [QSA,L]
```

### 7. Acceder al Panel Administrativo

1. Ve a: `https://tudominio.com/admin`
2. Credenciales por defecto:
   - **Email**: `admin@santiagourquieta.com`
   - **Contraseña**: `Admin123!`

**⚠️ IMPORTANTE**: Cambia la contraseña inmediatamente después del primer acceso.

---

## 📁 Estructura del Proyecto

```
/workspace
├── admin/                  # Panel de administración
│   ├── index.php          # Dashboard
│   ├── login.php          # Login
│   ├── logout.php         # Logout
│   ├── articulos.php      # Gestión de artículos
│   ├── categorias.php     # Gestión de categorías
│   ├── portafolio.php     # Gestión de portafolio
│   └── imagenes.php       # Biblioteca multimedia
├── assets/
│   ├── css/
│   │   └── style.css      # Estilos personalizados
│   ├── js/
│   │   └── main.js        # JavaScript principal
│   └── images/            # Imágenes del sitio
├── config/
│   ├── config.php         # Configuración general
│   └── database.php       # Configuración de BD
├── controllers/           # Controladores MVC
├── models/
│   ├── Usuario.php        # Modelo de usuarios
│   ├── Articulo.php       # Modelo de artículos
│   ├── Categoria.php      # Modelo de categorías
│   ├── Portafolio.php     # Modelo de portafolio
│   ├── Imagen.php         # Modelo de imágenes
│   └── Contacto.php       # Modelo de contactos
├── views/
│   ├── layouts/
│   │   └── main.php       # Layout principal
│   └── pages/             # Vistas de páginas
├── includes/
│   ├── Database.php       # Clase de conexión a BD
│   └── functions.php      # Funciones helper
├── install/
│   └── database.sql       # Script de instalación
├── uploads/               # Archivos subidos
│   ├── images/
│   └── documents/
└── index.php              # Página de inicio
```

---

## 🎯 Uso del Panel Administrativo

### Dashboard

El panel principal muestra:
- Total de artículos publicados
- Número de categorías
- Proyectos en portafolio
- Mensajes no leídos
- Últimos artículos publicados

### Gestión de Artículos

1. **Crear Artículo**:
   - Haz clic en "Artículos" → "Nuevo Artículo"
   - Completa el formulario con:
     - Título (el slug se genera automáticamente)
     - Extracto (resumen breve)
     - Contenido completo (editor WYSIWYG)
     - Imagen destacada
     - Categoría
     - Meta datos SEO (título, descripción, keywords)
   - Guarda como borrador o publica directamente

2. **Editor Visual**:
   - El editor incluye:
     - Formato de texto (negrita, cursiva, subrayado)
     - Encabezados H1-H6
     - Listas y tablas
     - Inserción de imágenes
     - Enlaces y videos de YouTube
     - Control de alineación

3. **Editar/Eliminar**:
   - Haz clic en el título del artículo para editar
   - Usa el botón eliminar para borrar (acción irreversible)

### Gestión de Categorías

1. **Crear Categoría**:
   - Nombre de la categoría
   - Descripción opcional
   - Orden de visualización
   - Estado (activa/inactiva)

2. **Consideraciones**:
   - No se pueden eliminar categorías con artículos asociados
   - El slug se genera automáticamente

### Gestión de Portafolio

1. **Crear Proyecto**:
   - Título y descripción
   - Imagen representativa
   - Tecnologías utilizadas (separadas por coma)
   - URL del proyecto (opcional)
   - URL de demo (opcional)
   - Marcar como destacado

### Biblioteca Multimedia

1. **Subir Imágenes**:
   - Formatos soportados: JPG, PNG, WEBP, GIF
   - Tamaño máximo: 5MB
   - Las imágenes se guardan en `uploads/images/`

2. **Insertar en Artículos**:
   - Desde el editor, haz clic en "Insertar imagen"
   - Selecciona de la biblioteca o sube una nueva

### Gestión de Contactos

- Visualiza todos los mensajes recibidos
- Marca como leído/respondido
- Elimina mensajes spam

---

## 🔒 Seguridad Implementada

### Protección CSRF
- Tokens únicos por sesión
- Validación en todos los formularios

### Protección XSS
- Sanitización de todas las entradas
- Escape de salidas HTML

### Protección SQL Injection
- Consultas preparadas con PDO
- Parámetros vinculados

### Contraseñas
- Hash bcrypt con salt automático
- Mínimo 8 caracteres recomendados

### Sesiones
- Timeout automático (1 hora)
- Regeneración de ID de sesión
- Cierre seguro

---

## 📊 SEO Implementado

- URLs amigables
- Meta tags dinámicos
- Open Graph para redes sociales
- Twitter Cards
- Sitemap XML (generar manualmente o con plugin)
- Robots.txt configurable
- Schema.org markup (pendiente de implementar)

---

## 🎨 Personalización

### Cambiar Colores

Edita `assets/css/style.css`:

```css
:root {
    --color-primary: #0d6efd;      /* Color principal */
    --color-primary-dark: #0a58ca; /* Color hover */
    /* ... otros colores */
}
```

### Cambiar Fuentes

El sitio usa Google Fonts (Poppins y Roboto). Para cambiar:

1. Edita el `<head>` en `views/layouts/main.php`
2. Reemplaza el link de Google Fonts
3. Actualiza las variables CSS `--font-primary` y `--font-secondary`

### Agregar Redes Sociales

Edita el footer en `views/layouts/main.php`:

```html
<div class="social-links">
    <a href="TU_URL_LINKEDIN" class="social-link"><i class="fab fa-linkedin"></i></a>
    <a href="TU_URL_GITHUB" class="social-link"><i class="fab fa-github"></i></a>
    <!-- Agrega más según necesites -->
</div>
```

---

## 🛠️ Solución de Problemas

### Error de Conexión a la Base de Datos

1. Verifica las credenciales en `config/database.php`
2. Asegúrate de que la base de datos existe
3. Comprueba que el usuario tiene permisos

### Error 404 en URLs Amigables

1. Verifica que mod_rewrite esté habilitado en Apache
2. Revisa la configuración de `.htaccess`
3. En Nginx, configura las reglas de rewrite apropiadamente

### Imágenes No Se Muestran

1. Verifica permisos de carpeta `uploads/`
2. Comprueba la ruta en `config/config.php`
3. Revisa que las imágenes se subieron correctamente

### Error al Subir Archivos

1. Verifica `upload_max_filesize` en php.ini
2. Verifica `post_max_size` en php.ini
3. Comprueba permisos de escritura en `uploads/`

---

## 📞 Soporte

Para asistencia técnica o personalizaciones adicionales, contacta a través del formulario en el sitio web.

---

## 📄 Licencia

Este proyecto es propiedad de Santiago Urquieta. Todos los derechos reservados.

---

**Última actualización**: Diciembre 2024  
**Versión**: 1.0.0
