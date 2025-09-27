<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\FlatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HouseOwnerController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\BillCategoryController;
use App\Http\Controllers\BillController;





Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


    
// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Only Admins
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return response()->json([
                'message' => 'Welcome Admin'
            ]);
        });
    });

    // Only House Owners
    Route::middleware('role:house_owner')->group(function () {
        Route::get('/house-owner/dashboard', function () {
            return response()->json([
                'message' => 'Welcome House Owner'
            ]);
        });
    });
});



// Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
//     Route::get('/house-owners', [HouseOwnerController::class, 'index']);
//     Route::post('/house-owners', [HouseOwnerController::class, 'store']);
//     Route::get('/house-owners/{id}', [HouseOwnerController::class, 'show']);
//     Route::put('/house-owners/{id}', [HouseOwnerController::class, 'update']);
//     Route::delete('/house-owners/{id}', [HouseOwnerController::class, 'destroy']);
// });

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/tenants', [TenantController::class, 'index']);
    Route::post('/tenants', [TenantController::class, 'store']);
    Route::get('/tenants/{id}', [TenantController::class, 'show']);
    Route::put('/tenants/{id}', [TenantController::class, 'update']);
    Route::delete('/tenants/{id}', [TenantController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {
    // Route::get('/bill-categories', [BillCategoryController::class, 'index']);
    Route::post('/bill-categories', [BillCategoryController::class, 'store']);
});

Route::middleware('auth:sanctum')->group(function () {
    // Route::get('/bills', [BillController::class, 'index']);
    Route::post('/bills', [BillController::class, 'store']);
    Route::post('/bills/{id}/pay', [BillController::class, 'pay']);

});
Route::middleware(['auth:sanctum', 'tenant.isolation'])->group(function () {
    Route::get('/flats', [FlatController::class, 'index']);
    Route::get('/bills', [BillController::class, 'index']);
    Route::get('/bill-categories', [BillCategoryController::class, 'index']);
});

    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::get('/tenants', [TenantController::class, 'index']);
        Route::post('/tenants', [TenantController::class, 'store']);
        Route::put('/tenants/{id}', [TenantController::class, 'update']);
        Route::delete('/tenants/{id}', [TenantController::class, 'destroy']);
    });

    // Route::post('register', [AuthController::class, 'register']);
    // Route::post('login',    [AuthController::class, 'login']);

    // // protected
    // Route::middleware('auth:sanctum')->group(function(){
    //     Route::get('me',    [AuthController::class, 'me']);
    //     Route::post('logout',[AuthController::class, 'logout']);
    // });

    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return response()->json(['message' => 'Welcome Admin']);
    });
});

Route::middleware(['auth:sanctum', 'role:house_owner'])->group(function () {
    Route::get('/owner/dashboard', function () {
        return response()->json(['message' => 'Welcome House Owner']);
    });
});


Route::middleware(['auth:sanctum'])->group(function () {
    // Buildings
    Route::get('/buildings', [BuildingController::class, 'index']);
    Route::post('/buildings', [BuildingController::class, 'store']);
    Route::get('/buildings/{building}', [BuildingController::class, 'show']);

    // Flats
    // Route::get('/flats', [FlatController::class, 'index']);
    Route::post('/flats', [FlatController::class, 'store']);
    Route::get('/flats/{flat}', [FlatController::class, 'show']);
});