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
Route::get('/web/solicitar/servicio', 'ServiciosController@preservicio')->name('web.preservicio');
Route::post('/web/preservicio/save', 'ServiciosController@preserviciosave')->name('web.preservicios.save');

Route::get('/web/pasajeros/servicios', 'ServiciosController@pasajeroListarServicios')->name('web.pasajeros.servicios');
Route::post('/web/pasajeros/servicios/cancelar/{id}', 'ServiciosController@pasajeroCancelarServicio')->name('web.pasajeros.cancelarservicio');

Route::get('/web/conductor/servicios', 'ServiciosController@conductorListarServicios')->name('web.conductor.servicios');
Route::post('/web/conductor/servicios/finalizar/{id}', 'ServiciosController@conductorFinalizarServicio')->name('web.conductor.finalizarservicio');
Route::get('/web/conductor/recoger/pasajero/{id}', 'ServiciosController@conductorRecogerPasajero')->name('web.conductor.recogerpasajero');


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
Route::get('/clientes/contrato/fuec/{id}', 'ClientesController@contrato_fuec')->name('customers.fuec.contract');
Route::get('/clientes/contrato/fuec/{id}/edit/{contratoid}', 'ClientesController@contrato_fuec')->name('customers.fuec.contract.edit');
Route::post('/clientes/contrato/fuec/save', 'ClientesController@contrato_fuec_save')->name('customers.contract.fuec.save');
Route::post('/clientes/documentos/save', 'ClientesController@documentossave')->name('customers.documentos.save');
Route::get('/clientes/exportar', 'ClientesController@exportar')->name('customer.export');
Route::get('/clientes/register', 'ClientesController@new')->name('signIn');

Route::get('/empleados', 'EmpleadosController@index')->name('employes');
Route::get('/empleados/new', 'EmpleadosController@new')->name('employes.new');
Route::get('/empleados/edit/{id}', 'EmpleadosController@edit')->name('employes.edit');
Route::post('/empleados/save', 'EmpleadosController@save')->name('employes.save');
Route::post('/empleados/delete', 'EmpleadosController@delete')->name('employes.delete');
Route::get('/empleados/delete/{id}', 'EmpleadosController@delete')->name('employes.delete.get');


Route::get('/pasajeros', 'PasajerosController@index')->name('pasajeros');
Route::get('/pasajeros/new', 'PasajerosController@new')->name('pasajeros.new');
Route::get('/pasajeros/edit/{id}', 'PasajerosController@edit')->name('pasajeros.edit');
Route::post('/pasajeros/save', 'PasajerosController@save')->name('pasajeros.save');
Route::post('/pasajeros/delete', 'PasajerosController@delete')->name('pasajeros.delete');
Route::get('/pasajeros/delete/{id}', 'PasajerosController@delete')->name('pasajeros.delete.get');
Route::get('/pasajeros/importar', 'PasajerosController@importar')->name('pasajeros.importar');
Route::get('/pasajeros/exportar', 'PasajerosController@exportar')->name('pasajeros.exportar');

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
Route::get('/conductores/jornada', 'JornadaConductoresController@index')->name('conductores.jornada');
Route::get('/conductores/jornada/{placa}', 'JornadaConductoresController@placa')->name('conductores.jornada.placa');
Route::get('/conductores/jornada/save/{tipo}', 'JornadaConductoresController@save')->name('conductores.jornada.save');
Route::get('/conductores/location/map', 'ConductoresController@getLocationMap')->name('conductores.localizacion');
Route::post('/conductores/location/save', 'ConductoresController@locationSave')->name('conductores.location.save');
Route::get('/conductores/admin/sms', 'ConductoresController@adminsms')->name('conductores.admin.sms');
Route::post('/conductores/admin/sms/save', 'ConductoresController@adminsmsSave')->name('conductores.admin.sms.save');
Route::post('/conductores/admin/sms/delete', 'ConductoresController@deleteSms')->name('conductores.delete.sms');
Route::get('/conductores/admin/sms/delete', 'ConductoresController@deleteSms')->name('conductores.delete.sms');

