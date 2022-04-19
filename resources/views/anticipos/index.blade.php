
@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li>Anticipos</li>
      </ul>
  </div>
  <div class="separator-breadcrumb border-top"></div>


  <div class="row">
          <div class="col-md-12">
            <h1>Anticipos</h1>
            <div class="d-sm-flex mb-3" data-view="print">
                  <span class="m-auto"></span>
                    <a class="btn btn-primary" href="{{route('anticipos.new')}}">Nuevo</a>
            </div>
            
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Lista Anticipos</h3>
              <!-- /.card-header -->
             <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Fecha</th>
                      <th>Conductor</th>
                      <th>Valor</th>
                      <th>Estado</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($anticipos as $anticipo)
                    <tr>
                     <td>{{$anticipo->id}}</td>
                     <td>{{$anticipo->created_at}}</td>
                     <td>{{$anticipo->conductor->nombres}} {{$anticipo->conductor->apellidos}}</td>
                     <td>{{$anticipo->valor}}</td>
                     <td>Activo</td>
                     
                     <td>
                        <a class="text-success mr-2" href="{{route('anticipos.edit',['id'=>$anticipo->id])}}" title="Editar">
                          <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                        </a>
                        <a class="text-danger mr-2 eliminar" href="{{route('anticipos.delete', $anticipo->id)}}" title="Eliminar" >
                        <i class="nav-icon i-Close-Window font-weight-bold"></i></i></a>

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
   				    	<?php echo $anticipos->links(); ?>
   				    </div>

				</div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>


        	 <form action="#" method="POST" id="user-delete-form"  >
    				{{ csrf_field() }}
      			<input type="hidden" name="id" id="userid" value="0">
   
        	</form>


@endsection

@section('bottom-js')
<script type="text/javascript">
 $(document).ready(function(){
 	$('.eliminar').click(function(e){
 		e.preventDefault();
 		var url=$(this).attr('href');
 		$('#user-delete-form').attr('action',url);

 		Swal
	    .fire({
	        title: "Eliminar",
	        text: "Está seguro de eliminar este usuario?",
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
