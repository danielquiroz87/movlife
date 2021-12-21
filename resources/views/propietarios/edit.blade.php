@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('propietarios')}}">Propietarios Vehiculos</a></li>
          <li>Nuevo Propietario</li>
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
                <h3 class="card-title mb3">Editar Propietario</h3>
  
 <div class="box box-info">
    <form action="{{route('propietarios.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="{{$propietario->id}}">
      <input type="hidden" name="is_new" value="false">

        <div class="row">
            <div class="col-md-6 form-group mb-3">
              <label><strong>Documento / Nit:</strong></label>
                   <input type="text" name="documento"  class="form-control" placeholder="000000" maxlength="20" required value="{{$propietario->documento}}">
            </div>

             <div class="col-md-6 form-group mb-3">
              <label><strong>Nombre propietario / Razón Social:</strong></label>
                   <input type="text" name="razon_social"  class="form-control" placeholder="000000" maxlength="20" required value="{{$propietario->razon_social}}">
            </div>
            <div class="col-md-6 form-group mb-3">
                  <label><strong>Nombres Contacto</strong></label>
                  <input type="text" name="nombres" class="form-control" id="nombres" placeholder="Nombres" required value="{{$propietario->nombres}}">
            </div>
            <div class="col-md-6 form-group mb-3">
                  <label><strong>Apellidos Contacto</strong></label>
                  <input type="text" name="apellidos" class="form-control" id="apellidos" placeholder="Apellidos" required value="{{$propietario->apellidos}}">
            </div>

           <div class="col-md-6 form-group mb-3">
              <label><strong>Teléfono:</strong></label>
              <input type="number" name="telefono" class="form-control" placeholder="000000"
                         maxlength="10" required value="{{$propietario->telefono}}">
            </div>
             <div class="col-md-6 form-group mb-3">
                   <label> <strong>Celular:</strong></label>
                    <input type="number" name="celular" class="form-control" placeholder="0000000000"
                        maxlength="255" required value="{{$propietario->celular}}">
            </div>
            <div class="col-md-6 form-group mb-3">
                   <label> <strong>Whatsapp:</strong></label>
                    <input type="number" name="whatsapp" class="form-control" placeholder="0000000000"
                         maxlength="255" required value="{{$propietario->whatsapp}}">
            </div>
           <div class="col-md-6 form-group mb-3">
              <label><strong>Departamento:</strong></label>
                    <select class="form-control" name="departamento">
                      <option value="{{$direccion->departamento_id}}">Antioquia</option>
                    </select>
                   
            </div>
           
           <div class="col-md-6 form-group mb-3">
              <label><strong>Ciudad:</strong></label>
                  <select class="form-control" name="ciudad">
                    <option value="{{$direccion->ciudad_id}}">Medellín</option>
                  </select>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Dirección:</strong></label>
                   <input type="text" name="direccion" class="form-control" placeholder="" maxlength="20" required value="{{$direccion->direccion1}}">
            </div>
           
            <div class="col-md-6 form-group mb-3">
              <label><strong>Detalle Dirección:</strong></label>
                   <input type="text" name="direccion_detalle" class="form-control" placeholder="" maxlength="20"  value="{{$direccion->direccion2}}">
            </div>
            <div class="col-md-6 form-group mb-3">
                    <label><strong>Email:</strong></label>
                    <input type="email" name="email" class="form-control" placeholder="example@email.com"
                         maxlength="255" required value="{{$propietario->email_contacto}}">
            </div>
            <div class="col-md-6 form-group mb-3">
                   <label> <strong>Nuevo Password:</strong></label>
                    <input type="password" name="password" class="form-control" placeholder=""
                        value="" autocomplete="off" maxlength="20" >
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('propietarios') }}" class="btn btn-danger">Cancelar</a>
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
        razon_social: { required:true },
        email:{ required:true },
        documento:{ required:true },
        departamento_id:{ required:true },
        ciudad_id: { required:true },
        password:{ required:true },
        
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