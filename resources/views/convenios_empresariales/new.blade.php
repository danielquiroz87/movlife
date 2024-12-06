@extends('layouts.master')

@section('main-content')


  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('convenios')}}">Convenios Empresariales</a></li>
          <li>Nuevo Convenio</li>
      </ul>
  </div>
  <div class="separator-breadcrumb border-top"></div>

<div class="row">

  
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

<div class="col-md-10 mb-2">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Nuevo Convenio</h3>
  
 <div class="box box-info">
    <form action="{{route('convenios.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="">
      <input type="hidden" name="is_new" value="true">

        <div class="row">

          
        <div class="col-md-6 form-group mb-3">
              <label><strong>Fecha Inicial:</strong></label>
                   <input type="date" name="fecha_inicial" value="" class="form-control" placeholder=""  required>
          </div>

          <div class="col-md-6 form-group mb-3">
              <label><strong>Fecha Final:</strong></label>
                   <input type="date" name="fecha_final" value="" class="form-control" placeholder=""  required>
          </div>

          <div class="col-md-6 form-group mb-3">
              <label><strong>Empresa:</strong></label>
                  <select name="id_empresa" id="id_empresa" class="form-control id_empresa">
                        <?php echo Helper::selectEmpresas() ?>
                  </select>
            </div>
           
            <div class="col-md-6 form-group mb-3">
              <label><strong>Placa (Vehículo):</strong></label>
              <input type="text" name="placa" id="placa" value="" class="form-control" maxlength="6" />
            </div>


            <div class="col-md-6 form-group mb-3">
              <label><strong>Conductor:</strong></label>
                  <select name="id_conductor" id="conductor_servicio" class="form-control conductor_servicio">
                        <?php echo Helper::selectConductores() ?>

                  </select>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Nro (Resolución):</strong></label>
              <input type="text" name="numero_resolucion" id="numero_resolucion" value="" class="form-control" maxlength="20" />
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Convenio Firmado:</strong></label>
              <input type="file" name="convenio_firmado" id="file" value="" class="form-control" maxlength="20" />
            </div>
          
           
            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('convenios') }}" class="btn btn-danger">Cancelar</a>
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

 $('#ruta_id').select2({
   theme: 'bootstrap-5'
 });

 $('#list-rutas').change(function(){
   $('#ruta_id').val($(this).val());
 })

 $('#id_cliente').change(function(){
   var id=$(this).val();
   var strurl='/fuec/contrato/'+id;
   $.get(strurl,{},function(response){
    contratos=JSON.parse(JSON.stringify(response.data));
    var option = ``;
    $('#id_contrato_cliente').empty();

    for (var i=0;i<contratos.length;i++){
      option += `<option value=${contratos[i].id}>${contratos[i].responsable_nombres}</option>`;
    }
    $('#id_contrato_cliente').append(option);
    if(contratos.length>0){
      objeto_id=contratos[0].objeto_contrato_id;
      $('#objeto_contrato_id').val(objeto_id);
    }
     //console.log(response.data.objeto_contrato_id);
   })

 })
 
  $("#placa").blur(function(){
    var placa=$(this).val();
    $.get('/conductores/placa/'+placa,function(html){
      $(".conductor_servicio").html(html);
    })
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