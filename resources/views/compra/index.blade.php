@extends('painel.padrao')

@section('css')
    <style>
        body {
            color: white;
        }

        .caixa {
            border-radius: 15px;

            background-color: rgba(233, 0, 0, 0.4);
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
    <div class="container">


    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-solid">
                    <div class="card-body">
                        <div class="row">

                            <h3 style="color: white" class="d-inline-block d-sm-none">{{ $compra->plano->name }}</h3>
                            <div class="col-12">
                                <img class="img img-responsive"
                                     src="{{ "https://nftcash.sfo3.digitaloceanspaces.com/". $compra->plano->img }}"
                                     class="product-image" alt="Product Image">

                            </div>


                        </div>

                        <!-- /.card-body -->
                    </div>
                </div>

            </div>
            <div class="col-md-6">
                <div style="border-radius: 10px" class="panel caixa">
                    <div class="panel-heading">

                    </div>
                    <div class="panel-body text-center">

                        <center>
                            <h2 class="mb-0">
                                $ {{ number_format($compra->plano->valor, 2, ',', '.') }}
                            </h2>
                        </center>
                        <h2>Selecionar Metodo de Pagamento</h2>
                        <a target="_blank" href="{{url('gerarpix',$compra->id)}}" class="btn intas">
                            CREDITO
                        </a>
                        <a target="_blank" href="{{url('geraroix2',$compra->id)}}" class="btn intas">
                            PIX
                        </a>

                        <br>
                        <br>

                        <br>
                        <br>

                        <br>


                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection


@section('js')
    <script>
        function fracao(ship) {

            $(".intas").css("background-color", "transparent");
            document.getElementById('sel' + ship).style.backgroundColor = '#055863';
            $('input[name="moeda"]').attr('value', ship);
            // calculate percent of progress per second


        }
    </script>
@endsection
