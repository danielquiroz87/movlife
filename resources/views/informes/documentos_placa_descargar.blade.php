<?php 
$clasificacion_vehiculo=array(
  '1'=>'Intermunicipal',
  '2'=>'Especial',
  '3'=>'Particular',
  '4'=>'Taxi',
  '5'=>'Otro',
  ''=>'NA'
);
?>
<table border="1" style="border-collapse: collapse;border-spacing: 4px;">

<thead>
  <tr>
    <th>Placa</th>
    <th>Modelo</th>
    <th>Clase</th>
    <th>Marca</th>
    <th>Clasificación</th>
    <th>Número Pasajeros</th>
                      

    <th colspan=3>TARJETA DE OPERACIÓN</th>
    <th colspan=3>SOAT</th>
    <th colspan=3>REVISION TECNOMECANICA</th>
    <th colspan=3>REVISION PREVENTIVA</th>
    <th colspan=3>POLIZA RCC</th>
    <th colspan=3>POLIZA RCE</th>
    <th colspan=3>POLIZA SEGURO TODO RIESGO </th>

  </tr>
  <tr>

      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>

      <?php for($i=1;$i<8;$i++):?>
          <td>FIN DE VIGENCIA</td>
          <td>FECHA EXPEDICIÓN</td>
          <td>DIAS RESTANTES</td>
      <?php endfor;?>
  </tr>
</thead>
<tbody>
    @foreach ($vehiculos as $vehiculo)
    <?php $documentos_vehiculo=Helper::getDocumentosVehiculo($vehiculo->placa);?>
  <tr>
    <td>{{$vehiculo->placa}}</td>
    <td>{{$vehiculo->modelo}}</td>
    <td>{{$vehiculo->clase->nombre}}</td>
    <td>{{$vehiculo->marca->nombre}} {{$vehiculo->linea}}</td>
    <td>
      {{$clasificacion_vehiculo[$vehiculo->id_vehiculo_uso]}}
    </td>
    <td>{{$vehiculo->capacidad_pasajeros}}</td>

    <td>{{$documentos_vehiculo[$vehiculo->placa][13]['fecha_vencimiento']}}</td>
    <td>{{$documentos_vehiculo[$vehiculo->placa][13]['fecha_expedicion']}}</td>
    <td <?php $vencimiento13=Helper::getFechasDias($documentos_vehiculo[$vehiculo->placa][13]['fecha_vencimiento']);
     if(str_contains($vencimiento13,'Vigente')){ $class='verde'; } else {$class='rojo';} ?> class="{{$class}}" >
      {{$vencimiento13}}
    </td>
    <td>{{$documentos_vehiculo[$vehiculo->placa][9]['fecha_vencimiento']}}</td>
    <td>{{$documentos_vehiculo[$vehiculo->placa][9]['fecha_expedicion']}}</td>
    <td <?php $vencimiento9=Helper::getFechasDias($documentos_vehiculo[$vehiculo->placa][9]['fecha_vencimiento']);
     if(str_contains($vencimiento9,'Vigente')){ $class='verde'; } else {$class='rojo';} ?> class="{{$class}}" >
      {{$vencimiento9}}
    </td>
    <td>{{$documentos_vehiculo[$vehiculo->placa][10]['fecha_vencimiento']}}</td>
    <td>{{$documentos_vehiculo[$vehiculo->placa][10]['fecha_expedicion']}}</td>
    <td <?php $vencimiento10=Helper::getFechasDias($documentos_vehiculo[$vehiculo->placa][10]['fecha_vencimiento']);
     if(str_contains($vencimiento10,'Vigente')){ $class='verde'; } else {$class='rojo';} ?> class="{{$class}}" >
      {{$vencimiento10}}
    </td>
    <td>{{$documentos_vehiculo[$vehiculo->placa][14]['fecha_vencimiento']}}</td>
    <td>{{$documentos_vehiculo[$vehiculo->placa][14]['fecha_expedicion']}}</td>
    <td <?php $vencimiento14=Helper::getFechasDias($documentos_vehiculo[$vehiculo->placa][14]['fecha_vencimiento']);
     if(str_contains($vencimiento14,'Vigente')){ $class='verde'; } else {$class='rojo';} ?> class="{{$class}}" >
      {{$vencimiento14}}
    </td>
    <td>{{$documentos_vehiculo[$vehiculo->placa][11]['fecha_vencimiento']}}</td>
    <td>{{$documentos_vehiculo[$vehiculo->placa][11]['fecha_expedicion']}}</td>
    <td <?php $vencimiento11=Helper::getFechasDias($documentos_vehiculo[$vehiculo->placa][11]['fecha_vencimiento']);
     if(str_contains($vencimiento11,'Vigente')){ $class='verde'; } else {$class='rojo';} ?> class="{{$class}}" >
      {{$vencimiento11}}
    </td>
    <td>{{$documentos_vehiculo[$vehiculo->placa][12]['fecha_vencimiento']}}</td>
    <td>{{$documentos_vehiculo[$vehiculo->placa][12]['fecha_expedicion']}}</td>
    <td <?php $vencimiento12=Helper::getFechasDias($documentos_vehiculo[$vehiculo->placa][12]['fecha_vencimiento']);
     if(str_contains($vencimiento12,'Vigente')){ $class='verde'; } else {$class='rojo';} ?> class="{{$class}}" >
      {{$vencimiento12}}
    </td>

    <td>{{$documentos_vehiculo[$vehiculo->placa][30]['fecha_vencimiento']}}</td>
    <td>{{$documentos_vehiculo[$vehiculo->placa][30]['fecha_expedicion']}}</td>
    <td <?php $vencimiento30=Helper::getFechasDias($documentos_vehiculo[$vehiculo->placa][30]['fecha_vencimiento']);
     if(str_contains($vencimiento30,'Vigente')){ $class='verde'; } else {$class='rojo';} ?> class="{{$class}}" >
      {{$vencimiento30}}
    </td>

   
  </tr>
    @endforeach
</tbody>
<tfoot>
    <tr>
    

    </tr>
</tfoot>
</table>