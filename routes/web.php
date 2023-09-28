<?php

use App\Http\Controllers\Controller;
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

Route::get('', [Controller::class, 'data']);
Route::get('{status}', [Controller::class, 'data']);

Route::post('ambil', [Controller::class, 'ambilData']);
Route::get('tambah/produk', [Controller::class, 'tambah']);
Route::post('tambah/produk/simpan', [Controller::class, 'tambahSimpan']);
Route::delete('hapus/{id_produk}', [Controller::class, 'hapus']);
Route::get('edit/produk/{id_produk}', [Controller::class, 'edit']);
Route::put('edit/produk/simpan/{id_produk}', [Controller::class, 'editSimpan']);
