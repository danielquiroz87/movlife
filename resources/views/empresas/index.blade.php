
@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li>Empresas Convenios</li>
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
            <h1>Empresas Convenios</h1>

                  <div class="d-sm-flex mb-3" data-view="print">
                    <span class="m-auto"></span>
                   <span class="m-auto"></span>
                    <a class="btn btn-primary" href="{{route('empresas.convenios.new')}}">Nuevo</a>
                  </div>
                
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Lista Empresas Convenios</h3>
              <!-- /.card-header -->
                
                <form class="form" method="GET" >
                <div class="col-md-4 form-group mb-4">  
                  <label>Buscar</label>
                  <input name="q" class="form-control " value="{{$q}}" ></input>
                </div>
                 <div class="col-md-3 form-group mb-3">
                   
                    <button class="btn btn-success">Buscar</button>
                  </div>

                </form>


             <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Nit</th>
                      <th>Razón Social</th>
                      <th>Doc Rep Legal</th>
                      <th>Nombres Rep Legal</th>
                      <th>Email Contacto</th>
                      <th>Celular</th>
                      <th>Whatsapp</th>
                      <th>Activo</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($empresas as $empresa)
                    <tr>
                      <td>{{$empresa->nit}}</td>
                      <td>{{$empresa->razon_social}}</td>
                      <td>{{$empresa->representante_legal_documento}}</td>
                      <td>{{$empresa->representante_legal_nombres}}</td>
                      <td>{{$empresa->email_contacto}}</td>
                      <td>{{$empresa->celular}}</td>
                      <td>{{$empresa->whatsapp}}</td>
                      <td>
                        @if($empresa->activo==1)
                        Si
                        @else
                        No
                        @endif
                      </td>
                      <td>
                      	<a class="text-success mr-2" href="{{route('empresas.convenios.edit', $empresa->id)}}" title="Editar"><i class="nav-icon i-Pen-2 font-weight-bold"></i></a>
                      	<a class="text-danger mr-2 eliminar" href="{{route('empresas.convenios.delete.get', $empresa->id)}}" title="Eliminar" ><i class="nav-icon i-Close-Window"></i></a>
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
   				    	<?php echo $empresas->appends(['q' => $q])->links(); ?>
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
