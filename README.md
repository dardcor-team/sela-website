# Sela-Website

Laravel 12 backend for the Sela project management platform. The current repo is configured around **Supabase PostgreSQL** and **Laravel Sanctum**.

> Status note: this repository still contains some legacy controller/service naming (`UserAbility`, `SubTask`, `SupportFile`, etc.). The PostgreSQL schema is now managed natively through standard Laravel Migrations (`database/migrations/`) and JSON-backed Seeders, fully replacing the legacy `db.sql` import.

**Production:** `https://sela.my.id/api`

## Tech Stack

- **Framework:** Laravel 12
- **PHP:** `8.4` (in Docker), `^8.2` (local development)
- **Auth:** Laravel Sanctum bearer tokens
- **Database:** PostgreSQL via Supabase
- **Frontend assets:** Vite (compiled during Docker build)
- **Web Server:** Nginx (reverse proxy on port 8000)
- **Deployment:** Docker on Render (recommended) or AWS EC2
- **ETHOL Integration:** PENS academic portal login flow

## Project Structure

- `routes/api.php` — active API routes
- `app/Http/Controllers/Api` — API controllers
- `app/Models` — current Eloquent models
- `database/migrations/` — standard Laravel migration files handling schema, RLS policies, and triggers
- `database/seeders/` & `database/data/` — JSON-backed seeders for initial data (courses, classes, test users)
- `deploy/` — deployment scripts and Nginx config

## Local Setup (Cara Menjalankan Server)

1. Clone repository dan masuk ke folder backend.
2. Salin file environment:
   ```bash
   cp .env.example .env
   ```
3. Set konfigurasi database Anda di dalam file `.env` (contoh untuk Supabase/PostgreSQL ada di bawah).
4. Jalankan perintah berikut secara berurutan untuk menginisialisasi dan menjalankan server:

```bash
composer install
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
php artisan serve --host=<YOUR-IP> --port=8000
```

### Supabase / PostgreSQL example

Pooled connection URL:

```env
DB_CONNECTION=pgsql
DB_URL=postgresql://postgres.YOUR_PROJECT_REF:YOUR_PASSWORD@aws-1-ap-southeast-1.pooler.supabase.com:5432/postgres
DB_SSLMODE=require
```

### Frontend assets

This repo includes Vite assets. If you change frontend files, run:

```bash
npm install
npm run build
```

 > `public/build/` may already be present in the repo, so asset build is not always required just to boot the backend.

## Docker Setup

This repo includes a multi-stage Dockerfile that builds both frontend (Node.js/Vite) and backend (PHP 8.4 + Nginx) in a single container. This is the recommended way to run the application in production and on Render.

### Docker Architecture

The Dockerfile uses **two-stage build**:

1. **Stage 1 (Frontend)**: Node.js 20 compiles Vite assets → outputs to `public/build/`
2. **Stage 2 (Backend)**: PHP 8.4-FPM + Nginx
   - Copies compiled frontend assets
   - Installs PHP dependencies with Composer
   - Runs Nginx on port 8000 as reverse proxy
   - Nginx forwards PHP requests to PHP-FPM internally

**Why Nginx?** PHP-FPM alone cannot listen on HTTP ports. Nginx accepts requests on port 8000 and forwards them to PHP-FPM on localhost:9000.

### Local Docker Deployment

#### Prerequisites

