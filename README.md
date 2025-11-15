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

### 🗄️ Arquitectura de Base de Datos

El sistema utiliza una estructura relacional bien definida que permite gestionar contenido jerárquico, etiquetado flexible y secciones personalizables. A continuación se detalla cada entidad y sus relaciones.

#### 📊 Diagrama de Relaciones

```
Users (id)
  └── Articles (id_article, user_id, category_id, ...)
       ├── Comments (id_comment, article_id, ...)
       └── Article_Tag (article_id, tag_id) ←→ Tags (id_tag)

Categories (id_category, parent_id)
  ├── Articles (id_article, category_id, ...)
  ├── Children (id_category, parent_id) [auto-relación]
  └── HomepageSections (id, category_id, ...)
```

#### 📝 Tabla: `articles`

**Propósito**: Almacena el contenido principal de las noticias.

**Campos principales**:
- `id_article` (PK): Identificador único del artículo.
- `title`: Título del artículo.
- `slug`: URL amigable única (ej: `mi-noticia-importante`).
- `content`: Contenido HTML completo del artículo (RichEditor).
- `excerpt`: Resumen corto para SEO y listados (opcional).
- `user_id` (FK → `users.id`): Autor del artículo. **Cascade delete**: si se elimina el usuario, se eliminan sus artículos.
- `category_id` (FK → `categories.id_category`): Categoría principal. **Restrict delete**: no se puede eliminar una categoría si tiene artículos.
- `published_at` (timestamp, nullable): Fecha/hora de publicación. Si es `null`, el artículo no se publica automáticamente.
- `status` (enum): Estado del artículo:
  - `draft`: Borrador (solo visible en admin).
  - `scheduled`: Programado (visible cuando `published_at` llegue).
  - `published`: Publicado (visible si `published_at <= now()`).
- `featured_image_url` (string, nullable): Ruta de la imagen destacada.
- `allows_comments` (boolean): Controla si se permiten comentarios.
- `created_at`, `updated_at`, `deleted_at` (soft deletes).

**Reglas de negocio**:
- Un artículo se muestra públicamente solo si `status = 'published'` Y `published_at <= now()`.
- El slug debe ser único en toda la tabla.
- Un artículo pertenece a una única categoría principal.
- Un artículo puede tener múltiples etiquetas (relación muchos-a-muchos).

**Relaciones**:
- `belongsTo(User)` → `author()`: Autor del artículo.
- `belongsTo(Category)` → `category()`: Categoría principal.
- `hasMany(Comment)` → `comments()`: Comentarios asociados.
- `belongsToMany(Tag)` → `tags()`: Etiquetas (tabla pivote `article_tag`).

---

#### 📂 Tabla: `categories`

**Propósito**: Organiza artículos en una estructura jerárquica (árbol de categorías).

**Campos principales**:
- `id_category` (PK): Identificador único de la categoría.
- `name`: Nombre de la categoría (ej: "Deportes", "Tecnología").
- `slug`: URL amigable única.
- `parent_id` (FK → `categories.id_category`, nullable): Categoría padre. Si es `null`, es una categoría raíz. **Null on delete**: si se elimina el padre, los hijos pasan a ser raíz.
- `created_at`, `updated_at`.

**Jerarquía**:
- El sistema soporta hasta **3 niveles de profundidad** (configurable en el trait `HasHierarchy`):
  - Nivel 0: Categorías raíz (ej: "Deportes").
  - Nivel 1: Subcategorías (ej: "Fútbol" bajo "Deportes").
  - Nivel 2: Sub-subcategorías (ej: "Liga Local" bajo "Fútbol").
- Se previenen ciclos infinitos: una categoría no puede ser padre de sus propios ancestros.
- Se valida la profundidad máxima antes de asignar un padre.

**Relaciones**:
- `hasMany(Article)` → `articles()`: Artículos asociados a esta categoría.
- `belongsTo(Category)` → `parent()`: Categoría padre (si existe).
- `hasMany(Category)` → `children()`: Subcategorías directas.
- `hasMany(HomepageSection)` → `homepageSection()`: Secciones de portada que usan esta categoría.

