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
Route::get('/clientes/delete/{id}', 'ClientesController@delete')->name('customers.delete.get');
Route::get('/clientes/importar', 'ClientesController@importar')->name('customers.importar');
Route::get('/clientes/register', 'ClientesController@new')->name('signIn');

Route::get('/empleados', 'EmpleadosController@index')->name('employes');
Route::get('/empleados/new', 'EmpleadosController@new')->name('employes.new');
Route::get('/empleados/edit/{id}', 'EmpleadosController@edit')->name('employes.edit');
Route::post('/empleados/save', 'EmpleadosController@save')->name('employes.save');
Route::post('/empleados/delete', 'EmpleadosController@delete')->name('employes.delete');
Route::post('/empleados/delete/{id}', 'EmpleadosController@delete')->name('employes.delete.get');


Route::get('/pasajeros', 'PasajerosController@index')->name('pasajeros');
Route::get('/pasajeros/new', 'PasajerosController@new')->name('pasajeros.new');
Route::get('/pasajeros/edit/{id}', 'PasajerosController@edit')->name('pasajeros.edit');
Route::post('/pasajeros/save', 'PasajerosController@save')->name('pasajeros.save');
Route::post('/pasajeros/delete', 'PasajerosController@delete')->name('pasajeros.delete');
Route::get('/pasajeros/delete/{id}', 'PasajerosController@delete')->name('pasajeros.delete.get');
Route::get('/pasajeros/importar', 'PasajerosController@importar')->name('pasajeros.importar');



Route::get('/propietarios', 'PropietariosVehiculosController@index')->name('propietarios');
Route::get('/propietarios/new', 'PropietariosVehiculosController@new')->name('propietarios.new');
Route::get('/propietarios/edit/{id}', 'PropietariosVehiculosController@edit')->name('propietarios.edit');
Route::post('/propietarios/save', 'PropietariosVehiculosController@save')->name('propietarios.save');
Route::post('/propietarios/delete/{id}', 'PropietariosVehiculosController@delete')->name('propietarios.delete');
Route::get('/propietarios/delete/{id}', 'PropietariosVehiculosController@delete')->name('propietarios.delete.get');
Route::get('/propietarios/importar', 'PropietariosVehiculosController@importar')->name('propietarios.importar');



Route::get('/conductores', 'ConductoresController@index')->name('conductores');
Route::get('/conductores/new', 'ConductoresController@new')->name('conductores.new');
Route::get('/conductores/edit/{id}', 'ConductoresController@edit')->name('conductores.edit');
Route::post('/conductores/save', 'ConductoresController@save')->name('conductores.save');
Route::post('/conductores/hojavida/save', 'ConductoresController@hojavidasave')->name('conductores.hojavida.save');
Route::post('/conductores/documentos/save', 'ConductoresController@documentossave')->name('conductores.documentos.save');
Route::post('/conductores/delete', 'ConductoresController@delete')->name('conductores.delete');
Route::get('/conductores/delete/{id}', 'ConductoresController@delete')->name('conductores.delete.get');
Route::get('/conductores/importar', 'ConductoresController@importar')->name('conductores.importar');


Route::get('/vehiculos', 'VehiculosController@index')->name('vehiculos');
Route::get('/vehiculos/new', 'VehiculosController@new')->name('vehiculos.new');
Route::get('/vehiculos/edit/{id}', 'VehiculosController@edit')->name('vehiculos.edit');
Route::post('/vehiculos/save', 'VehiculosController@save')->name('vehiculos.save');
Route::post('/vehiculos/delete/{id}', 'VehiculosController@delete')->name('vehiculos.delete');
Route::get('/vehiculos/delete/{id}', 'VehiculosController@delete')->name('vehiculos.delete.get');

Route::post('/vehiculos/conductores/save', 'VehiculosController@saveConductores')->name('vehiculos.save.conductores');
Route::post('/vehiculos/documentos/save', 'VehiculosController@documentossave')->name('vehiculos.documentos.save');

Route::get('/vehiculos/delete/conductor/{id}', 'VehiculosController@deleteConductor')->name('vehiculos.delete.conductor');
Route::get('/vehiculos/importar', 'VehiculosController@importar')->name('vehiculos.importar');


Route::get('/servicios', 'ServiciosController@index')->name('servicios');
Route::get('/servicios/new', 'ServiciosController@new')->name('servicios.new');
Route::get('/servicios/new/fromaddress/{id}', 'ServiciosController@fromAddress')->name('servicios.new.fromaddress');

