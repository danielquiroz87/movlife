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

<div class="col-md-6">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Nueva Tarifa / Usuario: {{ Auth::user()->name }} </h3>
  
 <div class="box box-info">
    <form action="{{route('tarifario.save')}}" method="POST" id="tarifario-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="0">
      <input type="hidden" name="is_new" value="true">
      <input type="hidden" name="id_user" id="id_user" value="{{Auth::user()->id}}">


        <div class="row">

           
             <div class="col-md-6 form-group mb-3">
              <label><strong>Origen:</strong></label>
                   <input type="text" name="origen" id="origin-input" value="" class="form-control" placeholder="" required>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Destino:</strong></label>
                   <input type="text" name="destino" id="destination-input" value="" class="form-control" placeholder=""  required>
            </div>

             <div class="col-md-12 form-group mb-3">
              <label><strong>Tipo Vehiculo:</strong></label>
                <select name="tipo_vehiculo" id="tipo_vehiculo" class="form-control select-busqueda">
                  <option value="" selected="selected">Seleccione</option>
                  <option value="1">Automovil</option>
                  <option value="2">Bus</option>
                  <option value="3">Buseta</option>
                  <option value="4">Camioneta</option>
                  <option value="5">Campero</option>
                  <option value="6">Chery</option>
                  <option value="7">Microbus</option>
                  <option value="8">Moto</option>
                  <option value="9">Van</option>
                  <option value="10">MiniVan</option>
                  <option value="11">Camioneta Doble Cabina</option>
                  <option value="12">Sedan</option>
                  <option value="13">Buseton</option>
                </select>
            </div>
          
             <div class="col-md-6 form-group mb-3">
              <label><strong>Tiempo:</strong></label>
                   <input type="number" name="tiempo" id="tiempo" value="" class="form-control" placeholder="" >
            </div>

             <div class="col-md-6 form-group mb-3">
              <label><strong>Kilometros:</strong></label>
                   <input type="number" name="kilometros" id="kilometros" value="" class="form-control" placeholder="" >
            </div>


            <div class="col-md-6 form-group mb-3">
              <label><strong>Valor Conductor:</strong></label>
                   <input type="number" name="valor_conductor" id="valor_conductor" value=""  class="form-control" placeholder="0" maxlength="11" required>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Valor Cliente:</strong></label>
                   <input type="number" name="valor_cliente" value="0" id="valor_cliente" class="form-control" placeholder="0" maxlength="11" required>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Proveedor:</strong></label>
                   <input type="text" name="proveedor" id="proveedor-input" value="" class="form-control" placeholder=""  required>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Cliente:</strong></label>
                  <select name="id_cliente" class="form-control">
                      
                        <?php echo Helper::selectClientes() ?>
                    
                    </select>
            </div>

             <div class="col-md-6 form-group mb-3">
              <label><strong>Jornada:</strong></label>
                  <select name="jornada" id="jornada" class="form-control select-busqueda">
                  <option value="" selected="selected">Seleccione</option>
                 
                  <option value="1">1 Hora</option>
                  <option value="2">2 Horas</option>
                  <option value="3">3 Horas</option>
                  <option value="4">4 Horas</option>
                  <option value="5">5 Horas</option>
                  <option value="6">Media Jornada</option>
                  <option value="7">Extendida</option>
                  <option value="8">Completa</option>
                </select>
            </div>

              <div class="col-md-6 form-group mb-3">
              <label><strong>Trayecto:</strong></label>
                <select name="trayecto" id="trayecto" class="form-control select-busqueda">
                  <option value="" selected="selected">Seleccione</option>
                  <option value="1">Ida</option>
                  <option value="2">Ida y Regreso</option>


                </select>
            </div>
             <div class="col-md-12 form-group mb-3">
              <label><strong>Observaciones:</strong></label><br/>
                   <textarea class="form-control" name="observaciones" rows="3"></textarea>
            </div>
            <div class="col-xs-6 col-sm-12 col-md-12 ">
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
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCP9sxMbXwsUb0_DnlL4lQxP54BYBXyD_M&callback=initMap&libraries=places&v=weekly"
      async
    ></script>



<script type="text/javascript">




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
var form = $( "#tarifario-new-form" );
$.validator.messages.required = 'Este campo es requerido';

$('#tarifario-new-form').validate({
  rules: {
        origen : { required:true },
        destino : { required:true },
        valor_conductor: {required:true}
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