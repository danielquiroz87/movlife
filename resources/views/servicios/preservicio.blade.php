@extends('layouts..preservicio')

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


  
<div class="row" style="margin-top: -90px">
 <div class="separator-breadcrumb border-top"></div>

</div>
<div class="row">
<div class="col-md-12">

 @if ($message = Session::get('flash_message'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button> 
            <strong>{{ $message }}</strong>
    </div>
  @endif
  @if ($message = Session::get('flash_bad_message'))
    <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button> 
            <strong>{{ $message }}</strong>
    </div>
  @endif
</div>

<div class="col-md-8">
      <div class="card text-left">
          <div class="card-body">


    <div class="row">
          <div class="col-md-12">
            <h1>Servicios</h1>
            <div class="d-sm-flex mb-3" data-view="print">
                  <span class="m-auto"></span>
                    <a class="btn btn-success" href="https://app.movlife.co">Iniciar Sesión</a>&nbsp;&nbsp;
                   
            </div>
            
          </div>
  </div>

                <h3 class="card-title mb3">Nuevo Servicio</h3>
  
 <div class="box box-info">
    <form action="{{route('web.preservicios.save')}}" method="POST" id="nuevo-servicio" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="0">
      <input type="hidden" name="is_new" value="true">
      <input type="hidden" name="cotizacion_id" value="0">
      <input type="hidden" name="estado" value="1">
      <input type="hidden" name="fecha_solicitud" value="{{date('Y-m-d H:i:s')}}">


        <div class="row">


            <div class="col-md-6 form-group mb-3">
              <label><strong>Fecha Servicio: *</strong></label>
                   <input type="date" name="fecha_servicio" value="" class="form-control" placeholder="" maxlength="20" required>
            </div>

         <!--
           <div class="col-md-6 form-group mb-3">
              <label><strong>Cliente Documento: *</strong></label>
              <input type="text" name="cliente_documento" id="cliente_documento" class="form-control"  />
            </div>
          
           <div class="col-md-3 form-group mb-3">
              <label><strong>Cliente Nombres: *</strong></label>
              <input type="text" name="cliente_nombres" id="cliente_nombres" class="form-control"  />
            </div>

           <div class="col-md-3 form-group mb-3">
              <label><strong>Cliente Apellidos: *</strong></label>
                  <input type="text" name="cliente_apellidos" id="cliente_apellidos" class="form-control"  />
            </div>

           <div class="col-md-6 form-group mb-3">
              <label><strong>Cliente Email: *</strong></label>
                  <input type="text" name="cliente_email" id="cliente_email" class="form-control"  />
            </div>
            
            <div class="col-md-6 form-group mb-3">
              <label><strong>Cliente Celular: *</strong></label>
                  <input type="text" name="cliente_celular" id="cliente_celular" class="form-control"  />
            </div>  

            !-->

            <div class="col-md-6 form-group mb-3">
              <label><strong>Pasajero Documento: *</strong></label>
              <input type="text" name="pasajero_documento" id="pasajero_documento" class="form-control"  />
            </div>
          
           <div class="col-md-3 form-group mb-3">
              <label><strong>Pasajero Nombres: *</strong></label>
              <input type="text" name="pasajero_nombres" id="pasajero_nombres" class="form-control"  />
            </div>

           <div class="col-md-3 form-group mb-3">
              <label><strong>Pasajero Apellidos: *</strong></label>
                  <input type="text" name="pasajero_apellidos" id="pasajero_apellidos" class="form-control"  />
            </div>

           <div class="col-md-6 form-group mb-3">
              <label><strong>Pasajero Email: *</strong></label>
                  <input type="text" name="pasajero_email" id="pasajero_email" class="form-control"  />
            </div>
            
            <div class="col-md-6 form-group mb-3">
              <label><strong>Pasajero Celular: *</strong></label>
                  <input type="text" name="pasajero_celular" id="pasajero_celular" class="form-control"  />
            </div>



            <div class="col-md-6 form-group mb-3">
              <label><strong>Tipo Servicio: *</strong></label>
                  <select name="tipo_servicio" id="tipo_servicio" class="form-control">
                     <?php echo Helper::selectTipoServicios() ?>
                  </select>
            </div>

           

            <div class="col-md-6 form-group mb-3">
              <label><strong>Hora Recogida (Desde): * </strong></label>
                   <input type="time" name="hora_recogida" value="" class="form-control" max="23:59:59" min="00:00:00"  required >
            </div>

             <div class="col-md-6 form-group mb-3">
              <label><strong>Hora Regreso (Hasta):</strong></label>
                   <input type="time" name="hora_regreso" value="" class="form-control" max="23:59:59" min="00:00:00"   >
            </div>

            
            <div class="col-md-6 form-group mb-3">
              <label><strong>Barrio:</strong></label>
                   <input type="text" name="barrio" id="barrio" class="form-control" placeholder="" maxlength="600" value="" >
            </div>

          
             <div class="col-md-6 form-group mb-3">
              <label><strong>Origen:</strong></label>
                   <input type="text" name="origen" id="origin-input" class="form-control" placeholder="" maxlength="600" value="" >
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Destino1:</strong></label>
                   <input type="text" name="destino" id="destination-input" class="form-control" placeholder=""  maxlength="600" value="">
            </div>

             <div class="col-md-6 form-group mb-3">
              <label><strong>Destino2:</strong></label>
                   <input type="text" name="destino2"  value="" class="form-control" placeholder="" maxlength="600" >
            </div>

             <div class="col-md-6 form-group mb-3">
              <label><strong>Destino3:</strong></label>
                   <input type="text" name="destino2"  value="" class="form-control" placeholder="" maxlength="600" >
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Destino4:</strong></label>
                   <input type="text" name="destino4"  value="" class="form-control" placeholder="" maxlength="600" >
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Destino5:</strong></label>
              <input type="text" name="destino5"  value="" class="form-control" placeholder="" maxlength="600" >
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Kilometros:</strong></label>
                   <input type="text" name="kilometros" id="kilometros" value="" class="form-control" readonly="true" >
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Tiempo:</strong></label>
                   <input type="text" name="tiempo" id="tiempo" value="" class="form-control" placeholder="" readonly="true" >
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Coordinadora:</strong></label>
              <select class="form-control" name="educador_coordinador" id="educador_coordinador" >
              <?php echo Helper::selectEmpleadosDirectores() ?>
              </select>
            </div>

           
             <div class="col-md-6 form-group mb-3">
              <label><strong>Uri Sede: </strong></label>
              <input type="text" name="uri" id="uri" value="" class="form-control" placeholder="" maxlength="600" >
                   
            </div>

             <div class="opciones_disponibilidad col-md-6 form-group mb-3 ">
               <br/>
              <label class="checkbox checkbox-outline-primary" style="margin-top:10px">

                    <input type="checkbox" name="tiempo_adicional" id="tiempo_adicional" value="1"><span>Disponibilidad de Tiempo Adicional</span><span class="checkmark"></span>
                </label>
            </div>

            <div class="col-md-12 form-group mb-3" id="div-tiempo-adicional" style="display: none" >
              <label><strong> Horas de Espera Adicional</strong></label>
                   <input type="number" name="valor" value="0" class="form-control" min="0" max="24" placeholder="0" maxlength="11" required>
            </div>

            <div class="opciones_viaje col-md-6 form-group mb-3 ">
              <label class="radio radio-outline-warning">
                <input type="radio" name="tipo_viaje" value="1" ><span>Solo Ida</span><span class="checkmark"></span>
              </label>
              <label class="radio radio-outline-success">
                    <input type="radio" name="tipo_viaje" value="2"><span>Ida y Regreso</span><span class="checkmark"></span>
              </label>
              <label class="radio radio-outline-danger">
                  <input type="radio" name="tipo_viaje" value="3"><span>Regreso</span><span class="checkmark"></span>
              </label>
                 <label class="radio radio-outline-danger">
                  <input type="radio" name="tipo_viaje" value="4"><span>Multidestino</span><span class="checkmark"></span>
              </label>
            </div>
            
           
             <div class="col-md-12 form-group mb-3">
              <label><strong>Observaciones Servicio:</strong></label><br/>
                   <textarea class="form-control" name="observaciones" rows="3"></textarea>
            </div>
            
        
            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('servicios') }}" class="btn btn-danger">Cancelar</a>
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

               <div class="col-md-4">

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
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTB40SU4FspBupqEDIRYg3PlrVNcrNsBQ&callback=initMap&libraries=places&v=weekly"
      async>
