<?php

use App\Http\Controllers\EjemploGraficasController;
use App\Http\Controllers\Auth\EntradaController;
use App\Http\Controllers\TablaController;
use App\Http\Controllers\Uno20200424CipController;
use App\Http\Controllers\UnoCipController;

use Barryvdh\DomPDF\Facade\Pdf;
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

Route::get('/', function () {
    return redirect('/login');
});



Route::get('/descargar', [TablaController::class, 'descargar']);



// Route::get('/', [EjemploGraficasController::class, 'index']);
// Route::get('graficacion', [UnoCipController::class, 'index']);
// Route::get('old_graficacion', [Uno20200424CipController::class, 'index'])->name('home');
//Route::get('graficas', [TablaController::class, 'index']);
Route::get('/buscar/{dbtabla}', [TablaController::class, 'buscar']); //Ruta para la peticion ajax para la graficacion
Route::resource('graficas', TablaController::class); // Rutas definidas para graficacion
Route::get('/nomcips/{n}', [TablaController::class, 'nomcips']); //Ruta para la peticion de los nombre de cips a la bd para ingresarlos en el navbar de primera instancia


// Rutas para el login, el validado de usuarios y la salida de usuarios
Route::get('/login', [EntradaController::class, 'login'])->name('entrada');
Route::post('/validar', [EntradaController::class, 'validar'])->name('validar');
Route::get('/salir', [EntradaController::class, 'salir'])->name('salir');

Route::get('/bienvenida', [TablaController::class, 'bienvenida']); // Vista para la entrada del programa (Bienvenida)
Route::get('/principal/{nom}', [TablaController::class, 'principal']); // Metodo para obtener los nom de los cips mediante ajax
Route::get('/obtenerdivs', [TablaController::class, 'obtenerdivs']); // Ruta para el metodo donde retorna la cantidad de divs a mostrar en el navbar mediante peticion ajax