Route::get('/planilla/servicios', 'PlanillaServiciosController@index')->name('planillaservicios');
Route::get('/planilla/servicios/new', 'PlanillaServiciosController@new')->name('planillaservicios.new');
Route::get('/planilla/servicios/edit/{id}', 'PlanillaServiciosController@edit')->name('planillaservicios.edit');
Route::post('/planilla/servicios/save', 'PlanillaServiciosController@save')->name('planillaservicios.save');
Route::get('/planilla/servicios/delete/{id}', 'PlanillaServiciosController@delete')->name('planillaservicios.delete');



Route::get('/vehiculos/mantenimientos', 'VehiculosMantenimientosController@index')->name('vehiculos.mantenimientos');
Route::get('/vehiculos/mantenimientos/new', 'VehiculosMantenimientosController@new')->name('vehiculos.mantenimientos.new');
Route::get('/vehiculos/mantenimientos/edit/{id}', 'VehiculosMantenimientosController@edit')->name('vehiculos.mantenimientos.edit');
Route::post('/vehiculos/mantenimientos/save', 'VehiculosMantenimientosController@save')->name('vehiculos.mantenimientos.save');
Route::get('/vehiculos/mantenimientos/delete', 'VehiculosMantenimientosController@delete')->name('vehiculos.mantenimientos.delete');



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
Route::get('/vehiculos/descargar/excel', 'VehiculosController@descargarExcel')->name('vehiculos.descargar.excel');



Route::get('/preservicios', 'ServiciosController@listar_preservicios')->name('preservicios');
Route::post('/preservicios/placa/save', 'ServiciosController@preservicios_placasave')->name('preservicios.placa.save');

Route::get('/preservicios/delete/{id}', 'ServiciosController@preservicio_delete')->name('preservicios.delete');
Route::post('/preservicios/delete/{id}', 'ServiciosController@preservicio_delete')->name('preservicios.delete');

Route::get('/servicios', 'ServiciosController@index')->name('servicios');
Route::get('/servicios/new', 'ServiciosController@new')->name('servicios.new');
Route::get('/servicios/new/fromaddress/{id}', 'ServiciosController@fromAddress')->name('servicios.new.fromaddress');
Route::get('/servicios/from/preservicio/{id}', 'ServiciosController@fromPreservicio')->name('servicios.from.preservicio');
Route::get('/servicios/edit/{id}', 'ServiciosController@edit')->name('servicios.edit');
Route::post('/servicios/save', 'ServiciosController@save')->name('servicios.save');
Route::get('/servicios/delete/{id}', 'ServiciosController@delete')->name('servicios.delete');
Route::post('/servicios/delete/{id}', 'ServiciosController@delete')->name('servicios.delete');
Route::get('/servicios/descargar', 'ServiciosController@descargar')->name('servicios.descargar');
Route::get('/servicios/importar', 'ServiciosController@importar')->name('servicios.importar');
Route::post('/servicios/importar/save', 'ServiciosController@importarsave')->name('servicios.importar.save');
Route::get('/servicios/fuec/{id}', 'ServiciosController@fuec')->name('servicios.fuec');
Route::get('/servicios/importador/{auxid}', 'ServiciosController@importadorpreview')->name('servicios.importador.preview');
Route::get('/servicios/importador/enviarlote/{auxid}', 'ServiciosController@importadorenviarlote')->name('servicios.importador.enviarlote');
Route::get('/servicios/importador/eliminarlote/{auxid}', 'ServiciosController@importadoreliminarlote')->name('servicios.importador.eliminarlote');

Route::get('/servicios/test/email/{id}', 'ServiciosController@testEmail')->name('servicios.test.email');


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
Route::get('/cotizaciones/descargar/excel', 'CotizacionesController@descargarExcel')->name('cotizaciones.descargar.excel');
Route::get('/cotizaciones/descargar/{id}', 'CotizacionesController@descargar')->name('cotizaciones.descargar');
Route::post('/cotizaciones/match/tarifa', 'CotizacionesController@matchTarifa')->name('cotizaciones.matchtarifa');



