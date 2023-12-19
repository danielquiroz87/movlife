<?php
$fecha=$al->fecha;
$next_revision=date('Y-m-d',strtotime('+ 1 day',strtotime($fecha)));

?>
<!DOCTYPE html>
<html>
<head>
  <title>Alistamiento Diario</title>
  

<style type="text/css">
  body{
    margin: 20px;
    max-width: 980px;
    width: 100%;
    margin: auto;
    font-size:14px;
    font-family:"Times New Roman";
    font-style: normal;

  }
 table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 10px;
}
 .recuadro{
  border: 1px solid black;
  margin: 4px;
  padding: 2px;
  width: 20px;
  text-align: center;
  width: 20px;
  display:inline-block;
 }
 .text-left{
  text-align: left;
 }

</style>
</head>
<body>
<div class="row">

<div class="col-md-8 mb-4">
      <div class="card text-left">
          <div class="card-body">
                
 <div class="box box-info" style="margin-right: 20px">
    
        
        <div class="row">
          <table width="100%" border="0" style="border-collapse: collapse; text-align: center; border: none" >
           <thead style="border: none;" >
              <tr>
              <td style="border: 0;width: 20%" >
                <p>
                  <img src="https://app.movlife.co/images/movlife.png" width="160" >
                </p>
              </td>
              <td colspan="3" style="border: 0; text-align: center;width: 60%"><p>MOVLIFE S.A.S<br/>
                NIT. 901.184.493-5<br/><br/>
                <strong>REVISIÓN TÉCNICA <br/>ALISTAMIENTO DIARIO</strong>
                <div><span class="recuadro">B</span>BUENO PARA OPERAR <br/>
                     <span class="recuadro">M</span> MALO PARA OPERAR
                  </div>
                </p>
              </td>
              <td colspan="" style="border: 0;width: 20%">
                {{$al->fecha}}<br/>
                No. REV. {{$al->id}} <br/>
                Kilometros: {{$al->kilometros}} Km
              </td>
            </tr>

          </table>



          <table width="100%" border="1" style="border-collapse: collapse; text-align: center; ">
            
            <thead>
              <tr>
                <td style="border-bottom: none;"><strong>Placa:</strong> {{$vehiculo->placa}}</td>
                <td style="border-bottom: none;"><strong>Nro Interno:</strong> {{$vehiculo->codigo_interno}}</td>
                <td style="border-bottom: none;"><strong>Modelo:</strong> {{$vehiculo->modelo}}</td>
                <td style="width: 118px; border-bottom: none;"><strong>Clase:</strong> {{$vehiculo->clase->nombre}}</td>
                <td style="width: 120px; border-bottom: none;"><strong>Color:</strong> {{$vehiculo->color}}</td>


              </tr>
           
          </thead>
        </table>
        <table width="100%" border="1" style="border-collapse: collapse; text-align: center; border-top:none;border-bottom: none ">
           <tr>
              <td colspan="3" width="80%" style="font-weight: bold;">Aspectos</td>
              <td style="font-weight: bold;" width="118">B</td>
              <td style="font-weight: bold;" width="118">M</td>
              
            </tr>
          
          <tbody border="1" style="border-collapse: collapse; text-align: center;">
          <?php foreach($categorias as $key=>$categoria):?>
              <?php $cont=1;?>
                <tr>
                   <td colspan="5" width="100%" style="background-color: #ccc; text-align: left;font-weight: bold;"> {{$key}}</td>
                </tr>
                  @foreach($categoria as $item)
                  <tr>
                    <td colspan="3" width="80%" >
                        <span>{{$cont++}} - {{$item->item}}</span>
                      </td>
                     @if($item->check==1)
                      <td width="120">
                        <span>&#x2713;</span>
                      </td>
                      <td>
                      </td>
                     @else
                     <td width="120">
                        
                      </td>
                      <td width="120">
                        <span style="font-weight: bold;">&#10005;</span>
                      </td>
                     @endif 
                  </tr>
                  @endforeach
                   
                      
          <?php endforeach;?>
          <tr>
              <td colspan="5" style="background-color:#ccc;text-align: left; "><strong>Observaciones Conductor</strong></td>
             </tr>         
             <tr>
              <td colspan="5"> {{$al->observaciones_conductor}}</td>
             </tr>

              <tr>
              <td colspan="5" style="background-color:#ccc;text-align: left; "><strong>Observaciones Movlife</strong></td>
             </tr>         
             <tr>
              <td colspan="5">{{$al->observaciones_movlife}}</td>
          </tr>
          <tr>
            <td colspan="5" style="background-color:#ccc;text-align: left; ">
              <strong>Valoración del Vehiculo para Operar</strong>
            </td>
          </tr>
            <tr><td colspan="3" class="text-left"><strong>Razón Social:</strong> {{$empresa}}</td><td colspan="2" class="text-left"><strong>C.C. / NIT:</strong> {{$nit}}</td></tr>
            <tr>

            <td colspan="3" class="text-left"><strong>El Vehículo Puede Operar:</strong>
              @if($al->aprobado==1)
                <span class="recuadro"> X </span> SI
                <span class="recuadro"> &nbsp;</span> NO
              @else
                <span class="recuadro"> &nbsp; </span> SI
                <span class="recuadro"> X </span> NO
              @endif
            </td >

              <td colspan="2" class="text-left"><strong>Próxima Revisión:</strong> {{$next_revision}}</td>
            </tr>
            <tr>
              <td colspan="3" class="text-left"><strong>Revisor:</strong> {{$nombre_revisor}}</td>
              <td colspan="2" style="text-align: center; vertical-align: bottom;"  rowspan="2" >
                <strong>
                _____________________________________<br/>
              Firma Revisor</strong>
              </td>
            </tr>
            <tr>
              <td colspan="3" class="text-left">
                <strong><strong>CC:</strong> {{$cedula_revisor}}
              </td>
              
            </tr>
       
            
          </tbody>
          </table>

          
        </div>


</div>
             
   </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->


            <!-- /.card -->
          </div>


</div>

</body>
