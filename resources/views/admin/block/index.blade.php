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
    <div class="container">
        <div class="panel caixa">
            <div class="panel-body">
                <div style="z-index: 3000" class="col-md-6 text-center">
                    {!! QrCode::size(300)->backgroundColor(255, 255, 255)->generate($compra->pay_address) !!}
                    <p>{{ $compra->pay_address }}</p>
                    <button onclick="copyToClipboard('#p1')" class="btn">Copy Wallet</button>
                    <p style="opacity: 0;margin-bottom: -20px" id="p1">{{ $compra->pay_address }}</p>
                    <p style="opacity: 0;margin-bottom: -20px" id="p2">{{ $compra->consultahash()['pay_amount'] }}</p>
                </div>
                <div class="col-md-6 text-center">
                    <h1>{{ $compra->plano->name }}</h1>

                    <h2 for="" style="text-transform: uppercase"> Cryto {{ $compra->consultahash()['pay_currency'] }}</h2>
                    <h2 for="">$ {{ $compra->consultahash()['price_amount'] }}</h2>

                    <h2 for="">Amount {{ $compra->consultahash()['pay_amount'] }}</h2>
                    <button onclick="copyToClipboard('#p2')" class="btn">Copy Fraction</button>
                    <h2 for="">Status {{ $compra->consultahash()['payment_status'] }}</h2>

                </div>
            </div>
        </div>

    </div>
@endsection
@section('js')
    <script>
        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
        }
    </script>
@endsection
