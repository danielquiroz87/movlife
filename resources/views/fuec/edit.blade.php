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

<div class="col-md-8">

   @if ($message = Session::get('flash_message'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button> 
            <strong>{{ $message }}</strong>
    </div>
  @endif
  @if ($message = Session::get('flash_bad_message'))
    <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button> 
            <strong>{!! nl2br($message)!!}</strong>
    </div>
  @endif
</div>

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
      <input type="hidden" name="duplicado" id="duplicado" value="{{$duplicado}}">

        <div class="row">
           
            <div class="col-md-6 form-group mb-3">
              <label><strong>Placa (Vehículo):</strong></label>
              <input type="text" name="placa" id="placa" value="{{$fuec->placa}}" class="form-control" maxlength="6" />
            </div>


            <div class="col-md-6 form-group mb-3">
              <label><strong>Conductor:</strong></label>
                  <select name="id_conductor" id="conductor_servicio" class="form-control conductor_servicio">
                        <?php echo Helper::selectConductores($fuec->id_conductor) ?>

                  </select>
            </div>

             <div class="col-md-6 form-group mb-3">
              <label><strong>Conductor 2:</strong></label>
                  <select name="id_conductor_2" id="conductor_servicio_2" class="form-control conductor_servicio">
                        <?php echo Helper::selectConductores($fuec->id_conductor_2) ?>

                  </select>
            </div>

             <div class="col-md-6 form-group mb-3">
              <label><strong>Conductor 3:</strong></label>
                  <select name="id_conductor_3" id="conductor_servicio_3" class="form-control conductor_servicio">
                        <?php echo Helper::selectConductores($fuec->id_conductor_3) ?>
                  </select>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Conductor 4:</strong></label>
                  <select name="id_conductor_4" id="conductor_servicio_4" class="form-control conductor_servicio">
                        <?php echo Helper::selectConductores($fuec->id_conductor_4) ?>
                  </select>
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
                    <select class="form-control clientes" name="id_cliente" id="id_cliente">
                      <?php echo Helper::selectClientes($fuec->id_cliente) ?>
                    </select>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Responsable Contrato Cliente:</strong></label>
                    <select class="form-control clientes" name="id_contrato_cliente" id="id_contrato_cliente"    >
                    @foreach($contratos as $row)  
                      @if($row->id==$contrato->id)
                      <option value="{{$row->id}}" selected="selected" >{{$row->responsable_nombres}}</option>
                      @else
                      <option  value="{{$row->id}}">{{$row->responsable_nombres}}</option>
                      @endif
                    @endforeach
                    </select>
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
                    <select class="form-control" name="objeto_contrato_id" id="objeto_contrato_id" >
                        <?php echo Helper::selectObjetosContrato($contrato->objeto_contrato_id) ?>
                    </select>
          </div>
          <div class="col-md-12 form-group mb-3">
              <label><strong>Rutas:</strong></label>
                    <select class="form-control" name="ruta_id" id="ruta_id">
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
  var id_conductor="";
  var id_conductor_2="";
  var id_conductor_3="";
  var id_conductor_4="";

  $(document).ready(function(){

     id_conductor=$('#conductor_servicio').val();
     id_conductor_2=$('#conductor_servicio_2').val();
     id_conductor_3=$('#conductor_servicio_3').val();
     id_conductor_4=$('#conductor_servicio_4').val();

     getConductores();

  });


 $('#ruta_id').select2({
   theme: 'bootstrap-5'
 });

 function getConductores(duplicado){

  var placa=$('#placa').val();

  $.get('/conductores/placa/'+placa,function(html){

     $('.conductor_servicio').html(html);
     $('#conductor_servicio').val(id_conductor);
     $('#conductor_servicio_2').val(id_conductor_2);
     $('#conductor_servicio_3').val(id_conductor_3);
     $('#conductor_servicio_4').val(id_conductor_4);

       
    })
 }

$('#id_cliente').change(function(){
   var id=$(this).val();
   var strurl='/fuec/contrato/'+id;
   $.get(strurl,{},function(response){
     $('#objeto_contrato_id').val('');
     if(response.data.objeto_contrato_id){
       $('#objeto_contrato_id').val(response.data.objeto_contrato_id);
     }else{
      
     }
     console.log(response.data.objeto_contrato_id);
   })

 })
 

$("#placa").blur(function(){
    getConductores();
})


// just for the demos, avoids form submit
var form = $( "#user-new-form" );
$.validator.messages.required = 'Este campo es requerido';
$.validator.messages.email = 'Email invalido';

$('#user-new-form').validate({
  rules: {
        
        id_cliente: { required:true },
        id_conductor:{ required:true },
        placa: { required:true }
        
        
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