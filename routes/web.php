<?php

use Illuminate\Support\Facades\Route;
use Mockery\Matcher\Any;

use App\Http\Controllers\PDFController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/' ,function () {
    return view('welcome');
});

Route::get('/file',[PDFController::class,'index']);
Route::get('/dossiers_pvs/{name}',[PDFController::class,'get_URL_pvs']);
Route::get('/dossiers_plaintes/{name}',[PDFController::class,'get_URL_plaintes']);
Route::get('/dossiersEnquete/{name}',[PDFController::class,'getPdfEnquete']);
Route::get('/DescisionEnquetePDF/{name}',[PDFController::class,'DescisionEnquetePDF']);
