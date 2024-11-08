@extends('layouts.master')



@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('empresas.convenios')}}">Empresa Convenio</a></li>
          <li>Nueva Empresa Convenio</li>
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

<div class="col-md-8 mb-4">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Editar Empresa Convenio</h3>
  
 <div class="box box-info">
    <form action="{{route('empresas.convenios.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="{{$empresa->id}}">
      <input type="hidden" name="is_new" value="false">

        <div class="row">
            <div class="col-md-6 form-group mb-3">
              <label><strong>Nit:</strong></label>
                   <input type="text" name="nit"  class="form-control" placeholder="000000" maxlength="200" required value="{{$empresa->nit}}">
            </div>

             <div class="col-md-6 form-group mb-3">
              <label><strong>Razón Social:</strong></label>
                   <input type="text" name="razon_social"  class="form-control" placeholder="000000" maxlength="200" required value="{{$empresa->razon_social}}">
            </div>
            <div class="col-md-6 form-group mb-3">
                  <label><strong>Documento Rep Legal</strong></label>
                  <input type="text" name="representante_legal_documento" class="form-control" id="nombres" placeholder="Nombres" required value="{{$empresa->representante_legal_documento}}">
            </div>

            <div class="col-md-6 form-group mb-3">
                  <label><strong>Nombres Rep Legal</strong></label>
                  <input type="text" name="representante_legal_nombres" class="form-control" id="apellidos" placeholder="Apellidos" required value="{{$empresa->representante_legal_nombres}}">
            </div>

           <div class="col-md-6 form-group mb-3">
              <label><strong>Teléfono:</strong></label>
              <input type="number" name="telefono" class="form-control" placeholder="000000"
                         maxlength="10"  value="{{$empresa->telefono}}">
            </div>
             <div class="col-md-6 form-group mb-3">
                   <label> <strong>Celular:</strong></label>
                    <input type="number" name="celular" class="form-control" placeholder="0000000000"
                        maxlength="255"  value="{{$empresa->celular}}">
            </div>
            <div class="col-md-6 form-group mb-3">
                   <label> <strong>Whatsapp:</strong></label>
                    <input type="number" name="whatsapp" class="form-control" placeholder="0000000000"
                         maxlength="255"  value="{{$empresa->whatsapp}}">
            </div>
           <div class="col-md-6 form-group mb-3">
              <label><strong>Departamento:</strong></label>
                    <select class="form-control departamentos" name="departamento_id">
                        @if($direccion)
                          <?php echo Helper::selectDepartamentos($direccion->departamento_id) ?>
                        @else
                          <?php echo Helper::selectDepartamentos() ?>
                        @endif
                    </select>
                   
            </div>
           
           <div class="col-md-6 form-group mb-3">
              <label><strong>Ciudad:</strong></label>
                  <select class="form-control municipios" name="ciudad_id">
                    @if($direccion)
                      <?php echo Helper::selectMunicipios($direccion->departamento_id,$direccion->ciudad_id) ?>
                    @else
                      <?php echo Helper::selectMunicipios() ?>
                    @endif
                  </select>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Dirección:</strong></label>
                   <input type="text" name="direccion" class="form-control" placeholder="" maxlength="255"  value="@if($direccion){{$direccion->direccion1}}@endif">
            </div>
           
            <div class="col-md-6 form-group mb-3">
              <label><strong>Detalle Dirección:</strong></label>
                   <input type="text" name="direccion_detalle" class="form-control" placeholder="" maxlength="255"  value="@if($direccion){{$direccion->direccion2}}@endif">
            </div>
            <div class="col-md-6 form-group mb-3">
                    <label><strong>Email:</strong></label>
                    <input type="email" name="email" class="form-control" placeholder="example@email.com"
                         maxlength="255"  value="{{$empresa->email_contacto}}">
            </div>
          

            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('empresas.convenios') }}" class="btn btn-danger">Cancelar</a>
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
        nit:{ required:true },
        razon_social: { required:true },
        representante_legal_nombres: { required:true },
        representante_legal_documento: { required:true },
        celular: { required:true }

        
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