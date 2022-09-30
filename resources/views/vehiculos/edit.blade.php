@extends('layouts.master')

@section('main-content')
<div class="breadcrumb">
  <ul>
    <li><a href="/">Inicio</a></li>
    <li><a href="{{route('vehiculos')}}">Vehiculos</a></li>
    <li>Editar Vehículo</li>
  </ul>
</div>
<div class="separator-breadcrumb border-top"></div>

 @if ($message = Session::get('flash_message'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button> 
            <strong>{{ $message }}</strong>
    </div>
  @endif

<div class="row">

  @if ($errors->any())
  <div class="alert alert-danger">
    <strong>Error!</strong>
    <ul>
      @foreach ($errors->all() as $error)
      <li>{{$error}} </li>
      @endforeach
    </ul>
  </div>
  @endif
</div>
<div class="row">

  <div class="col-md-12">
    <div class="card text-left">
      <div class="card-body">
        <h3 class="card-title mb3">Editar Vehículo / Placa: {{$vehiculo->placa}},({{$vehiculo->marca->nombre}}-{{$vehiculo->color}})</h3>

        <ul class="nav nav-tabs" id="myIconTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active show" id="general-icon-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true"><i class="nav-icon i-Home1 mr-1"></i>General</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="general-icon-tab" data-toggle="tab" href="#admin-conductores" role="tab" aria-controls="general" aria-selected="true"><i class="nav-icon i-Home1 mr-1"></i>Conductores</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="general-icon-tab" data-toggle="tab" href="#hoja_vida" role="tab" aria-controls="general" aria-selected="true"><i class="nav-icon i-Home1 mr-1"></i>Hoja de Vida</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="documentos-icon-tab" data-toggle="tab" href="#documentos" role="tab" aria-controls="documentos" aria-selected="false"><i class="nav-icon i-Home1 mr-1"></i> Documentos</a>
          </li>
          <li class="nav-item"><a class="nav-link" id="historial-documentos-icon-tab" data-toggle="tab" href="#historial-documentos" role="tab" aria-controls="historial" aria-selected="false"><i class="nav-icon i-Home1 mr-1"></i> Historial Documentos</a>
          </li>
        </ul>


        <div class="tab-content">

          <div class="tab-pane fade active show" id="general" role="tabpanel" aria-labelledby="general-icon-tab">
            <div class="box box-info">
              <form action="{{route('vehiculos.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{$vehiculo->id}}">
                <input type="hidden" name="is_new" value="false">

                <div class="row">
                  <div class="col-md-6 form-group mb-3">
                    <label><strong>Placa:</strong></label>
                    <input type="text" name="placa"  class="form-control" placeholder="XXX000" maxlength="20" required value="{{$vehiculo->placa}}">
                  </div>
                  <div class="col-md-6 form-group mb-3">
                    <label><strong>Codigo Interno</strong></label>
                    <input type="text" name="codigo_interno" class="form-control" id="codigo_interno" placeholder="000" value="{{$vehiculo->codigo_interno}}"required>
                  </div>
                  <div class="col-md-6 form-group mb-3">
                        <label><strong>Modelo</strong></label>
                        <input type="number" name="modelo" class="form-control" id="modelo" min="1980"  placeholder="" value="{{$vehiculo->modelo}}" required>
                  </div>
                  
                  <div class="col-md-6 form-group mb-3">
                    <label><strong>Marca:</strong></label>
                    <select name="id_vehiculo_marca" class="form-control select-busqueda">
                        <?php echo Helper::selectVehiculoMarca($vehiculo->id_vehiculo_marca) ?>
                    </select>
                  </div>

                   <div class="col-md-6 form-group mb-3">
                    <label><strong>Clase:</strong></label>
                    <select name="id_vehiculo_clase" class="form-control select-busqueda">
                        <?php echo Helper::selectClaseVehiculos($vehiculo->id_vehiculo_clase) ?>
                    </select>
                  </div>

                  <div class="col-md-6 form-group mb-3">
                    <label><strong>Linea</strong></label>
                    <input type="text" name="linea" class="form-control" id="linea"  placeholder="Logan" value="{{$vehiculo->linea}}" required>
                  </div>


                <div class="col-md-6 form-group mb-3">
                <label><strong>Clasificación Vehículo:</strong></label>
                <select name="id_vehiculo_uso" class="form-control select-busqueda">
                    <option value="" >Seleccione</option>
                    <option value="1" @if($vehiculo->id_vehiculo_uso==1) selected=true @endif>Intermunicipal</option>
                    <option value="2" @if($vehiculo->id_vehiculo_uso==2) selected=true @endif>Especial</option>
                    <option value="3" @if($vehiculo->id_vehiculo_uso==3) selected=true @endif>Particular</option>
                    <option value="4" @if($vehiculo->id_vehiculo_uso==4) selected=true @endif>Taxi</option>
                    <option value="5" @if($vehiculo->id_vehiculo_uso==5) selected=true @endif>Otro</option>
                  </select>
                </div>
                <div class="col-md-6 form-group mb-3">
                <label><strong>Tipo Combustible:</strong></label>
                <select name="id_vehiculo_tipo_combustible" class="form-control select-busqueda">
                    <option value="" selected="selected">Seleccione</option>
                    <option value="1" @if($vehiculo->id_vehiculo_tipo_combustible==1) selected=true @endif>Acpm</option>
                    <option value="2" @if($vehiculo->id_vehiculo_tipo_combustible==2) selected=true @endif>Diesel</option>
                    <option value="3" @if($vehiculo->id_vehiculo_tipo_combustible==3) selected=true @endif>Gas-Gasolina</option>
                    <option value="4" @if($vehiculo->id_vehiculo_tipo_combustible==4) selected=true @endif>Gasolina</option>
                    <option value="5" @if($vehiculo->id_vehiculo_tipo_combustible==5) selected=true @endif>Gnb</option>
                 
                  </select>
                </div>
                <div class="col-md-6 form-group mb-3">
                  <label><strong>Pasajeros</strong></label>
                  <input type="number" name="pasajeros" class="form-control" id="pasajeros" min="0" max="100" placeholder="" value="{{$vehiculo->capacidad_pasajeros}}" required>
                </div>

                <div class="col-md-6 form-group mb-3">
                      <label><strong>Color</strong></label>
                      <input type="text" name="color" class="form-control" id="color"  placeholder="Color" value="{{$vehiculo->color}}" required>
                </div>

                <div class="col-md-6 form-group mb-3">
                      <label><strong>Nro Chasis</strong></label>
                      <input type="text" name="numero_chasis" class="form-control" id="chasis"  placeholder="# Chasis" value="{{$vehiculo->numero_chasis}}" >
                </div>

                <div class="col-md-6 form-group mb-3">
                      <label><strong>Nro Motor</strong></label>
                      <input type="text" name="numero_motor" class="form-control" id="motor"  placeholder="# Motor" value="{{$vehiculo->numero_motor}}">
                </div>

                <div class="col-md-6 form-group mb-3">
                      <label><strong>Cilindraje</strong></label>
                      <input type="text" name="cilindraje" class="form-control" id="cilindraje"  placeholder="Cilindraje" value="{{$vehiculo->cilindraje}}">
                </div>
                <div class="col-md-6 form-group mb-3">
                  <label><strong>Departamento:</strong></label>
                        <select name="departamento_id" id="departamento"  class="form-control departamentos">
                            <?php echo Helper::selectDepartamentos($vehiculo->departamento_id) ?>
                        </select>
                </div>
               
               <div class="col-md-6 form-group mb-3">
                  <label><strong>Ciudad:</strong></label>
                      <select name="ciudad_id" id="ciudad_id"  class="form-control municipios">
                            <?php echo Helper::selectMunicipios($vehiculo->departamento_id,$vehiculo->ciudad_id) ?>
                      </select>
              </div>

              <div class="col-md-6 form-group mb-3">
                <label><strong>Propietario:</strong></label>
                  <select name="propietario_id" id="propietario_id"  class="form-control municipios">
                    <?php echo Helper::selectPropietarios($vehiculo->propietario_id) ?>
                  </select>
              </div>


            
            <div class="col-md-6 form-group mb-3">
              <label class="switch pr-5 switch-success mr-3"><span>Vinculado</span>
                  <input type="checkbox" name="vinculado" @if($vehiculo->vinculado==1) checked=true @endif  ><span class="slider"></span>
              </label>
            </div>
            <div class="col-md-6 form-group mb-3">
               <label class="switch pr-5 switch-success mr-3"><span>Convenio Firmado</span>
                  <input type="checkbox" name="convenio" @if($vehiculo->convenio_firmado==1) checked=true @endif ><span class="slider"></span>
              </label>
            </div>

            <div class="col-md-6 form-group mb-3">
                      <label><strong>Empresa Afiliadora</strong></label>
                      <input type="text" name="empresa_afiliadora" class="form-control" id="empresa_afiliadora"  placeholder="" value="{{$vehiculo->empresa_afiliadora}}">
                </div>
                 

               <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('vehiculos') }}" class="btn btn-danger">Cancelar</a>
              </div>
            </div>

          </form>

        </div>                      

      </div>

        <div class="tab-pane" id="admin-conductores" role="tabpanel" aria-labelledby="general-icon-tab">
            <div class="box box-info">
                <div class="row">
                  <div class="col-md-6 form-group mb-3">
                    <label><strong>Administrar Conductores:</strong></label>
                  </div>
              </div>


              <form action="{{route('vehiculos.save.conductores')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{$vehiculo->id}}">
                <input type="hidden" name="is_new" value="false">

                <div class="row">
                  <table class="display table table-striped table-bordered dataTable dtr-inline">
                    <tbody>
                      <thead>
                        <th>Conductor</th>
                        <th>Acciones</th>
                      </thead>
                      <tbody>
                        @foreach ($conductores as $conductor)
                          <tr>
                            <td>{{$conductor['nombre']}}</td>
                            <td><a href="{{route('vehiculos.delete.conductor', $conductor['id'])}}">Eliminar</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </tbody>

                    
                  </table>
                </div>

                <div class="row">
                  <div class="col-md-12 form-group mb-3">
                  <h4>Asignar Conductores</h4>
                  </div>
                  <div class="col-md-6 form-group mb-3">
                    <label><strong>Propietario:</strong></label>
                      <select name="propietario_id" class="form-control">
                        <?php echo Helper::selectPropietarios($vehiculo->propietario_id) ?>
                      </select>
                  </div>

                  <div class="col-md-6 form-group mb-3">
                    <label><strong>Conductores:</strong></label>
                      <select name="conductor_id" class="form-control">
                        <?php echo Helper::selectConductores() ?>
                      </select>
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-12 ">
                    <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                   
                  </div>

                </div>
              </form>
        </div>
      </div>

      <div class="tab-pane" id="hoja_vida" role="tabpanel" aria-labelledby="documentos-icon-tab">
        <div class="box box-info">
          
              <div class="row">
                <div class="col-md-12 form-group mb-12">
                  <div class="accordion" id="accordionExample">
                      <div class="card ul-card__border-radius">
                          <div class="card-header">
                            <h6 class="card-title mb-0"><a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-motor">Motor</a></h6>
                          </div>
                          <div class="collapse" id="accordion-item-motor" data-parent="#accordionExample">
                            <div class="card-body">
                              <div class="row">
                                  <div class="col-md-3 form-group ">
                                    <label> <strong>Nro Valvulas:</strong></label>
                                    <input type="number" class="form-control" name="valvulas">
                                  </div>
                                  <div class="col-md-3 form-group ">
                                    <label> <strong>Nro Cilindros:</strong></label>
                                    <input type="number" class="form-control" name="cilindros">
                                  </div>
                                  <div class="col-md-3 form-group ">
                                    <label> <strong>Turbo:</strong></label>
                                    <input type="text" class="form-control" name="turbo">
                                  </div>
                                  <div class="col-md-3 form-group ">
                                    <label> <strong>Orientación:</strong></label>
                                    <input type="text" class="form-control" name="turbo">
                                  </div>
                              </div>
                            </div>
                          </div>
                      </div>

                      <div class="card ul-card__border-radius">
                          <div class="card-header">
                            <h6 class="card-title mb-0"><a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-direccion_transmision">Dirección-Transmisión-Suspensión</a></h6>
                          </div>
                          <div class="collapse" id="accordion-item-direccion_transmision" data-parent="#accordionExample">
                            <div class="card-body">
                              <div class="row">
                                  <div class="col-md-3 form-group ">
                                    <label> <strong>Tipo de Dirección</strong></label>
                                    <select class="form-control">
                                      <option>Mecanica</option>
                                      <option>Hidraulica</option>
                                      <option>Electrica</option>
                                    </select>
                                  </div>
                                  <div class="col-md-3 form-group ">
                                    <label> <strong>Tipo de Transmisión</strong></label>
                                    <select class="form-control">
                                      <option>Manual</option>
                                      <option>Automatica</option>
                                      
                                    </select>
                                  </div>
                                  <div class="col-md-3 form-group ">
                                    <label> <strong>Nro de Velocidades:</strong></label>
                                    <input type="number" class="form-control" min="4" max="20" name="velocidades">
                                  </div>
                                  <div class="col-md-3 form-group ">
                                    <label> <strong>Tipo de Rodamientos:</strong></label>
                                    <select class="form-control">
                                      <option>Bola</option>
                                      <option>Rodillos</option>
                                      
                                    </select>
                                  </div>

                                  <div class="col-md-3 form-group ">
                                    <label> <strong>Suspensión Delantera:</strong></label>
                                    <select class="form-control">
                                      <option>Si</option>
                                      <option>No</option>
                                      
                                    </select>
                                  </div>
                                  <div class="col-md-3 form-group ">
                                    <label> <strong>Suspensión Trasera:</strong></label>
                                      <select class="form-control">
                                      <option>Si</option>
                                      <option>No</option>
                                      
                                    </select>
                                  </div>
                                  <div class="col-md-3 form-group ">
                                    <label> <strong>Nro LLantas:</strong></label>
                                    <input type="number" class="form-control"  name="llantas">
                                  </div>
                                  <div class="col-md-3 form-group ">
                                    <label> <strong>Dimensión de Rines:</strong></label>
                                    <input type="text" class="form-control"  name="suspension_delantera">
                                  </div>
                                  <div class="col-md-6 form-group ">
                                    <label> <strong>Material de Rines:</strong></label>
                                    <input type="text" class="form-control"  name="suspension_delantera">
                                  </div>
                              </div>
                            </div>
                          </div>
                      </div>
                      <div class="card ul-card__border-radius">
                          <div class="card-header">
                            <h6 class="card-title mb-0"><a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-frenos">Frenos</a></h6>
                          </div>
                          <div class="collapse" id="accordion-item-frenos" data-parent="#accordionExample">
                            <div class="card-body">
                              <div class="row">
                                  <div class="col-md-6 form-group ">
                                     <label> <strong>Tipo de Frenos Delanteros:</strong></label>
                                      <select class="form-control">
                                      <option>Disco</option>
                                      <option>Tambor</option>
                                      <option>Abs</option>
                                      <option>Manos</option>
                                      
                                    </select>
                                  </div>
                                  <div class="col-md-6 form-group ">
                                     <label> <strong>Tipo de Frenos Traseros:</strong></label>
                                      <select class="form-control">
                                      <option>Disco</option>
                                      <option>Tambor</option>
                                      <option>Abs</option>
                                      <option>Manos</option>
                                    </select>
                                  </div>
                                 
                              </div>
                            </div>
                          </div>
                      </div>

                      <div class="card ul-card__border-radius">
                          <div class="card-header">
                            <h6 class="card-title mb-0"><a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-carroceria">Carroceria</a></h6>
                          </div>
                          <div class="collapse" id="accordion-item-carroceria" data-parent="#accordionExample">
                            <div class="card-body">
                              <div class="row">
                                  <div class="col-md-4 form-group ">
                                     <label> <strong>Nro de Serie:</strong></label>
                                     <input type="number" class="form-control"  name="carroceria_serie">
                                  </div>
                                   <div class="col-md-4 form-group ">
                                     <label> <strong>Nro de Ventanas:</strong></label>
                                     <input type="number" class="form-control"  name="carroceria_ventanas">
                                  </div>
                                  <div class="col-md-4 form-group ">
                                     <label> <strong>Nro de Pasajeros:</strong></label>
                                     <input type="number" class="form-control"  name="carroceria_serie">
                                  </div>
                              </div>
                            </div>
                          </div>
                      </div>
                        <div class="card ul-card__border-radius">
                          <div class="card-header">
                            <h6 class="card-title mb-0"><a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-caja_herramientas">Caja de Herramientas</a></h6>
                          </div>
                          <div class="collapse" id="accordion-item-caja_herramientas" data-parent="#accordionExample">
                            <div class="card-body">
                              <div class="row">
                                   <div class="col-md-3 form-group ">
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>LLaves</span>
                                      <span class="checkmark"></span>
                                    </label>
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Destornilladores</span>
                                      <span class="checkmark"></span>
                                    </label>
                                     <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Gato</span>
                                      <span class="checkmark"></span>
                                    </label>
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Alicate</span>
                                      <span class="checkmark"></span>
                                    </label>
                                  </div>
                                    <div class="col-md-3 form-group ">
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Extintor</span>
                                      <span class="checkmark"></span>
                                    </label>
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Rachas</span>
                                      <span class="checkmark"></span>
                                    </label>
                                     <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Linterna</span>
                                      <span class="checkmark"></span>
                                    </label>
                                    
                                    </label>
                                  </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="card ul-card__border-radius">
                          <div class="card-header">
                            <h6 class="card-title mb-0"><a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-equipo_carretera">Equipo de Carretera</a></h6>
                          </div>
                          <div class="collapse" id="accordion-item-equipo_carretera" data-parent="#accordionExample">
                            <div class="card-body">
                              <div class="row">
                                   <div class="col-md-3 form-group ">
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Conos</span>
                                      <span class="checkmark"></span>
                                    </label>
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Banderas</span>
                                      <span class="checkmark"></span>
                                    </label>
                                     <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Mechones</span>
                                      <span class="checkmark"></span>
                                    </label>
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>LLanta de Emergencia</span>
                                      <span class="checkmark"></span>
                                    </label>
                                  </div>
                              </div>
                            </div>
                          </div>
                      </div>
                      <div class="card ul-card__border-radius">
                          <div class="card-header">
                            <h6 class="card-title mb-0"><a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-botiquin">Botiquin</a></h6>
                          </div>
                          <div class="collapse" id="accordion-item-botiquin" data-parent="#accordionExample">
                            <div class="card-body">
                              <div class="row">
                                  <div class="col-md-6 form-group ">
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Algodon</span>
                                      <span class="checkmark"></span>
                                    </label>
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Vendas</span>
                                      <span class="checkmark"></span>
                                    </label>
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Analgesicos</span>
                                      <span class="checkmark"></span>
                                    </label>
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Agua Oxigenada</span>
                                      <span class="checkmark"></span>
                                    </label>
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Curas</span>
                                      <span class="checkmark"></span>
                                    </label>
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Alcohol</span>
                                      <span class="checkmark"></span>
                                    </label>
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Gel Dolores Musculares</span>
                                      <span class="checkmark"></span>
                                    </label>
                                   
                                    
                                  </div>

                                    <div class="col-md-6 form-group ">
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Tijeras</span>
                                      <span class="checkmark"></span>
                                    </label>
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Gasas</span>
                                      <span class="checkmark"></span>
                                    </label>
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Frasco de Yoodo,Isodine o Similar</span>
                                      <span class="checkmark"></span>
                                    </label>
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Suero o Solución Salina Normal</span>
                                      <span class="checkmark"></span>
                                    </label>
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Dos Baja Lenguas</span>
                                      <span class="checkmark"></span>
                                    </label>
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Guantes de Latex</span>
                                      <span class="checkmark"></span>
                                    </label>
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Bebidas Energeticas</span>
                                      <span class="checkmark"></span>
                                    </label>
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" checked="checked"><span>Esparadrapo Y/O Micropore</span>
                                      <span class="checkmark"></span>
                                    </label>
                                  </div>

                              </div>
                            </div>
                          </div>
                      </div>
                  </div>
                  <!--Fin Acordion<!-->
                 </div>
              </div>      


               <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
    
              </div>
        </div>

        </div> 

        <div class="tab-pane" id="documentos" role="tabpanel" aria-labelledby="documentos-icon-tab">
           
            <div class="box box-info">
                <form action="{{route('vehiculos.documentos.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{$vehiculo->id}}">

              <div class="card ul-card__border-radius">
                <div class="card-header">
                  <h6 class="card-title mb-0"><a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-fotos_vehiculo">Fotos Vehiculo</a></h6>
                </div>
                <div class="collapse" id="accordion-item-fotos_vehiculo" data-parent="#accordionExample">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6 form-group ">
                       <label> <strong>Foto Frontal:</strong></label>
                       <input type="file" class="form-control" name="documentos[15][cara][1]"  >
                     </div>
                     <div class="col-md-6 form-group ">
                       <label> <strong>Foto Trasera:</strong></label>
                       <input type="file" class="form-control" name="documentos[15][cara][2]"  >
                     </div>
                      <div class="col-md-6 form-group ">
                       <label> <strong>Foto Lateral Derecha:</strong></label>
                       <input type="file" class="form-control"  >
                     </div>
                     <div class="col-md-6 form-group ">
                       <label> <strong>Foto Lateral Izquierda:</strong></label>
                       <input type="file" class="form-control"  >
                     </div>
                     

                   </div>
                 </div>
               </div>
             </div>

              <div class="card ul-card__border-radius">
                <div class="card-header">
                  <h6 class="card-title mb-0"><a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-licencia_transito">Licencia De Transito</a></h6>
                </div>
                <div class="collapse" id="accordion-item-licencia_transito" data-parent="#accordionExample">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6 form-group ">
                       <label> <strong>Foto Frontal:</strong></label>
                       <input type="file" class="form-control" name="documentos[8][cara][1]"  >
                     </div>
                     <div class="col-md-6 form-group ">
                       <label> <strong>Foto Reverso:</strong></label>
                       <input type="file" class="form-control" name="documentos[8][cara][2]"  >
                     </div>
                   </div>
                 </div>
               </div>
             </div>


          <div class="card ul-card__border-radius">
            <div class="card-header">
              <h6 class="card-title mb-0"><a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-licencia">Tarjeta de operación </a></h6>
            </div>
            <div class="collapse" id="accordion-item-licencia" data-parent="#accordionExample">
               
              <div class="card-body">
                <div class="row"> 
                  
                  <div class="col-md-3 form-group ">
                        <label> <strong>Fecha Expedición:</strong></label>
                        <input type="date" class="form-control" placeholder="dd/mm/yyyy"
                        name="documentos[13][fecha_inicial]" id="" 
                        >
                  </div>

                   <div class="col-md-3 form-group ">
                        <label> <strong>Fecha Vencimiento:</strong></label>
                        <input type="date" name="documentos[13][fecha_final]" class="form-control" placeholder="dd/mm/yyyy">
                  </div>

                  <div class="col-md-6 form-group ">
                        <label> <strong>Número Documento:</strong></label>
                        <input type="text" name="documentos[13][numero]" class="form-control" >
                  </div>

                  <div class="col-md-6 form-group ">
                    <label> <strong>Foto Frontal:</strong></label>
                    <input type="file" class="form-control" name="documentos[13][cara][1]">

                  </div>
                
                </div>
              </div>
            </div>
        </div>


            <div class="card ul-card__border-radius">
                <div class="card-header">
                  <h6 class="card-title mb-0"><a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-soat">SOAT</a></h6>
                </div>
                <div class="collapse" id="accordion-item-soat" data-parent="#accordionExample">
                  <div class="card-body">

                    <div class="row">
                        <div class="col-md-3 form-group ">
                          <label> <strong>Fecha Vencimiento:</strong></label>
                          <input type="date" name="documentos[9][fecha_final]" class="form-control" placeholder="dd/mm/yyyy">
                        </div>
                      <div class="col-md-6 form-group ">
                       <label> <strong>Foto Frontal:</strong></label>
                       <input type="file" class="form-control" name="documentos[9][cara][1]"  >
                     </div>
                     
                   </div>
                 </div>
               </div>
             </div>

              <div class="card ul-card__border-radius">
                <div class="card-header">
                  <h6 class="card-title mb-0"><a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-revision_tecnicomecanica">Revisión Técnico Mecanica</a></h6>
                </div>
                <div class="collapse" id="accordion-item-revision_tecnicomecanica" data-parent="#accordionExample">
                  <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 form-group ">
                          <label> <strong>Fecha Expedición:</strong></label>
                          <input type="date" class="form-control" placeholder="dd/mm/yyyy"
                        name="documentos[10][fecha_inicial]" id="documentos_fecha_inicial" 
                        >
                        </div>

                      <div class="col-md-3 form-group ">
                          <label> <strong>Fecha Vencimiento:</strong></label>
                          <input type="date" name="documentos[10][fecha_final]" class="form-control" placeholder="dd/mm/yyyy">
                      </div>
                      <div class="col-md-6 form-group ">
                       <label> <strong>Foto Frontal:</strong></label>
                       <input type="file" class="form-control" name="documentos[10][cara][1]"  >
                     </div>
                     
                   </div>
                 </div>
               </div>
             </div>

              <div class="card ul-card__border-radius">
                <div class="card-header">
                  <h6 class="card-title mb-0"><a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-poliza_rcc">Polisa RCC</a></h6>
                </div>
                <div class="collapse" id="accordion-item-poliza_rcc" data-parent="#accordionExample">
                  <div class="card-body">
                    <div class="row">

                      <div class="col-md-3 form-group ">
                          <label> <strong>Fecha Vencimiento:</strong></label>
                          <input type="date" name="documentos[11][fecha_final]" class="form-control" placeholder="dd/mm/yyyy">
                      </div>

                      <div class="col-md-6 form-group ">
                       <label> <strong>Foto Frontal:</strong></label>
                       <input type="file" class="form-control" name="documentos[11][cara][1]"  >
                     </div>
                     
                   </div>
                 </div>
               </div>
             </div>

              <div class="card ul-card__border-radius">
                <div class="card-header">
                  <h6 class="card-title mb-0"><a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-poliza_rce">Polisa RCE</a></h6>
                </div>
                <div class="collapse" id="accordion-item-poliza_rce" data-parent="#accordionExample">
                  <div class="card-body">
                    <div class="row">

                      <div class="col-md-3 form-group ">
                          <label> <strong>Fecha Vencimiento:</strong></label>
                          <input type="date" name="documentos[12][fecha_final]" class="form-control" placeholder="dd/mm/yyyy">
                      </div>


                      <div class="col-md-6 form-group ">
                       <label> <strong>Foto Frontal:</strong></label>
                       <input type="file" class="form-control" name="documentos[12][cara][1]"  >
                     </div>
                     
                   </div>
                 </div>
               </div>
             </div>

             <div class="card ul-card__border-radius">
                <div class="card-header">
                  <h6 class="card-title mb-0"><a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-revision_preventiva">Revisión Preventiva</a></h6>
                </div>
                <div class="collapse" id="accordion-item-revision_preventiva" data-parent="#accordionExample">
                  <div class="card-body">
                    <div class="row">

                       <div class="col-md-3 form-group ">
                          <label> <strong>Fecha Vencimiento:</strong></label>
                          <input type="date" name="documentos[14][fecha_final]" class="form-control" placeholder="dd/mm/yyyy">
                      </div>

                      <div class="col-md-6 form-group ">
                       <label> <strong>Foto Frontal:</strong></label>
                       <input type="file" class="form-control" name="documentos[14][cara][1]" >
                     </div>
                     
                   </div>
                 </div>
               </div>
             </div>

             <br/>
               <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                
              </div>
            </form>
          </div>

        </div>                      

    

         <div class="tab-pane" id="historial-documentos" role="tabpanel" aria-labelledby="general-icon-tab">
            <div class="box box-info">
                <div class="row">
                  <div class="col-md-12">
                    <label><strong>Historial Documentos:</strong></label>
                     <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
            <tr>
              <thead>
                <th>Tipo Documento</th>
                <th>Fecha Expedición</th>
                <th>Fecha Vencimiento</th>
                <th>Número</th>
                <th>Entidad</th>
                <th>Cara Frontal</th>
                <th>Cara Trasera</th>
              </thead>
            </tr>
            <tbody>
              @foreach($documentos as $documento)
              <tr>
                <td>{{$tipo_documentos[$documento->id_tipo_documento]}}</td>
                <td>{{$documento->fecha_inicial}}</td>
                <td>{{$documento->fecha_final}}</td>
                <td>{{$documento->numero_documento}}</td>
                <td>{{$documento->nombre_entidad}}</td>
                <td><a href="{{asset($documento->cara_frontal)}}" target="_blank">{{$documento->cara_frontal}}</a></td>
                <td><a href="{{asset($documento->cara_trasera)}}" target="_blank">{{$documento->cara_trasera}}</a></td>

              </tr>
              @endforeach
            </tbody>
          </table>
                  </div>
                   <div class="col-xs-12 col-sm-12 col-md-12 ">
                      <a href="{{ route('vehiculos') }}" class="btn btn-danger">Regresar a lista de Vehiculos</a>
                  </div>
              </div>
            </div>
        </div>



    </div>


  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->


<!-- /.card -->
</div>


</div>
@endsection
@section('bottom-js')
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.js"></script>


<script>



// just for the demos, avoids form submit
var form = $( "#user-new-form" );
$.validator.messages.required = 'Este campo es requerido';
$.validator.messages.email = 'Email invalido';

$('#user-new-form').validate({
  rules: {
    nombres: { required:true },
    apellidos: { required:true },
    email:{ required:true },
    documento:{ required:true },
    departamento_id:{ required:true },
    ciudad_id: { required:true },
    password:{ required:false },

  },messages: {

  },

})

$("#submit").validate({ 
 onsubmit: false,

 submitHandler: function(form) {  
   if ($(form).valid())
   {
     form.submit(); 
   }
   return false; // prevent normal form posting
 }
});



/*
$( "#submit" ).click(function(e) {
  e.preventDefault();
  if($( "#user-new-form" ).valid()){
    alert('valido');
    $( "#user-new-form" ).submit();
  }else{
    alert('ERRORES')
  }
});
*/
</script>
@endsection