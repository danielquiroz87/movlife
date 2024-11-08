@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('alistamiento')}}">Alistamiento</a></li>
          <li>Nuevo Alistamiento</li>
      </ul>
  </div>
  <div class="separator-breadcrumb border-top">
    
   
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
</div>
<div class="row">



<div class="col-md-8 mb-4">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Nuevo Alistamiento</h3>
  
 <div class="box box-info" style="margin-right: 20px">
    <form action="{{route('alistamiento.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="{{$vehiculo->id}}">
      <input type="hidden" name="is_new" value="true">

        <div class="row">

        @if(auth()->user()->superadmin==1  )
        <div class="col-md-6 form-group mb-3">
              <label><strong>Fecha</strong></label>
              <input type="date" name="fecha" value="{{$fecha}}"  class="form-control" placeholder="dd/mm/yyyy"  >
        </div>
        @else
        <div class="col-md-6 form-group mb-3">
              <label><strong>Fecha</strong></label>
              <input type="date" name="fecha" value="{{$fecha}}"  class="form-control" placeholder="dd/mm/yyyy"  >
        </div>
        @endif

       

         <div class="col-md-6 form-group mb-3">
              <label><strong>Kilometros</strong></label>
                   <input type="number" name="kilometros" value=""  class="form-control" placeholder="0"  >
        </div>
      </div>
        <div class="row">

          <?php foreach($categorias as $key=>$categoria):?>
                <div class="col-md-12" >

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
                                      <input type="checkbox" name="items[{{$item->id}}]" checked="checked"><span>{{$item->item}}</span>
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
                   <textarea name="observaciones_conductor" style="height: 90px" class="form-control" placeholder=""></textarea>   
              </div>
        

            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('alistamiento') }}" class="btn btn-danger">Cancelar</a>
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
        fecha: {required:true },
        kilometros: { required:true }
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