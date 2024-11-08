@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('pasajeros')}}">Pasajeros</a></li>
          <li>Nuevo Pasajero</li>
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
                <h3 class="card-title mb3">Nuevo Pasajero</h3>
  
 <div class="box box-info">
    <form action="{{route('pasajeros.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="0">
      <input type="hidden" name="is_new" value="true">

        <div class="row">

           <div class="col-md-6 form-group mb-3">
              <label><strong>Cliente:</strong></label>
                    
                    <select name="cliente_id" class="form-control">
                      <?php echo Helper::selectClientes() ?>
                    </select>
            </div> 
            
            <div class="col-md-6 form-group mb-3">
              <label><strong>URI SEDE: </strong></label>
                   <select name="uri_sede" class="form-control">
                     <?php echo Helper::selectSedes() ?>
                   </select>
            </div>

           <div class="col-md-6 form-group mb-3">
              <label><strong>Documento / Nit:</strong></label>
                   <input type="text" name="documento" value="" class="form-control" placeholder="000000" maxlength="20" >
            </div>

            <div class="col-md-6 form-group mb-3">
                  <label><strong>Nombres</strong></label>
                  <input type="text" name="nombres" class="form-control" id="nombres" placeholder="Nombres" required>
            </div>
            <div class="col-md-6 form-group mb-3">
                  <label><strong>Apellidos</strong></label>
                  <input type="text" name="apellidos" class="form-control" id="apellidos" placeholder="Apellidos" required>
            </div>
             <div class="col-md-6 form-group mb-3">
                  <label><strong>Código</strong></label>
                  <input type="text" name="codigo" class="form-control" id="codigo" placeholder="000">
            </div>
           
           <div class="col-md-6 form-group mb-3">
              <label><strong>Teléfono:</strong></label>
              <input type="number" name="telefono" class="form-control" placeholder="000000"
                        value="" maxlength="10" >
            </div>
             <div class="col-md-6 form-group mb-3">
                   <label> <strong>Celular:</strong></label>
                    <input type="number" name="celular" class="form-control" placeholder="0000000000"
                        value="" maxlength="255" >
            </div>
            <div class="col-md-6 form-group mb-3">
                   <label> <strong>Whatsapp:</strong></label>
                    <input type="number" name="whatsapp" class="form-control" placeholder="0000000000"
                        value="" maxlength="255" >
            </div>
           <div class="col-md-6 form-group mb-3">
              <label><strong>Departamento:</strong></label>
                     <select name="departamento" class="form-control departamentos">
                       <?php echo Helper::selectDepartamentos() ?>
                    </select>
            </div>
           
           <div class="col-md-6 form-group mb-3">
              <label><strong>Ciudad:</strong></label>
                  <select name="ciudad" class="form-control municipios">
                     <?php echo Helper::selectMunicipios() ?>
                  </select>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Dirección:</strong></label>
                   <input type="text" name="direccion" value="" class="form-control" placeholder="" maxlength="255">
            </div>

             <div class="col-md-6 form-group mb-3">
              <label><strong>Detalle Dirección:</strong></label>
                   <input type="text" name="direccion_detalle" value="" class="form-control" placeholder="" maxlength="255" >
            </div>
           
            
            <div class="col-md-6 form-group mb-3">
                    <label><strong>Email:</strong></label>
                    <input type="text" name="email" class="form-control" placeholder="example@email.com"
                        value="" maxlength="255" >
            </div>
            <div class="col-md-6 form-group mb-3">
                   <label> <strong>Password:</strong></label>
                    <input type="text" name="password" class="form-control" placeholder=""
                        value="" maxlength="20" >
            </div>

             <div class="col-md-6 form-group mb-3">
                   <label> <strong>Nombre Contacto:</strong></label>
                    <input type="text" name="nombre_contacto" class="form-control" placeholder=""
                        value="" maxlength="20" >
            </div>
            
             <div class="col-md-6 form-group mb-3">
                   <label> <strong>Teléfono Contacto:</strong></label>
                    <input type="number" name="telefono_contacto" class="form-control" placeholder=""
                        value="" maxlength="20" >
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

$('#cliente_id').select2({
   theme: 'bootstrap-5'
 });

 $('#uri_sede').select2({
   theme: 'bootstrap-5'
 });

// just for the demos, avoids form submit
var form = $( "#user-new-form" );
$.validator.messages.required = 'Este campo es requerido';
$.validator.messages.email = 'Email invalido';

$('#user-new-form').validate({
  rules: {
        nombres: { required:true },
        apellidos: { required:true },
        email:{ required:false,email:true },
        //password:{ required:true },
        documento:{ required:true },
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