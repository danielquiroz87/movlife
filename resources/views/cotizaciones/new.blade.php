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
          <li><a href="{{route('cotizaciones')}}">Cotizaciones</a></li>
          <li>Nueva Cotización</li>
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

<div class="col-md-6">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Nueva Cotización / Usuario: {{ Auth::user()->name }} </h3>
  
 <div class="box box-info">
    <form action="{{route('cotizaciones.save')}}" method="POST" id="cotizacion-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="0">
      <input type="hidden" name="is_new" value="true">
      <input type="hidden" name="id_user" id="id_user" value="{{Auth::user()->id}}">


        <div class="row">

         
            <div class="col-md-6 form-group mb-3">
              <label><strong>Cliente:</strong></label>
                <select name="id_cliente" class="form-control">
                  <?php echo Helper::selectClientes() ?>
                </select>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Forma de Pago / Días:</strong></label>
               <input type="number" name="forma_pago" class="form-control" placeholder="Forma de Pago" value="30" step="15" max="90" min="15" />
                
            </div>
            <div class="">
            </div>
            
            <div class="col-md-6 form-group mb-3">
              <label><strong>Fecha Cotización:</strong></label>
                   <input type="date" name="fecha_cotizacion" value="" class="form-control" placeholder="" maxlength="20" required>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Fecha Vencimiento:</strong></label>
                   <input type="date" name="fecha_vencimiento" value="" class="form-control" placeholder="" maxlength="20" required>
            </div>


            <div class="col-md-6 form-group mb-3">
              <label><strong>Fecha Servicio:</strong></label>
                   <input type="date" name="fecha_servicio" value="" class="form-control" placeholder="" maxlength="20" required>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Hora Recogida (Desde):</strong></label>
                   <input type="time" name="hora_recogida" value="06:00:00" class="form-control" max="23:59:59" min="00:00:00"  required >
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Hora Estimada Salida (Hasta):</strong></label>
                   <input type="time" name="hora_salida" value="10:00:00" class="form-control" max="23:59:59" min="00:00:00"  required >
            </div>

             <div class="col-md-12 form-group mb-3">
              <label><strong>Descripción:</strong></label>
                   <input type="text" name="descripcion" value="" class="form-control" placeholder="" maxlength="255" required>
            </div>

             <div class="col-md-6 form-group mb-3">
              <label><strong>Origen:</strong></label>
                   <input type="text" name="origen" id="origin-input" value="" class="form-control" placeholder="" required>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Destino:</strong></label>
                   <input type="text" name="destino" id="destination-input" value="" class="form-control" placeholder=""  required>
            </div>
           
            <div class="opciones_viaje col-md-6 form-group mb-3 ">
              <label class="radio radio-outline-warning">
                <input type="radio" name="tipo_viaje" value="1"><span>Solo Ida</span><span class="checkmark"></span>
              </label>
              <label class="radio radio-outline-success">
                    <input type="radio" name="tipo_viaje" value="2"><span>Ida y Regreso</span><span class="checkmark"></span>
              </label>
              <label class="radio radio-outline-danger">
                  <input type="radio" name="tipo_viaje" value="3"><span>Regreso</span><span class="checkmark"></span>
              </label>
            </div>

            <div class="opciones_disponibilidad col-md-6 form-group mb-3 ">
              <label class="checkbox checkbox-outline-primary">
                    <input type="checkbox" name="tiempo_adicional" id="tiempo_adicional" value="1"><span>Disponibilidad de Tiempo Adicional</span><span class="checkmark"></span>
                </label>
            </div>

            <div class="col-md-12 form-group mb-3" id="div-tiempo-adicional" style="display: none" >
              <label><strong> Horas de Espera Adicional</strong></label>
                   <input type="number" name="horas_tiempo_adicional" id="horas_tiempo_adicional" value="0" class="form-control" min="0" max="24" placeholder="0" maxlength="11" required>
            </div>
          
            <div class="col-md-6 form-group mb-3">
              <label><strong>Cantidad:</strong></label>
                   <input type="number" name="cantidad" id="cantidad" value="1" class="form-control" placeholder="" maxlength="1" required>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Valor Unitario:</strong></label>
                   <input type="number" name="valor_unitario" id="valor_unitario" value=""  class="form-control" placeholder="0" maxlength="11" required>
            </div>

            <div class="col-md-12 form-group mb-3">
              <label><strong>Total:</strong></label>
                   <input type="number" name="total" value="0" id="total" class="form-control" placeholder="0" maxlength="11" required>
            </div>

              <div class="col-md-12 form-group mb-3">
              <label><strong>Foto Vehiculo:</strong></label>
                   <input type="file" name="foto" value="" id="foto" class="form-control" >
            </div>
            
             <div class="col-md-12 form-group mb-3">
              <label><strong>Observaciones Servicio:</strong></label><br/>
                   <textarea class="form-control" name="observaciones" rows="3"></textarea>
            </div>
             <div class="col-md-12 form-group mb-3">
              <label><strong>Comentarios Servicio:</strong></label>
                  <textarea class="form-control" name="comentarios" rows="3"></textarea>
            </div>
            
          
        
            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('cotizaciones') }}" class="btn btn-danger">Cancelar</a>
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

          <div class="col-md-6">

            <div>
                <div id="mode-selector" class="controls">
                  <input type="radio" name="type" id="changemode-driving" checked="checked"  />
                  <label for="changemode-driving"  >Manejando</label>
                </div>
            </div>

    <div id="map">
      
    </div>


          </div>


