## NoticiasProject

Plataforma de noticias construida con **Laravel 12**, **Filament v3** y **Livewire**.  
El proyecto ofrece un frontend estilo revista y un panel administrativo completo para gestionar artículos, categorías, secciones destacadas y comentarios.

---

### 📰 Características principales

- **Gestión de artículos** con editor enriquecido, imagen destacada, etiquetas y control de visibilidad (`Publicado`, `Programado`, `Borrador`).
- **Reglas de publicación**:
  - Un artículo se muestra en el sitio público solo si su `status` es `published`.
  - La fecha `published_at` debe ser igual o anterior al momento actual.
  - Los comentarios solo se permiten cuando `allows_comments` está activo.
- **Jerarquía de categorías** con soporte para categorías padre / hijo y contador automático de artículos asociados.
- **Secciones de portada configurables** (`grid`, `carousel`, `magazine`) para controlar la composición de la página principal.
- **Moderación de comentarios** desde el panel de Filament con estados (`Aprobado`, `Pendiente`, `Spam`).
- **Protección al eliminar**: no es posible borrar una categoría que todavía tiene artículos relacionados.
- **Localización completa en español** utilizando [`laravel-lang`](https://laravel-lang.com/packages-common.html).

---

### 🧱 Arquitectura y tecnologías

- **Backend**: Laravel 12, PHP 8.2+, SQLite/MySQL/PostgreSQL (configurable).
- **Panel administrativo**: Filament v3 con componentes personalizados y Livewire.
- **Frontend**: Blade + Livewire, Tailwind CSS y Vite; carruseles con Swiper.js.
- **Base de datos**: modelos `Article`, `Category`, `Comment`, `HomepageSection`, `Tag`, `User`.
- **Internacionalización**: `laravel-lang/common` + `lang:update` para mantener traducciones.

---

### 📦 Requisitos previos

- PHP 8.2 o superior con las extensiones de Laravel.
- Composer 2.x.
- Node.js 18.x o superior y npm.
- Una base de datos (SQLite por defecto, configurable).

---

### 🚀 Instalación y puesta en marcha

1. Clona el repositorio:
   ```bash
   git clone <URL_DEL_REPO>
   cd NoticiasProject
   ```

2. Instala dependencias PHP y JS:
   ```bash
   composer install
   npm install
   ```

3. Copia el archivo de entorno y genera la clave de la app:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Configura tu base de datos en `.env` (por ejemplo `DB_CONNECTION=sqlite` y apunta `DB_DATABASE` al archivo deseado).

5. Ejecuta migraciones y seeders iniciales:
   ```bash
   php artisan migrate --seed
   ```
   - El seeder crea un usuario administrador por defecto `admin@admin.com` / `password`.

6. Crea el enlace simbólico de storage:
   ```bash
   php artisan storage:link
   ```

7. Compila los assets:
   ```bash
   npm run dev   # para entorno de desarrollo
   # o
   npm run build # para producción
   ```

8. Lanza el servidor de desarrollo:
   ```bash
   php artisan serve
   ```

Accede al frontend en `http://localhost:8000` y al panel admin en `http://localhost:8000/admin`.

---

### 🌐 Localización en español

El proyecto incluye traducciones actualizadas mediante `laravel-lang/common`.  
Después de instalar nuevas dependencias ejecuta:
```bash
php artisan lang:update
```

También se recomienda establecer en `config/app.php`:
```php
'locale' => 'es',
'fallback_locale' => 'es',
'faker_locale' => 'es_ES',
```

En `config/filament.php` asegúrate de tener:
```php
'locales' => ['es'],
'default_locale' => 'es',
```

---

### 🧭 Flujo de trabajo en el panel de administración

#### Artículos
- Crear/editar desde **Contenido → Artículos**.
- Definir título, slug (generado automáticamente al crear), contenido, resumen y metadatos.
- Relacionar con un autor, categoría principal y etiquetas.
- Controlar si admite comentarios y cuándo debe ser visible mediante el estado y la fecha de publicación.

#### Categorías
- Gestión jerárquica: asigna una categoría padre para crear árboles.
- Los contadores muestran cuántos artículos y subcategorías directas tiene cada nodo.
- No se pueden eliminar categorías que tengan artículos vinculados.

#### Secciones de portada
- Definen qué categorías aparecen en la página principal y con qué layout (`grid`, `carousel`, `magazine`).
- Pueden personalizar título público, orden de aparición y visibilidad.

#### Comentarios
- Moderación desde el relation manager en Artículos (aprobar, marcar como spam, etc.).

---

### 📰 Renderizado del sitio público

- **Home** (`/`): Livewire arma la portada combinando:
  - Sección principal (hero) con la noticia destacada más reciente.
  - Listado de últimas noticias.
  - Carruseles y rejillas definidos en `HomepageSection`.
- **Categorías** (`/categoria/{slug}`): muestra artículos publicados de la categoría seleccionada.
- **Artículos** (`/articulo/{slug}`): detalle del artículo con contenido, breadcrumbs, etiquetas y bloque de comentarios (si están habilitados).
- **Búsqueda** (`/buscar?q=`): resultados filtrados en función del término ingresado.

Recuerda: solo se listan artículos `published` cuya fecha `published_at` es pasada o actual. Los artículos `scheduled` se ocultan hasta que se alcance su fecha; los `draft` únicamente se ven dentro de Filament.

---

### 🧪 Tests y herramientas útiles

- Ejecutar suite de tests:
  ```bash
  php artisan test
  ```
- Formateo/Lint (opcional):
  ```bash
  ./vendor/bin/pint
  ```
- Actualizar traducciones:
  ```bash
  php artisan lang:update
  ```

---

### 🛠 Troubleshooting

- **Categoría con artículos**: no podrás eliminarla hasta reasignar o borrar los artículos relacionados; el panel mostrará una notificación.
- **Assets faltantes**: asegúrate de haber corrido `npm run dev` y `php artisan storage:link`.
- **Traducciones**: si notas textos en inglés, limpia cachés (`php artisan config:clear && php artisan view:clear`) y vuelve a ejecutar `php artisan lang:update`.

---

### 📄 Licencia

Este proyecto se distribuye bajo la licencia que definas para tu repositorio (añade la sección correspondiente si aplica).

---

¿Dudas o sugerencias? Abre un issue en el repositorio. ¡Disfruta construyendo con NoticiasProject!

