<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Contact\ContactController;
use App\Http\Controllers\Landing\LandingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Use the Gen‑Z landing page as the main homepage (egg sizes & pricing from backend)
Route::get('/', [LandingController::class, 'genz'])->name('landing.genz');

Route::view('/egg-system', 'landing.system')->name('egg.system');

Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Authentication for inventory & sales system
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected backend: admin and inventory_manager have the same access
Route::middleware(['auth', 'role:admin,inventory_manager'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    Route::get('/egg-sizes', [\App\Http\Controllers\Admin\EggSizeController::class, 'index'])->name('egg-sizes.index');
    Route::post('/egg-sizes', [\App\Http\Controllers\Admin\EggSizeController::class, 'store'])->name('egg-sizes.store');
    Route::put('/egg-sizes/{eggSize}', [\App\Http\Controllers\Admin\EggSizeController::class, 'update'])->name('egg-sizes.update');
    Route::delete('/egg-sizes/{eggSize}', [\App\Http\Controllers\Admin\EggSizeController::class, 'destroy'])->name('egg-sizes.destroy');

    Route::get('/egg-prices', [\App\Http\Controllers\Admin\EggPriceController::class, 'index'])->name('egg-prices.index');
    Route::post('/egg-prices', [\App\Http\Controllers\Admin\EggPriceController::class, 'store'])->name('egg-prices.store');
    Route::put('/egg-prices/{eggPrice}', [\App\Http\Controllers\Admin\EggPriceController::class, 'update'])->name('egg-prices.update');
    Route::delete('/egg-prices/{eggPrice}', [\App\Http\Controllers\Admin\EggPriceController::class, 'destroy'])->name('egg-prices.destroy');

    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');

    Route::get('/reports', [\App\Http\Controllers\Admin\ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [\App\Http\Controllers\Admin\ReportsController::class, 'export'])->name('reports.export');
    Route::get('/reports/export-pdf', [\App\Http\Controllers\Admin\ReportsController::class, 'exportPdf'])->name('reports.export-pdf');

    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/stock-in', [\App\Http\Controllers\Admin\StockInController::class, 'index'])->name('stock-in.index');
    Route::post('/stock-in', [\App\Http\Controllers\Admin\StockInController::class, 'store'])->name('stock-in.store');
    Route::get('/stock-out', [\App\Http\Controllers\Admin\StockOutController::class, 'index'])->name('stock-out.index');
    Route::post('/stock-out', [\App\Http\Controllers\Admin\StockOutController::class, 'store'])->name('stock-out.store');
    Route::get('/cracked-eggs', [\App\Http\Controllers\Admin\CrackedEggController::class, 'index'])->name('cracked-eggs.index');
    Route::post('/cracked-eggs', [\App\Http\Controllers\Admin\CrackedEggController::class, 'store'])->name('cracked-eggs.store');
    Route::get('/inventory', [\App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('inventory.index');
    Route::put('/inventory/{inventory}', [\App\Http\Controllers\Admin\InventoryController::class, 'update'])->name('inventory.update');

    Route::get('/feeds', [\App\Http\Controllers\Admin\FeedController::class, 'index'])->name('feeds.index');
    Route::post('/feeds', [\App\Http\Controllers\Admin\FeedController::class, 'store'])->name('feeds.store');
    Route::put('/feeds/{feed}', [\App\Http\Controllers\Admin\FeedController::class, 'update'])->name('feeds.update');
    Route::post('/feeds/{feed}/adjust', [\App\Http\Controllers\Admin\FeedController::class, 'adjust'])->name('feeds.adjust');
    Route::delete('/feeds/{feed}', [\App\Http\Controllers\Admin\FeedController::class, 'destroy'])->name('feeds.destroy');

    Route::get('/activity-log', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('activity-log.index');
});