</div>
@endsection
@section('bottom-js')
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.js"></script>

<script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAXnAaSyOMKc7h9pXnQowO8sO4vpz5ZvQ&callback=initMap&libraries=places&v=weekly"
      async
    ></script>



<script type="text/javascript">

function totalizar(){
  var cantidad=$("#cantidad").val();
  var valor_unitario=$("#valor_unitario").val();
  var total=cantidad*valor_unitario;
  $('#total').val(total);

}

$("#cantidad").change(function(){
  totalizar();
})

$("#valor_unitario").change(function(){
  totalizar();
})
$("#valor_unitario").blur(function(){
  totalizar();
})

$('#tiempo_adicional').change(function(){
  if( $(this).prop('checked')){
    $('#div-tiempo-adicional').show();
  }else{
    $("#horas_adicionales").val(0);
    $('#div-tiempo-adicional').hide();
  }
})

class AutocompleteDirectionsHandler {
  map;
  originPlaceId;
  destinationPlaceId;
  travelMode;
  directionsService;
  directionsRenderer;
  constructor(map) {
    this.map = map;
    this.originPlaceId = "";
    this.destinationPlaceId = "";
    this.travelMode = google.maps.TravelMode.DRIVING;
    this.directionsService = new google.maps.DirectionsService();
    this.directionsRenderer = new google.maps.DirectionsRenderer();
    this.directionsRenderer.setMap(map);

    const originInput = document.getElementById("origin-input");
    const destinationInput = document.getElementById("destination-input");
    const modeSelector = document.getElementById("mode-selector");
    const originAutocomplete = new google.maps.places.Autocomplete(originInput);

    // Specify just the place data fields that you need.
    originAutocomplete.setFields(["place_id"]);

    const destinationAutocomplete = new google.maps.places.Autocomplete(
      destinationInput
    );

    // Specify just the place data fields that you need.
    destinationAutocomplete.setFields(["place_id"]);
    
    this.setupClickListener(
      "changemode-driving",
      google.maps.TravelMode.DRIVING
    );
    this.setupPlaceChangedListener(originAutocomplete, "ORIG");
    this.setupPlaceChangedListener(destinationAutocomplete, "DEST");
    //this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(originInput);
    //this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(destinationInput);
    this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(modeSelector);
  }
  // Sets a listener on a radio button to change the filter type on Places
  // Autocomplete.
  setupClickListener(id, mode) {
    const radioButton = document.getElementById(id);

    radioButton.addEventListener("click", () => {
      this.travelMode = mode;
      this.route();
    });
  }
  setupPlaceChangedListener(autocomplete, mode) {
    autocomplete.bindTo("bounds", this.map);
    autocomplete.addListener("place_changed", () => {
      const place = autocomplete.getPlace();

      if (!place.place_id) {
        window.alert("Please select an option from the dropdown list.");
        return;
      }

      if (mode === "ORIG") {
        this.originPlaceId = place.place_id;
      } else {
        this.destinationPlaceId = place.place_id;
      }

      this.route();
    });
  }
  route() {
    if (!this.originPlaceId || !this.destinationPlaceId) {
      return;
    }

    const me = this;

    this.directionsService.route(
      {
        origin: { placeId: this.originPlaceId },
        destination: { placeId: this.destinationPlaceId },
        travelMode: this.travelMode,
      },
      (response, status) => {
        if (status === "OK") {
          me.directionsRenderer.setDirections(response);
        } else {
          window.alert("Directions request failed due to " + status);
        }
      }
    );
  }
}


 function initMap() {
  const map = new google.maps.Map(document.getElementById("map"), {
    mapTypeControl: false,
    center: { lat: 4.60971, lng: -74.08175 },
    zoom: 13,
  });

  new AutocompleteDirectionsHandler(map);
}

</script>


<script>



// just for the demos, avoids form submit
var form = $( "#cotizacion-new-form" );
$.validator.messages.required = 'Este campo es requerido';
$.validator.messages.email = 'Email invalido';

$('#user-new-form').validate({
  rules: {
        id_cliente : { required:true },
        fecha_cotizacion: { required:true },
        fecha_vencimiento : { required:true },
        fecha_servicio : { required:true },
        hora_recogida : { required:true },
        hora_salida : { required:true },
        tipo_viaje: { required:true },
        direccion_recogida: { required:true },
        direccion_destino:{ required:true},
        valor_conductor: {required:true},
        valor_cliente: {required:true}
    },messages: {
                
            },
    
})


function totalizar(){
  var cantidad=$("#cantidad").val();
  var valor_unitario=$("#valor_unitario").val();
  var total=cantidad*valor_unitario;
  $('#total').val(total);

}

$("#cantidad").change(function(){
  totalizar();
})

$("#valor_unitario").change(function(){
  totalizar();
})
$("#valor_unitario").blur(function(){
  totalizar();
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