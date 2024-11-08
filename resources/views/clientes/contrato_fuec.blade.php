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

                <ul class="nav nav-tabs" id="myIconTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active show" id="general-icon-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true"><i class="nav-icon i-Home1 mr-1"></i>Formulario Contrato</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link show" id="general-icon-tab" data-toggle="tab" href="#lista" role="tab" aria-controls="lista" aria-selected="true"><i class="nav-icon i-Home1 mr-1"></i>Lista Contratos</a>
                        </li>
                </ul>
        
<div class="tab-content">
<div class="tab-pane fade active show" id="general" role="tabpanel" aria-labelledby="general-icon-tab">
         
 <div class="box box-info">
    <form action="{{route('customers.contract.fuec.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="{{$contrato->id}}">
      <input type="hidden" name="id_cliente" value="{{$id_cliente}}">

      <input type="hidden" name="is_new" value="true">

        <div class="row">
            <div class="col-md-6 form-group mb-3">
              <label><strong>Nro Contrato :</strong></label>
                   <input type="text" name="contrato"  class="form-control" placeholder="000000"
                    maxlength="11" required value="{{$contrato->contrato}}">
            </div>
            <div class="col-md-6 form-group mb-3">
              <label><strong>Objeto Contrato:</strong></label>
                    <select class="form-control" name="objeto_contrato_id">
                        <?php echo Helper::selectObjetosContrato($contrato->objeto_contrato_id) ?>
                    </select>
            </div>
            <div class="col-md-6 form-group mb-3">
              <label><strong>Documento / Nit Responsable :</strong></label>
                   <input type="text" name="responsable_documento"  
                   class="form-control" placeholder="000000" maxlength="11" required 
                   value="{{$contrato->responsable_documento}}">
            </div>

             <div class="col-md-6 form-group mb-3">
              <label><strong>Nombre Responsable / Razón Social:</strong></label>
                   <input type="text" name="responsable_nombres" 
                   class="form-control" placeholder="000000" maxlength="255" required 
                   value="{{$contrato->responsable_nombres}}">
            </div>
          

           <div class="col-md-6 form-group mb-3">
              <label><strong>Teléfono:</strong></label>
              <input type="number" name="responsable_telefono" class="form-control" placeholder="000000" 
                maxlength="10" required value="{{$contrato->responsable_telefono}}">
            </div>
           
            <div class="col-md-6 form-group mb-3">
              <label><strong>Dirección:</strong></label>
                   <input type="text" name="responsable_direccion" class="form-control" 
                   placeholder="" required 
                   value="{{$contrato->responsable_direccion}}">
            </div>
           
            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('customers') }}" class="btn btn-danger">Cancelar</a>
            </div>
        </div>

    </form>

</div>
     
</div>

<div class="tab-pane fade" id="lista" role="tabpanel" aria-labelledby="lista-icon-tab">
<div class="box box-info">
<div class="row">
        <div class="col-md-12 form-group mb-3">
          <label><strong>Contratos Cliente:</strong></label>
          <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
            <tr>
              <thead>
                <th>Nro Contrato</th>
                <th>Objeto Contrato</th>
                <th>Documento / Nit Responsable</th>
                <th>Responsable Nombres</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Acciones</th>
              </thead>
            </tr>
            <tbody>
              @foreach($contratos as $contrato)
              <tr>
                <td>{{$contrato->contrato}}</td>
                <td>{{$contrato->objeto_contrato_id}}</td>
                <td>{{$contrato->responsable_documento}}</td>
                <td>{{$contrato->responsable_nombres}}</td>
                <td>{{$contrato->responsable_telefono}}</td>
                <td>{{$contrato->responsable_direccion}}</td>
                <td>
                    <a href="{{route('customers.fuec.contract.edit',['id'=>$id_cliente,'contratoid'=>$contrato->id])}}">Editar</a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
</div>
      
</div>
</div>
              
</div>
        
</div>

</div>
</div>
@endsection
@section('bottom-js')

@endsection