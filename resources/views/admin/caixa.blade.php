@extends('painel.padrao')

@section('content')
    <br>
    <div class="container">


        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">CAIXA DA EMPRESA</h3>
            </div>
        </div>
        <a href="{{ url('novo/registro') }}" class="btn btn-success"><i class="fa fa-plus"></i> Adicionar Registro</a>
        <br>
        <br>
        <div class="row">
            <div class="col-lg-4">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>$ {{number_format($entrada,2,',','.')}}</h3>

                        <p>TOTAL DE ENTRADA</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-up"></i>
                    </div>

                </div>

            </div>
            <div class="col-lg-4">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>$ {{number_format($saida,2,',','.')}}</h3>

                        <p>TOTAL DE SAIDA</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-down"></i>
                    </div>

                </div>

            </div>

            <div class="col-lg-4">
                <div class="small-box bg-gray">
                    <div class="inner">
                        <h3>$ {{number_format($entrada - $saida,2,',','.')}}</h3>

                        <p>TOTAL DE CAIXA</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-exchange"></i>
                    </div>

                </div>

            </div>
        </div>
        <br>
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">CAIXA DA EMPRESA</h3>
            </div>

            <div class="panel-body">
                <table id="myTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>REGISTRO</th>
                            <th>TIPO</th>
                            <th>VALOR</th>
                            <th>DATA</th>


                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($caixas as $caixa)
                            <tr>
                                <td>{{ $caixa->id }}</td>
                                <td>{{ $caixa->descricao }}</td>
                                <td>

                                    <span
                                        class="@if ($caixa->tipo == 0)
                                right badge bg-red
                            @else
                            right badge bg-green

                            @endif">{{ $caixa->status }}</span>
                                </td>
                                <td>$ {{ number_format($caixa->valor, 2, ',', '.') }}</td>
                                <td>{{ $caixa->created_at->format('d-M-Y') }}</td>
                            </tr>
                        @empty

                        @endforelse

                    </tbody>
                </table>
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
            ],
            "ordering": false
        });
    </script>

@endsection
