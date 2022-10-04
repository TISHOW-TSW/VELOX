@extends('painel.padrao')
@section('css')
    <style>
        body {
            color: white
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

    </style>
@endsection
@section('content')
    <br>
    <br>
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <div style="border-radius: 15px" class="painel caixa">
                    <div class="panel-heading">
                        <h5 class="panel-title">Payment per Ship
                        </h5>
                    </div>
                    <div style="opacity: 100%" class="box-body">
                        @php
                            $saldo = Auth::user()->creditos->sum('valor');
                            $controle = 12;
                            $sobra = 0;
                        @endphp
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>

                                        <th>Ship</th>

                                        <th>Value</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse (Auth::user()->compras as $assinatura)
                                        <tr>

                                            <td>{{ $assinatura->plano->name }}</td>

                                            <td>
                                                ${{ number_format($assinatura->plano->valor, 2, ',', '.') }}
                                            </td>
                                            <td>{{ $assinatura->ativo_formated }}</td>
                                            <td class="text-center">

                                                @if ($assinatura->ativo == 1)
                                                    <button class="btn">Paid</button>
                                                @else
                                                    <a href="{{ url('player/payment', $assinatura->id) }}"
                                                        class="btn">Payment</a>
                                                    <a href="{{ url('cancelship', $assinatura->id) }}"
                                                        class="btn">Cancel</a>
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
        </div>
    </div>
    </div>
@endsection()


@section('js')
    <script>
        function newTab(url) {
            window.open(url, '_blank');
            setTimeout(reload, 5000);
        }


        function reload() {
            document.location.reload();
        }
    </script>
@endsection
