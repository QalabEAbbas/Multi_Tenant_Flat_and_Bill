<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HouseOwnerController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\FlatController;
use App\Http\Controllers\BillCategoryController;
use App\Http\Controllers\BillController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // Admin Routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return response()->json(['message' => 'Welcome Admin']);
        });

        // House Owners
        Route::get('/owners', [HouseOwnerController::class, 'index']);
        Route::post('/owners', [HouseOwnerController::class, 'store']);
        Route::get('/owners/{id}', [HouseOwnerController::class, 'show']);
        Route::put('/owners/{id}', [HouseOwnerController::class, 'update']);
        Route::delete('/owners/{id}', [HouseOwnerController::class, 'destroy']);

        // Tenants
        Route::get('/tenants', [TenantController::class, 'index']);
        Route::post('/tenants', [TenantController::class, 'store']);
        Route::get('/tenants/{id}', [TenantController::class, 'show']);
        Route::put('/tenants/{id}', [TenantController::class, 'update']);
        Route::delete('/tenants/{id}', [TenantController::class, 'destroy']);
    });

    // House Owner Routes
    Route::middleware('role:house_owner|admin')->group(function () {
        Route::get('/owner/dashboard', function () {
            return response()->json(['message' => 'Welcome House Owner']);
        });

        // Buildings
        Route::get('/buildings', [BuildingController::class, 'index']);
        Route::post('/buildings', [BuildingController::class, 'store']);
        Route::get('/buildings/{building}', [BuildingController::class, 'show']);
        Route::put('/buildings/{building}', [BuildingController::class, 'update']);
        Route::delete('/buildings/{building}', [BuildingController::class, 'destroy']);

        // Flats
        Route::get('/flats', [FlatController::class, 'index'])->middleware('tenant.isolation');
        Route::post('/flats', [FlatController::class, 'store']);
        Route::get('/flats/{flat}', [FlatController::class, 'show']);
        Route::put('/flats/{flat}', [FlatController::class, 'update']);
        Route::delete('/flats/{flat}', [FlatController::class, 'destroy']);

        // Bill Categories
        Route::get('/bill-categories', [BillCategoryController::class, 'index'])->middleware('tenant.isolation');
        Route::post('/bill-categories', [BillCategoryController::class, 'store']);
        Route::put('/bill-categories/{id}', [BillCategoryController::class, 'update']);
        Route::delete('/bill-categories/{id}', [BillCategoryController::class, 'destroy']);

        // Bills
        Route::get('/bills', [BillController::class, 'index'])->middleware('tenant.isolation');
        Route::post('/bills', [BillController::class, 'store']);
        Route::get('/bills/{id}', [BillController::class, 'show']);
        Route::put('/bills/{id}', [BillController::class, 'update']);
        Route::delete('/bills/{id}', [BillController::class, 'destroy']);
        Route::post('/bills/{id}/pay', [BillController::class, 'pay']);
        // Mark as paid (alternative method) only change the status to 'paid'
        Route::post('/bills/{id}/mark-as-paid', [BillController::class, 'markAsPaid']);

    });
});
