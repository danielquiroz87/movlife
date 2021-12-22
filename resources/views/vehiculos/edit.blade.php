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

  <div class="col-md-8 mb-4">
    <div class="card text-left">
      <div class="card-body">
        <h3 class="card-title mb3">Editar Vehículo</h3>

        <ul class="nav nav-tabs" id="myIconTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active show" id="general-icon-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true"><i class="nav-icon i-Home1 mr-1"></i>General</a>
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
              <form action="{{route('conductores.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
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
                        <input type="number" name="modelo" class="form-control" id="modelo" min="1900" max="<?php echo date('Y') ?>" placeholder="" value="{{$vehiculo->modelo}}" required>
                  </div>
                 

               <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('vehiculos') }}" class="btn btn-danger">Cancelar</a>
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
                <a href="{{ route('vehiculos') }}" class="btn btn-danger">Cancelar</a>
              </div>
        </div>

        </div> 

        <div class="tab-pane" id="documentos" role="tabpanel" aria-labelledby="documentos-icon-tab">
            <div class="box box-info">
               
              <div class="card ul-card__border-radius">
                <div class="card-header">
                  <h6 class="card-title mb-0"><a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-fotos_vehiculo">Fotos Vehiculo</a></h6>
                </div>
                <div class="collapse" id="accordion-item-fotos_vehiculo" data-parent="#accordionExample">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6 form-group ">
                       <label> <strong>Foto Frontal:</strong></label>
                       <input type="file" class="form-control"  >
                     </div>
                     <div class="col-md-6 form-group ">
                       <label> <strong>Foto Lateral Derecha:</strong></label>
                       <input type="file" class="form-control"  >
                     </div>
                     <div class="col-md-6 form-group ">
                       <label> <strong>Foto Lateral Izquierda:</strong></label>
                       <input type="file" class="form-control"  >
                     </div>
                     <div class="col-md-6 form-group ">
                       <label> <strong>Foto Trasera:</strong></label>
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
                       <input type="file" class="form-control"  >
                     </div>
                     <div class="col-md-6 form-group ">
                       <label> <strong>Foto Reverso:</strong></label>
                       <input type="file" class="form-control"  >
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
                      <div class="col-md-6 form-group ">
                       <label> <strong>Foto Frontal:</strong></label>
                       <input type="file" class="form-control"  >
                     </div>
                     
                   </div>
                 </div>
               </div>
             </div>

              <div class="card ul-card__border-radius">
                <div class="card-header">
                  <h6 class="card-title mb-0"><a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-revision_tecnicomecanica">Revisión Técnico Mecanica</a></h6>
                </div>
                <div class="collapse" id="accordion-item-tarjeta_operacion" data-parent="#accordionExample">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6 form-group ">
                       <label> <strong>Foto Frontal:</strong></label>
                       <input type="file" class="form-control"  >
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
                      <div class="col-md-6 form-group ">
                       <label> <strong>Foto Frontal:</strong></label>
                       <input type="file" class="form-control"  >
                     </div>
                     
                   </div>
                 </div>
               </div>
             </div>

              <div class="card ul-card__border-radius">
                <div class="card-header">
                  <h6 class="card-title mb-0"><a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-poliza_rce">Polisa RCE</a></h6>
                </div>
                <div class="collapse" id="accordion-item-poliza_rcc" data-parent="#accordionExample">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6 form-group ">
                       <label> <strong>Foto Frontal:</strong></label>
                       <input type="file" class="form-control"  >
                     </div>
                     
                   </div>
                 </div>
               </div>
             </div>

             <div class="card ul-card__border-radius">
                <div class="card-header">
                  <h6 class="card-title mb-0"><a class="collapsed text-default" data-toggle="collapse" href="#accordion-item-poliza_rce">Revisión Preventiva</a></h6>
                </div>
                <div class="collapse" id="accordion-item-poliza_rcc" data-parent="#accordionExample">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6 form-group ">
                       <label> <strong>Foto Frontal:</strong></label>
                       <input type="file" class="form-control"  >
                     </div>
                     
                   </div>
                 </div>
               </div>
             </div>


               <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('vehiculos') }}" class="btn btn-danger">Cancelar</a>
              </div>
            </div>

        </div>                      

    

         <div class="tab-pane" id="historial-documentos" role="tabpanel" aria-labelledby="general-icon-tab">
            <div class="box box-info">
               
                <div class="row">
                  <div class="col-md-6 form-group mb-3">
                    <label><strong>Historial Documentos:</strong></label>
                   
                  </div>
               <div class="col-xs-12 col-sm-12 col-md-12 ">
                <a href="{{ route('vehiculos') }}" class="btn btn-danger">Regresar a lista de Vehiculos</a>
              </div>
            </div>

          </form>

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