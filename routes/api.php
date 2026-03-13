<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CategoriaProductoController;
use App\Http\Controllers\ImagenProductoController;
use App\Http\Controllers\LenguajeController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\PaisProductoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProductoCotizacionController;
use App\Http\Controllers\StartController;
use App\Http\Controllers\TipoCuponController;
use App\Http\Controllers\TipoPagoController;
use App\Http\Controllers\TipoProductoController;
use App\Http\Controllers\TipoSetupController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TipoUsuarioController;
use App\Http\Controllers\UsuarioController;

Route::prefix('tipo-usuario')->group(function () {

    Route::get('/{lang}', [TipoUsuarioController::class, 'GetAll']);

});

Route::prefix('start')->group(function () {

    Route::get('/', [StartController::class, 'Start']);

});

Route::prefix('usuario')->group(function () {

    Route::post('/login', [UsuarioController::class, 'login']);
    Route::post('/loguot', [UsuarioController::class, 'logout']);
    Route::post('/logout-all', [UsuarioController::class, 'logoutAll']);
    Route::post('/create', [UsuarioController::class, 'crearUsuario']);

});

Route::prefix('tipo-cupon')->group(function () {

    Route::get('/{lang}', [TipoCuponController::class, 'GetAll']);

});

Route::prefix('tipo-pago')->group(function () {

    Route::get('/{lang}', [TipoPagoController::class, 'GetAll']);

});

Route::prefix('lenguaje')->group(function () {

    Route::get('/{lang}', [LenguajeController::class, 'GetAll']);

});

Route::prefix('pais')->group(function () {

    Route::get('/{lang}', [PaisController::class, 'GetAll']);

});

Route::prefix('tipo-producto')->group(function () {

    Route::get('/{lang}', [TipoProductoController::class, 'GetAll']);

});

Route::prefix('tipo-setup')->group(function () {

    Route::get('/{lang}', [TipoSetupController::class, 'GetAll']);

});

Route::prefix('producto')->group(function () {

    Route::post('/create-base', [ProductoController::class, 'Create']);
    Route::post('/create-imagen', [ImagenProductoController::class, 'Create']);
    Route::post('/create-pais', [PaisProductoController::class, 'Create']);
    Route::post('/create-categoria', [CategoriaProductoController::class, 'Create']);
    Route::post('/create-cotizacion', [ProductoCotizacionController::class, 'Create']);



    // Route::post('/create-basico', [ProductoController::class, 'CreateBasico']);
    // Route::post('/create-variable', [ProductoController::class, 'CreateVariante']);
    // Route::post('/create-plan', [ProductoController::class, 'CreatePlan']);
    
});

Route::prefix('categoria')->group(function () {
    Route::get('/{idlenguaje}/{lang}', [CategoriaController::class, 'GetAll']);
    Route::post('/create', [CategoriaController::class, 'Create']);
    Route::put('/update', [CategoriaController::class, 'Update']);
});
