# FocusTrack — Backend API

## Ecosistema FocusTrack

Plataforma full-stack de **gestión de inventario y stock**. Proyecto del ciclo **DAM** (La Salle Gràcia), desarrollado en equipo.

| Repositorio | Rol | Stack |
|-------------|-----|-------|
| [**focustrack**](https://github.com/focustrack/focustrack) | Índice y arquitectura | — |
| [**focustrack-api**](https://github.com/focustrack/focustrack-api) | Backend REST | Laravel 11, PHP, Sanctum |
| [**focustrack-ios**](https://github.com/focustrack/focustrack-ios) | App iOS | Swift, SwiftUI, MVVM |

> **Este repositorio:** API REST en Laravel 11 — autenticación Sanctum y modelos de inventario (productos, categorías, plataformas, cuentas).

## Descripción

Backend de FocusTrack: API JSON para registrar usuarios, iniciar sesión y gestionar el dominio de stock (productos, categorías, items de inventario, plataformas y cuentas).

## Stack

- Laravel 11
- Laravel Sanctum (tokens Bearer)
- PHP 8.2+
- MySQL / SQLite

## Endpoints principales

| Método | Ruta | Auth | Descripción |
|--------|------|------|-------------|
| POST | `/api/auth/register` | No | Registro de usuario |
| POST | `/api/auth/login` | No | Login — devuelve token |
| POST | `/api/auth/logout` | Sí | Cerrar sesión |
| GET | `/api/user` | Sí | Usuario autenticado |

## Arranque local

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

La API queda en `http://127.0.0.1:8000/api`.

## Autor

**Yago Alonso** — [GitHub](https://github.com/yagoalonsodev)
