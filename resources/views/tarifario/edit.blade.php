@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('tarifario')}}">Tarifario</a></li>
          <li>Nuevo Tarifario</li>
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
                <h3 class="card-title mb3">Tarifario</h3>
  
 <div class="box box-info">
    <form action="{{route('tarifario.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="{{$tarifario->id}}">
      <input type="hidden" name="is_new" value="false">

        <div class="row">
            

             <div class="col-md-6 form-group mb-3">
              <label><strong>Origen:</strong></label>
                   <input type="text" name="origen" id="origin-input" value="{{$tarifario->origen}}" class="form-control" placeholder="" required>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Destino:</strong></label>
                   <input type="text" name="destino" id="destination-input" value="{{$tarifario->destino}}" class="form-control" placeholder=""  required>
            </div>

             <div class="col-md-12 form-group mb-3">
              <label><strong>Tipo Vehiculo:</strong></label>
                  <select name="tipo_vehiculo" class="form-control select-busqueda">
                      <?php echo Helper::selectClaseVehiculos($tarifario->tipo_vehiculo) ?>

                </select>
            </div>
      

            <div class="col-md-6 form-group mb-3">
              <label><strong>Tiempo:</strong></label>
                   <input type="number" name="tiempo" id="tiempo" value="{{$tarifario->tiempo}}" class="form-control" placeholder="" >
            </div>

             <div class="col-md-6 form-group mb-3">
              <label><strong>Kilometros:</strong></label>
                   <input type="number" name="kilometros" id="kilometros" value="{{$tarifario->kilometros}}" class="form-control" placeholder="" >
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Valor Conductor:</strong></label>
                   <input type="number" name="valor_conductor" id="valor_conductor" value="{{$tarifario->valor_conductor}}"  class="form-control" placeholder="0" maxlength="11" required>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Valor Cliente:</strong></label>
                   <input type="number" name="valor_cliente" value="{{$tarifario->valor_cliente}}" id="valor_cliente" class="form-control" placeholder="0" maxlength="11" required>
            </div>

             <div class="col-md-6 form-group mb-3">
              <label><strong>Proveedor:</strong></label>
                   <input type="text" name="proveedor" id="proveedor-input" value="{{$tarifario->proveedor}}" class="form-control" placeholder=""  required>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Cliente:</strong></label>
                  <select name="id_cliente" class="form-control">
                      
                        <?php echo Helper::selectClientes($tarifario->id_cliente) ?>
                    
                    </select>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Jornada:</strong></label>
                  <select name="jornada" id="jornada" class="form-control select-busqueda">
                  <option value="" selected="selected">Seleccione</option>
                 
                  <option value="1" selected="selected">1 Hora</option>
                  <option value="2">2 Horas</option>
                  <option value="3">3 Horas</option>
                  <option value="4">4 Horas</option>
                  <option value="5">5 Horas</option>
                  <option value="6">Media Jornada</option>
                  <option value="7">Extendida</option>
                  <option value="8">Completa</option>
                </select>
            </div>

              <div class="col-md-6 form-group mb-3">
              <label><strong>Trayecto:</strong></label>
                <select name="trayecto" id="trayecto" class="form-control select-busqueda">
                  <option value="" selected="selected">Seleccione</option>
                  <option value="1" selected="selected">Sencillo</option>
                  <option value="2">Redondo</option>
                  <option value="3">Ida</option>
                  <option value="3">Ida y Regreso</option>


                </select>
            </div>
             <div class="col-md-12 form-group mb-3">
              <label><strong>Observaciones:</strong></label><br/>
                   <textarea class="form-control" name="observaciones" rows="3">{{$tarifario->observaciones}}</textarea>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('pasajeros') }}" class="btn btn-danger">Cancelar</a>
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



// just for the demos, avoids form submit
var form = $( "#user-new-form" );
$.validator.messages.required = 'Este campo es requerido';
$.validator.messages.email = 'Email invalido';

$('#user-new-form').validate({
  rules: {
        nombres: { required:true },
        apellidos: { required:true },
        departamento_id:{ required:true },
        ciudad_id: { required:true },
        
        
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