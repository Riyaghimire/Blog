<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    
        Route::post('/administrator/register',[AdministratorController::class,'store'])->name('register.store');
        Route::put('/administrator/update/{id}',[AdministratorController::class,'update'])->name('register.update'); 
        Route::delete('/administrator/delete/{id}',[AdministratorController::class,'destroy'])->name('register.delete');   

        Route::apiResource('categories',CategoryController::class);
        Route::apiResource('blogs',BlogController::class);
        Route::apiResource('btags',BtagController::class);
        
    });

require __DIR__.'/auth.php';