Route::get('/servicios/edit/{id}', 'ServiciosController@edit')->name('servicios.edit');
Route::post('/servicios/save', 'ServiciosController@save')->name('servicios.save');
Route::get('/servicios/delete/{id}', 'ServiciosController@delete')->name('servicios.delete');
Route::post('/servicios/delete/{id}', 'ServiciosController@delete')->name('servicios.delete');
Route::get('/servicios/descargar', 'ServiciosController@descargar')->name('servicios.descargar');
Route::get('/servicios/importar', 'ServiciosController@importar')->name('servicios.importar');
Route::post('/servicios/importar/save', 'ServiciosController@importarsave')->name('servicios.importar.save');

Route::get('/servicios/fuec/{id}', 'ServiciosController@fuec')->name('servicios.fuec');


Route::get('/facturas', 'FacturasController@index')->name('facturas');


Route::get('/cotizaciones', 'CotizacionesController@index')->name('cotizaciones');
Route::get('/cotizaciones/new', 'CotizacionesController@new')->name('cotizaciones.new');
Route::get('/cotizaciones/edit/{id}', 'CotizacionesController@edit')->name('cotizaciones.edit');
Route::post('/cotizaciones/save', 'CotizacionesController@save')->name('cotizaciones.save');
Route::post('/cotizaciones/delete', 'CotizacionesController@delete')->name('cotizaciones.delete');
Route::post('/cotizaciones/save/item', 'CotizacionesController@saveItem')->name('cotizaciones.save.item');
Route::get('/cotizaciones/delete/{id}', 'CotizacionesController@delete')->name('cotizaciones.delete.item');
Route::post('/cotizaciones/delete/{id}', 'CotizacionesController@delete')->name('cotizaciones.delete.item');

Route::get('/cotizaciones/delete/detalle/{id}', 'CotizacionesController@deleteDetalle')->name('cotizaciones.delete.detalle');

Route::get('/cotizaciones/descargar/{id}', 'CotizacionesController@descargar')->name('cotizaciones.descargar');

Route::get('/anticipos', 'AnticiposController@index')->name('anticipos');
Route::get('/anticipos/new', 'AnticiposController@new')->name('anticipos.new');
Route::get('/anticipos/edit/{id}', 'AnticiposController@edit')->name('anticipos.edit');
Route::post('/anticipos/save', 'AnticiposController@save')->name('anticipos.save');
Route::post('/anticipos/delete/{id}', 'AnticiposController@delete')->name('anticipos.delete.post');
Route::get('/anticipos/delete/{id}', 'AnticiposController@delete')->name('anticipos.delete.get');
Route::get('/anticipos/abonos/{id}', 'AbonosController@index')->name('anticipos.abonos');

Route::get('/municipios', 'UtilsController@municipios')->name('utils.municipios');
Route::get('/conductores/placa/{placa}', 'VehiculosController@getConductoresPlaca')->name('vehiculos.conductores.placa');

Route::post('/general/importar/save', 'ImportadorController@index')->name('general.importar.save');

Route::get('/tarifario', 'TarifarioController@index')->name('tarifario');
Route::get('/tarifario/new', 'TarifarioController@new')->name('tarifario.new');
Route::get('/tarifario/edit/{id}', 'TarifarioController@edit')->name('tarifario.edit');
Route::post('/tarifario/save', 'TarifarioController@save')->name('tarifario.save');
Route::post('/tarifario/delete', 'TarifarioController@delete')->name('tarifario.delete');
Route::get('/tarifario/delete/{id}', 'TarifarioController@delete')->name('tarifario.delete.get');

Route::get('/sedes', 'SedesController@index')->name('sedes');
Route::get('/sedes/new', 'SedesController@new')->name('sedes.new');
Route::get('/sedes/edit/{id}', 'SedesController@edit')->name('sedes.edit');
Route::post('/sedes/save', 'SedesController@save')->name('sedes.save');
Route::post('/sedes/delete', 'SedesController@delete')->name('sedes.delete');
Route::get('/sedes/delete/{id}', 'SedesController@delete')->name('sedes.delete.get');

Route::get('/perfil', 'PerfilController@index')->name('perfil');
Route::post('/perfil/save', 'PerfilController@save')->name('perfil.save');

Route::get('/auditoria', 'AuditoriaController@index')->name('auditoria');


Route::get('/fuec', 'FuecController@index')->name('fuec');
Route::get('/fuec/new', 'FuecController@new')->name('fuec.new');
Route::get('/fuec/edit/{id}', 'FuecController@edit')->name('fuec.edit');
Route::post('/fuec/save', 'FuecController@save')->name('fuec.save');
Route::get('/fuec/descargar/{id}', 'FuecController@descargar')->name('fuec.descargar');

Route::post('/fuec/delete', 'FuecController@delete')->name('fuec.delete');
Route::get('/fuec/delete/{id}', 'FuecController@delete')->name('fuec.delete.get');

Route::get('/informes/documentos', 'InformesController@documentos')->name('informes.documentos');

