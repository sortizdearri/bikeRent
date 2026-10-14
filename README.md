# BikeRent — Laravel + Blade + Vite

Proyecto de alquiler de bicicletas desarrollado en 2.º DAW. El backend se ejecuta con **Laravel Sail sobre Docker**, junto con MySQL y los servicios auxiliares definidos en `compose.yaml`. Para desarrollo, **Vite se ejecuta en el equipo anfitrión** (Windows o Ubuntu/Isard) y sirve los recursos CSS y JavaScript utilizados por las vistas Blade.

> La aplicación se visita en **http://localhost:8000**. El puerto 5173 corresponde al servidor de recursos de Vite, **no** a una aplicación frontend independiente.

## 1. Requisitos

- Git.
- Docker Desktop con motor Linux/WSL2 en Windows, o Docker Engine y el complemento Docker Compose en Ubuntu (Isard).
- Node.js compatible con Vite 8 (**22.12 o superior**, o una versión superior compatible) y npm.
- VS Code (recomendado).

No hace falta instalar PHP, Composer ni MySQL en el sistema anfitrión.

## 2. Primera instalación en Windows (PowerShell)

Abre PowerShell en la carpeta donde quieras guardar el proyecto.

### 2.1. Clonar

```powershell
git clone https://github.com/sortizdearri/bikeRent.git
cd bikeRent
```

Por defecto se clona la rama principal. Para trabajar en una rama existente, primero consulta las ramas y cambia a la que te hayan asignado:

```powershell
git fetch origin
git branch -a
# Ejemplo, solo si debes trabajar en esta rama:
git switch --track origin/dev/pablo
```

### 2.2. Configurar el entorno

```powershell
Copy-Item .env.example .env
```

Comprueba en `.env` al menos estos valores (ajusta los puertos si están ocupados):

```dotenv
APP_NAME=BikeRent
APP_URL=http://localhost:8000
APP_PORT=8000

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=bikerent
DB_USERNAME=sail
DB_PASSWORD=password

WWWUSER=1000
WWWGROUP=1000
```

Los valores de contraseña son solo de ejemplo para **desarrollo local**. No subas `.env` a Git.

### 2.3. Instalar las dependencias PHP

La carpeta `vendor/` no se versiona; es necesaria antes de construir Laravel Sail porque `compose.yaml` usa un Dockerfile alojado en `vendor/laravel/sail`.

```powershell
docker run --rm -v "${PWD}:/app" -w /app composer:2 install --no-interaction
```

Esta operación usa un contenedor temporal. Al terminar, el contenedor se elimina y `vendor/` permanece en el equipo. Si Composer informa de requisitos de plataforma incompatibles, no los ignores automáticamente: revisa el error.

### 2.4. Construir y arrancar Docker

```powershell
docker compose up -d --build
docker compose ps
```

La primera construcción puede tardar varios minutos. El archivo `compose.yaml` de este repositorio define Laravel, MySQL, phpMyAdmin, Redis, Meilisearch, Mailpit y Selenium; **no hace falta modificarlo para completar esta instalación**.

### 2.5. Inicializar Laravel y la base de datos

Si `APP_KEY` está vacío en `.env`:

```powershell
docker compose exec laravel.test php artisan key:generate
```

Después, ejecuta las migraciones:

```powershell
docker compose exec laravel.test php artisan migrate
```

Si el equipo docente indica que hay datos iniciales preparados mediante *seeders*, ejecuta además:

```powershell
docker compose exec laravel.test php artisan db:seed
```

### 2.6. Arrancar Vite en Windows

En otra terminal de PowerShell, situada en la raíz del repositorio:

```powershell
npm install
npm run dev
```

Mantén esta terminal abierta durante el desarrollo. Si PowerShell bloquea `npm.ps1`, utiliza `npm.cmd install` y `npm.cmd run dev`.

Abre **http://localhost:8000** para visitar BikeRent. La presencia del formulario de acceso depende de la rama que tengas seleccionada.

## 3. Primera instalación en Ubuntu (Isard)

### 3.1. Clonar y seleccionar la rama de trabajo

```bash
git clone https://github.com/sortizdearri/bikeRent.git
cd bikeRent
git fetch origin
git branch -a
# Ejemplo, solo si debes trabajar en esta rama:
git switch --track origin/dev/pablo
```

### 3.2. Crear `.env`

```bash
cp .env.example .env
```

Comprueba los mismos valores de aplicación y MySQL indicados en el apartado de Windows. Según el usuario y los permisos de la instalación Linux, puede ser necesario adaptar `WWWUSER` y `WWWGROUP`:

```bash
id -u
id -g
```

### 3.3. Instalar Composer mediante un contenedor temporal

```bash
docker run --rm \
  -u "$(id -u):$(id -g)" \
  -v "$(pwd):/app" \
  -w /app \
  composer:2 install --no-interaction
```

Si hay errores de escritura, revisa el propietario y los permisos de la carpeta del proyecto. Evita dar permisos `777` a directorios completos.

### 3.4. Construir y arrancar los servicios

```bash
docker compose up -d --build
docker compose ps
```

### 3.5. Inicializar Laravel

Si `APP_KEY` está vacío:

```bash
docker compose exec laravel.test php artisan key:generate
```

Crear las tablas:

