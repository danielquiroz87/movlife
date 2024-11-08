@extends('layouts.master')

@section('main-content')
<style type="text/css">
  #map {
    height: 100%;
    width: 100%;
    display: block;
    min-height: 600px;
  }
</style>
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="/#">Mapas</a></li>
      </ul>
  </div>
  <div class="separator-breadcrumb border-top"></div>

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


  <div class="row">

    <div class="col-md-12 mb-4">
        <div class="card text-left">
              <div class="card-body">
                <h3>Mi Localización</h3>
                <form action="{{route('conductores.location.save')}}" method="POST" id="form-map" enctype="multipart/form-data" >
                  
                </form>
                <div id="map"  >
                </div>

              </div>
              <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
  </div>


        


@endsection

@section('bottom-js')

<script type="text/javascript">

      let map;
      let marker;
      
      function initMap(lat, lng) {
        // Inicializa el mapa con la primera ubicación
        const location = { lat: lat, lng: lng };
        map = new google.maps.Map(document.getElementById("map"), {
          zoom: 15,
          center: location
        });
        marker = new google.maps.Marker({
          position: location,
          map: map
        });
      }

      function updatePosition(lat, lng) {
        const newLocation = { lat: lat, lng: lng };
        marker.setPosition(newLocation);
        map.setCenter(newLocation);
        var url=$('#form-map').attr('action');

        $.post(url,{lat:lat,long:lng},function(response){
          console.log(response);
        })
        console.log(newLocation);
      }

      function getLocation() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function (position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            updatePosition(lat, lng);
          }, function(error) {
            console.error("Error al obtener la ubicación: ", error);
          });
        } else {
          console.error("La geolocalización no es soportada por este navegador.");
        }
      }

      window.onload = function() {
        // Obtener la ubicación inicial y mostrar el mapa
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function (position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            initMap(lat, lng);
          });
        }

        // Actualizar la ubicación cada 5 segundos
        setInterval(getLocation, 5000);
      }
   



</script>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBKkK3A_KT0_PyXE66Srs177YSm7WHSMqw&callback=getLocation&libraries=places&v=weekly" >
</script>  
@endsection
