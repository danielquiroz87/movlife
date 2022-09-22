      <div class="table-responsive">
                                        <table id="user_table" class="table  text-center">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col"># Documento</th>
                                                    <th scope="col">Razón Social</th>
                                                    <th scope="col">Nombres</th>
                                                    <th scope="col">Email</th>
                                                    <th scope="col">Celular</th>
                                                    <th scope="col">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($clientes as $user)
                                                    <tr>
                                                      <td>{{$user->documento}}</td>
                                                      <td>{{$user->razon_social}}</td>
                                                      <td>{{$user->nombres}} {{$user->apellidos}}</td>
                                                      <td>{{$user->email_contacto}}</td>
                                                      <td>{{$user->celular}}</td>
                                                      <td>{{$user->whatsapp}}</td>
                                                      <td>{{$user->activo}}</td>
                                                      <td>
                                                        <a class="text-success mr-2" href="{{route('customers.edit', $user->id)}}" title="Editar">
                                                          <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                        </a>
                                                        <a class="text-danger mr-2 eliminar" href="{{route('customers.delete', $user->id)}}" title="Eliminar"><i class="nav-icon i-Close-Window font-weight-bold"></i></a>
                                                      </td>
                                                    </tr>
                                                    @endforeach
                                         
                                       

                                           
                                            </tbody>
                                        </table>
                                    </div>  