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
    <br>
    <div class="container">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Saques Raiz</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="myTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Valor a receber</th>
                            <th>Status</th>
                            <th>Solicitado em</th>
                            <th>Limite de Pagamento</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>

                        @forelse($saquesRaiz as $saque)
                            <tr>
                                <td>{{ $saque->id }}</td>
                                <td>{{ $saque->user->name }}
                                <td>$ {{ $saque->valor }}</td>
                                <td>{{ $saque->status_formated }}</td>
                                <td>{{ $saque->created_at->format('d-m-Y') }}</td>
                                <td>{{ $saque->created_at->addDays(2)->format('d-m-Y') }}</td>
                                <td>
                                    @if ($saque->status == 0)
                                        <a class="btn btn-primary"
                                           href="{{ url('admin/rendimento/visualizar/saque', $saque->id) }}">Visualizar</a>

                                        <a class="btn btn-danger"
                                           href="{{ url('admin/rendimento/cancelar/saque', $saque->id) }}">Cancelar</a>
                                    @else
                                    @endif
                                </td>
                            </tr>
                        @empty
                        @endforelse


                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Saques {{ $tipo }}</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="myTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Valor a receber</th>
                                <th>Status</th>
                                <th>Solicitado em</th>
                                <th>Limite de Pagamento</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($saques as $saque)
                                <tr>
                                    <td>{{ $saque->id }}</td>
                                    <td>{{ $saque->user->name }}
                                    <td>$ {{ $saque->valor }}</td>
                                    <td>{{ $saque->status_formated }}</td>
                                    <td>{{ $saque->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $saque->created_at->addDays(2)->format('d-m-Y') }}</td>
                                    <td>
                                        @if ($saque->status == 0)
                                            <a class="btn btn-primary"
                                                href="{{ url('admin/rendimento/visualizar/saque', $saque->id) }}">Visualizar</a>

                                            <a class="btn btn-danger"
                                                href="{{ url('admin/rendimento/cancelar/saque', $saque->id) }}">Cancelar</a>
                                        @else
                                        @endif
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
    <div class="container">

        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Saques Indicação</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="myTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Valor a receber</th>
                                <th>Status</th>
                                <th>Solicitado em</th>
                                <th>Limite de Pagamento</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($saqueindicaos as $saque)
                                <tr>
                                    <td>{{ $saque->id }}</td>
                                    <td>{{ $saque->user->name }}
                                    <td>$ {{ $saque->valor }}</td>
                                    <td>{{ $saque->status_formated }}</td>
                                    <td>{{ $saque->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $saque->created_at->addDays(2)->format('d-m-Y') }}</td>
                                    <td>
                                        @if ($saque->status == 0)
                                            <a class="btn btn-primary"
                                                href="{{ url('admin/indica/visualizar/saque', $saque->id) }}">Visualizar</a>

                                            <a class="btn btn-danger"
                                                href="{{ url('admin/indica/cancelar/saque', $saque->id) }}">Cancelar</a>
                                        @else
                                        @endif
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
