@extends('layouts.master')

@section('main-content')

<style type="text/css">
  
/* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
#map {
  height: 100%;
}

.controls {
  margin-top: 10px;
  border: 1px solid transparent;
  border-radius: 2px 0 0 2px;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  height: 32px;
  outline: none;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
}

#origin-input,
#destination-input {
  float: left;
  display: block;
}

#origin-input:focus,
#destination-input:focus {
  border-color: #4d90fe;
}

#mode-selector {
  color: #fff;
  background-color: #4d90fe;
  margin-left: 12px;
  padding: 5px 11px 0px 11px;
}

#mode-selector label {
  font-family: Roboto;
  font-size: 13px;
  font-weight: 300;
}



</style>


  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('tarifario')}}">Tarifario</a></li>
          <li>Nueva Tarifa</li>
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

<div class="col-md-12">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Nueva Ruta </h3>
  
 <div class="box box-info">
    <form action="{{route('rutas.save')}}" method="POST" id="tarifario-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="0">
      <input type="hidden" name="is_new" value="true">
      <input type="hidden" name="codigo" id="codigo" value="{{($ruta->codigo+1)}}">

                <div class="row">
            
             <div class="col-md-6 form-group mb-3">
              <label><strong>Código Ruta:</strong></label>
                   <input type="text" name="codigo" id="strong" value="{{($ruta->codigo+1)}}" class="form-control" placeholder="" disabled="true" >
            </div>
            <div class="col-md-6 form-group mb-3"></div>
             <div class="col-md-6 form-group mb-3">
              <label><strong>Origen Destino:</strong></label>
                  
                   <textarea class="form-control" name="origen_destino" rows="5" ></textarea>
            </div>

         

            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('rutas') }}" class="btn btn-danger">Cancelar</a>
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

<script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTB40SU4FspBupqEDIRYg3PlrVNcrNsBQ&callback=initMap&libraries=places&v=weekly"
      async
    ></script>



<script type="text/javascript">


// just for the demos, avoids form submit
var form = $( "#tarifario-new-form" );
$.validator.messages.required = 'Este campo es requerido';

$('#tarifario-new-form').validate({
  rules: {
        origen_destino : { required:true },
        valor_cliente: {required:true}
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

</script>
@endsection