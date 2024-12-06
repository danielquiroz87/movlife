@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('vehiculos.mantenimientos')}}">Vehiculos Mantenimientos</a></li>
          <li>Nuevo Mantenimiento</li>
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
                <h3 class="card-title mb3">Nuevo Mantenimiento</h3>
                <form action="{{route('vehiculos.mantenimientos.save')}}" method="POST" id="form-planilla" enctype="multipart/form-data"  >
                    {{ csrf_field() }}

                <input type="hidden" name="id" id="id" value="0">
        <input type="hidden" name="is_new" id="is_new" value="true">
        
        <div class="col-md-12 form-group mb-3">
        <label><strong>Fecha Mantenimiento:</strong></label>
        <input type="date" name="fecha" value="{{date('Y-m-d')}}"  class="form-control" placeholder="" maxlength="20" required>
        </div>
        <div class="col-md-12 form-group mb-3">
        <label><strong>Placa Vehiculo:</strong></label>
        <input type="text"  name="placa" id="placa" value="" class="form-control" >
        </div>
        <div class="col-md-12 form-group mb-3">
        <label><strong>Kilometros Actuales:</strong></label>
        <input type="number"  name="kilometros" id="kilometros" value="" class="form-control" >
        </div>
                <div class="box box-info">
                
                    <div class="col-md-12 form-group mb-3">
                    <label><strong>Tipo Mantenimiento:</strong></label>

                     <select name="tipo_mantenimiento" class="form-control">
                         <option value="1">Preventivo</option>
                         <option value="2">Correctivo</option>
                    </select>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                    <label><strong>Item Mantenimiento:</strong></label>

                     <select name="item" class="form-control">
                        @foreach($items as $key=>$item)
                            @if($item->tipo==1)
                            <option value="{{$item->id}}">{{$item->nombre}} ({{number_format($item->intervalo_km)}}) km</option>
                            @else
                            <option value="{{$item->id}}">{{$item->nombre}} ({{number_format($item->intervalo_year)}}) años</option>
                            @endif
                        @endforeach;
                    </select>
                    </div>

                    <div class="col-md-12 form-group mb-3">
                    <label><strong>Proveedor:</strong></label>
                    <input type="text" name="proveedor" id="proveedor" value="" class="form-control" >
                    </div>

                    <div class="col-md-12 form-group mb-3">
                    <label><strong>Valor:</strong></label>
                    <input type="text"  name="valor" id="valor" value="" class="form-control" >
                    </div>

                    <div class="col-md-12 form-group mb-3">
                    <label><strong>Archivo Factura:</strong></label>
                    <input type="file" name="archivo" id="archivo" />
                    </div>

                    <div class="col-md-12 form-group mb-3">
                    <label><strong>Observaciones:</strong></label>
                    <textarea name="observaciones" class="form-control"></textarea>
                    </div>
                    
                </div>
                 
                                                
                        <div class="col-xs-12 col-sm-12 col-md-12 ">
                            <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                            <a href="{{ route('planillaservicios') }}" class="btn btn-danger">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card-body -->
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
        file: { required:true },
        
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