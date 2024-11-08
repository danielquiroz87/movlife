@extends('layouts.master')
@section('page-css')
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<style>
#div-dropzone{
  display: none;
}
.dz-button{
  font-size:24px;
  font-weight: 800;
}
</style>
@endsection
@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('sigdocumentos.index')}}">Documentos SIG</a></li>
          <li><a href="{{route('sigdocumentos.categorias',['id'=>$categoria->id])}}">Categorias SIG</a></li>
          <li><a href="{{route('sigdocumentos.subcategorias',['id'=>$subcategoria->id])}}">SubCategorias SIG</a></li>
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
          <div class="col-md-12">
            
            <h1>Archivos SIG</h1>
            <div class="d-sm-flex mb-3" data-view="print">
                  <span class="m-auto"></span>
                  <a class="btn btn-primary nuevo" href="#">Nuevo</a>
                  &nbsp;<a class="btn btn-success" href="">Actualizar Lista</a>


            </div>
            <div class="col-md-12 mb-4" id="div-dropzone">
             
            <form action="{{route('sigdocumentos.subcategorias.files.upload')}}" 
              class="dropzone"
              id="my-dropzone">
              <div class="dz-message" data-dz-message><span>De click o arrastre y suelte los archivos que desea subir en esta zona.</span></div>

              <input type="hidden" name="subcategoria" value="{{$subcategoria->id}}" />
            </form>
            </div>
           
          </div>
  </div>


  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body"> 
          
          <h3 class="card-title mb3">Archivos SIG / {{$mastercategoria->nombre}} / {{$categoria->nombre}} / {{$subcategoria->nombre}}</h3>

          <form class="form" method="GET" >
            <div class="col-md-4 form-group mb-4">  
                <label>Buscar</label>
                <input name="q" class="form-control " value="{{$q}}" ></input>
            </div>
            <div class="col-md-3 form-group mb-3">
                <button class="btn btn-success">Buscar</button>
            </div>
        </form>
              <!-- /.card-header -->
             <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Nombre</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($items as $item)
                    <tr>
                      <td>{{$item->id}}</td>
                      <td><a href="{{asset($item->file)}}" target="__blank" class="ver" title="Ver Archivo">{{$item->nombre}}</a></td>
                      <td>
                        <a href="{{asset($item->file)}}" target="__blank" class="ver" title="Ver Archivo"><i class="nav-icon i-Car-Wheel font-weight-bold"  ></i>  </a>
                        &nbsp;<a href="{{route('sigdocumentos.subcategorias.files.delete',['id'=>$item->id])}}" data-id="{{$item->id}}" class="eliminar" title="Eliminar"><i class="nav-icon i-Close-Window font-weight-bold"></i></a>

                     </td>
                    </tr>
                  	@endforeach
                  </tbody>
                  <tfoot>
                  	<tr>
                  	

                  	</tr>
                  </tfoot>
                </table>

                <div class="d-flex justify-content-center">
   				    <div class="">
   				    	<?php echo $items->appends(['q' => $q])->links(); ?>
   				    </div>

				</div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

      <form action="#" method="POST" id="user-delete-form"  >
        {{ csrf_field() }}
        <input type="hidden" name="id" id="itemid" value="0">
      </form>

@endsection

@section('bottom-js')

<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script type="text/javascript">

myDropzone=false;

Dropzone.options.myDropzone = { // The camelized version of the ID of the form element

// The setting up of the dropzone
init: function() {
   myDropzone = this;
  this.on("success", function(files, response) {
    // Gets triggered when the files have successfully been sent.
    // Redirect user or notify of success.
    if(response.success){
      Swal
	    .fire({
	        title: "OK",
	        text: "Archivo Subido Exitosamente",
	        icon: 'success',
	        confirmButtonText: "Cerrar",
	    })
	    .then(resultado => {
	        if (resultado.value) {
	            // Hicieron click en "Sí"
             // myDropzone.removeAllFiles(true); 
            } else {
	            // Dijeron que no
	            console.log("*NO se elimina la venta*");
	        }
	    });
    }
  });
  this.on("error", function(files, response) {
    // Gets triggered when there was an error sending the files.
    // Maybe show form again, and notify user of error
  });
}

}


 $(document).ready(function(){
  
  $('.select2').select2({
   theme: 'bootstrap-5'
  });
 
  $('.nuevo').click(function(e){
    e.preventDefault();
    $('#div-dropzone').toggle('slow');
    myDropzone.removeAllFiles(true); 

  })

  $('#enviarplanilla').click(function(e){
    e.preventDefault();
    $('#form-planilla').submit();
  })

 	$('.eliminar').click(function(e){
 		e.preventDefault();
 		var url=$(this).attr('href');
 		$('#user-delete-form').attr('action',url);
    $('#itemid').val($(this).data('id'));

 		Swal
	    .fire({
	        title: "Eliminar",
	        text: "Está seguro de eliminar este Registro?",
	        icon: 'warning',
	        showCancelButton: true,
	        confirmButtonText: "Sí, eliminar",
	        cancelButtonText: "Cancelar",
	    })
	    .then(resultado => {
	        if (resultado.value) {
	            // Hicieron click en "Sí"
 				     $('#user-delete-form').submit();
	        } else {
	            // Dijeron que no
	            console.log("*NO se elimina la venta*");
	        }
	    });
 	})
 })
</script>
@endsection