**Reglas de negocio**:
- No se puede eliminar una categoría que tenga artículos asociados (restricción de integridad referencial).
- Al eliminar una categoría padre, sus hijos pasan a ser raíz (`parent_id = null`).
- El slug debe ser único en toda la tabla.

**Métodos útiles del trait `HasHierarchy`**:
- `getDepth()`: Calcula la profundidad actual (0 = raíz).
- `canHaveChildren()`: Verifica si puede tener hijos según la profundidad máxima.
- `getAllAncestors()`: Obtiene todos los ancestros hacia arriba.
- `hasCircularReference($newParentId)`: Detecta si asignar un padre crearía un ciclo.
- `getFullPath($separator)`: Devuelve la ruta completa (ej: "Deportes > Fútbol > Liga Local").

---

#### 🏷️ Tabla: `tags`

**Propósito**: Etiquetas flexibles para clasificar artículos de forma transversal (no jerárquica).

**Campos principales**:
- `id_tag` (PK): Identificador único de la etiqueta.
- `name`: Nombre de la etiqueta (ej: "Breaking News", "Análisis").
- `slug`: URL amigable única.
- `created_at`, `updated_at`.

**Relaciones**:
- `belongsToMany(Article)` → `articles()`: Artículos etiquetados (tabla pivote `article_tag`).

**Reglas de negocio**:
- Un tag puede estar asociado a múltiples artículos.
- Un artículo puede tener múltiples tags.
- El slug debe ser único.

---

#### 🔗 Tabla pivote: `article_tag`

**Propósito**: Relación muchos-a-muchos entre artículos y etiquetas.

**Estructura**:
- `article_id` (FK → `articles.id_article`): **Cascade delete**: si se elimina un artículo, se eliminan sus relaciones con tags.
- `tag_id` (FK → `tags.id_tag`): **Cascade delete**: si se elimina un tag, se eliminan sus relaciones con artículos.
- Clave primaria compuesta: `PRIMARY KEY (article_id, tag_id)` para evitar duplicados.

**Reglas de negocio**:
- No puede haber duplicados: un artículo no puede tener la misma etiqueta dos veces.

---

#### 🏠 Tabla: `homepage_sections`

**Propósito**: Configura qué categorías y cómo se muestran en la página principal del sitio.

**Campos principales**:
- `id` (PK): Identificador único de la sección.
- `category_id` (FK → `categories.id_category`): Categoría que se mostrará en esta sección. **Cascade delete**: si se elimina la categoría, se elimina la sección.
- `display_title` (string, nullable): Título personalizado. Si es `null`, se usa el nombre de la categoría.
- `display_order` (integer, default: 0): Orden de aparición en la portada (0 = primero, 1 = segundo, etc.). Debe ser único.
- `is_active` (boolean, default: true): Activa/desactiva la sección sin eliminarla.
- `layout` (enum): Estilo visual de la sección:
  - `grid`: Rejilla de 6 artículos en formato cuadrícula.
  - `carousel`: Carrusel deslizante horizontal.
  - `magazine`: 1 artículo grande + 4 pequeños en formato revista.
- `created_at`, `updated_at`.

**Relaciones**:
- `belongsTo(Category)` → `category()`: Categoría asociada.

**Reglas de negocio**:
- Cada sección muestra los artículos **publicados** de su categoría asociada.
- El `display_order` debe ser único para evitar conflictos de ordenamiento.
- Solo las secciones con `is_active = true` aparecen en la portada.
- Las secciones se ordenan por `display_order` ascendente.

**Flujo en el frontend**:
1. El componente `HomePage` (Livewire) consulta todas las secciones activas ordenadas.
2. Para cada sección, obtiene los artículos publicados de su categoría.
3. Renderiza cada sección según su `layout` (grid, carousel, magazine).

---

#### 💬 Tabla: `comments`

**Propósito**: Comentarios de usuarios en artículos (sistema de moderación).

**Campos principales**:
- `id_comment` (PK): Identificador único del comentario.
- `article_id` (FK → `articles.id_article`): Artículo al que pertenece. **Cascade delete**: si se elimina el artículo, se eliminan sus comentarios.
- `author_name` (string): Nombre del autor del comentario.
- `author_email` (string): Email del autor.
- `content` (text): Contenido del comentario.
- `status` (enum): Estado de moderación:
  - `pending`: Pendiente de revisión (no visible públicamente).
  - `approved`: Aprobado (visible en el sitio).
  - `spam`: Marcado como spam (no visible).
