<?php
use App\Http\Controllers\GuestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('property.listing');
Route::get('reservations/book', [ReservationController::class, 'createFrontendReservation'])->name('book');
Route::post('reservations/book', [ReservationController::class, 'storeFrontendReservation'])->name('book.store');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Profile management routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Property routes
    Route::resource('properties', PropertyController::class)->except(['update']);
    Route::put('properties/{property}', [PropertyController::class, 'update'])->name('properties.update');
    Route::get('properties/{property}/rooms', [RoomController::class, 'index'])->name('properties.rooms.index');

    // Room routes
    Route::resource('rooms', RoomController::class);

    // Reservation management routes
    Route::resource('reservations', ReservationController::class)->except(['store']);
    Route::post('reservations/{reservation}/approve', [ReservationController::class, 'approve'])->name('reservations.approve');
    Route::post('reservations/{reservation}/reject', [ReservationController::class, 'reject'])->name('reservations.reject');

    // Guest management routes
    Route::resource('guests', GuestController::class);
});

// Authentication routes (login, register, etc.)
require __DIR__.'/auth.php';
