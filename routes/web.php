<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\ApisController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CompaniaController;
use App\Http\Controllers\CotizacioneController;
use App\Http\Controllers\CreditoclienteController;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\FormaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
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

// Rutas públicas
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');


// Rutas de Errores
Route::get('/401', fn() => view('pages.401'))->name('error.401');
Route::get('/404', fn() => view('pages.404'))->name('error.404');
Route::get('/500', fn() => view('pages.500'))->name('error.500');


// Rutas Autenticadas
Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('panel');
    Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');

    Route::resource('usuarios', UsuarioController::class)->only(['index', 'edit', 'update'])->names('usuarios');
    Route::resource('roles', RoleController::class)->names('roles');
    Route::resource('profile', ProfileController::class)->only(['index', 'update']);

    Route::get('/backup', [BackupController::class, 'index'])->name('admin.backup.index'); // Página principal de backup
    Route::post('/backup', [BackupController::class, 'backup'])->name('admin.backup.create'); // Crear un backup
    Route::post('/restore', [BackupController::class, 'restore'])->name('admin.backup.restore'); // Restaurar un backup

    Route::resource('clientes', ClienteController::class)->except('show')->names('admin.clientes');
    Route::resource('formas', FormaController::class)->except('show')->names('admin.formas');


    Route::get('/listarClientes', [DatatableController::class, 'clients'])->middleware('can:admin.clientes.index')->name('admin.clients.list');
    Route::get('/listarPrestamos', [DatatableController::class, 'prestamos'])->middleware('can:admin.prestamos.index')->name('admin.prestamos.list');
    Route::get('/listarCreditoclientes/{id}', [DatatableController::class, 'creditoclientes'])->middleware('can:admin.creditoclientes.index')->name('admin.creditoclientes.list');
    Route::get('/listarFormas', [DatatableController::class, 'formas'])->middleware('can:admin.formas-pago.index')->name('admin.formas.list');

    Route::get('/compania', [CompaniaController::class, 'index'])->middleware('can:admin.compania.index')->name('admin.compania.index');
    Route::put('/compania/{compania}', [CompaniaController::class, 'update'])->middleware('can:admin.compania.update')->name('admin.compania.update');

    Route::get('/prestamos', [PrestamoController::class, 'index'])->middleware('can:admin.prestamos.index')->name('admin.prestamos.index');
    Route::get('/prestamos/show', [PrestamoController::class, 'show'])->middleware('can:admin.prestamos.show')->name('admin.prestamos.show');
    Route::get('/prestamos/cliente', [PrestamoController::class, 'cliente'])->middleware('can:admin.prestamos.index')->name('admin.prestamos.cliente');
    Route::post('/prestamos', [PrestamoController::class, 'store'])->middleware('can:admin.prestamos.store')->name('admin.prestamos.store');
    Route::get('/prestamos/{id}/edit', [PrestamoController::class, 'edit'])->middleware('can:admin.prestamos.edit')->name('admin.prestamos.edit');
    Route::put('/prestamos/{id}/update', [PrestamoController::class, 'update'])->middleware('can:admin.prestamos.update')->name('admin.prestamos.update');
    Route::put('/prestamos/{id}/anular', [PrestamoController::class, 'anular'])->middleware('can:admin.prestamos.anular')->name('admin.prestamos.anular');
    Route::get('/prestamos/{id}/ticket', [PrestamoController::class, 'ticket'])->middleware('can:admin.prestamos.ticket')->name('admin.prestamos.ticket');
    Route::get('/prestamos/{id}/cocina', [PrestamoController::class, 'cocina'])->middleware('can:admin.prestamos.cocina')->name('admin.prestamos.cocina');
    Route::get('/prestamos-report-excel', [PrestamoController::class, 'generateExcelReport'])->middleware('can:admin.prestamos.reportes')->name('admin.prestamos.reportExcel');
    Route::get('/prestamos-report-pdf', [PrestamoController::class, 'generatePdfReport'])->middleware('can:admin.prestamos.reportes')->name('admin.prestamos.reportPdf');

    Route::get('/creditolimite/{id}', [CreditoclienteController::class, 'limitecliente'])->middleware('can:admin.creditoclientes.abonos')->name('admin.creditoclientes.limitecliente');
    Route::get('/creditoclientes/{id}', [CreditoclienteController::class, 'index'])->middleware('can:admin.creditoclientes.index')->name('admin.creditoclientes.index');
    Route::get('/creditoclientes/{id}/ticket', [CreditoclienteController::class, 'ticket'])->middleware('can:admin.creditoclientes.abonos')->name('admin.creditoclientes.ticket');
    Route::get('/creditoclientes/{id}/abonos', [CreditoclienteController::class, 'abonos'])->middleware('can:admin.creditoclientes.abonos')->name('admin.creditoclientes.abonos');
    Route::get('/creditoclientes/{id}/detalle', [CreditoclienteController::class, 'detalle'])->middleware('can:admin.creditoclientes.abonos')->name('admin.creditoclientes.detalle');
    Route::get('/creditoclientes-report-excel', [CreditoclienteController::class, 'generateExcelReport'])->middleware('can:admin.creditoclientes.reportes')->name('admin.creditoclientes.reportExcel');
    Route::get('/creditoclientes-report-pdf', [CreditoclienteController::class, 'generatePdfReport'])->middleware('can:admin.creditoclientes.reportes')->name('admin.creditoclientes.reportPdf');
    Route::post('/abonoclientes', [CreditoclienteController::class, 'registrarAbono'])->middleware('can:admin.creditoclientes.abonos')->name('admin.creditoclientes.registrarAbono');

    Route::get('/cotizacion', [CotizacioneController::class, 'index'])->middleware('can:admin.cotizacion.index')->name('admin.cotizacion.index');
    Route::get('/cotizacion/show', [CotizacioneController::class, 'show'])->middleware('can:admin.cotizacion.show')->name('admin.cotizacion.show');
    Route::get('/cotizacion/cliente', [CotizacioneController::class, 'cliente'])->middleware('can:admin.cotizacion.index')->name('admin.cotizacion.cliente');
    Route::post('/cotizacion', [CotizacioneController::class, 'store'])->middleware('can:admin.cotizacion.index')->name('admin.cotizacion.store');
    Route::put('/cotizacion/{id}/eliminar', [CotizacioneController::class, 'eliminar'])->middleware('can:admin.cotizacion.eliminar')->name('admin.cotizacion.eliminar');
    Route::get('/cotizacion/{id}/ticket', [CotizacioneController::class, 'ticket'])->name('admin.cotizacion.ticket');

    Route::get('/pdf/clientes', [ReporteController::class, 'pdfCliente'])->middleware('can:admin.clientes.reportes')->name('admin.clientes.pdf');
    Route::get('/excel/clientes', [ReporteController::class, 'excelCliente'])->middleware('can:admin.clientes.reportes')->name('admin.clientes.excel');

    Route::post('/consulta-dni', [ApisController::class, 'consultaDni'])->name('consulta.dni');

    Route::put('/creditoclientes/comentario/{id}', [CreditoclienteController::class, 'actualizarComentario'])->middleware('can:admin.creditoclientes.index')->name('admin.creditoclientes.comentario');
});

// Redirigir rutas no autenticadas
Route::fallback(function () {
    return redirect()->route('login');
});
