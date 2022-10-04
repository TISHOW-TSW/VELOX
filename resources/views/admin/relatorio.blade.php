@extends('painel.padrao')
@section('content')
    <br>
    <br>


    <div class="container">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Log Usuarios</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="myTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>DESCRIÇÃO</th>
                                <th>URL</th>
                                <th>METODO</th>
                                <th>IP</th>
                                <th width="300px">User Agent</th>
                                <th>USUARIO</th>

                            </tr>
                        </thead>
                        @if ($logs->count())
                            @foreach ($logs as $key => $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>{!! $log->subject !!}</td>
                                    <td class="text-success">{{ $log->url }}</td>
                                    <td><label class="label label-info">{{ $log->method }}</label></td>
                                    <td class="text-warning">{{ $log->ip }}</td>
                                    <td class="text-danger">{{ $log->agent }}</td>
                                    <td>{{ $log->user->name }}</td>

                                </tr>
                            @endforeach
                        @endif
                    </table>
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