Route::get('/anticipos', 'AnticiposController@index')->name('anticipos');
Route::get('/anticipos/new', 'AnticiposController@new')->name('anticipos.new');
Route::get('/anticipos/edit/{id}', 'AnticiposController@edit')->name('anticipos.edit');
Route::post('/anticipos/save', 'AnticiposController@save')->name('anticipos.save');
Route::post('/anticipos/delete/{id}', 'AnticiposController@delete')->name('anticipos.delete.post');
Route::get('/anticipos/delete/{id}', 'AnticiposController@delete')->name('anticipos.delete.get');
Route::get('/anticipos/abonos/{id}', 'AbonosController@index')->name('anticipos.abonos');
Route::get('/anticipos/abonos/{id}/new', 'AbonosController@new')->name('anticipos.abonos.new');
Route::post('/anticipos/abonos/save', 'AbonosController@save')->name('anticipos.abonos.save');
Route::get('/anticipos/fromservicio/{id}', 'AnticiposController@fromservicio')->name('anticipos.fromservicio');
Route::get('/anticipos/descargar/excel', 'AnticiposController@descargarExcel')->name('anticipos.descargar.excel');
Route::get('/anticipos/descargar/{id}', 'AnticiposController@descargar')->name('anticipos.descargar');


Route::get('/municipios', 'UtilsController@municipios')->name('utils.municipios');
Route::get('/conductores/placa/{placa}', 'VehiculosController@getConductoresPlaca')->name('vehiculos.conductores.placa');

Route::post('/general/importar/save', 'ImportadorController@index')->name('general.importar.save');


Route::get('/tarifario', 'TarifarioController@index')->name('tarifario');
Route::get('/tarifario/new', 'TarifarioController@new')->name('tarifario.new');
Route::get('/tarifario/edit/{id}', 'TarifarioController@edit')->name('tarifario.edit');
Route::post('/tarifario/save', 'TarifarioController@save')->name('tarifario.save');
Route::post('/tarifario/delete', 'TarifarioController@delete')->name('tarifario.delete');
Route::get('/tarifario/delete/{id}', 'TarifarioController@delete')->name('tarifario.delete.get');


Route::get('/tarifas/tiposervicio', 'TarifasTipoServicioController@index')->name('tarifastiposervicio');
Route::get('/tarifas/tiposervicio/new', 'TarifasTipoServicioController@new')->name('tarifastiposervicio.new');
Route::get('/tarifas/tiposervicio/edit/{id}', 'TarifasTipoServicioController@edit')->name('tarifastiposervicio.edit');
Route::post('/tarifas/tiposervicio/save', 'TarifasTipoServicioController@save')->name('tarifastiposervicio.save');
Route::post('/tarifas/tiposervicio/delete', 'TarifasTipoServicioController@delete')->name('tarifastiposervicio.delete');
Route::get('/tarifas/tiposervicio/delete/{id}', 'TarifasTipoServicioController@delete')->name('tarifastiposervicio.delete.get');
Route::get('/tarifas/tiposervicio/match', 'TarifasTipoServicioController@match')->name('tarifastiposervicio.match');


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
Route::get('/fuec/duplicar/{id}', 'FuecController@duplicar')->name('fuec.duplicar');
Route::get('/fuec/contrato/{id}', 'FuecController@getContratoCliente')->name('fuec.contrato');
Route::post('/fuec/delete', 'FuecController@delete')->name('fuec.delete');
Route::get('/fuec/delete/{id}', 'FuecController@delete')->name('fuec.delete.get');

Route::get('/informes/documentos', 'InformesController@documentos')->name('informes.documentos');
Route::get('/informes/documentos/placa', 'InformesController@documentosPlaca')->name('informes.documentos.placa');



