@extends('painel.padrao')
@section('css')
    <style>
        body {
            color: white;
        }

        .caixa {
            border-radius: 15px;
            background-color: rgba(39, 34, 40, 0.7);
            border: 3px solid rgba(233, 0, 0, 0.4);
            color: white
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            box-sizing: border-box;
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

    </style>
@endsection

@section('content')
    <br>
    <br>

    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog caixa">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 style="color: #0a0a0a" class="modal-title">PIX</h4>
                </div>
                <div style="background-color: #0a0a0a" class="modal-body">

                    @php

                    $agora = \Carbon\Carbon::now()->format('H:i:s');
                  //  dd($agora);

                        @endphp


                    @if($agora>= '09:00:00'&&$agora<='18:00:00')

                        <button>Saque disponivel no proximo dia util!</button>

                    @else

                        <label for="">Saques pertidos nos horarios entre 9H as 18H</label>


                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <div class="small-box  caixa">
                    <div class="inner">
                        <h3>

                            R$ {{ number_format($reward, 2, ',', '.') }}</h3>

                        <p>Saldo De TIME</p>

                        @if($resposta == false)
                        <button class="btn" data-toggle="modal" data-target="#myModal">Saque de Rede</button>
                        @else

                            Operações de saques permitidos apenas em dias Uteis


                        @endif
                    </div>
                    <div class="icon">

                    </div>

                </div>

            </div>
            <div class="col-md-12">


                <div class="panel caixa">
                    <div class="panel-heading">
                        <h5 class="panel-title">MEUS DIRETOS</h5>

                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="myTable" class="cell-border compact stripe">
                                <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Whatsapp</th>


                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse (Auth::user()->indicados as $indicado)
                                    <tr style="background-color: transparent">
                                        <td>{{ $indicado->name }}</td>
                                        <td>{{ $indicado->email }}</td>
                                        <td>{{ $indicado->telefone }}</td>


                                        <td>{{ $indicado->status }}</td>
                                    </tr>
                                @empty
                                    <tr style="background-color: transparent">
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

            <div class="col-md-12">
                <div class="panel caixa ">
                    <div class="panel-heading">
                        <h5 class="panel-title">MEU SEGUNDO NIVEL</h5>

                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="myTable1" class="cell-border compact stripe">
                                <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Lider</th>
                                    <th>Email</th>
                                    <th>Whatsapp</th>
                                    <th>Status</th>

                                </tr>
                                </thead>
                                <tbody>
                                @forelse (Auth::user()->primeiroindicados() as $indicado)
                                    <tr style="background-color: transparent">
                                        <td>{{ $indicado->name }}</td>
                                        <td>{{ $indicado->meindica->name }}</td>
                                        <td>{{ $indicado->email }}</td>
                                        <td>{{ $indicado->telefone }}</td>
                                        <td>{{ $indicado->status }}</td>

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
            <div class="col-md-12">
                <div class="panel caixa">
                    <div class="panel-heading">
                        <h5 class="panel-title"><p> MEU TERCEIRO NIVEL </p>
                        </h5>

                    </div>
                    <div class="panel-body">

                        <div class="table-responsive">
                            <table id="myTable2" class="cell-border compact stripe">
                                <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Lider</th>
                                    <th>Email</th>

                                    <th>Whatsapp</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse (Auth::user()->segundoindicados() as $segundo)
                                    <tr style="background-color: transparent">
                                        <td>{{ $segundo->name }}</td>
                                        <td>{{ $segundo->meindica->name }}</td>
                                        <td>{{ $segundo->email }}</td>

                                        <td>{{ $segundo->telefone }}</td>
                                        <td>{{ $segundo->status }}</td>

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
        $('#myTable1').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel', 'pdf'
            ]
        });
        $('#myTable2').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel', 'pdf'
            ]
        });
        $('#myTable3').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel', 'pdf'
            ]
        });
        $('#myTable4').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel', 'pdf'
            ]
        });
    </script>
@endsection
