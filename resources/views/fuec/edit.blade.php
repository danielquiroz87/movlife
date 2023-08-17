@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('fuec')}}">Fuecs</a></li>
          <li>Editar Fuec</li>
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
                <h3 class="card-title mb3">Editar Fuec</h3>
  
 <div class="box box-info">
    <form action="{{route('fuec.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="{{$fuec->id}}">
      <input type="hidden" name="is_new" value="false">

        <div class="row">
           
            <div class="col-md-6 form-group mb-3">
              <label><strong>Placa (Vehículo):</strong></label>
              <input type="text" name="placa" id="placa" value="{{$fuec->placa}}" class="form-control" maxlength="6" />
            </div>


            <div class="col-md-6 form-group mb-3">
              <label><strong>Conductor:</strong></label>
                  <select name="id_conductor" id="conductor_servicio" class="form-control">
                        <?php echo Helper::selectConductores($fuec->id_conductor) ?>

                  </select>
            </div>

            <div class="col-md-6 form-group mb-3">
                  <label><strong>Consecutivo</strong></label>
                  <input type="text" name="consecutivo" class="form-control" id="consecutivo" placeholder="Consecutivo" required value="{{$fuec->consecutivo}}">
            </div>


           <div class="col-md-6 form-group mb-3">
              <label><strong>Tipo:</strong></label>
                    <select class="form-control" name="tipo">
                      <option value="1" @if($fuec->tipo==1)selected=selected @endif>Normal</option>
                      <option value="2" @if($fuec->tipo==2)selected=selected @endif >Ocasional</option>
                    </select>
                   
            </div>
        
          
           <div class="col-md-6 form-group mb-3">
              <label><strong>Cliente:</strong></label>
                    <select class="form-control clientes" name="id_cliente">
                      <?php echo Helper::selectClientes($fuec->id_cliente) ?>
                    </select>
                   
            </div>

            <div class="col-md-6 form-group mb-3">
                  <label><strong>Contrato</strong></label>
                  <input type="text" name="contrato" class="form-control" id="contrato" placeholder="Consecutivo" required value="{{$fuec->contrato}}">
            </div>
              <div class="col-md-12 form-group mb-3">
                  <label><strong>Responsable Contrato</strong></label>
                  <input type="text" name="responsable_contrato" class="form-control" id="responsable_contrato" placeholder="Responsable Contrato" required value="{{$fuec->responsable_contrato}}">
            </div>
          
          <div class="col-md-6 form-group mb-3">
              <label><strong>Fecha Inicial:</strong></label>
                   <input type="date" name="fecha_inicial" value="{{$fuec->fecha_inicial}}" class="form-control" placeholder=""  required>
          </div>

          <div class="col-md-6 form-group mb-3">
              <label><strong>Fecha Final:</strong></label>
                   <input type="date" name="fecha_final" value="{{$fuec->fecha_final}}" class="form-control" placeholder=""  required>
          </div>

          <div class="col-md-12 form-group mb-3">
              <label><strong>Objeto Contrato:</strong></label>
                    <select class="form-control" name="objeto_contrato_id">
                        <?php echo Helper::selectObjetosContrato($fuec->id) ?>
                    </select>
          </div>
          <div class="col-md-12 form-group mb-3">
              <label><strong>Rutas:</strong></label>
                    <select class="form-control" name="ruta_id">
                        <?php echo Helper::selectRutas($fuec->ruta_id) ?>
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