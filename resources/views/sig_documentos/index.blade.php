@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('sigdocumentos.index')}}" >Documentos SIG</a></li>
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
            <h1>Documentos SIG</h1>
            <div class="d-sm-flex mb-3" data-view="print">
                  <span class="m-auto"></span>
                  
                    <a class="btn btn-primary" href="{{route('planillaservicios.new')}}">Nuevo</a>
            </div>
          </div>
  </div>


  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body"> 
                    @if (Route::currentRouteName()=='sigdocumentos.index')
                    <h3 class="card-title mb3">Master Categorias Documentos SIG</h3>
                    @else
                    @if (Route::currentRouteName()=='sigdocumentos.categorias')
                    <h3 class="card-title mb3">Categorias Documentos SIG / {{$migapan['master']}}</h3>
                    @endif
                    @if (Route::currentRouteName()=='sigdocumentos.subcategorias')
                        <h3 class="card-title mb3">SubCategorias Documentos SIG / {{$migapan['master']}} / {{$migapan['categoria']}}</h3>
                    @endif
                    @endif 
                    

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
                     
                      @if (Route::currentRouteName()=='sigdocumentos.index')
                      <td>
                        <a class="mr-2" href="{{route('sigdocumentos.categorias', ['id'=>$item->id])}}" title="Ver"> {{$item->nombre}}</a>
                      </td>
                      <td>
                      <a class="mr-2" href="{{route('sigdocumentos.categorias', ['id'=>$item->id])}}" title="Ver"><i class="nav-icon i-Car-Wheel font-weight-bold"></i> </a>

                      </td>
                      @else
                      @if (Route::currentRouteName()=='sigdocumentos.categorias')
                      <td><a class="mr-2" href="{{route('sigdocumentos.subcategorias', ['id'=>$item->id])}}" title="Ver">{{$item->nombre}}</a></td>
                      <td>
                      <a class="mr-2" href="{{route('sigdocumentos.subcategorias', ['id'=>$item->id])}}" title="Ver"><i class="nav-icon i-Car-Wheel font-weight-bold"></i> </a>
                      </td>
                      @endif
                      @if (Route::currentRouteName()=='sigdocumentos.subcategorias')
                      <td>
                        <a class="mr-2" href="{{route('sigdocumentos.subcategorias.files', ['id'=>$item->id])}}" title="Ver Archivos">{{$item->nombre}}</a>
                      </td>
                      <td>
                        <a class="mr-2" href="{{route('sigdocumentos.subcategorias.files', ['id'=>$item->id])}}" title="Ver Archivos"><i class="nav-icon i-Car-Wheel font-weight-bold"></i></a>
                      </td>
                      @endif
                      @endif

                 
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
        </div>





        	 <form action="#" method="GET" id="user-delete-form"  >
    				{{ csrf_field() }}
      			<input type="hidden" name="id" id="userid" value="0">
   
        	</form>
</div>

@endsection

@section('bottom-js')
<script type="text/javascript">
 $(document).ready(function(){
  $('.select2').select2({
   theme: 'bootstrap-5'
 });

  $('.nueva').click(function(e){
    e.preventDefault();
    $('#modal').modal('show');
  })

  $('#enviarplanilla').click(function(e){
    e.preventDefault();
    $('#form-planilla').submit();
  })

 	$('.eliminar_all').click(function(e){
 		e.preventDefault();
 		var url=$(this).attr('href');
 		$('#user-delete-form').attr('action',url);

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
