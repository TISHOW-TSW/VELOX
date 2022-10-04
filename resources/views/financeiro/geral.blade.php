@extends('painel.padrao')
@section('css')
    <style>
        body {
            color: white;
        }

        .caixa1 {
            border-radius: 15px;
            /*opacity: 55%*/
            background-color:rgba(39, 34, 40, 0.9);
            border: 3px solid rgba(233, 0, 0,0.4);
            color: white;
            padding-bottom: 30px;
            display:flex;
            flex-direction: column;
            justify-content: space-around;
            text-transform: uppercase;
        }

        .caixa {
            border-radius: 15px;
            /*opacity: 55%*/
            background-color:rgba(39, 34, 40, 0.9);
            border: 3px solid rgba(233, 0, 0,0.4);
            color: white;
            padding-bottom: 30px;
            display:flex;
            flex-direction: column;
            justify-content: space-around;
            text-transform: uppercase;
        }

        .previous {
            color: white;
        }

        a.paginate_button {
            background-color: #43308a;
            color: white
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            panel-sizing: border-panel;
            display: inline-block;
            min-width: 1.5em;
            padding: 0.5em 1em;
            margin-left: 2px;
            text-align: center;
            text-decoration: none !important;
            cursor: pointer;
            *cursor: hand;
            color: white !important;
            border: 1px solid transparent;
            border-radius: 2px;
        }

        label {
            color: white;
        }

        span {
            color: white;
        }

        #myTable_info {
            color: white;
        }

        #myTable1_info {
            color: white;
        }

        #myTable2_info {
            color: white;
        }

        #myTable3_info {
            color: white;
        }

        #myTable4_info {
            color: white;
        }

        .dataTables_empty {
            background-color: transparent;
        }

        #myTable_paginate {
            color: white;

            #myTable_paginate.a {
                color: white;
            }
        }

        #myTable_paginate:a {
            color: white;
        }

        #myTable_previous {
            border-radius: 15px;
            opacity: 55%;
            background-color: #43308a;
            color: white
        }

        .paginate_button {
            color: white;
        }

        #myTable_paginate {
            color: white;
        }

    </style>
@endsection

@section('content')
    <br>
    <br>
    <div class="container">
        <div class="row">

            <div class="col-md-6">
                <div class="panel  caixa1">
                    <div class="panel-heading">
                        <h5 class="panel-title" style="color: white" class="panel-title">Total Balance
                        </h5>

                    </div>


                    <div class="panel-body text-center">
                        <h1>R$ {{ number_format($reward, 2, ',', '.') }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel  caixa1">
                    <div class="panel-heading">
                        <h5 class="panel-title" style="color: white" class="panel-title">Total Withdrawals
                        </h5>

                    </div>


                    <div class="panel-body text-center">
                        <h1>R${{ number_format(Auth::user()->total_saque, 2, ',', '.') }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-center">
                @if ($reward >= 25 && $totalDuration >= 14400)
                    <a class="btn" href="{{ url('carryout/withdrawal') }}">Withdrawal</a>
                @endif
            </div>
            <br>
            <br>
        </div>
    </div>
    <div class="container">
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="panel caixa">
                    <div class="panel-heading">


                    </div>


                    <div class="panel-body  text-center">
                        <div class="table-responsive">
                            <table id="myTable" class="table ">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Descriçao</th>
                                        <th>Value</th>
                                        <th>tipo</th>
                                        <th>data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($buscas as $movimento)
                                        <tr style="background-color: transparent">
                                            <td>{{ $movimento->id }}</td>
                                            <td>{{ $movimento->descricao }}</td>
                                            <td>R$ {{ number_format($movimento->valor, 2, ',', '.') }}</td>
                                            <td>{{ $movimento->status_formated }}</td>
                                            <td>{{ $movimento->created_at->format('d-m-y') }}</td>
                                        </tr>
                                    @empty
                                        <tr style="background-color: transparent">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
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
