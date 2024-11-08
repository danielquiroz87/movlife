@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('customers')}}">Clientes</a></li>
          <li>Editar Cliente</li>
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

<div class="col-md-8 mb-4">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Editar Cliente</h3>

                <ul class="nav nav-tabs" id="myIconTab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active show" id="general-icon-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">
                        <i class="nav-icon i-Home1 mr-1"></i>General</a>
                    </li>
                  
                    <li class="nav-item">
                      <a class="nav-link" id="documentos-icon-tab" data-toggle="tab" href="#documentos" role="tab" aria-controls="documentos" aria-selected="false"><i class="nav-icon i-Home1 mr-1"></i> Documentos</a>
                    </li>
                      </li>
                  
                    <li class="nav-item"><a class="nav-link" id="historial-documentos-icon-tab" data-toggle="tab" href="#historial-documentos" role="tab" aria-controls="historial" aria-selected="false">
                      <i class="nav-icon i-Home1 mr-1"></i> Historial Documentos</a>
                  
                  </ul>
                  <div class="tab-content">

<div class="tab-pane fade active show" id="general" role="tabpanel" aria-labelledby="general-icon-tab">

 <div class="box box-info">
    <form action="{{route('customers.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="{{$cliente->id}}">
      <input type="hidden" name="is_new" value="false">

        <div class="row">
            <div class="col-md-6 form-group mb-3">
              <label><strong>Documento / Nit:</strong></label>
                   <input type="text" name="documento"  class="form-control" placeholder="000000" maxlength="11" required value="{{$cliente->documento}}">
            </div>

             <div class="col-md-6 form-group mb-3">
              <label><strong>Nombre Cliente / Razón Social:</strong></label>
                   <input type="text" name="razon_social"  class="form-control" placeholder="000000" maxlength="255" required value="{{$cliente->razon_social}}">
            </div>
            <div class="col-md-6 form-group mb-3">
                  <label><strong>Nombres Contacto</strong></label>
                  <input type="text" name="nombres" class="form-control" id="nombres" placeholder="Nombres" required value="{{$cliente->nombres}}">
            </div>
            <div class="col-md-6 form-group mb-3">
                  <label><strong>Apellidos Contacto</strong></label>
                  <input type="text" name="apellidos" class="form-control" id="apellidos" placeholder="Apellidos" required value="{{$cliente->apellidos}}">
            </div>

           <div class="col-md-6 form-group mb-3">
              <label><strong>Teléfono:</strong></label>
              <input type="number" name="telefono" class="form-control" placeholder="000000"
                         maxlength="10" required value="{{$cliente->telefono}}">
            </div>
             <div class="col-md-6 form-group mb-3">
                   <label> <strong>Celular:</strong></label>
                    <input type="number" name="celular" class="form-control" placeholder="0000000000"
                        maxlength="255" required value="{{$cliente->celular}}">
            </div>
            <div class="col-md-6 form-group mb-3">
                   <label> <strong>Whatsapp:</strong></label>
                    <input type="number" name="whatsapp" class="form-control" placeholder="0000000000"
                         maxlength="255" required value="{{$cliente->whatsapp}}">
            </div>
           <div class="col-md-6 form-group mb-3">
              <label><strong>Departamento:</strong></label>
                    <select class="form-control departamentos" name="departamento_id">
                      <?php echo Helper::selectDepartamentos($direccion->departamento_id) ?>
                    </select>
                   
            </div>
           
           <div class="col-md-6 form-group mb-3">
              <label><strong>Ciudad:</strong></label>
                  <select class="form-control municipios" name="ciudad_id">
                     <?php echo Helper::selectMunicipios($direccion->departamento_id,$direccion->ciudad_id) ?>
                  </select>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label><strong>Dirección:</strong></label>
                   <input type="text" name="direccion" class="form-control" placeholder="" maxlength="20" required value="{{$direccion->direccion1}}">
            </div>
           
            <div class="col-md-6 form-group mb-3">
              <label><strong>Detalle Dirección:</strong></label>
                   <input type="text" name="direccion_detalle" class="form-control" placeholder="" maxlength="20"  value="{{$direccion->direccion2}}">
            </div>
            <div class="col-md-6 form-group mb-3">
              <label><strong>Plazo Pagos:</strong></label>
                   <input type="number" name="plazo_pagos" class="form-control" placeholder="" maxlength="3"  value="{{$cliente->plazo_pagos}}">
            </div>

            <div class="col-md-6 form-group mb-3">
                    <label><strong>Email:</strong></label>
                    <input type="text" name="email" class="form-control" placeholder="example@email.com"
                         maxlength="255"  value="{{$cliente->email_contacto}}">
            </div>
            <div class="col-md-6 form-group mb-3">
                   <label> <strong>Nuevo Password:</strong></label>
                    <input type="text" name="password" class="form-control" placeholder=""
                        value="" autocomplete="off" maxlength="20">
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('customers') }}" class="btn btn-danger">Cancelar</a>
            </div>
        </div>

    </form>

