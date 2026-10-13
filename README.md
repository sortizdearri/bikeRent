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

## Configurar variables de entorno
```bash

cp .env.example .env
```
## Instalar dependencias de Composer (Primer Arranque)
Como la carpeta vendor/ no se sube al repositorio, utilizamos un contenedor temporal para instalar Composer:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs

    ```

## Levantar los contenedores Sail

```bash
./vendor/bin/sail up -d
```
## Configurar clave y base de datos

```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
```
## Instalar e iniciar el Frontend (Local)

Abre una terminal normal en tu máquina (fuera de Docker):

```bash
npm install
npm run dev
```




