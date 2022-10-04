@extends('painel.padrao')

@section('content')
    <br>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-12">


                <div class="box ">
                    <div class="box-header">
                        <h5 class="box-title">Indicações De {{$nivel}} Nivel</h5>

                    </div>
                    <div class="box-body">
                        <table id="myTable" class="table table-striped">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Patrocinador</th>
                                <th>Status</th>
                                <th>Telefone</th>
                                <th>Email</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($indicados as $indicado)
                                <tr>
                                    <td>{{$indicado->name}}</td>
                                    <td>{{$indicado->meindica->name}}</td>
                                    <td>{{$indicado->status}}</td>
                                    <td>{{$indicado->telefone}}</td>
                                    <td>{{$indicado->email}}</td>
                                </tr>
                            @empty

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
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel', 'pdf'
            ]
        });
    </script>

@endsection
