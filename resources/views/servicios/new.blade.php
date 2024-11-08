@extends('layouts.master')
@section('main-content')
<?php
if(isset($servicio)){
  $servicio=$servicio;
}else{
  $servicio=false;
}

?>

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


  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('servicios')}}">Servicios</a></li>
          <li>Nuevo Servicio</li>
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

<div class="col-md-8">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Nuevo Servicio / Usuario: {{auth()->user()->name}} </h3>
  
 <div class="box box-info">
    <form action="{{route('servicios.save')}}" method="POST" id="nuevo-servicio" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="0">
      <input type="hidden" name="is_new" value="true">
      <input type="hidden" name="url_tarifa_tiposervicio" id="url_tarifa_tiposervicio" value="{{route('tarifastiposervicio.match')}}">


      @if($cotizacion) 
        <input type="hidden" name="cotizacion_id" value="{{$cotizacion->id}}">
      @else 
        <input type="hidden" name="cotizacion_id" value="0">
      @endif 

        <input type="hidden" name="estado" value="1">
        @if ($servicio)
        <input type="hidden" name="preservicio_id" value="{{$servicio->preservicio_id}}">
        @endif 


        <div class="row">

         
           <div class="col-md-6 form-group mb-3">
              <label><strong>Cliente:</strong></label>
                    <select name="id_cliente" id="id_cliente" class="form-control observertarifatiposervicios">
                      @if ($servicio) 
                        <?php echo Helper::selectClientes($servicio->id_cliente) ?>
                      @else
                        <?php echo Helper::selectClientes() ?>
                      @endif
                    </select>
            </div>

            @if($servicio)
            <div class="col-md-6 form-group mb-3">
              <label><strong>URI SEDE: </strong></label>
                   <select name="uri_sede" id="uri_sede"  class="form-control observertarifatiposervicios"  >
                    <option value="">Seleccione una URI/SEDE</option> 
                    @foreach($sedes as $sede)
                      <option value="{{$sede->id}}" @if($servicio->uri_sede==$sede->id) selected="selected" @else @endif>{{$sede->nombre}}</option>
                    @endforeach
                   </select>
            </div>
            @else

            <div class="col-md-6 form-group mb-3">
              <label><strong>URI SEDE: </strong></label>
                   <select name="uri_sede" id="uri_sede" class="form-control observertarifatiposervicios">
                   <option value="">Seleccione una URI/SEDE</option>
                    @foreach($sedes as $sede)
                      <option value="{{$sede->id}}">{{$sede->nombre}}</option>
                    @endforeach
                   </select>
            </div>

            @endif

            @if ($servicio)

            <div class="col-md-6 form-group mb-3">
              <label><strong>Placa (Vehículo):</strong></label>
              <input type="text" name="placa" id="placa" value="{{$servicio->placa}}" class="form-control" maxlength="6" 
              style="text-transform:uppercase;" 
              onkeyup="javascript:this.value=this.value.toUpperCase();" />
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Conductor (Pago):</strong></label>
                  <select name="id_conductor_pago" id="conductor_pago" class="form-control">
                      <?php echo Helper::selectConductores($servicio->id_conductor_pago) ?>
                  </select>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Conductor Prestador Servicio:</strong></label>
                  <select name="id_conductor_servicio" id="conductor_servicio" class="form-control">
                      <?php echo Helper::selectConductores($servicio->id_conductor_servicio) ?>
                  </select>
            </div>
            @else
            
            <div class="col-md-6 form-group mb-3">
              <label><strong>Placa (Vehículo):</strong></label>
              <input type="text" name="placa" id="placa" value="" class="form-control" maxlength="6" 
              style="text-transform:uppercase;" 
              onkeyup="javascript:this.value=this.value.toUpperCase();" />
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Conductor (Pago):</strong></label>
                  <select name="id_conductor_pago" id="conductor_pago" class="form-control">
                      <?php echo Helper::selectConductores() ?>
                  </select>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Conductor Prestador Servicio:</strong></label>
                  <select name="id_conductor_servicio" id="conductor_servicio" class="form-control">
                      <?php echo Helper::selectConductores() ?>
                  </select>
            </div>

            @endif
            <div class="col-md-6 form-group mb-3">
              <label><strong>Pasajero:</strong></label>
                 
                  @if($servicio && $servicio->id_pasajero!="")
                  <select name="id_pasajero" id="id_pasajero" class="form-control">
                    <?php echo Helper::selectPasajeros($servicio->id_pasajero) ?>
                  </select>
                  @else
                  <select name="id_pasajero" id="id_pasajero" class="form-control">
                    <?php echo Helper::selectPasajeros() ?>
                  </select>
                  @endif
            </div>
            @if ($servicio)
            <div class="col-md-6 form-group mb-3">
              <label><strong>Fecha Solicitud:</strong></label>
                   <input type="text" name="fecha_solicitud" value="{{$servicio->fecha_solicitud}}"  class="form-control" placeholder="" maxlength="20" required>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Fecha Servicio:</strong></label>
                   <input type="date" name="fecha_servicio" value="{{$servicio->fecha_servicio}}" class="form-control" placeholder="" maxlength="20" required>
            </div>
            @else

            <div class="col-md-6 form-group mb-3">
              <label><strong>Fecha Solicitud:</strong></label>
                   <input type="text" name="fecha_solicitud" value="{{date('Y-m-d H:i:s')}}"  class="form-control" placeholder="" maxlength="20" required>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Fecha Servicio:</strong></label>
                   <input type="date" name="fecha_servicio" value="" class="form-control" placeholder="" maxlength="20" required>
            </div>
            @endif

      


            @if ($cotizacion)

            <div class="col-md-6 form-group mb-3">
              <label><strong>Hora Recogida (Desde):</strong></label>
                   <input type="text" name="hora_recogida" id="hora_recogida" value="{{$cotizacion->hora_recogida}}" class="form-control time" max="23:59:59" min="00:00:00"  required >
            </div>

             <div class="col-md-6 form-group mb-3">
              <label><strong>Hora Estimada Salida (Hasta):</strong></label>
                   <input type="time" name="hora_regreso" value="{{$cotizacion->hora_salida}}" class="form-control time" max="23:59:59" min="00:00:00"   >
            </div>
            @else

            <div class="col-md-6 form-group mb-3">
              <label><strong>Hora Recogida (Desde):</strong></label>
                   <input type="text" name="hora_recogida" id="hora_recogida" value="" class="form-control time" max="23:59:59" min="00:00:00"  required >
            </div>

             <div class="col-md-6 form-group mb-3">
              <label><strong>Hora Estimada Salida (Hasta):</strong></label>
                   <input type="time" name="hora_estimada_salida" value="10:00:00" class="form-control time" max="23:59:59" min="00:00:00"   >
            </div>

            @endif

            @if($servicio)
            <div class="col-md-6 form-group mb-3">
              <label><strong>Tipo Servicio:</strong></label>
                  <select name="tipo_servicio" id="tipo_servicio" class="form-control observertarifatiposervicios">
                     <?php echo Helper::selectTipoServicios($servicio->tipo_servicio) ?>
                  </select>
            </div>
            @else
            <div class="col-md-6 form-group mb-3">
              <label><strong>Tipo Servicio:</strong></label>
                  <select name="tipo_servicio" id="tipo_servicio" class="form-control observertarifatiposervicios">
                     <?php echo Helper::selectTipoServicios() ?>
                  </select>
            </div>
            @endif

            <div class="col-md-6 form-group mb-3">
              <label><strong>Semana:</strong></label>
                   <input type="number" name="semana" value="" class="form-control" min="1" max="5">
            </div>

            @if($servicio)

            <div class="col-md-6 form-group mb-3">
              <label><strong>Barrio:</strong></label>
                   <input type="text" name="barrio" id="barrio" class="form-control" placeholder="" maxlength="600" value="{{$servicio->barrio}}" >
            </div>
            @else
             <div class="col-md-12 form-group mb-3">
              <label><strong>Barrio:</strong></label>
                   <input type="text" name="barrio" id="barrio" class="form-control" placeholder="" maxlength="600" value="" >
            </div>
            @endif

          
             <div class="col-md-6 form-group mb-3">
              <label><strong>Origen:</strong></label>
                   <input type="text" name="origen" id="origin-input" class="form-control" placeholder="" maxlength="600" value="{{$detalle->origen}}" >
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Destino1:</strong></label>
                   <input type="text" name="destino" id="destination-input" class="form-control observertarifatiposervicios" placeholder=""  maxlength="600" value="{{$detalle->destino}}">
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

            @if($servicio)

             <div class="col-md-6 form-group mb-3">
              <label><strong>Kilometros:</strong></label>
                   <input type="text" name="kilometros" id="kilometros"  value="{{$servicio->kilometros}}" class="form-control" placeholder="" maxlength="600" >
            </div>

              <div class="col-md-6 form-group mb-3">
              <label><strong>Tiempo:</strong></label>
                   <input type="text" name="tiempo" id="tiempo"  value="{{$servicio->tiempo}}" class="form-control" placeholder="" maxlength="600" >
            </div>


            <div class="col-md-6 form-group mb-3">
              <label><strong>Coordinadora:</strong></label>
              <select class="form-control" name="educador_coordinador">
              <?php echo Helper::selectEmpleadosDirectores($servicio->educador_coordinador) ?>
              </select>
            </div>


            @else

            <div class="col-md-6 form-group mb-3">
              <label><strong>Kilometros:</strong></label>
                   <input type="text" name="kilometros" id="kilometros"  value="" class="form-control" placeholder="" maxlength="600" >
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Tiempo:</strong></label>
                   <input type="text" name="tiempo" id="tiempo"  value="" class="form-control" placeholder="" maxlength="600" >
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Coordinadora:</strong></label>
              <select class="form-control" name="educador_coordinador">
                <?php echo Helper::selectEmpleadosDirectores() ?>
              </select>
            </div>

            @endif


            <div class="opciones_viaje col-md-6 form-group mb-3 ">
              <label class="radio radio-outline-warning">
                <input type="radio" name="tipo_viaje" value="1" <?php if(isset($servicio->tipo_viaje) && $servicio->tipo_viaje==1): ?> checked="true" <?php endif;?> ><span>Solo Ida</span><span class="checkmark"></span>
              </label>
              <label class="radio radio-outline-success">
                    <input type="radio" name="tipo_viaje" value="2" <?php if(isset($servicio->tipo_viaje) && $servicio->tipo_viaje==2): ?> checked="true" <?php endif;?>><span>Ida y Regreso</span><span class="checkmark"></span>
              </label>
              <label class="radio radio-outline-danger">
                  <input type="radio" name="tipo_viaje" value="3" <?php if(isset($servicio->tipo_viaje) && $servicio->tipo_viaje==3): ?> checked="true" <?php endif;?>><span>Regreso</span><span class="checkmark"></span>
              </label>
                 <label class="radio radio-outline-danger">
                  <input type="radio" name="tipo_viaje" value="4" <?php if(isset($servicio->tipo_viaje) && $servicio->tipo_viaje==4): ?> checked="true" <?php endif;?>><span>Multidestino</span><span class="checkmark"></span>
              </label>
            </div>


            <div class="opciones_viaje col-md-6 form-group mb-3 ">
              <label class="radio radio-outline-warning">
                <input type="radio" name="jornada" value="0" ><span>N/A</span><span class="checkmark"></span>
              </label>
              <label class="radio radio-outline-success">
                    <input type="radio" name="jornada" value="1"><span>Media Jornada</span><span class="checkmark"></span>
              </label>
              <label class="radio radio-outline-danger">
                  <input type="radio" name="jornada" value="2"><span>Jornada Completa</span><span class="checkmark"></span>
              </label>
                
            </div>

            <div class="opciones_disponibilidad col-md-6 form-group mb-3 ">
              <label class="checkbox checkbox-outline-primary">
                    <input type="checkbox" name="tiempo_adicional" id="tiempo_adicional" value="1"><span>Disponibilidad de Tiempo Adicional</span><span class="checkmark"></span>
                </label>
            </div>

            <div class="col-md-12 form-group mb-3" id="div-tiempo-adicional" style="display: none" >
              <label><strong> Horas de Espera Adicional</strong></label>
                   <input type="number" name="horas_adicionales" id="horas_adicionales" value="0" class="form-control" min="0" max="24" placeholder="0" maxlength="11" required>
            </div>

            <div class="col-md-12 form-group mb-3 div-auxiliar"  >
                <label><strong>Auxiliar</strong></label>
                    <input type="text" name="auxiliar" value="" class="form-control input-auxiliar" placeholder="" maxlength="255" required="false" >
            </div>
            <div class="col-md-12 form-group mb-3 div-auxiliar" >
                <label><strong>Valor Auxiliar:</strong></label>
                    <input type="number" name="valor_auxiliar" value="" class="form-control input-auxiliar" placeholder="0" maxlength="11" required="false" >
            </div>

            <div class="col-md-12 form-group mb-3 div-auxiliar"  >
                <label><strong>Auxiliar 2</strong></label>
                    <input type="text" name="auxiliar2" value="" class="form-control" placeholder="" maxlength="255"  >
            </div>
            <div class="col-md-12 form-group mb-3 div-auxiliar" >
                <label><strong>Valor Auxiliar 2:</strong></label>
                    <input type="number" name="valor_auxiliar2" value="" class="form-control" placeholder="0" maxlength="11"  >
            </div>

            <div class="col-md-12 form-group mb-3 div-auxiliar"  >
                <label><strong>Auxiliar 3</strong></label>
                    <input type="text" name="auxiliar3" value="" class="form-control" placeholder="" maxlength="255"  >
            </div>
            <div class="col-md-12 form-group mb-3 div-auxiliar" >
                <label><strong>Valor Auxiliar 3:</strong></label>
                    <input type="number" name="valor_auxiliar3" value="" class="form-control" placeholder="0" maxlength="11"  >
            </div>
            @if($servicio)
            <div class="col-md-12 form-group mb-3">
              <label><strong>Valor Servicio Conductor:</strong></label>
                   <input type="number" name="valor_conductor" id="valor_conductor" value="{{$servicio->valor_conductor}}" class="form-control" placeholder="0" maxlength="11" required>
            </div>

            <div class="col-md-12 form-group mb-3">
              <label><strong>Valor Servicio Cliente:</strong></label>
                   <input type="number" name="valor_cliente" id="valor_cliente" value="{{$servicio->valor_cliente}}" class="form-control" placeholder="0" maxlength="11" required>
            </div>
            @else
            <div class="col-md-12 form-group mb-3">
              <label><strong>Valor Servicio Conductor:</strong></label>
                   <input type="number" name="valor_conductor" id="valor_conductor" value="" class="form-control" placeholder="0" maxlength="11" required>
            </div>

            <div class="col-md-12 form-group mb-3">
              <label><strong>Valor Servicio Cliente:</strong></label>
                   <input type="number" name="valor_cliente" id="valor_cliente"  value="" class="form-control" placeholder="0" maxlength="11" required>
            </div>
            @endif
            @if ($servicio)
             <div class="col-md-12 form-group mb-3">
              <label><strong>Observaciones Servicio:</strong></label><br/>
                   <textarea class="form-control" name="observaciones" rows="3">{{$servicio->observaciones}}</textarea>
            </div>
            @else
             <div class="col-md-12 form-group mb-3">
              <label><strong>Observaciones Servicio:</strong></label><br/>
                   <textarea class="form-control" name="observaciones" rows="3"></textarea>
            </div>
            @endif
             <div class="col-md-12 form-group mb-3">
              <label><strong>Comentarios Servicio:</strong></label>
                  <textarea class="form-control" name="comentarios" rows="3"></textarea>
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
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBKkK3A_KT0_PyXE66Srs177YSm7WHSMqw&callback=initMap&libraries=places&v=weekly"
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

