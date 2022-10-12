@extends('admin.padrao')

@section('content')

    <div class="container">

        <br>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel ">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buscar Usuario</h3>
                    </div>
                    <div class="panel-body">
                        <form action="{{ url('admin/buscarcpf') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="">Digite o email</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel ">
                    <div class="panel-heading">
                        <h3 class="panel-title">Usuarios</h3>
                    </div>
                    <div class="panel-body">

                        <table id="myTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>CPF</th>
                                    <th>Telefone</th>
                                    <th>Setor</th>
                                    <th>Ações</th>

                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->cpf }}</td>
                                        <td>{{ $user->telefone }}</td>
                                        <td>{{ $user->atividade }}</td>
                                        <td><a href="{{ url('admin/usuarios/edit', $user->id) }}"
                                                class="btn btn-success">Editar</a>
                                            <a href="{{ url('admin/usuarios/visualizar', $user->id) }}"
                                                class="btn btn-primary">Visualizar</a>

                                        </td>

                                    </tr>
                                @empty
                                    <p>Vazio</p>
                                @endforelse


                            </tbody>
                        </table>


                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
@section('js')

    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>

    <script>
        $('#myTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.4/i18n/pt_br.json'
            },
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel', 'pdf'
            ]
        });
    </script>

@endsection
