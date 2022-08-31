@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('employes')}}">Empleados</a></li>
          <li>Editar Empleado</li>
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
                <h3 class="card-title mb3">Editar Empleado</h3>
  
 <div class="box box-info">
    <form action="{{route('employes.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="{{$cliente->id}}">
      <input type="hidden" name="is_new" value="false">

        <div class="row">

            <div class="col-md-6 form-group mb-3">
                  <label><strong>Nombres</strong></label>
                  <input type="text" name="nombres" class="form-control" id="nombres" placeholder="Nombres" required value="{{$cliente->nombres}}">
            </div>
            <div class="col-md-6 form-group mb-3">
                  <label><strong>Apellidos</strong></label>
                  <input type="text" name="apellidos" class="form-control" id="apellidos" placeholder="Apellidos" required value="{{$cliente->apellidos}}">
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Documento / Nit:</strong></label>
                   <input type="text" name="documento" class="form-control" placeholder="000000" maxlength="20" required value="{{$cliente->documento}}">
            </div>
           <div class="col-md-6 form-group mb-3">
              <label><strong>Teléfono:</strong></label>
              <input type="number" name="telefono" class="form-control" placeholder="000000"
                         maxlength="10" required value="{{$cliente->telefono}}">
            </div>
             <div class="col-md-6 form-group mb-3">
                   <label> <strong>Celular:</strong></label>
                    <input type="number" name="celular" class="form-control" placeholder="0000000000"
                        maxlength="255" required value="{{$cliente->celular}}">
            </div>
            <div class="col-md-6 form-group mb-3">
                   <label> <strong>Whatsapp:</strong></label>
                    <input type="number" name="whatsapp" class="form-control" placeholder="0000000000"
                         maxlength="255" required value="{{$cliente->whatsapp}}">
            </div>
            <div class="col-md-6 form-group mb-3">
              <label><strong>Departamento:</strong></label>
                    <select class="form-control departamentos" name="departamento_id">
                      <?php echo Helper::selectDepartamentos($direccion->departamento_id) ?>
                    </select>
                   
            </div>
           
           <div class="col-md-6 form-group mb-3">
              <label><strong>Ciudad:</strong></label>
                  <select class="form-control municipios" name="ciudad_id">
                     <?php echo Helper::selectMunicipios($direccion->departamento_id,$direccion->ciudad_id) ?>
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
        
                   <label> <strong>Area Empresa:</strong></label>
                  <select name="cargo" class="form-control">
                      <option value="1">Dirección General</option>
                      <option value="2">Dirección SIG</option>
                      <option value="3">Dirección Administrativa Financiera</option>
                      <option value="4">Dirección de RRHH</option>
                      <option value="5">Dirección de Operaciones</option>
                      <option value="6">Dirección de Servicio al Cliente</option>
                  </select>
               
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Cargo:</strong></label>
                   <input type="text" name="cargo" value="{{$cliente->cargo}}" class="form-control" placeholder="" maxlength="255" required>
            </div>
            
            <div class="col-md-6 form-group mb-3">
                    <label><strong>Email:</strong></label>
                    <input type="email" name="email" class="form-control" placeholder="example@email.com"
                         maxlength="255" required value="{{$cliente->email_contacto}}">
            </div>
            <div class="col-md-6 form-group mb-3">
                   <label> <strong>Nuevo Password:</strong></label>
                    <input type="password" name="password" class="form-control" placeholder=""
                        value="" autocomplete="off" maxlength="20">
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('employes') }}" class="btn btn-danger">Cancelar</a>
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
        email:{ required:true },
        documento:{ required:true },
        cargo: {required:true}
        
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