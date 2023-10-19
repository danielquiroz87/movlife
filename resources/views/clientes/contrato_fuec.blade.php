@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('customers')}}">Clientes</a></li>
          <li>Nuevo Contrato Fuec</li>
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
                <h3 class="card-title mb3">Editar Fuec Contrato</h3>
  
 <div class="box box-info">
    <form action="{{route('customers.contract.fuec.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="{{$contrato->id}}">
      <input type="hidden" name="is_new" value="false">

        <div class="row">
            <div class="col-md-6 form-group mb-3">
              <label><strong>Nro Contrato :</strong></label>
                   <input type="text" name="contrato"  class="form-control" placeholder="000000" maxlength="11" required value="{{$contrato->contrato}}">
            </div>
            <div class="col-md-6 form-group mb-3">
              <label><strong>Objeto Contrato:</strong></label>
                    <select class="form-control" name="objeto_contrato_id">
                        <?php echo Helper::selectObjetosContrato($contrato->objeto_contrato_id) ?>
                    </select>
            </div>
            <div class="col-md-6 form-group mb-3">
              <label><strong>Documento / Nit Responsable :</strong></label>
                   <input type="text" name="responsable_documento"  class="form-control" placeholder="000000" maxlength="11" required value="{{$contrato->responsable_documento}}">
            </div>

             <div class="col-md-6 form-group mb-3">
              <label><strong>Nombre Responsable / Razón Social:</strong></label>
                   <input type="text" name="responsable_nombres"  class="form-control" placeholder="000000" maxlength="255" required value="{{$contrato->responsable_nombres}}">
            </div>
          

           <div class="col-md-6 form-group mb-3">
              <label><strong>Teléfono:</strong></label>
              <input type="number" name="responsable_telefono" class="form-control" placeholder="000000"
                         maxlength="10" required value="{{$contrato->responsable_telefono}}">
            </div>
           
            <div class="col-md-6 form-group mb-3">
              <label><strong>Dirección:</strong></label>
                   <input type="text" name="responsable_direccion" class="form-control" placeholder="" required value="{{$contrato->responsable_direccion}}">
            </div>
           
            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('customers') }}" class="btn btn-danger">Cancelar</a>
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

@endsection