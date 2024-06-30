<?php

use App\Http\Controllers\payment\FlowController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::get('v1/productos', [ ProductsController::class, 'index' ]);
Route::post('v1/flow/create', [ FlowController::class, 'create' ]);

Route::post('v1/flow/confirmation', [FlowController::class, 'confirmation']);

Route::get('v1/flow/confirmation', [FlowController::class, 'confirmation']);
Route::get('v1/flow/status/{token}', [FlowController::class, 'getStatus']);