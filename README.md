## 🚀 Guía de Inicio Rápido (Entorno Isard / Docker)

Esta guía explica cómo levantar el proyecto desde cero en un escritorio virtual de Isard (o cualquier entorno basado en Linux/Docker).

### Prerrequisitos

Asegúrate de tener instalados y corriendo en tu máquina:
* **Docker**
* **Docker Compose**
* **Git**

---

### Pasos de Instalación

#### 1. Clonar el repositorio
```bash
git clone <URL_DEL_REPOSITORIO>
cd <nombre-de-la-carpeta>
```

#### 2. Configurar variables de entorno
Copia la plantilla de entorno `.env.example` para crear tu fichero `.env` local:
```bash
cp .env.example .env
```

#### 3. Levantar los contenedores
Ejecuta el siguiente comando para construir y arrancar los servicios en segundo plano:

```bash
docker compose up -d --build
```

> **Nota:** Al arrancar por primera vez, el script `entrypoint.sh` se ejecutará automáticamente y realizará las siguientes tareas de forma desatendida:
> - Instalará las dependencias de Composer (`vendor/`).
> - Generará la `APP_KEY` de Laravel en tu `.env`.

#### 4. Migraciones y Seeders (Base de datos)
Una vez que los contenedores estén levantados, ejecuta las migraciones para preparar la base de datos:

```bash
docker compose exec app php artisan migrate --seed
```
*Verificación:* Si las migraciones finalizan sin errores, la base de datos está lista.

#### 5. Frontend (Vite)
Instala las dependencias del frontend e inicia el servidor de desarrollo:

```bash
# Instalar paquetes de Node
docker compose exec app npm install

# Arrancar el servidor de desarrollo
docker compose exec app npm run dev
```

---

### Acceso a la Aplicación

* **Backend / App:** `http://localhost` (o `http://localhost:8000` según tu `docker-compose.yml`)
* **Vite Dev Server:** `http://localhost:5173`

---

### Comandos Útiles del Día a Día

* **Detener los contenedores:** `docker compose down`
* **Ver logs de la aplicación:** `docker compose logs -f app`
* **Ejecutar comandos artisan:** `docker compose exec app php artisan <comando>`