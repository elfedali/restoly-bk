<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

# Blueprint

Route::prefix('admin')->middleware('auth')->group(function () {

    Route::resource('post', App\Http\Controllers\Admin\PostController::class);

    Route::resource('page', App\Http\Controllers\Admin\PageController::class);

    Route::resource('contact', App\Http\Controllers\Admin\ContactController::class);

    Route::resource('payment', App\Http\Controllers\Admin\PaymentController::class);

    Route::resource('subscription', App\Http\Controllers\Admin\SubscriptionController::class);

    Route::resource('plan', App\Http\Controllers\Admin\PlanController::class);

    Route::resource('review', App\Http\Controllers\Admin\ReviewController::class);

    Route::resource('reservation', App\Http\Controllers\Admin\ReservationController::class);

    Route::resource('table', App\Http\Controllers\Admin\TableController::class);

    Route::resource('salle', App\Http\Controllers\Admin\SalleController::class);

    Route::resource('menu-item', App\Http\Controllers\Admin\MenuItemController::class);

    Route::resource('menu-category', App\Http\Controllers\Admin\MenuCategoryController::class);

    Route::resource('menu', App\Http\Controllers\Admin\MenuController::class);

    Route::resource('restaurant', App\Http\Controllers\Admin\RestaurantController::class);

    Route::resource('user', App\Http\Controllers\Admin\UserController::class);

    Route::resource('tag', App\Http\Controllers\Admin\TagController::class);

    Route::resource('kitchen', App\Http\Controllers\Admin\KitchenController::class);

    Route::resource('service', App\Http\Controllers\Admin\ServiceController::class);

    Route::resource('city', App\Http\Controllers\Admin\CityController::class);

    Route::resource('street', App\Http\Controllers\Admin\StreetController::class);

    Route::resource('role', App\Http\Controllers\Admin\RoleController::class);

    Route::resource('permission', App\Http\Controllers\Admin\PermissionController::class);
});
