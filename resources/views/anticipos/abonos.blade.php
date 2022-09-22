
@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('anticipos')}}">Anticipos</a></li>
      </ul>
  </div>
  <div class="separator-breadcrumb border-top"></div>


  <div class="row">
          <div class="col-md-12">
            <h1>Abono Anticipos</h1>
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Lista Abonos Anticipos</h3>
              <!-- /.card-header -->
              


             <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Fecha</th>
                      <th>Anticipo Id</th>
                      <th>Valor</th>
                      
                    </tr>
                  </thead>
                  <tbody>
                   
                    <tr>
                                           <td>{{$anticipo->id}}</td>

                     <td>{{$anticipo->created_at}}</td>
                     <td>{{$anticipo->id}}</td>

                      <td>${{number_format($anticipo->valor)}}</td>

                    </tr>
                   
                  </tbody>
                  <tfoot>
                    <tr>
                    

                    </tr>
                  </tfoot>
                </table>



             <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Fecha</th>
                      <th>Servicio Id</th>
                      <th>Valor</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $total=0?>
                  	@foreach ($abonos as $abono)
                    <?php $total+=$abono->valor?>
                    <tr>
                       <td>{{$abono->id}}</td>
                       <td>{{$abono->created_at}}</td>
                       <td>{{$abono->orden_servicio_id}}</td>
                       <td>${{number_format($abono->valor)}} </td>
                       
                    </tr>
                  	@endforeach
                  </tbody>
                  <tfoot>
                  	<tr>
                  	 <td colspan="3">Total</td>
                     <td>${{number_format($total)}}</td>
                  	</tr>
                    <tr>
                      <?php $restante=$anticipo->valor-$total;?>
                      <td colspan="3">Restante</td>
                      <td>${{number_format($restante)}}</td>
                    </tr>
                  </tfoot>
                </table>

                <div class="d-flex justify-content-center">
   				    <div class="">
   				    	<?php echo $abonos->links(); ?>
   				    </div>

				</div>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
</div>


        	


@endsection

@section('bottom-js')

@endsection