</div>
          
</div>

<div class="tab-pane" id="documentos" role="tabpanel" aria-labelledby="general-icon-tab">

  <div class="box box-info">
  <form action="{{route('customers.documentos.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
      {{ csrf_field() }}
      <input type="hidden" name="id" value="{{$cliente->id}}">

        <div class="card-body">
              <div class="row"> 
                  <div class="col-md-6 form-group ">
                        <label> <strong>Rut:</strong></label>
                        <input type="file" name="documentos[22][cara][1]" class="form-control" placeholder="">
                  </div>
                  <div class="col-md-6 form-group ">
                      <label> <strong>Camara Comercio:</strong></label>
                      <input type="file" class="form-control" name="documentos[23][cara][1]">
                  </div>

                  <div class="col-md-6 form-group ">
                      <label> <strong>Cédula de Representante Legal:</strong></label>
                      <input type="file" class="form-control" name="documentos[26][cara][1]">
                  </div>

                  <div class="col-md-6 form-group ">
                      <label> <strong>Estados Financieros:</strong></label>
                      <input type="file" class="form-control" name="documentos[27][cara][1]">
                  </div>

                  <div class="col-md-6 form-group ">
                      <label> <strong>Formato FR-03-002 / Acuerdo de Servicio:</strong></label>
                      <input type="file" class="form-control" name="documentos[28][cara][1]">
                  </div>

                  <div class="col-md-6 form-group ">
                      <label> <strong>Copia Del Contrato:</strong></label>
                      <input type="file" class="form-control" name="documentos[29][cara][1]">
                  </div>

                  <div class="col-xs-12 col-sm-12 col-md-12 ">
                    <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                    <a href="{{ route('customers') }}" class="btn btn-danger">Cancelar</a>
                  </div>
                </div>
        </div>
     
    </form>
  </div>
</div>
<div class="tab-pane" id="historial-documentos" role="tabpanel" aria-labelledby="general-icon-tab">

  <div class="box box-info">
  <div class="row">
        <div class="col-md-12 form-group mb-3">
          <label><strong>Historial Documentos:</strong></label>
          <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
            <tr>
              <thead>
                <th>Tipo Documento</th>
                <th>Cara Frontal</th>
                <th>Cara Trasera</th>
              </thead>
            </tr>
            <tbody>
              @foreach($documentos as $documento)
              <tr>
                <td>{{$tipo_documentos[$documento->id_tipo_documento]}}</td>
                <td><a href="{{asset($documento->cara_frontal)}}" target="_blank">{{$documento->cara_frontal}}</a></td>
                <td><a href="{{asset($documento->cara_trasera)}}" target="_blank">{{$documento->cara_trasera}}</a></td>

              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      
      </div>
  </div>
</div>


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


<script>



// just for the demos, avoids form submit
var form = $( "#user-new-form" );
$.validator.messages.required = 'Este campo es requerido';
$.validator.messages.email = 'Email invalido';

$('#user-new-form').validate({
  rules: {
        nombres: { required:true },
        apellidos: { required:true },
        razon_social: { required:true },
        documento:{ required:true },
        departamento_id:{ required:true },
        ciudad_id: { required:true },
        file: { 
              required:true ,
              extension:"jpg,jpeg,png",
              maxsize: 400000
        }
    },messages: {
                file:{
                    filesize:" El archivo no debe superar los 400 KB.",
                    extension:"Por favor subir imagenes con extensión .jpg o .png.",
                    maxsize:"Por favor suba una imagen."
                }
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