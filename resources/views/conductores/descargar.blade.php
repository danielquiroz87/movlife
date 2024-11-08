    <!-- /.card-header -->
    <table border="1" style="border-collapse: collapse;border-spacing: 4px;">
    <thead>
                        <tr>
                          <th>Documento</th>
                          <th>Nombres</th>
                          <th>Email</th>
                          <th>Celular</th>
                          <th>Whatsapp</th>
                          <th>Tipo Vinculación</th>
                          <th >EPS</th>
                          <th >ARL</th>
                          <th >PENSIONES</th>
                          <th >FECHA EXPEDICIÓN LICENCIA DE CONDUCCIÓN</th>
                          <th >FECHA VENCIMIENTO LICENCIA DE CONDUCCIÓN</th>
                          <th >CATEGORÍA LICENCIA DE CONDUCCIÓN</th>
                          <th >RUT </th>
                          <th >SIMIT </th>
                          <th >PLANILLA APORTES</th>
                          <th >CEDULA</th>
                          <th >EXAMENES MEDICOS</th>
                          <th >ANTECEDENTES PENALES </th>
                          <th >CURSOS ADICIONALES </th>
                          <th colspan=2>CUENTA BANCARIA </th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($conductores as $user)
                        <tr>
                        <?php $documentos=Helper::getDocumentosConductor($user->id);?>

                          <td>{{$user->documento}}</td>
                          <td>{{$user->nombres}} {{$user->apellidos}}</td>
                          <td>{{$user->email_contacto}}</td>
                          <td>{{$user->celular}}</td>
                          <td>{{$user->whatsapp}}</td>
                          <td>
                            @if($user->tipo_vinculacion==1)
                            Empleado
                            @elseif($user->tipo_vinculacion==2)
                            Vinculado
                            @elseif($user->tipo_vinculacion==3)
                            Tercero
                            @else
                            NA
                            @endif
                          </td>

                          <td>{{$user->hojavida->eps}}</td>
                          <td>{{$user->hojavida->arl}}</td>
                          <td>{{$user->hojavida->pensiones}}</td>
                          <td>{{$documentos[$user->id][1]['fecha_expedicion']}}</td>
                          <td>{{$documentos[$user->id][1]['fecha_vencimiento']}}</td>
                          <td>{{$documentos[$user->id][1]['extra1']}}</td>
                          <td>{{$documentos[$user->id][7]['cargado']}}</td>
                          <td>{{$documentos[$user->id][16]['cargado']}}</td>
                          <td>
                            {{$documentos[$user->id][4]['cargado']}}

                          </td>
                          <td>{{$documentos[$user->id][5]['cargado']}}</td>
                          <td>{{$documentos[$user->id][6]['cargado']}}</td>
                          <td>{{$documentos[$user->id][18]['cargado']}}</td>
                          <td>{{$documentos[$user->id][19]['cargado']}}</td>
                          <td>
                            @if($documentos[$user->id][20]['extra1']!="NA")
                                {{$documentos[$user->id][20]['extra1']}} {{$documentos[$user->id][20]['nombre_entidad']}}
                            @else
                            @endif
                            </td>
                          <td>{{$documentos[$user->id][20]['numero']}}</td>


                        </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                        <tr>
                        

                        </tr>
                      </tfoot>
                    </table>