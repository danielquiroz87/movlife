@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('fuec')}}">Fuec</a></li>
          <li>Nuevo Fuec</li>
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
                <h3 class="card-title mb3">Nuevo Fuec</h3>
  
 <div class="box box-info">
    <form action="{{route('fuec.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="">
      <input type="hidden" name="is_new" value="true">

        <div class="row">
           
            <div class="col-md-6 form-group mb-3">
              <label><strong>Placa (Vehículo):</strong></label>
              <input type="text" name="placa" id="placa" value="" class="form-control" maxlength="6" />
            </div>


            <div class="col-md-6 form-group mb-3">
              <label><strong>Conductor:</strong></label>
                  <select name="id_conductor" id="conductor_servicio" class="form-control">
                        <?php echo Helper::selectConductores() ?>

                  </select>
            </div>

            <div class="col-md-6 form-group mb-3">
                  <label><strong>Consecutivo</strong></label>
                  <input type="text" name="consecutivo" class="form-control" id="consecutivo" placeholder="Consecutivo" required value="">
            </div>


           <div class="col-md-6 form-group mb-3">
              <label><strong>Tipo:</strong></label>
                    <select class="form-control" name="tipo">
                      <option value="1">Normal</option>
                      <option value="2">Ocasional</option>
                    </select>
                   
            </div>
        
          
           <div class="col-md-6 form-group mb-3">
              <label><strong>Cliente:</strong></label>
                    <select class="form-control clientes" name="id_cliente">
                      <?php echo Helper::selectClientes() ?>
                    </select>
                   
            </div>

            <div class="col-md-6 form-group mb-3">
                  <label><strong>Contrato</strong></label>
                  <input type="text" name="contrato" class="form-control" id="contrato" placeholder="Consecutivo" required value="">
            </div>

          
          <div class="col-md-6 form-group mb-3">
              <label><strong>Fecha Inicial:</strong></label>
                   <input type="date" name="fecha_inicial" value="" class="form-control" placeholder=""  required>
          </div>

          <div class="col-md-6 form-group mb-3">
              <label><strong>Fecha Final:</strong></label>
                   <input type="date" name="fecha_final" value="" class="form-control" placeholder=""  required>
          </div>

          <div class="col-md-12 form-group mb-3">
              <label><strong>Objeto Contrato:</strong></label>
                    <select class="form-control" name="objeto_contrato_id">
                        <?php echo Helper::selectObjetosContrato() ?>
                    </select>
          </div>
          <div class="col-md-12 form-group mb-3">
              <label><strong>Rutas:</strong></label>
                    <select class="form-control" name="ruta_id">
                        <?php echo Helper::selectRutas() ?>
                    </select>
          </div>

          
           
            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('fuec') }}" class="btn btn-danger">Cancelar</a>
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


  $("#placa").blur(function(){
    var placa=$(this).val();
    $.get('/conductores/placa/'+placa,function(html){
      $("#conductor_servicio").html(html);
    })
  })


// just for the demos, avoids form submit
var form = $( "#user-new-form" );
$.validator.messages.required = 'Este campo es requerido';
$.validator.messages.email = 'Email invalido';

$('#user-new-form').validate({
  rules: {
        nombre: { required:true },
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