@extends('layouts.master')

@section('main-content')

<style type="text/css">
  
/* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
.chk_guardartarifa{
  display: none;
}

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
          <li><a href="{{route('cotizaciones')}}">Cotizaciones</a></li>
          <li>Editar Cotización</li>
      </ul>
  </div>
  <div class="separator-breadcrumb border-top"></div>

<div class="row">

   @if ($message = Session::get('flash_message'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button> 
            <strong>{{ $message }}</strong>
    </div>
  @endif

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
                <h3 class="card-title mb3">Editar Cotización / Usuario: {{$cotizacion->user->name }}</h3>
  
 <div class="box box-info">
    <form action="{{route('cotizaciones.save')}}" method="POST" id="cotizacion-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="{{$cotizacion->id}}">
      
      <input type="hidden" name="is_new" value="false">
      <input type="hidden" id="finalizada" name="finalizada" value="{{$cotizacion->finalizada}}">
      <input type="hidden" name="id_user" id="id_user" value="{{$cotizacion->user_id}}" >

        <div class="row">

         
           <div class="col-md-6 form-group mb-3">
              <label><strong>Cliente:</strong></label>
                    <select name="id_cliente" class="form-control">
                      <?php echo Helper::selectClientes($cotizacion->id_cliente) ?>
                    </select>
            </div>
            <div class="col-md-6 form-group mb-3">
              <label><strong>Contacto Nombres y/o Razón Social:</strong></label>
                <input type="text" name="contacto_nombres" value="{{$cotizacion->contacto_nombres}}" class="form-control" placeholder="" maxlength="255" >
            </div>
            <div class="col-md-6 form-group mb-3">
              <label><strong>Contacto Teléfono</strong></label>
                <input type="text" name="contacto_telefono" value="{{$cotizacion->contacto_telefono}}" class="form-control" placeholder="" maxlength="255" >
            </div>
            <div class="col-md-6 form-group mb-3">
              <label><strong>Contacto Email</strong></label>
                <input type="text" name="contacto_email" value="{{$cotizacion->contacto_email}}" class="form-control" placeholder="" maxlength="255" >
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Forma de Pago / Días:</strong></label>
              @if(auth()->user()->superadmin==1  )
              <input type="number" name="forma_pago" class="form-control" placeholder="Forma de Pago" value="{{$cotizacion->forma_pago}}" step="15" max="90" min="15" />
              @else
              <input type="number" name="forma_pago" class="form-control" placeholder="Forma de Pago" value="{{$cotizacion->forma_pago}}" max="30" min="30" readonly=true />

              @endif
            </div>
          
            <div class="">
            </div>
            
            <div class="col-md-6 form-group mb-3">
              <label><strong>Fecha Cotización:</strong></label>
                   <input type="date" name="fecha_cotizacion"  class="form-control" placeholder="" maxlength="20" id="fecha_cotizacion" value="{{ date('Y-m-d', strtotime($cotizacion->fecha_cotizacion)) }}" required>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Fecha Vencimiento:</strong></label>
                   <input type="date" name="fecha_vencimiento" class="form-control" placeholder="" maxlength="20" value="{{ date('Y-m-d', strtotime($cotizacion->fecha_vencimiento)) }}""  required>
            </div>

              <div class="col-md-6 form-group mb-3">
              <label><strong>Fecha Servicio:</strong></label>
                   <input type="date" name="fecha_servicio" value="{{$cotizacion->fecha_servicio}}" class="form-control" placeholder="" maxlength="20" required>
            </div>
          
             <div class="col-md-12 form-group mb-3">
              <label><strong>Descripción:</strong></label>
                   <input type="text" name="descripcion"  class="form-control" placeholder="" maxlength="255" value="{{$cotizacion->descripcion}}"required>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Hora Recogida (Desde):</strong></label>
                   <input type="time" name="hora_recogida" value="{{$cotizacion->hora_recogida}}" class="form-control" max="23:59:59" min="00:00:00"  required >
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Hora Estimada Salida (Hasta):</strong></label>
                   <input type="time" name="hora_salida" value="{{$cotizacion->hora_salida}}" class="form-control" max="23:59:59" min="00:00:00"  required >
            </div>


          
            <div class="col-md-6 form-group mb-3">
              <label><strong>Origen:</strong></label>
                @if(isset($direcciones[0]))
                    <input type="text" name="origen" id="origin-input" class="form-control matchTarifa" placeholder="" value="{{$direcciones[0]->origen}}"  >
                @else
                    <input type="text" name="origen" id="origin-input" class="form-control" placeholder=""  >
                @endif
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Destino:</strong></label>
                @if(isset($direcciones[0]))
                   <input type="text" name="destino" id="destination-input" class="form-control matchTarifa" placeholder="" value="{{$direcciones[0]->destino}}" >
                @else
                  <input type="text" name="destino" id="destination-input" class="form-control" placeholder="" >
                @endif
            </div>

             <div class="col-md-6 form-group mb-3">
              <label><strong>Destino2:</strong></label>
                   <input type="text" name="destino2" id="destination-input2" value="" class="form-control" placeholder=""  >
            </div>

             <div class="col-md-6 form-group mb-3">
              <label><strong>Destino3:</strong></label>
                   <input type="text" name="destino3" id="destination-input3" value="" class="form-control" placeholder=""  >
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Destino4:</strong></label>
                   <input type="text" name="destino4" id="destination-input4" value="" class="form-control" placeholder=""  >
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Destino5:</strong></label>
                   <input type="text" name="destino5" id="destination-input5" value="" class="form-control" placeholder=""  >
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Kilometros:</strong></label>
                   <input type="text" name="kilometros" id="kilometros" value="" class="form-control" readonly="true"  >
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Tiempo:</strong></label>
                   <input type="text" name="tiempo" id="tiempo" value="" class="form-control" placeholder="" readonly="true" >
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Tipo Vehiculo:</strong></label>
                <select name="tipo_vehiculo" id="tipo_vehiculo" class="form-control select-busqueda matchTarifa">
                  <?php echo Helper::selectClaseVehiculos($cotizacion->tipo_vehiculo) ?>
                </select>
            </div>
            <div class="col-md-6 form-group mb-3">
              <label><strong>Jornada:</strong></label>
                  <select name="jornada" id="jornada" class="form-control select-busqueda matchTarifa">
                  <option value="1" @if($cotizacion->jornada==1) selected="selected" @endif>1 Hora</option>
                  <option value="2" @if($cotizacion->jornada==2) selected="selected" @endif>2 Horas</option>
                  <option value="3" @if($cotizacion->jornada==3) selected="selected" @endif>3 Horas</option>
                  <option value="4" @if($cotizacion->jornada==4) selected="selected" @endif>4 Horas</option>
                  <option value="5" @if($cotizacion->jornada==5) selected="selected" @endif>5 Horas</option>
                  <option value="6" @if($cotizacion->jornada==6) selected="selected" @endif>Media Jornada</option>
                  <option value="7" @if($cotizacion->jornada==7) selected="selected" @endif>Extendida</option>
                  <option value="8" @if($cotizacion->jornada==8) selected="selected" @endif>Completa</option>
                </select>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Cantidad:</strong></label>
                   <input type="number" name="cantidad" id="cantidad" value="{{$cotizacion->cantidad}}" class="form-control" placeholder="" maxlength="100" required>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Valor Unitario:</strong></label>
                   <input type="number" name="valor_unitario" id="valor_unitario" value="{{$cotizacion->valor}}" class="form-control" placeholder="0" maxlength="11" required>
            </div>


            <div class="col-md-12 form-group mb-3">
              <label><strong>Total:</strong></label>
                   <input type="number" name="total" id="total" value="{{$cotizacion->total}}" class="form-control" placeholder="0" maxlength="11" disabled="disabled" required>
            </div>

         

            <div class="opciones_viaje col-md-6 form-group mb-3 ">
              <label class="radio radio-outline-warning">
                <input type="radio" name="tipo_viaje" class="matchTarifa" value="1" @if($cotizacion->tipo_viaje==1) checked="checked" @endif ><span>Solo Ida</span><span class="checkmark"></span>
              </label>
              <label class="radio radio-outline-success">
                    <input type="radio" name="tipo_viaje" class="matchTarifa" value="2" @if($cotizacion->tipo_viaje==2 ) checked="checked" @endif ><span>Ida y Regreso</span><span class="checkmark"></span>
              </label>
              <label class="radio radio-outline-danger">
                  <input type="radio" name="tipo_viaje" class="matchTarifa" value="3" @if($cotizacion->tipo_viaje==3 ) checked="checked" @endif><span>Regreso</span><span class="checkmark"></span>
              </label>
            </div>


            
            <div class="chk_guardartarifa col-md-6 form-group mb-3 ">
              <label class="checkbox checkbox-outline-primary">
                    <input type="checkbox" name="guardar_tarifa" id="guardar_tarifa" value="1" ><span>Guardar Tarifa</span><span class="checkmark"></span>
                </label>
            </div>
            

          @if($cotizacion->finalizada==0)

            <div class="col-md-12 form-group mb-3">
              <button id="btn-submit" type="button" data-url={{route('cotizaciones.save.item',$cotizacion->id)}} class="btn btn-success">Guardar Item Dirección</button>
            </div>
          
          @endif




            <div class="opciones_disponibilidad col-md-6 form-group mb-3 ">
              <label class="checkbox checkbox-outline-primary">
                    <input type="checkbox" name="tiempo_adicional" id="tiempo_adicional" value="1"  @if($cotizacion->tiempo_adicional==1 )  checked="checked"  @endif><span>Disponibilidad de Tiempo Adicional</span><span class="checkmark"></span>
                </label>
            </div>


            <div class="col-md-12 form-group mb-3" id="div-tiempo-adicional" >
              <label><strong> Horas de Espera Adicional</strong></label>
                   <input type="number" name="horas_tiempo_adicional" id="horas_tiempo_adicional" value="{{$cotizacion->horas_tiempo_adicional}}" class="form-control" min="0" max="24" placeholder="0" maxlength="11" required>
            </div>
          

            <div class="col-md-12 form-group mb-3">
 
            <table  class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
              <thead>
              <th>Origen</th>
              <th>Destino</th>
              <th>Cantidad</th>
              <th>Valor</th>
              <th>Total</th>
              <th colspan="2">Acciones</th>
              </thead>
              <tbody>
             @foreach ($direcciones as $direccion)
             <tr>
              <td>
                {{$direccion->origen}}
              </td>  
              <td>
                {{$direccion->destino}}
                @if($direccion->destino2){{$direccion->destino2}}, @endif
                @if($direccion->destino3){{$direccion->destino3}}, @endif
                @if($direccion->destino4){{$direccion->destino4}}, @endif
                @if($direccion->destino5){{$direccion->destino5}} @endif
              </td>
              <td>{{$direccion->cantidad}}</td>
              <td>{{number_format($direccion->valor,2,',','.')}}</td>
              <td>{{number_format($direccion->total,2,',','.')}}</td>
              <td><a href="{{route('servicios.new.fromaddress',[$direccion->id])}}">Nueva Orden De Servicio</a></td>
              <td> <a class="text-danger mr-2 eliminar_all" href="{{route('cotizaciones.delete.detalle', $direccion->id)}}" title="Eliminar"><i class="nav-icon i-Close-Window font-weight-bold"></i></a></td>
              </tr>
              @endforeach
              </tbody>
              
            </table>
            </div>
             
            <div class="col-md-6 form-group mb-3">
              <label><strong>Estado:</strong></label>
                  <select name="estado" id="estado" class="form-control">
                  <option value="" selected="selected">Seleccione</option>
                  <option value="1" @if($cotizacion->estado==1) selected="selected" @endif>Aprobada</option>
                  <option value="2" @if($cotizacion->estado==2) selected="selected" @endif>Pendiente</option>
                  <option value="3" @if($cotizacion->estado==3) selected="selected" @endif>Modificada</option>
                  <option value="4" @if($cotizacion->estado==4) selected="selected" @endif>Cancelada</option>
                  <option value="5" @if($cotizacion->estado==5) selected="selected" @endif>Rechazada</option>
                  
                </select>
            </div>
            
            <div class="col-md-6 form-group mb-3">
              <label><strong>Foto Vehiculo:</strong></label>
                  @if($cotizacion->foto_vehiculo!="")
                  <img src="{{asset($cotizacion->foto_vehiculo)}}" style="max-width: 200px;margin:10px" >
                  @else
                  
                  @endif
                  <input type="file" name="foto" value="" id="foto" class="form-control" >
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Servicio Id:</strong></label>
                   <input type="text" name="servicio_id" id="servicio_id" value="{{$cotizacion->servicio_id}}" class="form-control" placeholder=""  >
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Anticipo Id:</strong></label>
                   <input type="text" name="anticipo_id" id="anticipo_id" value="{{$cotizacion->anticipo_id}}" class="form-control" placeholder=""  >
            </div>
            
             <div class="col-md-12 form-group mb-3">
              <label><strong>Observaciones Servicio:</strong></label><br/>
                   <textarea class="form-control" name="observaciones" rows="3">{{$cotizacion->observaciones}}</textarea>
            </div>
             <div class="col-md-12 form-group mb-3">
              <label><strong>Comentarios Servicio:</strong></label>
                  <textarea class="form-control" name="comentarios" rows="3">{{$cotizacion->comentarios}}</textarea>
            </div>
            
            <div class="col-xs-12 col-sm-12 col-md-12 ">

            @if($cotizacion->finalizada==0)
                <button id="submit-guardar" type="submit" id="finalizar" value="guardar" class="btn btn-primary">Guardar Cotización</button>

                  <button id="submit-finalizar" type="submit" id="finalizar" value="finalizar" class="btn btn-success">Cerrar Cotización</button>
            @else


            @endif
                <a href="{{ route('cotizaciones') }}" class="btn btn-danger">Listar Cotizaciones</a>
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


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBKkK3A_KT0_PyXE66Srs177YSm7WHSMqw&callback=initMap&libraries=places&v=weekly" async>
</script>


<script type="text/javascript">

matchTarifa=false;
function buscarTarifa(){
    var data=$('#cotizacion-new-form').serializeArray();
    $.post('/cotizaciones/match/tarifa',data,function(response){
      $('#valor_unitario').val(response.data.vcliente);
      if(response.data.id==0){
        matchTarifa=true;
        $('.chk_guardartarifa').show('200');
      }else{
        $('.chk_guardartarifa').hide('200');
      }
    })
}
$('#valor_unitario').blur(function(){
  if(!matchTarifa){
    buscarTarifa();
  }
});

$('.matchTarifa').change(function(){
  matchTarifa=false;
  buscarTarifa();
});
$('#origin-input').blur(function(){
  matchTarifa=false;
  buscarTarifa();
});
$('#destination-input').blur(function(){
  matchTarifa=false;
  buscarTarifa();
});

renderMap=false;
handlerMap=false;

class AutocompleteDirectionsHandler {
  map;
  originPlaceId;
  destinationPlaceId;
  travelMode;
  directionsService;
  directionsRenderer;
  totalDistance;
  totalDuration;
  geocoderOrigin;
  geocoderDest;

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
    this.geocoderOrigin = new google.maps.Geocoder();
    this.geocoderDest = new google.maps.Geocoder();



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
    const me = this;
    
    if (!this.originPlaceId || !this.destinationPlaceId) {
       
        const originInput = document.getElementById("origin-input");
        const destinationInput = document.getElementById("destination-input");
       
        this.geocoderOrigin.geocode( { 'address': originInput.value}, function(results, status) {
        
          if (status == google.maps.GeocoderStatus.OK) {
            me.originPlaceId=results[0].place_id;
            //me.route();
          } 
        });

        this.geocoderDest.geocode( { 'address': destinationInput.value}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            me.destinationPlaceId=results[0].place_id;
             me.route();
          } 
        });
        //this.setupPlaceChangedListener(originAutocomplete, "ORIG");
        //this.setupPlaceChangedListener(destinationAutocomplete, "DEST");
    }


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
    console.log("distance",legs[0].distance.text);
    $('#kilometros').val(legs[0].distance.text);
    $('#tiempo').val(legs[0].duration.text);

    
  }
}


 function initMap() {
   map = new google.maps.Map(document.getElementById("map"), {
    mapTypeControl: false,
    center: { lat: 4.60971, lng: -74.08175 },
    zoom: 13,
  });
  handlerMap=new AutocompleteDirectionsHandler(map);
     setTimeout(function () {
        handlerMap.route();
      }, 1000);
  //handlerMap.route();
}



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
    $('#div-tiempo-adicional').hide();
  }
})


$('#btn-submit').click(function(e){
  e.preventDefault();
  var data=$('#cotizacion-new-form').serializeArray();
  var url=$(this).data('url');
  console.log(data);

  $.post(url,data,function(response){
      Swal
      .fire({
          title: "Ok",
          text: "Registro guardado exitosamente",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: "Ok",
          cancelButtonText: "Cerrar",
      })
      .then(resultado => {
          if (resultado.value) {
              // Hicieron click en "Sí"
            location.reload();
          } else {
              // Dijeron que no
            location.reload();
          }
      });
  });

})


$('#submit-finalizar').click(function(e){
  e.preventDefault();

  Swal.fire({
  title: 'Está seguro de cerrar la cotización?',
  icon: 'warning',
  showCancelButton: true,
  confirmButtonText: `Si`,
  cancelButtonText: `No`,

}).then((result) => {
  /* Read more about isConfirmed, isDenied below */
  if (result.isConfirmed) {
    $("#finalizada").val(1);
    $("#submit-guardar").click();
  } else  {
    Swal.fire('Solicitud Cancelada', '', 'info')
  }
})



    
})

$("#submit-guardar").validate({ 
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