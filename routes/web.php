<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\OwnerBladeController;
use App\Http\Controllers\BuildingBladeController;
use App\Http\Controllers\FlatBladeController;
use App\Http\Controllers\BillBladeController;
use App\Http\Controllers\BillCategoryBladeController;
use App\Http\Controllers\HouseOwnerController;
use App\Http\Controllers\TenantBladeController;
use App\Http\Controllers\FlatController;
use App\Http\Controllers\TenantController;



Route::get('/login', function() {
    return view('auth.login'); // login page
})->name('login');

Route::get('/register', function() {
    return view('auth.register'); // register page
})->name('register');

// Admin dashboard
// Route::get('/admin/dashboard', function() {
//     return view('admin.dashboard');
// })->middleware(['auth:sanctum', 'role:admin']);

Route::get('/admin/dashboard', function() {
    return view('admin.dashboard');
});

// House owner dashboard
// Route::get('/owner/dashboard', function() {
//     return view('owner.dashboard');
// })->middleware(['auth:sanctum', 'role:house_owner']);

// Route::get('/owner/dashboard', [OwnerBladeController::class, 'index']);


Route::get('/dashboard', function() {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/admin/house-owners', function () {
    return view('admin.house-owners'); 
});
// Only authenticated users with admin or house_owner role can access these
// Route::middleware(['auth', 'role:admin|house_owner'])->group(function () {
    Route::get('/buildings', fn() => view('buildings.index'))->name('buildings.index');
    // Route::view('/flats', 'flats.index')->name('flats.index');
    // Add other shared routes here
// });

// Serve Flats blade on web; API CRUD lives in routes/api.php
Route::view('/flats', 'flats.index')->name('flats.index');
// Route::middleware(['auth', 'role:house_owner'])->group(function() {
//     Route::get('/owners', [OwnerBladeController::class,'index'])->name('owners.index');
// });
Route::view('/tenants', 'tenants.index')->name('tenants.index');
