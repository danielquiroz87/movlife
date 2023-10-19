@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('vehiculos')}}">Vehiculos</a></li>
          <li>Nuevo Vehiculo</li>
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
                <h3 class="card-title mb3">Nuevo Vehiculo</h3>
  
 <div class="box box-info">
    <form action="{{route('vehiculos.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="0">
      <input type="hidden" name="is_new" value="true">

        <div class="row">

           <div class="col-md-6 form-group mb-3">
              <label><strong>Placa:</strong></label>
                   <input type="text" name="placa" value="" class="form-control" placeholder="XXX123" maxlength="6" required>
            </div>

            <div class="col-md-6 form-group mb-3">
                  <label><strong>Codigo Interno</strong></label>
                  <input type="text" name="codigo_interno" class="form-control" id="codigo_interno" placeholder="000" required>
            </div>
            <div class="col-md-6 form-group mb-3">
                  <label><strong>Modelo</strong></label>
                  <input type="number" name="modelo" class="form-control" id="modelo" min="1900" max="<?php echo (date('Y')+1) ?>" placeholder="" required>
            </div>

              <div class="col-md-6 form-group mb-3">
              <label><strong>Clase:</strong></label>
              <select name="id_vehiculo_clase" class="form-control select-busqueda">
                  <option value="" selected="selected">Seleccione</option>
                  <option value="1">Automovil</option>
                  <option value="2">Bus</option>
                  <option value="3">Buseta</option>
                  <option value="4">Camioneta</option>
                  <option value="5">Campero</option>
                  <option value="6">Chery</option>
                  <option value="7">Microbus</option>
                  <option value="8">Moto</option>
                  <option value="9">Van</option>
                  <option value="10">MiniVan</option>
                  <option value="11">Camioneta Doble Cabina</option>
                  <option value="12">Sedan</option>
                  <option value="13">Buseton</option>



                </select>
              </div>
           
            <div class="col-md-6 form-group mb-3">
              <label><strong>Marca:</strong></label>
              <select name="id_vehiculo_marca" class="form-control select-busqueda" id="id_vehiculo_marca" required="true">
                  <option value="" selected="selected">Seleccione</option>
                  <option value="1">AGRALE</option>
                  <option value="2">ASIA MOTOR</option>
                  <option value="3">BAIC</option>
                  <option value="4">CHANA</option>
                  <option value="5">CHANGAN</option>
                  <option value="6">CHERRY</option>
                  <option value="7">CHEVROLET</option>
                  <option value="8">DAEWOO</option>
                  <option value="9">DAIHATSU</option>
                  <option value="10">DFM</option>
                  <option value="11">DFSK</option>
                  <option value="12">DODGE</option>
                  <option value="13">DONG FENG</option>
                  <option value="14">FORD</option>
                  <option value="15">FOTON</option>
                  <option value="16">GOLDEN DRAGON</option>
                  <option value="17">GREAT WALL</option>
                  <option value="18">HAFEI</option>
                  <option value="19">HINO</option>
                  <option value="20">HYUNDAI</option>
                  <option value="21">INTERNATIONAL</option>
                  <option value="22">IVECO</option>
                  <option value="23">JAC</option>
                  <option value="24">JIMBEI</option>
                  <option value="25">JOYLONG</option>
                  <option value="26">KIA</option>
                  <option value="27">MAZDA</option>
                  <option value="28">MERCEDES BENZ</option>
                  <option value="29">MITSUBISHI</option>
                  <option value="30">NISSAN</option>
                  <option value="31">NON PLUS ULTRA</option>
                  <option value="32">RENAULT</option>
                  <option value="33">SCANIA</option>
                  <option value="34">SUZUKI</option>
                  <option value="35">TOYOTA</option>
                  <option value="36">VOLKSWAGEN</option>
                  <option value="37">VOLVO</option>
                </select>
              </div>
              <div class="col-md-6 form-group mb-3">
                  <label><strong>Linea</strong></label>
                  <input type="text" name="linea" class="form-control" id="linea"  placeholder="Logan" required>
             </div>
            
              <div class="col-md-6 form-group mb-3">
              <label><strong>Clasificación Vehículo:</strong></label>
              <select name="id_vehiculo_clase" class="form-control select-busqueda">
                  <option value="" selected="selected">Seleccione</option>
                  <option value="1">Publico</option>
                  <option value="2">Especial</option>
                  <option value="3">Particular</option>
                  <option value="4">Taxi</option>
                  <option value="5">Otro</option>
                </select>
              </div>
            <div class="col-md-6 form-group mb-3">
              <label><strong>Tipo Combustible:</strong></label>
              <select name="id_vehiculo_tipo_combustible" class="form-control select-busqueda">
                  <option value="" selected="selected">Seleccione</option>
                  <option value="1">Acpm</option>
                  <option value="2">Diesel</option>
                  <option value="3">Gas-Gasolina</option>
                  <option value="4">Gasolina</option>
                  <option value="5">Gnb</option>
               
                </select>
              </div>

            <div class="col-md-6 form-group mb-3">
                  <label><strong>Pasajeros</strong></label>
                  <input type="number" name="pasajeros" class="form-control" id="pasajeros" min="0" max="100" placeholder="" required>
            </div>
            <div class="col-md-6 form-group mb-3">
                  <label><strong>Color</strong></label>
                  <input type="text" name="color" class="form-control" id="color"  placeholder="Color" required>
            </div>
            <div class="col-md-6 form-group mb-3">
                  <label><strong>Nro Chasis</strong></label>
                  <input type="text" name="numero_chasis" class="form-control" id="chasis"  placeholder="# Chasis" required>
            </div>
            <div class="col-md-6 form-group mb-3">
                  <label><strong>Nro Motor</strong></label>
                  <input type="text" name="numero_motor" class="form-control" id="motor"  placeholder="# Motor" required>
            </div>
            <div class="col-md-6 form-group mb-3">
                  <label><strong>Cilindraje</strong></label>
                  <input type="text" name="cilindraje" class="form-control" id="cilindraje"  placeholder="Cilindraje" required>
            </div>
           <div class="col-md-6 form-group mb-3">
              <label><strong>Departamento Donde Trabaja El Vehículo:</strong></label>
                    <select name="departamento_id" id="departamento"  class="form-control departamentos">
                        <?php echo Helper::selectDepartamentos() ?>
                    </select>
            </div>
           
           <div class="col-md-6 form-group mb-3">
              <label><strong>Ciudad Donde Trabaja El Vehículo:</strong></label>
                  <select name="ciudad_id" id="ciudad_id"  class="form-control municipios">
                        <?php echo Helper::selectMunicipios() ?>
                  </select>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Propietario:</strong></label>
                  <select name="propietario_id" class="form-control">
                    <?php echo Helper::selectPropietarios() ?>
                  </select>
            </div>
            
            <div class="col-md-6 form-group mb-3">
              <label class="switch pr-5 switch-success mr-3"><span>Vinculado</span>
                  <input type="checkbox" name="Vinculado" id="vinculado" ><span class="slider"></span>
              </label>
            </div>
             <div class="col-md-6 form-group mb-3">
               <label class="switch pr-5 switch-success mr-3"><span>Convenio Firmado</span>
                  <input type="checkbox" name="convenio" id="convenio" ><span class="slider"></span>
              </label>
            </div>

            <div class="col-md-6 form-group mb-3" id="div_empresa_afiliadora">
                      <label><strong>Empresa Afiliadora</strong></label>
                      <input type="text"  name="empresa_afiliadora" class="form-control" id="empresa_afiliadora"  placeholder="" value="">
            </div>

             <div class="col-md-6 form-group mb-3" id="div_fecha_convenio">
                      <label><strong>Fecha Convenio</strong></label>
                      <input type="date"  name="fecha_convenio" class="form-control" id="fecha_convenio"  placeholder="" value="">
            </div>
                 

            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('conductores') }}" class="btn btn-danger">Cancelar</a>
            </div>
        </div>

    </form>

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

$('#vinculado').change(function(e){

  
    if ($(this).is(':checked')) {

       $('#empresa_afiliadora').val('Movlife SAS');
       $('#div_empresa_afiliadora').show();
       $("#convenio").prop('checked', false);
       $('#div_fecha_convenio').hide();


    }else{

      $("#convenio").prop('checked', false);
      $('#div_empresa_afiliadora').hide();
      $('#div_fecha_convenio').hide();
      $('#empresa_afiliadora').val('');
    }

})

$('#convenio').change(function(e){
    
   
    if ($(this).is(':checked')) {
      $('#div_empresa_afiliadora').show();
      $('#div_fecha_convenio').show();

    }else{

      $('#div_empresa_afiliadora').hide();
      $('#div_fecha_convenio').hide();
      $("#vinculado").prop('checked', false);
    }

})


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
        password:{ required:true },
        propietario_id: {required:true}
        
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