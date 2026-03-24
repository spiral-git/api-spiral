<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CategoriaProductoController;
use App\Http\Controllers\DetallePlanController;
use App\Http\Controllers\ImagenProductoController;
use App\Http\Controllers\LenguajeController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\PaisProductoController;
use App\Http\Controllers\ProductoBasicoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProductoCotizacionController;
use App\Http\Controllers\ProductoPlanController;
use App\Http\Controllers\ProductoVarianteController;
use App\Http\Controllers\StartController;
use App\Http\Controllers\TipoCuponController;
use App\Http\Controllers\TipoDescuentoController;
use App\Http\Controllers\TipoPagoController;
use App\Http\Controllers\TipoProductoController;
use App\Http\Controllers\TipoSetupController;
use App\Http\Controllers\TipoUsuarioController;
use App\Http\Controllers\UploadFileController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::prefix('start')->group(function () {
    Route::get('/', [StartController::class, 'Start']);
});

Route::get('/swagger-setup', function () {

    $output = [];
    $output[] = \Illuminate\Support\Facades\Artisan::call('config:clear');
    $output[] = \Illuminate\Support\Facades\Artisan::call('route:clear');
    $output[] = \Illuminate\Support\Facades\Artisan::call('package:discover');
    $output[] = \Illuminate\Support\Facades\Artisan::call('l5-swagger:generate');

    return response()->json(['done' => true, 'output' => $output]);

});

Route::prefix('usuario')->group(function () {

    Route::post('/login', [UsuarioController::class, 'login']);
    Route::post('/loguot', [UsuarioController::class, 'logout']);
    Route::post('/logout-all', [UsuarioController::class, 'logoutAll']);
    Route::post('/create', [UsuarioController::class, 'crearUsuario']);
    Route::post('/create-socio', [UsuarioController::class, 'crearSocio']);

});

Route::prefix('categoria')->group(function () {

    Route::get('/{idlenguaje}/{lang}', [CategoriaController::class, 'GetAll']);
    Route::post('/create', [CategoriaController::class, 'Create']);
    Route::put('/update', [CategoriaController::class, 'Update']);

});

Route::prefix('base')->group(function () {

    Route::get('/tipo-cupon/{lang}', [TipoCuponController::class, 'GetAll']);
    Route::get('/tipo-pago/{lang}', [TipoPagoController::class, 'GetAll']);
    Route::get('/lenguajes/{lang}', [LenguajeController::class, 'GetAll']);
    Route::get('/paises/{lang}', [PaisController::class, 'GetAll']);
    Route::get('/tipo-producto/{lang}', [TipoProductoController::class, 'GetAll']);
    Route::get('/tipo-setup/{lang}', [TipoSetupController::class, 'GetAll']);
    Route::get('/tipo-usuario/{lang}', [TipoUsuarioController::class, 'GetAll']);
    Route::get('/tipo-descuento/{lang}', [TipoDescuentoController::class, 'GetAll']);

});

Route::prefix('producto')->group(function () {

    Route::post('/create-base', [ProductoController::class, 'Create']);
    Route::post('/create-imagen', [ImagenProductoController::class, 'Create']);
    Route::post('/create-pais', [PaisProductoController::class, 'Create']);
    Route::post('/create-categoria', [CategoriaProductoController::class, 'Create']);
    Route::post('/create-cotizacion', [ProductoCotizacionController::class, 'Create']);
    Route::post('/create-basico', [ProductoBasicoController::class, 'Create']);
    Route::post('/create-variante', [ProductoVarianteController::class, 'Create']);
    Route::post('/create-plan', [ProductoPlanController::class, 'Create']);
    Route::post('/create-detalle-plan', [DetallePlanController::class, 'Create']);

    Route::post('/getall', [ProductoController::class, 'GetAll']);
    Route::post('/get-cotizable-by-id', [ProductoCotizacionController::class, 'GetById']);
    Route::post('/get-basico-by-id', [ProductoBasicoController::class, 'GetById']);
    Route::post('/get-planes', [ProductoPlanController::class, 'GetById']);
    Route::post('/get-variante-by-id', [ProductoVarianteController::class, 'GetById']);


});

Route::prefix('uploads')->group(function () {

    Route::post('/upload', [UploadFileController::class, 'uploadFiles']);

});
