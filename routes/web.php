<?php

use Illuminate\Support\Facades\Route;
use App\Exports\PesertaTemplateExport;
use Maatwebsite\Excel\Facades\Excel;

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

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('auth.login');
});


Auth::routes();

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/peserta/data', [App\Http\Controllers\PesertaController::class, 'getData'])->name('peserta.data');
Route::get('/peserta', [App\Http\Controllers\PesertaController::class, 'index'])->name('peserta.index');
Route::get('/peserta/create', [App\Http\Controllers\PesertaController::class, 'create'])->name('peserta.create');
Route::post('/peserta', [App\Http\Controllers\PesertaController::class, 'store'])->name('peserta.store');
Route::get('/peserta/{id}/edit', [App\Http\Controllers\PesertaController::class, 'edit'])->name('peserta.edit');
Route::put('/peserta/{id}', [App\Http\Controllers\PesertaController::class, 'update'])->name('peserta.update');
Route::delete('/peserta/{id}', [App\Http\Controllers\PesertaController::class, 'destroy'])->name('peserta.destroy');
Route::post('/peserta/import', [App\Http\Controllers\PesertaController::class, 'import'])->name('peserta.import');
Route::get('/template/peserta', function () {
    return Excel::download(new PesertaTemplateExport, 'template_peserta.xlsx');
})->name('template.peserta.download');

