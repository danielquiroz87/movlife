@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('anticipos')}}">Antcipos</a></li>
          <li>Nuevo Antcipos</li>
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
                <h3 class="card-title mb3">Nuevo Antcipos</h3>
  
 <div class="box box-info">
    <form action="{{route('anticipos.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="0">
      <input type="hidden" name="is_new" value="true">

        <div class="row">


            <div class="col-md-6 form-group mb-3">
              <label><strong>Tipo Anticipo:</strong></label>
                    <select name="tipo" id="tipo" class="form-control">
                      <option value="1">Anticipo Servicios</option>
                      <option value="2">Anticipo Rodamiento</option>
                    </select>
            </div> 

            <div class="col-md-6 form-group mb-3">
              <label><strong>Cliente:</strong></label>
                    
                    <select name="cliente_id" id="cliente_id" class="form-control">
                      <?php echo Helper::selectClientes() ?>
                    </select>
            </div> 


            <div class="col-md-6 form-group mb-3">
              <label><strong>Coordinador(a):</strong></label>
                    
                    <select name="coordinador_id" class="form-control">
                      <?php echo Helper::selectEmpleadosDirectores() ?>
                    </select>
            </div> 

           
            <div class="col-md-6 form-group mb-3">
              <label><strong>Conductor Pago:</strong></label>
                    
                    <select name="conductor_id" id="conductor_id" class="form-control">
                      <?php echo Helper::selectConductores() ?>
                    </select>
            </div> 

            <div class="col-md-6 form-group mb-3">
              <label><strong>Conductor Servicio:</strong></label>
                    
                    <select name="conductor_servicio_id" id="conductor_servicio_id" class="form-control">
                      <?php echo Helper::selectConductores() ?>
                    </select>
            </div> 

            <div class="col-md-6 form-group mb-3">
                   <label> <strong>Valor Cliente:</strong></label>
                    <input type="number" name="valor_cliente" class="form-control" placeholder=""
                        value="0" maxlength="20" required>
            </div>


             <div class="col-md-6 form-group mb-3">
                   <label> <strong>Valor Anticipo:</strong></label>
                    <input type="number" name="valor" class="form-control" placeholder=""
                        value="0" maxlength="20" required>
            </div>

            <div class="col-md-6 form-group mb-3">
                   <label> <strong>Servicio Id:</strong></label>
                    <input type="number" name="servicio_id" class="form-control" placeholder=""
                        value="0" maxlength="20">
            </div>

            <div class="col-md-6 form-group mb-3">
                   <label> <strong>PreServicio Id:</strong></label>
                    <input type="number" name="preservicio_id" class="form-control" placeholder=""
                        value="0" maxlength="20">
            </div>

            <div class="col-md-6 form-group mb-3">
            <label> <strong>Observaciones:</strong></label>
                  <textarea class="form-control" name="observaciones" rows="3"></textarea>
            </div>
         
        
            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('anticipos') }}" class="btn btn-danger">Cancelar</a>
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

$('#cliente_id').select2({
   theme: 'bootstrap-5'
 });

 $('#coordinador_id').select2({
   theme: 'bootstrap-5'
 });
 $('#conductor_id').select2({
   theme: 'bootstrap-5'
 });
 $('#conductor_servicio_id').select2({
   theme: 'bootstrap-5'
 });
</script>
@endsection