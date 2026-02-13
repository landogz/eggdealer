# Egg Dealer

A Laravel-based inventory and sales system for egg supply businesses. Manage stock in/out, pricing, cracked eggs, feed inventory, expenses, and reports from a single admin dashboard.

## Features

- **Landing page** – Public site with egg sizes and pricing (configurable from admin)
- **Authentication** – Login for admin and inventory manager roles; redirect to `/admin` when already logged in
- **Dashboard** – Stats, today’s snapshot, monthly revenue (net of expenses), expenses (purchases + feed value), Revenue vs Expenses chart with period filter (Today, Yesterday, This month, Last month, Last 12 months), Sales overview, inventory and feed tables
- **Egg sizes & pricing** – CRUD for size categories and price entries (per piece, tray, bulk) with effective dates
- **Stock in** – Record purchases/deliveries by date, size, quantity (pieces/trays), cost; date search; 12-hour time in table
- **Stock out** – Record sales by order type (piece/tray/bulk), size, quantity, price; date search; 12-hour time in table
- **Cracked eggs** – Log damaged eggs by size and reason; date search; 12-hour time in table
- **Inventory** – View current stock by size; update minimum stock alerts; auto-updated on stock in/out and cracked
- **Feeds** – Separate feed inventory: CRUD, quantity, unit, cost per unit, min alert, stock value; adjust stock; included in expenses
- **Reports** – Date-range reports: summary, stock in/sales/cracked detail, inventory snapshot, feed snapshot, by size; Print and CSV/PDF export
- **Expenses** – Dashboard shows purchases (stock in) this month, feed inventory value, total; Revenue vs Expenses chart includes both
- **Users** – CRUD for users (admin, inventory_manager)
- **Settings** – Business name, address, contact, currency, default tray size, logo, report options
- **Activity log** – Audit trail with filters (action, user, date range); 12-hour time; per-page option
- **Dark mode** – Toggle with persistent preference
- **Responsive** – Tailwind CSS; works on mobile and desktop (including Apple devices)

## Tech stack

- **Laravel** (PHP)
- **Tailwind CSS** for styling
- **Alpine.js** for UI (e.g. sidebar, dark mode)
- **Chart.js** for dashboard charts
- **Axios** for API calls
- **SweetAlert2** for notifications
- **MySQL** (or configured DB)

## Setup

1. Clone the repo and install dependencies:
   ```bash
   composer install
   npm install && npm run build
   ```

2. Copy `.env.example` to `.env`, set `APP_KEY`, database, and mail if needed:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Run migrations:
   ```bash
   php artisan migrate
   ```

4. (Optional) Seed or create an admin user and set role to `admin` or `inventory_manager`.

5. Serve the app (e.g. `php artisan serve` or Laragon) and visit:
   - `/` – Landing page
   - `/login` – Admin login (redirects to `/admin` if already logged in)
   - `/admin` – Dashboard and all admin sections

## Project structure (admin)

- **Controllers:** `app/Http/Controllers/Admin/` – Dashboard, StockIn, StockOut, CrackedEgg, Feed, Inventory, Reports, Settings, Users, EggSize, EggPrice, AuditLog, Notifications
- **Form Requests:** `app/Http/Requests/Admin/` – Validation for store/update (StockIn, StockOut, Feed, EggSize, EggPrice, User, Settings, Inventory, CrackedEgg)
- **Models:** EggSize, EggPrice, StockIn, StockOut, CrackedEgg, Inventory, Feed, Setting, User, AuditLog
- **Views:** `resources/views/admin/` – Layout, dashboard, list/modals for each section; `resources/views/auth/login.blade.php`
- **Docs:** `docs/SUGGESTIONS_ADMIN_FUNCTIONS.md` – Ideas for future improvements

## License

MIT (or as specified in the repository).
