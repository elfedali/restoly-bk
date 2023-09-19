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

    Route::resource('post', App\Http\Controllers\Admin\PostController::class)->names("admin.post");

    Route::resource('page', App\Http\Controllers\Admin\PageController::class)->names("admin.page");

    Route::resource('contact', App\Http\Controllers\Admin\ContactController::class)->names("admin.contact");

    Route::resource('payment', App\Http\Controllers\Admin\PaymentController::class)->names("admin.payment");

    Route::resource('subscription', App\Http\Controllers\Admin\SubscriptionController::class)->names("admin.subscription");

    Route::resource('plan', App\Http\Controllers\Admin\PlanController::class)->names("admin.plan");

    Route::resource('review', App\Http\Controllers\Admin\ReviewController::class)->names("admin.review");

    Route::resource('reservation', App\Http\Controllers\Admin\ReservationController::class)->names("admin.reservation");

    Route::resource('table', App\Http\Controllers\Admin\TableController::class)->names("admin.table");

    Route::resource('salle', App\Http\Controllers\Admin\SalleController::class)->names("admin.salle");

    Route::resource('menu-item', App\Http\Controllers\Admin\MenuItemController::class)->names("admin.menu-item");

    Route::resource('menu-category', App\Http\Controllers\Admin\MenuCategoryController::class)->names("admin.menu-category");

    Route::resource('menu', App\Http\Controllers\Admin\MenuController::class)->names("admin.menu");

    Route::resource('restaurant', App\Http\Controllers\Admin\RestaurantController::class)->names("admin.restaurant");

    Route::resource('user', App\Http\Controllers\Admin\UserController::class)->names("admin.user");

    Route::resource('tag', App\Http\Controllers\Admin\TagController::class)->names("admin.tag");

    Route::resource('kitchen', App\Http\Controllers\Admin\KitchenController::class)->names("admin.kitchen");

    Route::resource('service', App\Http\Controllers\Admin\ServiceController::class)->names("admin.service");

    Route::resource('city', App\Http\Controllers\Admin\CityController::class)->names("admin.city");

    Route::resource('country', App\Http\Controllers\Admin\CountryController::class)->names("admin.countries");

    Route::resource('street', App\Http\Controllers\Admin\StreetController::class)->names("admin.street");

    Route::resource('role', App\Http\Controllers\Admin\RoleController::class)->names("admin.role");

    Route::resource('permission', App\Http\Controllers\Admin\PermissionController::class)->names("admin.permission");
});