- Docker Desktop installed ([get it here](https://www.docker.com/products/docker-desktop))
- Docker Compose (comes with Docker Desktop)

#### Build the image

```bash
docker build -t sela-backend:latest .
```

#### Run container locally

```bash
docker run -d \
  --name sela-backend \
  -p 8000:8000 \
  -e DB_CONNECTION=pgsql \
  -e DB_HOST=your-supabase-host \
  -e DB_PORT=5432 \
  -e DB_DATABASE=postgres \
  -e DB_USERNAME=postgres.YOUR_PROJECT_REF \
  -e DB_PASSWORD=your-password \
  -e DB_SSLMODE=require \
  -e APP_KEY=your-app-key \
  sela-backend:latest
```

Access the API at `http://localhost:8000/api`

#### Using Docker Compose (easier)

Create `docker-compose.yml` (if not present):

```yaml
version: '3.8'

services:
  sela-backend:
    build: .
    ports:
      - "8000:8000"
    environment:
      DB_CONNECTION: pgsql
      DB_HOST: ${DB_HOST}
      DB_PORT: 5432
      DB_DATABASE: postgres
      DB_USERNAME: ${DB_USERNAME}
      DB_PASSWORD: ${DB_PASSWORD}
      DB_SSLMODE: require
      APP_KEY: ${APP_KEY}
      APP_URL: http://localhost:8000
    restart: unless-stopped
```

Then run:

```bash
docker-compose up -d
```

View logs:

```bash
docker-compose logs -f sela-backend
```

Stop the container:

```bash
docker-compose down
```

### Render Deployment

Render automatically detects `Dockerfile` and `docker-compose.yml` in your repo.

#### Steps

1. **Push to GitHub** and connect your repo to Render
2. **Create Web Service** on Render
3. **Configure environment variables** in Render dashboard:
   ```
   DB_HOST=your-supabase-host
   DB_USERNAME=postgres.YOUR_PROJECT_REF
   DB_PASSWORD=your-password
   DB_SSLMODE=require
   APP_KEY=your-generated-key
   APP_URL=https://your-app.onrender.com
   ```
4. **Render auto-detects** port 8000 from `EXPOSE 8000` in Dockerfile
5. **Deploy** — your app is live at `https://your-app.onrender.com/api`

**Note:** Render uses free tier databases that might sleep. Keep your Supabase project active.

### Environment Variables for Docker

| Variable | Required | Example |
|----------|----------|---------|
| `DB_CONNECTION` | Yes | `pgsql` |
| `DB_HOST` | Yes | `aws-1-ap-southeast-1.pooler.supabase.com` |
| `DB_PORT` | No | `5432` |
| `DB_DATABASE` | Yes | `postgres` |
| `DB_USERNAME` | Yes | `postgres.YOUR_PROJECT_REF` |
| `DB_PASSWORD` | Yes | Your Supabase password |
| `DB_SSLMODE` | Yes | `require` |
| `APP_KEY` | Yes | Generated by `php artisan key:generate` |
| `APP_URL` | No | `http://localhost:8000` or `https://your-app.onrender.com` |

### Troubleshooting Docker

#### Container exits immediately

Check logs:
```bash
docker logs sela-backend
```

Common causes:
- Missing `APP_KEY` → generate with `php artisan key:generate`
- Database unreachable → verify `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`
- Port 8000 already in use → `docker run -p 8001:8000 ...`

#### "Address already in use"

```bash
docker ps  # List running containers
docker stop <container-id>
```

#### Rebuild after code changes

```bash
docker build --no-cache -t sela-backend:latest .
docker-compose down && docker-compose up -d
```

#### Execute commands inside running container

```bash
docker exec sela-backend php artisan migrate
docker exec sela-backend php artisan config:cache
```

#### View Nginx/PHP logs

```bash
docker exec sela-backend tail -f /var/log/nginx/error.log
```

### Performance Notes

- **Build time**: ~2-3 minutes (first build), <1 minute (cached)
- **Image size**: ~800MB (includes Node, PHP, Nginx)
- **RAM usage**: ~200-300MB idle
- **Cold start**: ~10-15s on Render free tier (normal)

## Authentication

Public routes:

- `POST /api/register`
- `POST /api/login`
- `POST /api/ethol/login`

All other API routes are protected by `auth:sanctum`.

Typical headers:

| Header | Value |
|---|---|
| `Accept` | `application/json` |
| `Authorization` | `Bearer {token}` |

## API Routes

This section reflects the current `routes/api.php` file.

### Auth

| Method | Endpoint |
|---|---|
| `POST` | `/api/register` |
| `POST` | `/api/login` |
| `POST` | `/api/logout` |
| `GET` | `/api/me` |

### Dashboard

| Method | Endpoint |
|---|---|
| `GET` | `/api/dashboard/{user_id}` |

### Users

| Method | Endpoint |
|---|---|
| `GET` | `/api/users` |
| `GET` | `/api/users/{id}` |
| `PUT` | `/api/users/{id}` |
| `DELETE` | `/api/users/{id}` |

### Abilities

| Method | Endpoint |
|---|---|
| `GET` | `/api/abilities` |
| `POST` | `/api/abilities` |
| `GET` | `/api/abilities/{ability}` |
| `PUT/PATCH` | `/api/abilities/{ability}` |
| `DELETE` | `/api/abilities/{ability}` |

### User Abilities

| Method | Endpoint |
|---|---|
| `GET` | `/api/users/{userId}/abilities` |
| `POST` | `/api/users/{userId}/abilities` |
| `PUT` | `/api/user-abilities/{id}` |
| `DELETE` | `/api/user-abilities/{id}` |

### Groups

| Method | Endpoint |
|---|---|
| `GET` | `/api/groups/user/{user_id}` |
| `GET` | `/api/groups/{group_id}` |
| `POST` | `/api/groups` |
| `POST` | `/api/groups/join` |

### Group Members

| Method | Endpoint |
|---|---|
| `GET` | `/api/groups/{groupId}/members` |
| `POST` | `/api/groups/{groupId}/members` |
| `PUT` | `/api/group-members/{id}` |
| `DELETE` | `/api/groups/{groupId}/members/{userId}` |

### Tasks

| Method | Endpoint |
|---|---|
| `GET` | `/api/tasks/user/{user_id}` |
| `GET` | `/api/tasks/{task_id}/detail/{user_id}` |
| `POST` | `/api/tasks` |

### Subtasks

| Method | Endpoint |
|---|---|
| `POST` | `/api/tasks/{task_id}/subtasks` |
| `PATCH` | `/api/subtasks/{subtask_id}/status` |
| `DELETE` | `/api/subtasks/{id}` |

### Subtask Assignments

| Method | Endpoint |
|---|---|
| `GET` | `/api/sub-tasks/{subTaskId}/assignments` |
| `POST` | `/api/sub-tasks/{subTaskId}/assignments` |
| `PUT` | `/api/sub-task-assignments/{id}` |
| `DELETE` | `/api/sub-task-assignments/{id}` |

### Support Files

| Method | Endpoint |
|---|---|
| `GET` | `/api/tasks/{taskId}/files` |
| `POST` | `/api/tasks/{taskId}/files` |
| `GET` | `/api/support-files/{id}` |
| `DELETE` | `/api/support-files/{id}` |

### Task Generations

| Method | Endpoint |
|---|---|
| `GET` | `/api/tasks/{taskId}/generations` |
| `POST` | `/api/tasks/{taskId}/generations` |
| `GET` | `/api/task-generations/{id}` |
| `DELETE` | `/api/task-generations/{id}` |

### ETHOL

| Method | Endpoint |
|---|---|
| `POST` | `/api/ethol/login` |
| `POST` | `/api/ethol/logout` |
| `GET` | `/api/ethol/schedule` |
| `GET` | `/api/ethol/homework` |
| `GET` | `/api/ethol/attendance` |
| `GET` | `/api/ethol/token` |

## Database Schema

The backend database is structured for **PostgreSQL/Supabase** and is managed via native Laravel Migrations.

### Migrations & Seeders

- **Schema:** Defined structurally inside `database/migrations/` using standard Laravel Schema Blueprints.
- **Supabase native features:** Row Level Security (RLS), native Triggers, PostgreSQL Functions, and `supabase_realtime` publications are injected safely via raw DB statements in a dedicated migration (`_apply_supabase_rls_and_triggers.php`).
- **Seed Data:** Initial setup data (like default courses, classes, and test accounts) is stored in `database/data/*.json` and seeded automatically when you run `php artisan migrate --seed`.

### Main tables

- `profiles` (links to `auth.users`)
- `courses`
- `classes`
- `groups`
- `group_members`
- `tasks`
- `subtasks`
- `subtask_progress`
- `task_links`
- `task_files`
- `profile_abilities`
- `notifications`

### Schema notes

- IDs are mostly UUIDs.
- `profiles.id` references the `users` table, resolving local relationships while integrating smoothly with Supabase `auth.users` behavior.
- Storage policies depend on Supabase `storage.objects` and `storage.buckets`.
- Several RLS policies depend on Supabase `auth.uid()`.
- Auth triggers mapped from Supabase synchronize directly into the `profiles` table.

## Current Models

The active Eloquent models currently present in `app/Models` are:

- `User`
- `Profile`
- `Course`
- `SchoolClass`
- `Group`
- `GroupMember`
- `Task`
- `Subtask`
- `SubtaskProgress`
- `TaskFile`
- `TaskLink`
- `ProfileAbility`
- `Notification`

## Known Repo Caveat

This repo is in a transition state:

- the **database schema** and **models** already point toward the Supabase/UUID structure
- some **route names**, **controller names**, and **service names** still reflect the older Laravel schema terminology

If documentation and implementation ever disagree, use these as the source of truth in order:

1. `routes/api.php`
2. `database/migrations/` (The new source of truth for the schema)
3. `app/Models`

## Deployment

Deploy target: AWS EC2 + Nginx + PHP-FPM + Supabase PostgreSQL.

### Server bootstrap

```bash
ssh -i "your-key.pem" ubuntu@YOUR_EC2_IP
sudo git clone -b backend https://github.com/Dardcor/Sela-Website.git /var/www/sela-website
sudo bash /var/www/sela-website/deploy/setup-server.sh
```

### Environment

```bash
cd /var/www/sela-website
sudo cp .env.example .env
sudo nano .env
```

Minimum important settings:

```env
APP_URL=https://your-domain.example
DB_CONNECTION=pgsql
DB_HOST=...
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=...
DB_PASSWORD=...
DB_SSLMODE=require
```

### Deploy

```bash
sudo bash deploy/deploy.sh
sudo bash deploy/setup-ssl.sh
```

### What `deploy.sh` does

- validates `.env`
- pulls the `backend` branch
- runs Composer install
- runs migrations
- creates storage symlink
- clears/builds Laravel caches
- fixes permissions
- reloads Nginx and restarts PHP-FPM

## Troubleshooting

| Issue | Fix |
|---|---|
| 502 Bad Gateway | `sudo systemctl restart php8.2-fpm` |
| Wrong database selected | ensure `DB_CONNECTION=pgsql` is set |
| Supabase connection refused | verify host, username, password, SSL mode, and whether project is paused |
| Migration fails on Supabase objects | confirm `auth.users`, `storage.objects`, and `storage.buckets` exist in the target project |
| SSL expired | `sudo certbot renew` |

```bash
tail -f /var/www/sela-website/storage/logs/laravel.log
```