Route::get('/rutas', 'RutasController@index')->name('rutas');
Route::get('/rutas/new', 'RutasController@new')->name('rutas.new');
Route::get('/rutas/edit/{id}', 'RutasController@edit')->name('rutas.edit');
Route::post('/rutas/save', 'RutasController@save')->name('rutas.save');
Route::post('/rutas/delete', 'RutasController@delete')->name('rutas.delete');
Route::get('/rutas/delete/{id}', 'RutasController@delete')->name('rutas.delete.get');


Route::get('/alistamiento', 'AlistamientoVehiculosController@index')->name('alistamiento');
Route::get('/alistamiento/new/{id}', 'AlistamientoVehiculosController@new')->name('alistamiento.new');
Route::get('/alistamiento/edit/{id}', 'AlistamientoVehiculosController@edit')->name('alistamiento.edit');
Route::get('/alistamiento/descargar/excel', 'AlistamientoVehiculosController@descargarExcel')->name('alistamiento.descargar.excel');
Route::get('/alistamiento/descargar/{id}', 'AlistamientoVehiculosController@descargar')->name('alistamiento.descargar');
Route::post('/alistamiento/save', 'AlistamientoVehiculosController@save')->name('alistamiento.save');
Route::post('/alistamiento/save/revision', 'AlistamientoVehiculosController@save_revision')->name('alistamiento.save.revision');

Route::get('/empresas/convenios', 'EmpresasConveniosController@index')->name('empresas.convenios');
Route::get('/empresas/convenios/new', 'EmpresasConveniosController@new')->name('empresas.convenios.new');
Route::get('/empresas/convenios/edit/{id}', 'EmpresasConveniosController@edit')->name('empresas.convenios.edit');
Route::post('/empresas/convenios/save', 'EmpresasConveniosController@save')->name('empresas.convenios.save');
Route::post('/empresas/convenios/delete/{id}', 'EmpresasConveniosController@delete')->name('empresas.convenios.delete');
Route::get('/empresas/convenios/delete/{id}', 'EmpresasConveniosController@delete')->name('empresas.convenios.delete.get');

Route::get('/convenios/empresariales', 'ConveniosEmpresarialesController@index')->name('convenios');
Route::get('/convenios/empresariales/new', 'ConveniosEmpresarialesController@new')->name('convenios.new');
Route::get('/convenios/empresariales/edit/{id}', 'ConveniosEmpresarialesController@edit')->name('convenios.edit');
Route::post('/convenios/empresariales/save', 'ConveniosEmpresarialesController@save')->name('convenios.save');
Route::get('/convenios/empresariales/descargar/{id}', 'ConveniosEmpresarialesController@descargar')->name('convenios.descargar');
Route::post('/convenios/empresariales/delete', 'ConveniosEmpresarialesController@delete')->name('convenios.delete');
Route::get('/convenios/empresariales/delete/{id}', 'ConveniosEmpresarialesController@delete')->name('convenios.delete.get');

Route::get('/sigdocumentos', 'SigDocumentosController@index')->name('sigdocumentos.index');
Route::get('/sigdocumentos/categorias/{id}', 'SigDocumentosController@index')->name('sigdocumentos.categorias');
Route::post('/sigdocumentos/subcategorias/files/delete', 'SigDocumentosController@deleteFile')->name('sigdocumentos.subcategorias.files.delete');
Route::get('/sigdocumentos/subcategorias/files/delete', 'SigDocumentosController@deleteFile')->name('sigdocumentos.subcategorias.files.delete.get');
Route::get('/sigdocumentos/subcategorias/{id}', 'SigDocumentosController@index')->name('sigdocumentos.subcategorias');
Route::get('/sigdocumentos/subcategorias/files/{id}', 'SigDocumentosController@subcategoriasFiles')->name('sigdocumentos.subcategorias.files');
Route::post('/sigdocumentos/subcategorias/files/upload', 'SigDocumentosController@subcategoriasFilesUpload')->name('sigdocumentos.subcategorias.files.upload');

