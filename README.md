# 🚀 Proyecto Laravel + Frontend (Entorno Híbrido Docker/Local)

Este repositorio utiliza **Laravel Sail (Docker)** para el backend/API y la base de datos, garantizando un entorno idéntico entre desarrolladores, y **Vite en la máquina local** para asegurar un rendimiento óptimo y rápido durante el desarrollo del frontend.

---

## 📋 Prerrequisitos

Antes de comenzar la instalación, asegúrate de tener instalado en tu equipo:

* **Git**: [Descargar Git](https://git-scm.com/)
* **Node.js** (v18 o superior) y **npm** (para ejecutar Vite en local).
* **Docker Engine / Desktop**:
  * **Windows**: [Docker Desktop](https://www.docker.com/products/docker-desktop/) iniciado y configurado.
  * **Ubuntu / Linux**: Docker Engine + Docker Compose habilitados en tu escritorio virtual.

---

## 🐧 Guía de Instalación en Ubuntu / Linux (Isard)

### 1. Clonar el repositorio
```bash
git clone <URL_DEL_REPOSITORIO>
cd <nombre-de-la-carpeta>
```

### 2. Configurar variables de entorno
```bash
cp .env.example .env
```

### 3. Instalar dependencias de Composer (Primer arranque)
Como la carpeta `vendor/` no se sube al repositorio, utilizamos un contenedor temporal para instalar Composer:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

### 4. Levantar los contenedores de Sail
```bash
./vendor/bin/sail up -d
```

### 5. Configurar clave y base de datos
```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
```

### 6. Instalar e iniciar el Frontend (Local)
Abre una terminal normal en tu máquina (fuera de Docker):

```bash
npm install
npm run dev
```

---

## 🪟 Guía de Instalación en Windows (PowerShell)

Abre **PowerShell** en la carpeta donde desees clonar el proyecto:

### 1. Clonar el repositorio
```powershell
git clone <URL_DEL_REPOSITORIO>
cd <nombre-de-la-carpeta>
```

### 2. Configurar variables de entorno
```powershell
Copy-Item .env.example .env
```

### 3. Instalar dependencias de Composer (Primer arranque)
```powershell
docker run --rm `
    -v "${PWD}:/var/www/html" `
    -w /var/www/html `
    laravelsail/php83-composer:latest `
    composer install --ignore-platform-reqs
```

### 4. Levantar los contenedores de Sail
```powershell
./vendor/bin/sail up -d
```

### 5. Configurar clave y base de datos
```powershell
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
```

### 6. Instalar e iniciar el Frontend (Local)
Abre una ventana de PowerShell en la raíz del proyecto (sin usar Sail):

```powershell
npm install
npm run dev
```

---

## ⚙️ Configuración del `.env` para Desarrollo Híbrido

Asegúrate de que tu fichero `.env` contenga las siguientes direcciones de comunicación:

```env
APP_URL=http://localhost
VITE_APP_URL="${APP_URL}"
```

---

## 🌐 Puertos y Accesos en Desarrollo

* **Frontend (Vite Server - Local):** `http://localhost:5173`
* **Backend / API (Laravel - Docker):** `http://localhost`
* **Base de datos (MySQL/PostgreSQL):** `localhost:3306` (o el puerto configurado en Sail)

---

## 🛠️ Flujo de Trabajo y Comandos Habituales

### Comandos de Backend (vía Laravel Sail)

* **Iniciar servicios:** `./vendor/bin/sail up -d`
* **Detener servicios:** `./vendor/bin/sail down`
* **Ver logs de la aplicación:** `./vendor/bin/sail logs -f`
* **Ejecutar comandos Artisan:** `./vendor/bin/sail artisan <comando>`
* **Ejecutar pruebas:** `./vendor/bin/sail test`

### Comandos de Frontend (en la máquina host)

* **Servidor de desarrollo (HMR):** `npm run dev`
* **Compilar para producción:** `npm run build`

---

## 💡 Solución de Problemas Frecuentes (Troubleshooting)

* **Error de permisos en carpetas (Ubuntu/Linux):**
  Si tienes problemas al escribir en `storage` o `bootstrap/cache`:
  ```bash
  sudo chmod -R 777 storage bootstrap/cache
  ```

* **Conflicto de puertos al ejecutar Sail:**
  Si el puerto 80 o 3306 ya está ocupado en tu equipo local, puedes cambiar el puerto de exposición en tu fichero `.env`:
  ```env
  FORWARD_DB_PORT=3307
  APP_PORT=8080
  ```