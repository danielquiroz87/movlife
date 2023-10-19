@extends('layouts.master')

@section('main-content')
<style type="text/css">
  .checkbox input[disabled] ~ *, .radio input[disabled] ~ *{
    color: #9fa4a9 !important;
  }
</style>
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('alistamiento')}}">Alistamiento</a></li>
          <li>Editar Alistamiento Diario</li>
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

<div class="col-md-8 mb-4">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Editar Alistamiento</h3>
  
 <div class="box box-info" style="margin-right: 20px">
    <form action="{{route('alistamiento.save.revision')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="{{$al->id}}">
      <input type="hidden" name="is_new" value="false">
      <input type="hidden" name="revisado_por" value="{{auth()->user()->id}}">


        <div class="col-md-6 form-group mb-3">
              <label><strong>Fecha</strong></label>
                   <input type="date" name="fecha" value="{{$al->fecha}}"  class="form-control" placeholder="dd/mm/yyyy" disabled="true" >
            </div>
        <div class="row">

          <?php foreach($categorias as $key=>$categoria):?>
                <div class="col-md-12">

                   <div class="card ul-card__border-radius">
                          <div class="card-header">
                            <h6 class="card-title mb-0">
                              <a class="text-default" href="#">{{$key}}</a>
                            </h6>
                          </div>
                          <div class="" id="accordion-item-equipo_carretera" data-parent="#accordionExample">
                            <div class="card-body">
                              @foreach($categoria as $item)
                              <div class="row">
                                   <div class="col-md-12 form-group ">
                                    <label class="checkbox checkbox-outline-primary">
                                      <input type="checkbox" disabled="true" name="items[{{$item->id}}]" @if($item->check==1) checked="checked" @else @endif><span>{{$item->item}}</span>
                                      <span class="checkmark"></span>
                                    </label>
                                  </div>
                              </div>
                              @endforeach
                            </div>
                          </div>
                      </div>
            </div>
          <?php endforeach;?>


            <div class="col-md-12" style="margin-bottom: 20px">
              <label><strong>Observaciones Conductor:</strong></label>
                   <textarea name="observaciones_conductor" style="height: 90px" class="form-control" placeholder="" disabled="true">{{$al->observaciones_conductor}}</textarea>   
              </div>

            <div class="col-md-12 form-group mb-3">
              <label><strong>Aprobado Para Operar:</strong></label>
                    <select class="form-control" name="aprobado">
                      <option value="1">Si</option>
                      <option value="0">No</option>
                    </select>
                   
            </div>   


              
             <div class="col-md-12" style="margin-bottom: 20px">
              <label><strong>Observaciones Movlife:</strong></label>
                   <textarea name="observaciones_movlife" style="height: 90px" class="form-control" placeholder="">{{$al->observaciones_movlife}}</textarea>   
              </div>
          
            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('alistamiento',['q'=>$al->vehiculo->placa]) }}" class="btn btn-danger">Cancelar</a>
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
        nombre: { required:true },
        departamento_id:{ required:true },
        ciudad_id: { required:true },
       
        
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