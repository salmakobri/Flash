HEAD
# Flash
Delivery platform

# Flash — Delivery Management Platform

Flash is a delivery management platform made of three applications that share one Laravel API:

| App | Folder | Technology | Used by |
|---|---|---|---|
| Admin panel + API | `backend/` | Laravel 12, Blade, Sanctum | Admin |
| Customer web app | `frontend/` | React, Vite, Tailwind | Customers |
| Driver mobile app | `flash delivery/` | Flutter | Drivers |

It was built as a learning project: Laravel CRUD, a REST API secured with Sanctum, role-based access, and Flutter/React apps consuming that API.

---

## Table of contents

- [Features](#features)
- [Tech stack](#tech-stack)
- [Architecture](#architecture)
- [Data model](#data-model)
- [Order lifecycle](#order-lifecycle)
- [Getting started (Windows + XAMPP)](#getting-started-windows--xampp)
- [Demo accounts](#demo-accounts)
- [Try the full workflow](#try-the-full-workflow)
- [API reference](#api-reference)
- [Security](#security)
- [Tests](#tests)
- [Troubleshooting](#troubleshooting)
- [Project structure](#project-structure)
- [Out of scope](#out-of-scope)

---

## Features

### Admin panel (Blade)
- Login restricted to the `admin` role
- Full CRUD for **clients**, **drivers** and **orders**
- Order list with filters (status, driver, date) and pagination
- **Assign a driver** to an order (not possible once the order is in progress or delivered)
- **Dashboard**: orders by status, deliveries completed today, top drivers
- Review **driver and partner applications**: accept (creates the driver account) or refuse with a note

### Driver app (Flutter)
- Secure login (token kept in `flutter_secure_storage`), driver accounts only
- List of own deliveries with pull-to-refresh, loading, empty and error states
- Delivery details: client, phone, address, order details
- Start a delivery, then mark it delivered (with confirmation)
- Add a note to a delivery
- Automatic return to the login screen when the session expires

### Customer app (React)
- Register and log in
- Browse partner stores and catalogs, add items to a cart
- Free-text orders for stores that need them (for example a pharmacy)
- Checkout with delivery address and phone, payment on delivery
- "My orders" page with live status
- Apply to become a driver or a partner

---

## Tech stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12 (PHP ≥ 8.2), Laravel Sanctum, Laravel Breeze (admin login) |
| Admin UI | Blade, Tailwind CSS |
| Customer app | React 19, Vite, Tailwind CSS 4, React Router, axios, react-hook-form |
| Driver app | Flutter (Dart), `http`, `flutter_secure_storage` |
| Database | MySQL (XAMPP); tests use in-memory SQLite |

---

## Architecture

```
 ┌───────────────┐   ┌───────────────┐   ┌───────────────┐
 │  Admin panel  │   │  Driver app   │   │ Customer app  │
 │ Blade (web)   │   │   Flutter     │   │    React      │
 └──────┬────────┘   └──────┬────────┘   └──────┬────────┘
   session login       Bearer token          Bearer token
        │                   │                     │
        └───────────────────┴──────────┬──────────┘
                                       ▼
                         ┌───────────────────────────┐
                         │      Laravel backend      │
                         │  roles: admin / chauffeur │
                         │          / client         │
                         └─────────────┬─────────────┘
                                       ▼
                                 ┌───────────┐
                                 │   MySQL   │
                                 └───────────┘
```

A `role` middleware protects every route group (`admin`, `chauffeur`, `client`).

---

## Data model

| Table | Main columns |
|---|---|
| `users` | `id`, `name`, `email`, `password`, `role` (`admin` / `chauffeur` / `client`) |
| `clients` | `id`, `user_id` (nullable), `nom`, `telephone` |
| `chauffeurs` | `id`, `user_id`, `nom` |
| `commandes` | `id`, `client_id`, `chauffeur_id` (nullable), `date`, `adresse_livraison`, `statut`, `note`, `details` |
| `demandes` | `id`, `type` (`rider` / `partner`), `nom`, `email`, `telephone`, `ville`, `vehicule`, `magasin`, `categorie`, `statut`, `note_admin` |

An order belongs to one client and, once assigned, to one driver.

---

## Order lifecycle

```
en_attente ──(admin assigns a driver)──▶ affectee ──(driver starts)──▶ en_cours ──(driver delivers)──▶ livree
```

| Status | Meaning |
|---|---|
| `en_attente` | Order created, no driver yet |
| `affectee` | Driver assigned |
| `en_cours` | Driver is delivering |
| `livree` | Delivered |

The API only accepts `affectee → en_cours` and `en_cours → livree`, and only from the assigned driver. Any other change returns `422`.

---

## Getting started (Windows + XAMPP)

All commands are for **PowerShell** in the VS Code terminal. The project root is the folder that contains `backend`, `frontend` and `flash delivery`.

### Prerequisites

- XAMPP (Apache is not needed, MySQL is), PHP ≥ 8.2 and Composer
- Node.js ≥ 20 and npm
- Flutter SDK (for the driver app)

### 1. Database

1. Start **MySQL** in the XAMPP Control Panel.
2. Open http://localhost/phpmyadmin and create an empty database, for example `flash`, with collation `utf8mb4_unicode_ci`.

### 2. Backend (Laravel)

```powershell
cd backend
composer install
npm install
npm run build

copy .env.example .env
php artisan key:generate
```

Edit `backend/.env`:

```env
APP_DEBUG=true          # local development only
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flash
DB_USERNAME=root
DB_PASSWORD=
```

Then create the tables and demo data, and start the server:

```powershell
php artisan migrate --seed
php artisan serve
```

Admin panel: **http://127.0.0.1:8000**, API: **http://127.0.0.1:8000/api**. Keep this terminal open.

Check which database the app uses at any time:

```powershell
php artisan db:show
```

### 3. Customer app (React)

In a second terminal:

```powershell
cd frontend
npm install
npm run dev
```

Open the address Vite prints (usually http://localhost:5173).

The API address defaults to `http://localhost:8000/api`. To change it, copy `frontend/.env.example` to `frontend/.env.local` and edit `VITE_API_URL`.

### 4. Driver app (Flutter)

In a third terminal:

```powershell
cd "flash delivery"
flutter pub get
```

Then run it on the device you have:

| Where | Command |
|---|---|
| **Chrome** (quickest demo) | `flutter run -d chrome --dart-define=API_URL=http://127.0.0.1:8000/api` |
| **Android emulator** | `flutter run` (default API address is `http://10.0.2.2:8000/api`) |
| **Real phone** (same Wi-Fi) | `flutter run --dart-define=API_URL=http://<YOUR_PC_IP>:8000/api` |

For a real phone, start the backend with `php artisan serve --host=0.0.0.0`, find your IP with `ipconfig` (IPv4 line), and allow PHP in the Windows Firewall prompt.

List the devices Flutter can see with `flutter devices`.

---

## Demo accounts

Created by `php artisan migrate --seed`. All passwords are `password`.

| Role | Email | Where to log in |
|---|---|---|
| Admin | `admin@test.com` | Admin panel |
| Driver | `karim@test.com`, `sara@test.com`, `youssef@test.com` | Flutter driver app |
| Client | `client@test.com` | Customer app |



---

## Try the full workflow

1. **Admin:** log in at http://127.0.0.1:8000 and create an order for a client (with an address).
2. **Admin:** assign the order to a driver, for example Karim. The status becomes `affectee`.
3. **Driver:** log in to the Flutter app as `karim@test.com`. The delivery appears in the list.
4. **Driver:** open it, add a note, tap **Démarrer la livraison**, then **Marquer comme livrée**.
5. **Admin:** reload the order. It shows `livree` with the driver's note, and the dashboard counts change.
6. **Customer:** log in to the React app as `client@test.com`, place an order, and see it appear in the admin panel as `en_attente`.

---

## API reference

Base URL: `/api`. Protected routes expect `Authorization: Bearer <token>`.

### Public

| Method | Endpoint | Description |
|---|---|---|
| POST | `/login` | Log in as driver or client, returns a token (10 attempts per minute) |
| POST | `/register` | Create a client account, returns a token (10 attempts per minute) |
| GET | `/catalog` | Partner stores and their items |
| POST | `/applications` | Apply as driver or partner (5 per minute) |

### Any logged-in user

| Method | Endpoint | Description |
|---|---|---|
| POST | `/logout` | Revoke the current token |

### Driver (`role: chauffeur`)

| Method | Endpoint | Description |
|---|---|---|
| GET | `/mes-livraisons` | My deliveries |
| GET | `/livraisons/{id}` | One of my deliveries |
| PATCH | `/livraisons/{id}/statut` | Body `{ "statut": "en_cours" \| "livree" }` |
| POST | `/livraisons/{id}/note` | Body `{ "note": "..." }` (max 1000 characters) |

### Client (`role: client`)

| Method | Endpoint | Description |
|---|---|---|
| GET | `/client/commandes` | My orders |
| POST | `/orders` | Place an order (prices are computed by the server) |

Example:

```powershell
curl.exe -X POST http://127.0.0.1:8000/api/login `
  -H "Accept: application/json" -H "Content-Type: application/json" `
  -d "{\"email\":\"karim@test.com\",\"password\":\"password\"}"
```

---

## Security

- Role middleware on every route group; the admin panel also requires a session login
- A driver can only read or change deliveries assigned to them (`403` otherwise)
- A client only sees their own orders, and cannot use driver routes
- Order status is set by the server and every status change is validated
- Prices and delivery fees are computed server-side from the catalog, never trusted from the client
- Input validation with Form Requests; rate limits on login, register and applications
- Tokens are stored with `flutter_secure_storage` in the driver app
- `.env` is never committed; `APP_DEBUG=false` in `.env.example`

---

## Tests

```powershell
cd backend
php artisan test
```

Tests run on an in-memory SQLite database, so they never touch the MySQL data. They cover:

- Driver ownership: a driver can open their own delivery but not another driver's
- Status transitions: allowed steps pass, skipping steps, going back and unknown statuses return `422`
- Client isolation: a client only sees their own orders and cannot call driver routes
- Login rate limiting, and a driver account without a profile returning `403` instead of crashing
- The default Laravel Breeze authentication tests

---

## Troubleshooting

| Problem | Fix |
|---|---|
| `SQLSTATE[HY000] [2002] connection refused` | MySQL is not running. Start it in XAMPP. |
| App shows empty or unexpected data | A terminal may still have `$env:DB_DATABASE` set. Run `Remove-Item Env:DB_DATABASE` and check with `php artisan db:show`. |
| `head` is not recognized | PowerShell has no `head`. Use `Get-Content file -TotalCount 12`. |
| Driver app cannot log in | Is `php artisan serve` running? Is the `API_URL` right for where the app runs (see the table above)? |
| Fresh install check without losing data | Create a second empty database, then: `$env:DB_DATABASE="flash_test"; php artisan migrate:fresh --seed`, and afterwards `Remove-Item Env:DB_DATABASE`. |
| Store photos missing in the catalog | The photos live in `backend/public/images/catalog/` and are not tracked by Git. The app works without them (photos are simply empty). `php artisan catalog:images` lists the missing ones. |

---

## Project structure

```
flash/
├── backend/                  # Laravel 12: API + admin panel
│   ├── app/Http/Controllers/       # Admin controllers (Blade)
│   ├── app/Http/Controllers/Api/   # Auth, driver, client, applications
│   ├── app/Http/Middleware/        # RoleMiddleware
│   ├── app/Models/                 # User, Client, Chauffeur, Commande, Demande
│   ├── app/Services/               # DeliveryFee, CatalogImage
│   ├── database/migrations/ + seeders/
│   ├── resources/views/            # Blade templates
│   ├── routes/                     # web.php, api.php
│   └── tests/Feature/              # Feature tests
├── frontend/                 # React + Vite: customer app
│   └── src/pages/                  # Home, Store, Checkout, MesCommandes, Join ...
└── flash delivery/           # Flutter: driver app
    └── lib/                        # screens, services, models, widgets
```

---

## Out of scope

Deliberately excluded to keep the project focused: real-time GPS tracking, QR codes, route optimization, SMS notifications, offline mode.

---

## Author

**salma kobri**

Educational project, not published.
864afb4 (Initial commit)
