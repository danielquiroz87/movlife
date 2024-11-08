@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="#" >Jornada Conductores</a></li>
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
            <h1>Control Jornada  </h1>
           
          </div>
  </div>


  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body"> 
                <h3 class="card-title mb3">Jornada Conductores Fecha: {{date('d/m/y')}} </h3>
              <!-- /.card-header -->
             <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Placa</th>
                      <th>Conductor</th>
                      <th>Fecha Inicio Jornada</th>
                      <th>Fecha Inicio Servicios</th>
                      <th>Fecha Fin Jornada</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  @if($jornadas)  
                  @foreach($jornadas as $jornada)
                    <tr>
                      <td>{{$jornada->id}}</td>
                      <td>{{$jornada->placa}}</td>
                      <td>{{$jornada->conductor->nombres}} {{$jornada->conductor->apellidos}}</td>

                      <td>{{$jornada->inicio_jornada}}</td>
                      <td>{{$jornada->inicio_servicios}}</td>
                      <td>{{$jornada->fin_jornada}}</td>
                      <td>
                        @if($jornada->inicio_jornada=="")
                         <center><a class="btn btn-primary" href="{{route('conductores.jornada.save',['tipo'=>1,'placa'=>$placa])}}" >Iniciar Jornada</a></center> 
                        @elseif($jornada->inicio_servicios=="")
                        <center><a class="btn btn-primary" href="{{route('conductores.jornada.save',['tipo'=>2,'placa'=>$placa])}}" >Iniciar Servicios</a></center>                        
                        @elseif($jornada->fin_jornada=="")
                        <center><a class="btn btn-primary" href="{{route('conductores.jornada.save',['tipo'=>3,'placa'=>$placa])}}" >Finalizar Jornada</a></center>
                        @else
                        Jornada Completa
                        @endif
                      </td>
                 
                    </tr>
                    @endforeach

                    @else
                    <tr>
                      <td colspan="8"> <center><a class="btn btn-primary" href="{{route('conductores.jornada.save',['tipo'=>1,'placa'=>$placa])}}" >Iniciar Jornada</a></center> </td>
                    </tr>
                    @endif
                    
                  </tbody>
                  <tfoot>
                  	<tr>
                  	

                  	</tr>
                  </tfoot>
                </table>

             
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
