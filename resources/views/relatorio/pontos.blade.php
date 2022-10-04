@extends('painel.padrao')

@section('content')
    <br>
    <br>
    <div class="container">


        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">Relatorio de Pontos</h3>
                    </div>
                    <div class="box-header">


                    </div>


                    <div class="box-body text-center">
                        <div class="table-responsive">
                            <table id="myTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">DESCRIÇÃO</th>
                                        <th class="text-center">PONTOS</th>
                                        <th class="text-center">DATA</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(Auth::user()->extratos as $extrato)
                                        <tr>
                                            <td>{{ $extrato->id }}</td>
                                            <td>bonus ref. login {{ $extrato->indicado->name }}</td>
                                            <td>{{ $extrato->pontos }}</td>
                                            <td>{{ $extrato->created_at->format('d-m-y') }}</td>

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
