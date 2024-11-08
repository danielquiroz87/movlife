@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('planillaservicios')}}">Planilla Servicios</a></li>
          <li>Nueva Planilla</li>
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
                <h3 class="card-title mb3">Editar Planilla</h3>
  
 <div class="box box-info">
<div class="row">
 <form action="{{route('planillaservicios.save')}}" method="POST" id="form-planilla" enctype="multipart/form-data"  >
        {{ csrf_field() }}
        <input type="hidden" name="id" id="id" value="{{$planilla->id}}">
        <input type="hidden" name="is_new" id="is_new" value="false">
        
        <div class="col-md-12 form-group mb-3">
        <label><strong>Fecha Solicitud:</strong></label>
        <input type="date" name="fecha" value="{{$planilla->fecha}}"  class="form-control" placeholder="" maxlength="20" required>
        </div>
        <div class="col-md-12 form-group mb-3">
            <label><strong>Cliente:</strong></label>

            <select name="cliente_id" id="cliente_id" class="form-control">
                <?php echo Helper::selectClientes($planilla->cliente_id) ?>
            </select>
        </div>
        <div class="col-md-6 form-group mb-3">
            <label><strong>Uri Sede :</strong></label>
            <select name="uri_sede" id="uri_sede" class="form-control">
                <?php echo Helper::selectSedes($planilla->uri_sede) ?>
            </select>
        </div>
        

        <div class="col-md-12 form-group mb-3">
              <label><strong>Conductor:</strong></label>
                <select name="conductor_id" id="conductor_id" class="form-control">
                    <?php echo Helper::selectConductores($planilla->conductor_id) ?>
                </select>
         </div> 
        <div class="col-md-10 form-group mb-3">
            <label><strong>Archivo Planilla:</strong></label>
            <input type="file" name="file" id="file" class="form-control">
        </div>

        <div class="col-md-12 form-group mb-3">
              <label><strong>Aprobada:</strong></label>
                <select name="aprobada" id="aprobado" class="form-control">
                   <option value="0">No</option>
                   <option value="1" @if($planilla->aprobado==1) selected="selected" @endif >Si</option>

                </select>
         </div> 

         <div class="col-md-12 form-group mb-3">
              <label><strong>Observaciones:</strong></label>
                <textarea name="observaciones" id="observaciones" class="form-control">{{$planilla->observaciones}}</textarea>
         </div> 
                                    
                                     
            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('planillaservicios') }}" class="btn btn-danger">Cancelar</a>
            </div>
</form>

</div>

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

 $('#conductor_id').select2({
   theme: 'bootstrap-5'
 });

 $('#uri_sede').select2({
   theme: 'bootstrap-5'
 });

// just for the demos, avoids form submit
var form = $( "#user-new-form" );
$.validator.messages.required = 'Este campo es requerido';
$.validator.messages.email = 'Email invalido';

$('#form-planilla').validate({
  rules: {
        fecha: { required:true },
        cliente_id: { required:true },
        file: { required:false },
        
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