</script>



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
   
    /*this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(originInput);
    this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(
      destinationInput
    );
    this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(modeSelector);
    */
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
          var totalDistance = 0;
          var totalDuration = 0;

          var legs = response.routes[0].legs;
          console.log(response.routes[0])
          for(var i=0; i<legs.length; ++i) {
              totalDistance += legs[i].distance.value;
              totalDuration += legs[i].duration.value;
          }
          me.totalDistance=totalDistance;
          me.totalDuration=totalDuration;
          this.setDataInputs(me,legs);
          
        } else {
          window.alert("Directions request failed due to " + status);
        }
      }
    );

   
  }
  setDataInputs(data,legs){
    $('#kilometros').val(legs[0].distance.text);
    $('#tiempo').val(legs[0].duration.text)
    
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

  $("#placa").blur(function(){
    var placa=$(this).val();
    $.get('/conductores/placa/'+placa,function(html){
      $("#conductor_pago").html(html);
      $("#conductor_servicio").html(html);
    })
  })

$('#tiempo_adicional').change(function(){
  if( $(this).prop('checked')){
    $('#div-tiempo-adicional').show();
  }else{

    $("#horas_adicionales").val(0);
    $('#div-tiempo-adicional').hide();
  }
})

// just for the demos, avoids form submit
var form = $("#nuevo-servicio");
$.validator.messages.required = 'Este campo es requerido';
$.validator.messages.email = 'Email invalido';

$(form).validate({
  
  rules: {
        /*cliente_documento:{required:true},
        cliente_nombres:{required:true},
        cliente_apellidos:{required:true},
        cliente_email:{required:true},
        cliente_celular:{required:true},
        */
        pasajero_documento:{required:true},
        pasajero_nombres:{required:true},
        pasajero_apellidos:{required:true},
        pasajero_email:{required:true},
        pasajero_celular:{required:true},
        hora_recogida:{required:true},
        origen: { required:true },
        destino: { required:true },
        tipo_viaje:{ required:true },
        tipo_servicio:{required:true},
        educador_coordinador: {required:true},
        uri: {required:true}
        
    },messages: {
                
    },
    
})



$("#submit").validate({ 
 onsubmit: false,
  
 submitHandler: function(form) {  
   if ($(form).valid())
   {
      $('#submit').attr('disabled',true); 
      form.submit(); 
   }
   $('#submit').attr('disabled',false); 
   return false; // prevent normal form posting
 }
});



</script>
@endsection