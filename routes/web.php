<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\MedicalController;
use App\Http\Controllers\NurseController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\UserController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class)->except(['create', 'edit', 'show']);
    Route::resource('patients', PatientController::class)->except(['create', 'edit', 'show']);
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::resource('orders', OrderController::class);
    Route::get('orders/{order}/hemodialysis-pdf', [OrderController::class, 'hemodialysisPdf'])->name('orders.hemodialysis.pdf');
    Route::resource('histories', HistoryController::class);
    Route::get('histories/{history}/pdf', [App\Http\Controllers\HistoryController::class, 'generatePdf'])->name('histories.pdf');
    Route::resource('medicals', MedicalController::class);
    Route::resource('nurses', NurseController::class);
    Route::resource('treatments', TreatmentController::class);
    Route::get('laboratories/report/range-pdf', [LaboratoryController::class, 'rangePdf'])->name('laboratories.range.pdf');
    Route::get('laboratories/{laboratory}/pdf', [LaboratoryController::class, 'pdf'])->name('laboratories.pdf');
    Route::resource('laboratories', LaboratoryController::class);
});
