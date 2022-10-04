@extends('painel.padrao')
@section('css')
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet"
        href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
@endsection
@section('content')
    <br>
    <br>
    <div class="container">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Consultar Faturas</h3>
            </div>
            <div class="panel-body">
                <form class="form-inline" action="{{ url('admin/consulta/relatorio') }}" method="post">
                    @csrf
                    <div class="form-group col-md-12">
                        <label for="">Data</label>
                        <input id="reservation" name="data" type="text" class="form-control">
                    </div>

                    <br>
                    <br>
                    <br>
                    <div class="form-group col-md-12">
                        <button class="btn btn-success btn-block"><i class="fa fa-search"></i> Buscar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Relatorio</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>DATA </th>
                                <th>Entrada</th>
                                <th>Saques</th>
                                <th>Valores Pendentes</th>
                                <th>Despesas</th>
                                <th>Usuarios Ativos</th>
                                <th>Usuarios Pendentes</th>
                                <th> Faturas Pagas</th>
                                <th>Faturas Pendentes</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($relatorios as $relatorio)
                                <tr class="text-center">
                                    <td>{{ $relatorio->data_formated }}</td>
                                    <td>R$ {{ number_format($relatorio->entrada, 2, ',', '.') }}</td>
                                    <td>R$ {{ number_format($relatorio->saques, 2, ',', '.') }}</td>
                                    <td>R$ {{ number_format($relatorio->pendentes, 2, ',', '.') }}</td>
                                    <td>R$ {{ number_format($relatorio->despesas, 2, ',', '.') }}</td>
                                    <td>{{ $relatorio->ativos }}</td>
                                    <td>{{ $relatorio->inativos }}</td>
                                    <td>{{ $relatorio->pagas }}</td>
                                    <td>{{ $relatorio->naopagas }}</td>
                                    </td>
                                </tr>
                            @empty
                            @endforelse


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('js')
    <script src="{{ asset('bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

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
    <script>
        $('#reservation').daterangepicker({


            ranges: {
                'Hoje': [moment(), moment()],
                'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Ultimos 7 dias': [moment().subtract(6, 'days'), moment()],
                'Ultimos 30 dias': [moment().subtract(29, 'days'), moment()],
                'Este mês': [moment().startOf('month'), moment().endOf('month')],
                'Ultimo mês': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                    'month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment(),
            function(start, end) {
                $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
            },
            "locale": {
                "format": "DD/MM/YYYY",
                "separator": " - ",
                "applyLabel": "Aplicar",
                "cancelLabel": "Cancelar",
                "customRangeLabel": "Personalizado",
                "daysOfWeek": [
                    "Dom",
                    "Seg",
                    "Ter",
                    "Qua",
                    "Qui",
                    "Sex",
                    "Sab"
                ],
                "monthNames": [
                    "Janeiro",
                    "Fevereiro",
                    "Março",
                    "Abril",
                    "Maio",
                    "Junho",
                    "Julho",
                    "Agosto",
                    "Setembro",
                    "Outubro",
                    "Novembro",
                    "Dezembro"
                ],
                "firstDay": 1
            }
        });
    </script>
@endsection