```bash
docker compose exec laravel.test php artisan migrate
```

Opcionalmente, si el proyecto utiliza datos iniciales:

```bash
docker compose exec laravel.test php artisan db:seed
```

### 3.6. Instalar el frontend y arrancar Vite

```bash
npm install
npm run dev
```

Abre **http://localhost:8000** en el navegador del entorno correspondiente. Esta guía de Ubuntu debe validarse en las máquinas de Isard del centro antes de darse por definitiva.

## 4. Accesos y servicios

Con la configuración de desarrollo utilizada actualmente:

| Servicio | Dirección o puerto |
| --- | --- |
| Aplicación Laravel (Blade) | http://localhost:8000 |
| Vite (recursos y HMR) | http://localhost:5173, si está libre |
| phpMyAdmin | http://localhost:8080 |
| Mailpit (bandeja de pruebas) | http://localhost:8025 |
| MySQL (desde el equipo anfitrión) | `localhost:3306` |

En la red interna de Docker, Laravel se conecta a MySQL mediante `DB_HOST=mysql` y `DB_PORT=3306`. No se usa `localhost` como host de MySQL dentro del contenedor de Laravel.

> Si Vite indica que el puerto 5173 está ocupado, puede utilizar otro. Comprueba la salida de `npm run dev` y evita ejecutar simultáneamente otro servidor Vite en ese puerto. Los puertos publicados pueden configurarse en `.env` o `compose.yaml` según corresponda.

## 5. Trabajo diario

Desde la raíz del repositorio:

**Arrancar los servicios Docker**:

```bash
docker compose up -d
```

**Arrancar Vite en una segunda terminal**:

```bash
npm run dev
```

**Comprobar el estado de los contenedores**:

```bash
docker compose ps
```

**Detener los servicios** (sin borrar los volúmenes de MySQL):

```bash
docker compose down
```

**Consultar los registros de Laravel**:

```bash
docker compose logs -f laravel.test
```

**Ejecutar migraciones**:

```bash
docker compose exec laravel.test php artisan migrate
```

**Ejecutar las pruebas de Laravel**:

```bash
docker compose exec laravel.test php artisan test
```

**Compilar los recursos frontend para producción**:

```bash
npm run build
```

No es necesario reconstruir las imágenes con `--build` en cada arranque; hazlo si cambia la configuración de construcción o el equipo docente lo indica.

## 6. Qué archivos se comparten en Git

**Se versionan** el código PHP, las vistas Blade, los recursos CSS/JS, las migraciones, `compose.yaml`, `composer.json`, `composer.lock`, `package.json`, el archivo de bloqueo de npm y `.env.example`.

**No se versionan** `.env`, `vendor/`, `node_modules/`, datos locales ni credenciales. Cada equipo dispone de su configuración `.env` y de sus propios volúmenes Docker.

Las migraciones permiten reproducir la **estructura** de la base de datos. Los datos de cada instalación no se comparten automáticamente mediante Git.

## 7. Ramas y colaboración

El proyecto comparte repositorio entre el trabajo de Servidor y Cliente/DIW. Antes de desarrollar:

```bash
git branch --show-current
git status
git fetch origin
```

Trabaja en la rama acordada con el equipo docente y utiliza *pull requests* para integrar cambios. No modifiques directamente la rama de otra persona sin coordinarlo previamente. Las ramas actualmente empleadas incluyen `main`, `feature/login` y `dev/pablo`; esto no establece por sí solo una política definitiva de integración.

## 8. Resolución de problemas

### `vendor/` no existe

Ejecuta el paso de instalación de Composer correspondiente a tu sistema antes de `docker compose up -d --build`.

### Laravel devuelve un error 500

Revisa `.env`, `APP_KEY`, las migraciones y los registros:

```bash
docker compose logs --tail=100 laravel.test
docker compose exec laravel.test php artisan optimize:clear
```

### MySQL no conecta

Verifica que el servicio esté iniciado (`docker compose ps`) y que dentro del `.env` de Laravel figure `DB_HOST=mysql`.

### No se actualiza CSS o JavaScript

Comprueba que `npm run dev` continúa activo. Laravel carga los recursos a través de la directiva Blade `@vite(...)`.

### Faltan estilos en el login

Algunas vistas de autenticación del proyecto usan clases de Tailwind. Si se elimina Tailwind, esas clases dejan de proporcionar estilos. El formulario puede seguir funcionando; su adaptación a CSS propio es una tarea independiente.

### Error por un puerto ocupado

Revisa los puertos publicados con `docker compose ps`. Puedes modificar, por ejemplo, `APP_PORT` o `FORWARD_DB_PORT` en `.env` y recrear los servicios cuando sea necesario.

### Problemas de permisos en Ubuntu

Comprueba el propietario y los permisos de los directorios afectados, especialmente `storage/` y `bootstrap/cache/`. Corrige los permisos específicos; **no utilices `chmod -R 777`** como solución general.

### Precaución con los datos

`docker compose down` detiene y elimina contenedores, pero conserva los volúmenes nombrados. **No ejecutes `docker compose down -v` si quieres conservar la base de datos local.**

---

**Nota:** este README documenta el entorno híbrido que se ha probado en Windows y conserva la infraestructura de la profesora de Servidor. El despliegue en Ubuntu/Isard está pendiente de validación en ese entorno.