- `created_at`, `updated_at`.

**Relaciones**:
- `belongsTo(Article)` → `article()`: Artículo asociado.

**Reglas de negocio**:
- Solo los comentarios con `status = 'approved'` se muestran públicamente.
- Los comentarios se pueden moderar desde el panel de Filament (RelationManager en Artículos).
- Un artículo debe tener `allows_comments = true` para permitir nuevos comentarios.

---

#### 🔐 Restricciones de Integridad Referencial

| Tabla Origen | Campo FK | Tabla Destino | Acción al Eliminar |
|--------------|----------|---------------|-------------------|
| `articles` | `user_id` | `users` | **CASCADE**: Elimina artículos del usuario. |
| `articles` | `category_id` | `categories` | **RESTRICT**: Impide eliminar categoría con artículos. |
| `comments` | `article_id` | `articles` | **CASCADE**: Elimina comentarios del artículo. |
| `article_tag` | `article_id` | `articles` | **CASCADE**: Elimina relaciones del artículo. |
| `article_tag` | `tag_id` | `tags` | **CASCADE**: Elimina relaciones del tag. |
| `categories` | `parent_id` | `categories` | **NULL ON DELETE**: Los hijos pasan a ser raíz. |
| `homepage_sections` | `category_id` | `categories` | **CASCADE**: Elimina secciones de la categoría. |

---

#### 📈 Consultas y Scopes Importantes

**Artículos publicados**:
```php
Article::published()->get(); // status='published' AND published_at <= now()
```

**Artículos por categoría**:
```php
$category->articles()->published()->latest('published_at')->get();
```

**Categorías raíz**:
```php
Category::whereNull('parent_id')->get();
```

**Secciones activas ordenadas**:
```php
HomepageSection::where('is_active', true)
    ->orderBy('display_order')
    ->with('category.articles')
    ->get();
```

**Comentarios aprobados**:
```php
$article->comments()->where('status', 'approved')->get();
```

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

   **Para desarrollo local** (recomendado):
   ```bash
   php artisan storage:link
   ```

   **Para servidores de producción** (Hostinger, cPanel, etc.):
   
   En algunos servidores compartidos no se puede ejecutar `php artisan storage:link` directamente. Sigue estos pasos manuales:
   
   1. Entra a tu carpeta `public` (la que está dentro de `public_html`):
      ```bash
      cd public
      ```
      (Tu ruta ahora será `.../public_html/public`)
   
   2. Verifica si ya existe un enlace o carpeta `storage`:
      ```bash
      ls -l
      ```
      - Si ves una **carpeta** `storage` (raro, pero posible), bórrala: `rm -r storage`
      - Si ves un **enlace** `storage` (probablemente roto), bórralo: `rm storage`
      - Si no ves nada, perfecto.
   
   3. Crea el enlace simbólico correcto desde la carpeta `public`:
      ```bash
      ln -s ../storage/app/public storage
      ```
      Esto creará el enlace `public_html/public/storage` que apunta correctamente a `public_html/storage/app/public`.
   
   **Verificación**: Después de crear el enlace, verifica que funciona:
      ```bash
      ls -l storage
      ```
      Deberías ver algo como: `storage -> ../storage/app/public`

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
- **Enlace simbólico de storage no funciona en producción**: si estás en un servidor compartido (Hostinger, cPanel, etc.) y `php artisan storage:link` no funciona, crea el enlace manualmente siguiendo los pasos del punto 6 de la instalación.
- **Traducciones**: si notas textos en inglés, limpia cachés (`php artisan config:clear && php artisan view:clear`) y vuelve a ejecutar `php artisan lang:update`.

---

### 📄 Licencia

Este proyecto se distribuye bajo la licencia que definas para tu repositorio (añade la sección correspondiente si aplica).

---

¿Dudas o sugerencias? Abre un issue en el repositorio. ¡Disfruta construyendo con NoticiasProject!