function calcularTarifaServicio(esdireccion){
  $('.observertarifatiposervicios').each(function(i,obj,esdireccion){
  var id_cliente=$('#id_cliente').val();
  var uri_sede=$('#uri_sede').val();
  var tipo_servicio=$('#tipo_servicio').val();
  var destination=$('#destination-input').val();
  var url=$('#url_tarifa_tiposervicio').val();
  $.get(url,{id_cliente:id_cliente,uri_sede:uri_sede,tipo_servicio:tipo_servicio,destination:destination},function(data){
    var response=JSON.parse(JSON.stringify(data));
     $('#valor_conductor').val(response.data.valor_conductor);
     $('#valor_cliente').val(response.data.valor_cliente);

  })


  });
 }

$(document).ready(function(){
  var TarifaTipoServicio=0;



  var vselect= $('#tipo_servicio').val();
  if(vselect==12){
    $('.div-auxiliar').show();
    $(".input-auxiliar").attr("required", "true");
  }else{
    $('.div-auxiliar').hide();
    $(".input-auxiliar").attr("required", "false");
  }
})

$('#tipo_servicio').change(function(e){
  e.preventDefault();
  var vselect=($(this).val());
  if(vselect==12){
    $('.div-auxiliar').show();
    $(".div-auxiliar").attr("required", "true");

  }else{
    $('.div-auxiliar').hide();
    $(".div-auxiliar").attr("required", "false");
  }

 })

 $('.observertarifatiposervicios').change(function(e){
  e.preventDefault();
  var valor=$(this).val();
  var tipoNumber=Number(valor);
  if(isNaN(tipoNumber)){
    calcularTarifaServicio(true);
  }else{
    calcularTarifaServicio(false);
  }
  
 })

$('#id_cliente').select2({
   theme: 'bootstrap-5'
 });

 $('#id_pasajero').select2({
   theme: 'bootstrap-5'
 });

 $('#conductor_pago').select2({
   theme: 'bootstrap-5'
 });
 
 $('#conductor_servicio').select2({
   theme: 'bootstrap-5'
 });


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
        id_cliente: {required:true},
        placa: {required:true},
        id_conductor_pago:{required:true},
        id_conductor_servicio: {required:true},
        id_pasajero:{required:true},
        tipo_servicio:{required:true},
        origen: { required:true },
        destino: { required:true },
        tipo_viaje:{ required:true },
        valor_conductor:{ required:true },
        valor_cliente:{ required:true },
        uri_sede:{ required:true }
        
        
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

$('.time').datetimepicker({
            "allowInputToggle": true,
            "format": "HH:mm",
        });


</script>
@endsection