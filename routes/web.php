<?php

use App\Http\Controllers\EjemploGraficasController;
use App\Http\Controllers\Auth\EntradaController;
use App\Http\Controllers\TablaController;
use App\Http\Controllers\Uno20200424CipController;
use App\Http\Controllers\UnoCipController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cip01SecuenciaController;
use App\Http\Controllers\Cip02SecuenciaController;
use App\Http\Controllers\EtiquetasG2Controller;
use App\Http\Controllers\EtiquetasG1Controller;
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
Route::get('/buscar/{dbtabla}', [TablaController::class, 'buscar'])->name('prueba'); //Ruta para la peticion ajax para la graficacion
Route::resource('graficas', TablaController::class); // Rutas definidas para graficacion
Route::get('/nomcips/{n}', [TablaController::class, 'nomcips']); //Ruta para la peticion de los nombre de cips a la bd para ingresarlos en el navbar de primera instancia


//INGRESANDO RUTA PARA SOLO CLIENTE
Route::get('/pasar', [EntradaController::class, 'ingresar_cliente'])->name('pasar');

// Rutas para el login, el validado de usuarios y la salida de usuarios
Route::get('/login', [EntradaController::class, 'login'])->name('entrada');
Route::post('/validar', [EntradaController::class, 'validar'])->name('validar');
Route::get('/salir', [EntradaController::class, 'salir'])->name('salir');

//Route::get('/bienvenida', [TablaController::class, 'bienvenida']); // Vista para la entrada del programa (Bienvenida)
Route::get('/principal/{nom}', [TablaController::class, 'principal']); // Metodo para obtener los nom de los cips mediante ajax
Route::get('/obtenerdivs', [TablaController::class, 'obtenerdivs']); // Ruta para el metodo donde retorna la cantidad de divs a mostrar en el navbar mediante peticion ajax


// PROBANDO RUTAS PARA ENTRAR COMO CLIENTE SIN LOGUE

Route::get('/', [EntradaController::class, 'como_cliente']);


//CREANDO RUTAS PARA VISUALIZAR LO DATO DE LA DB DE CIP01_SECUENCIAS Y RUTAS PARA PODER EDITAR LOS DATOS
Route::get('listar', [Cip01SecuenciaController::class, 'index']);
Route::get('vista-datos', [Cip01SecuenciaController::class, 'vistadato']);
Route::get('editar-cip01/{valor}', [Cip01SecuenciaController::class, 'edit']);
Route::put('actualizar-secuencia1/{valor}', [Cip01SecuenciaController::class, 'update']);


//CREANDO RUTAS PARA VISUALIZAR Y EDITAR DATOS DE LA DB DEL CIP02_SECUENCIAS
Route::get('listar2', [Cip02SecuenciaController::class, 'index']);
Route::get('vista-datos2', [Cip02SecuenciaController::class, 'vistadatos2']);
Route::get('editar-cip02/{valor2}', [Cip02SecuenciaController::class, 'edit']);
Route::put('actualizar-secuencia2/{valor2}', [Cip02SecuenciaController::class, 'update']);


//CREANDO RUTAS PARA VISUALIZAR Y EDITAR DATOS DE LA DB DE ETIQUTAS_GRAFICA2;
Route::get('etiquetas2', [EtiquetasG2Controller::class, 'index']);
Route::get('vista-etiquetas2', [EtiquetasG2Controller::class, 'etiquetasG2']);
Route::post('agregar-etiqueta2', [EtiquetasG2Controller::class, 'store' ]);
Route::get('editar-etiqueta2/{id2}', [EtiquetasG2Controller::class, 'edit']);
Route::put('actualizar-etiqueta2/{id2}', [EtiquetasG2Controller::class, 'update']);
Route::delete('eliminar-etiquetas2/{id2}', [EtiquetasG2Controller::class, 'destroy']);



//CREANDO RUTAS PARA VISUALIZAR Y EDITAR DATOS DE LA DB DE ETIQUETAS_GRAFICA1

Route::get('etiquetas1', [EtiquetasG1Controller::class, 'index']);
Route::get('vista-etiquetas1', [EtiquetasG1Controller::class, 'etiquetasG1']);
Route::post('agregar-etiqueta1',[EtiquetasG1Controller::class, 'store']);
Route::get('editar-etiquetas1/{id1}', [EtiquetasG1Controller::class, 'edit']);
Route::put('actualizar-etiqueta1/{id1}', [EtiquetasG1Controller::class, 'update']);
Route::delete('eliminar-etiquetas1/{id1}', [EtiquetasG1Controller::class, 'destroy']);

