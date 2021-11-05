@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h2 class="title" style="margin-top: 10px">Editar Usuario</h2>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('users')}}">Usuarios</a></li>
              <li class="breadcrumb-item active">Editar Usuario</li>
            </ol>
          </div>
        </div>
    </div>

@if(Session::has('flash_message'))
    <div class="alert alert-success">
      <span class="glyphicon glyphicon-ok"></span>
      <em> {!! session('flash_message') !!}</em>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
    </div>
@endif
@if(Session::has('flash_message_delete'))
    <div class="alert alert-danger">
      <span class="glyphicon glyphicon-ok"></span>
      <em> {!! session('flash_message_delete') !!}</em>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
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

<div class="col-md-6">
            <!-- Form Element sizes -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Formulario</h3>
              </div>
              <div class="card-body">
                

 <div class="box box-info">
    <form action="{{route('user.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="{{$user->id}}">
      <input type="hidden" name="is_new" value="false">

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Nombre:</strong>
                    <input type="text" name="nombre" value="{{$user->nombre}}" class="form-control" placeholder="Ingrese un nombre de usuario"  maxlength="255">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Identificacion:</strong>
                   <input type="text" name="identificacion" value="{{$user->identificacion}}" class="form-control" placeholder="000000" maxlength="20" required>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Email:</strong>
                    <input type="email" name="email" class="form-control" placeholder="example@email.com"
                        value="{{$user->email}}" maxlength="255" required autocomplete="off">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Password</strong>
                    <a href="#" id="cambiar-password"> / Cambiar</a>
                    
                    <input id="password" type="password" name="password" class="form-control" value="" placeholder=""
                        value="" maxlength="20" required>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                  @if( Auth::user()->cargo=='administrador' )
                    <strong>Cargo:</strong>
                    <select name="cargo" id="select-cargo" class="form-control" required  >
                      <option value="">Seleccione un cargo</option>
                      <option value="administrador" @if ($user->cargo=='administrador') selected='selected' @endif >Administrador</option>
                      <option value="supervisor" @if ($user->cargo=='supervisor') selected='selected' @endif>Supervisor</option>
                      <option value="agente" @if ($user->cargo=='agente') selected='selected' @endif>Agente</option>
                    </select>
                    @else
                      <strong>Cargo:</strong>
                      <p>{{Auth::user()->cargo}}</p>
                      <input type="hidden" name="cargo" value="{{Auth::user()->cargo}}">
                    @endif
                </div>
            </div>


            <div id="section-horario" class="col-xs-12 col-sm-12 col-md-12 horario" >
              
              <p><strong>Horario</strong><br/>
                <em>Selecciona la hora de entrada y salida de cada día de la semana</em>
              </p>
                <div class="form-group">
                  <label>Lunes:</label>
                  <p>
                  De <input type="time"  class="time" name="lunes_hora_inicial" min="00:00:00" max="23:59:59" value="{{$user->lunes_hora_inicial}}"  >
                  A <input type="time"  name="lunes_hora_final" min="00:00" max="23:59" value="{{$user->lunes_hora_final}}" maxlength="5">
                  </p>
                </div>
                <div class="form-group">
                  <label>Martes:</label>
                  <p>
                  De <input type="time" class="time" name="martes_hora_inicial" min="00:00" max="23:59" value="{{$user->martes_hora_inicial}}" >
                  A <input type="time" class="time" name="martes_hora_final" min="00:00" max="23:59" value="{{$user->martes_hora_final}}" >
                  </p>
                </div>
                <div class="form-group">
                  <label>Miercoles:</label>
                  <p>
                  De <input type="time" class="time" name="miercoles_hora_inicial" min="00:00" max="23:59" value="{{$user->miercoles_hora_inicial}}" >
                  A <input type="time" class="time" name="miercoles_hora_final" min="00:00" max="23:59" value="{{$user->miercoles_hora_final}}" >
                  </p>
                </div>
                <div class="form-group">
                  <label>Jueves:</label>
                  <p>
                  De <input type="time" class="time" name="jueves_hora_inicial" min="00:00" max="23:59" value="{{$user->jueves_hora_inicial}}" >
                  A <input type="time" class="time" name="jueves_hora_final" min="00:00" max="23:59" value="{{$user->jueves_hora_final}}" >
                  </p>
                </div>
                <div class="form-group">
                <label>Viernes</label>
                 <p>
                  De <input type="time" class="time" name="viernes_hora_inicial" min="00:00" max="23:59" value="{{$user->viernes_hora_inicial}}" >
                  A <input type="time" class="time" name="viernes_hora_final" min="00:00" max="23:59" value="{{$user->viernes_hora_final}}" >
                  </p>
                </div>
                <div class="form-group">
                  <label>Sábado</label>
                  <p>
                  De <input type="time" class="time" name="sabado_hora_inicial" min="00:00" max="23:59" value="{{$user->sabado_hora_inicial}}" >
                  A <input type="time" class="time" name="sabado_hora_final" min="00:00" max="23:59" value="{{$user->sabado_hora_final}}" >
                  </p>
                </div>
                <div class="form-group">
                  <label>Domingo:</label>
                  <p>
                  De <input type="time" class="time" name="domingo_hora_inicial" min="00:00" max="23:59" value="{{$user->domingo_hora_inicial}}" >
                  A <input type="time" class="time" name="domingo_hora_final" min="00:00" max="23:59" value="{{$user->domingo_hora_final}}" >
                  </p>
                </div>
                <div class="form-group">
                  <label>Festivo:</label>
                 <p>
                  De <input type="time" class="time" name="festivo_hora_inicial" min="00:00" max="23:59" value="{{$user->festivo_hora_inicial}}" >
                  A <input type="time" class="time" name="festivo_hora_final" min="00:00" max="23:59" value="{{$user->festivo_hora_final}}" >
                  </p>
                </div>
            </div>
            
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Imagen:</strong>
                    <img src="{{asset($user->foto)}}" style="max-width: 100px">
                    <input type="hidden" name="imagen_old" id="imagen_old" value="{{$user->foto}}">
                    <input type="hidden" name="imagen_new" id="imagen_new" value="">
                   
                    <input type="file" name="file" id="file" class="form-control" placeholder="" value="{{$user->foto}}" >
                </div>
            </div>
      
            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('users') }}" class="btn btn-danger">Cancelar</a>
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
@push('page_scripts')
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.js"></script>


<script>
// just for the demos, avoids form submit
$("#password").hide();
$("#cambiar-password").click(function(){
  $("#password").toggle();
})
if($('#select-cargo').val()=='agente'){
  $('#section-horario').show();
}

$('.time').change(function(e){
  var val=$(this).val()+':00';
  $(this).val(val);
})

$('#select-cargo').change(function(e){

  if($(this).val()=="agente"){
    $('#section-horario').show();
  }else{
    $('#section-horario').hide();
  }

})

var form = $( "#user-new-form" );
$.validator.messages.required = 'Este campo es requerido';
$.validator.messages.email = 'Email invalido';

$('#user-new-form').validate({
  rules: {
        nombre: { required:true },
        email:{ required:true },
        identificacion:{ required:true },
        cargo:{ required:true },
        password:{ required:true },
        file: { 
              required:false,
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
       if($('#file').val()!=""){
          $("#imagen_new").val($("#file").val());
       }
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
@endpush