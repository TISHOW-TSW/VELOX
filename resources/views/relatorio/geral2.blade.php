@extends('painel.padrao')
@section('css')
    <style>
        body {
            color: white;
        }

        .caixa {
            border-radius: 15px;
            /*opacity: 55%*/
            background-color:rgba(39, 34, 40, 0.7);
            border: 3px solid rgba(233, 0, 0,0.4);
            color: white
        }

        .previous {
            color: white;
        }

        a.paginate_button {
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
            <div class="col-lg-3">
                <div class="small-box bg-white caixa">
                    <div class="inner">
                        <h3>{{ Auth::user()->contador }}</h3>

                        <p>Click on the link
                        </p>
                        <i class="fa fa-rocket"></i>
                    </div>
                    <div style="font-size: 20px" class="icon">

                    </div>

                </div>

            </div>


            <div class="col-lg-3">
                <div class="small-box  caixa">
                    <div class="inner">
                        <h3> {{ count(Auth::user()->indicados) }}
                        </h3>

                        <p>Players</p>
                        <i class="fa fa-diamond"></i>
                    </div>
                    <div class="icon">

                    </div>

                </div>

            </div>

            <div class="col-lg-3">
                <div class="small-box bg-white caixa">
                    <div class="inner">
                        <h3> {{ Auth::user()->totalindicados() }}</h3>

                        <p>My Squads</p>
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="icon">

                    </div>

                </div>

            </div>

            <div class="col-lg-3">

                <div class="small-box  caixa">
                    <div class="inner">
                        <h3>

                            {{ number_format($reward, 2, ',', '.') }}</h3>

                        <p>Bonus</p>
                        <i class="fa fa-dollar"></i>
                    </div>
                    <div class="icon">

                    </div>

                </div>

            </div>
        </div>
        <br>
        <br>


        <div class="col-md-12 text-center">
            @if ($reward >= 25)
                <a class="btn" href="{{ url('carryout/withdrawal/squad') }}">Withdrawal</a>
            @endif
        </div>
        <br>
        <br>
        <br>


    </div>
    <div class="container">


        <div class="row">
            <div class="col-md-12">
                <div class="panel caixa">
                    <div class="panel-heading">
                        <h3 class="panel-title">Bonus</h3>
                    </div>
                    <div class="panel-header">


                    </div>


                    <div class="panel-body text-center">
                        <div class="table-responsive">
                            <table id="myTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>DESCRIPTION</th>
                                        <th>VALUE</th>
                                        <th>TYPE</th>
                                        <th>DATA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($buscas as $movimento)
                                        <tr style="background-color: transparent">
                                            <td>{{ $movimento->id }}</td>
                                            <td>{{ $movimento->descricao }}</td>
                                            <td>{{ number_format($movimento->valor, 2, ',', '.') }}</td>
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
