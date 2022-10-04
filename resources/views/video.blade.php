@extends('painel.padrao')
@section('css')
    <style>
        body {
            color: white;
        }

        .caixa {
            border-radius: 15px;
            opacity: 55%;
            background-color: #43308a;
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
    <div class="container">
        <div class="row">
            <div class="col-md-12">


                <div class="panel caixa">
                    <div class="panel-heading">
                        <h5 class="panel-title">Videos</h5>

                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="myTable" class="cell-border compact stripe">
                                <thead>
                                    <tr>
                                        @if (Auth::user()->tipo == 1)
                                            <th>Usuario</th>
                                        @endif
                                        <th>Video</th>

                                        <th>Status</th>
                                        <th>Ação</th>



                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($videos as $video)
                                        <tr style="background-color: transparent">
                                            @if (Auth::user()->tipo == 1)
                                                <td>{{ $video->user->name }}</td>
                                            @endif
                                            <td>{{ $video->video }}</td>

                                            <td>{{ $video->status }}</td>

                                            <td>
                                                <a class="btn"
                                                    href="{{ url('admin/validar', $video->id) }}">Validar</a>
                                                <a class="btn"
                                                    href="{{ url('admin/invalidar', $video->id) }}">Invalidar</a>

                                            </td>



                                        </tr>
                                    @empty
                                        <tr style="background-color: transparent">
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
