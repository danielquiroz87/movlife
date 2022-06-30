	<?php

use Illuminate\Support\Facades\Route;


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
Route::get('/user/logout', 'ClientesController@logout')->name('user.logout');

Auth::routes();
Route::get('/', 'HomeController@index')->name('index');
Route::get('/home', 'HomeController@index')->name('index');
Route::get('/inicio', 'HomeController@index')->name('home');
Route::get('/clientes', 'ClientesController@index')->name('customers');
Route::get('/clientes/new', 'ClientesController@new')->name('customers.new');
Route::get('/clientes/edit/{id}', 'ClientesController@edit')->name('customers.edit');
Route::post('/clientes/save', 'ClientesController@save')->name('customers.save');
Route::post('/clientes/delete', 'ClientesController@delete')->name('customers.delete');
Route::get('/clientes/register', 'ClientesController@new')->name('signIn');

Route::get('/empleados', 'EmpleadosController@index')->name('employes');
Route::get('/empleados/new', 'EmpleadosController@new')->name('employes.new');
Route::get('/empleados/edit/{id}', 'EmpleadosController@edit')->name('employes.edit');
Route::post('/empleados/save', 'EmpleadosController@save')->name('employes.save');
Route::post('/empleados/delete', 'EmpleadosController@delete')->name('employes.delete');

Route::get('/pasajeros', 'PasajerosController@index')->name('pasajeros');
Route::get('/pasajeros/new', 'PasajerosController@new')->name('pasajeros.new');
Route::get('/pasajeros/edit/{id}', 'PasajerosController@edit')->name('pasajeros.edit');
Route::post('/pasajeros/save', 'PasajerosController@save')->name('pasajeros.save');
Route::post('/pasajeros/delete', 'PasajerosController@delete')->name('pasajeros.delete');

Route::get('/propietarios', 'PropietariosVehiculosController@index')->name('propietarios');
Route::get('/propietarios/new', 'PropietariosVehiculosController@new')->name('propietarios.new');
Route::get('/propietarios/edit/{id}', 'PropietariosVehiculosController@edit')->name('propietarios.edit');
Route::post('/propietarios/save', 'PropietariosVehiculosController@save')->name('propietarios.save');
Route::post('/propietarios/delete', 'PropietariosVehiculosController@delete')->name('propietarios.delete');

Route::get('/conductores', 'ConductoresController@index')->name('conductores');
Route::get('/conductores/new', 'ConductoresController@new')->name('conductores.new');
Route::get('/conductores/edit/{id}', 'ConductoresController@edit')->name('conductores.edit');
Route::post('/conductores/save', 'ConductoresController@save')->name('conductores.save');
Route::post('/conductores/delete', 'ConductoresController@delete')->name('conductores.delete');

Route::get('/vehiculos', 'VehiculosController@index')->name('vehiculos');
Route::get('/vehiculos/new', 'VehiculosController@new')->name('vehiculos.new');
Route::get('/vehiculos/edit/{id}', 'VehiculosController@edit')->name('vehiculos.edit');
Route::post('/vehiculos/save', 'VehiculosController@save')->name('vehiculos.save');
Route::post('/vehiculos/delete', 'VehiculosController@delete')->name('vehiculos.delete');
Route::post('/vehiculos/save/conductores', 'VehiculosController@saveConductores')->name('vehiculos.save.conductores');

Route::get('/vehiculos/delete/conductor/{id}', 'VehiculosController@deleteConductor')->name('vehiculos.delete.conductor');

Route::get('/servicios', 'ServiciosController@index')->name('servicios');
Route::get('/servicios/new', 'ServiciosController@new')->name('servicios.new');
Route::get('/servicios/new/fromaddress/{id}', 'ServiciosController@fromAddress')->name('servicios.new.fromaddress');

Route::get('/servicios/edit/{id}', 'ServiciosController@edit')->name('servicios.edit');
Route::post('/servicios/save', 'ServiciosController@save')->name('servicios.save');
Route::post('/servicios/delete', 'ServiciosController@delete')->name('servicios.delete');
Route::get('/servicios/importar', 'ServiciosController@delete')->name('servicios.importar');


Route::get('/cotizaciones', 'CotizacionesController@index')->name('cotizaciones');
Route::get('/cotizaciones/new', 'CotizacionesController@new')->name('cotizaciones.new');
Route::get('/cotizaciones/edit/{id}', 'CotizacionesController@edit')->name('cotizaciones.edit');
Route::post('/cotizaciones/save', 'CotizacionesController@save')->name('cotizaciones.save');
Route::post('/cotizaciones/delete', 'CotizacionesController@delete')->name('cotizaciones.delete');
Route::post('/cotizaciones/save/item', 'CotizacionesController@saveItem')->name('cotizaciones.save.item');
Route::post('/cotizaciones/delete/item', 'CotizacionesController@delete')->name('cotizaciones.delete.item');


Route::get('/anticipos', 'AnticiposController@index')->name('anticipos');
Route::get('/anticipos/new', 'AnticiposController@new')->name('anticipos.new');
Route::get('/anticipos/edit/{id}', 'AnticiposController@edit')->name('anticipos.edit');
Route::post('/anticipos/save', 'AnticiposController@save')->name('anticipos.save');
Route::post('/anticipos/delete', 'AnticiposController@delete')->name('anticipos.delete');


Route::get('/municipios', 'UtilsController@municipios')->name('utils.municipios');

