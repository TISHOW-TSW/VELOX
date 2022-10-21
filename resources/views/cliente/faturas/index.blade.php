@extends('painel.padrao')
@section('css')
    <style>
        body {
            color: white
        }

        .caixa {
            border-radius: 15px;
            /*opacity: 55%*/
            background-color: rgba(39, 34, 40, 0.9);
            border: 3px solid rgba(233, 0, 0, 0.4);
            color: white;
            padding-bottom: 30px;
            display: flex;
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
                        <h5 class="panel-title">Planos/Carros
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
                                    <td>#</td>
                                    <th>Carro</th>

                                    <th>Valor</th>
                                    <th>Corridas</th>
                                    <th>Status</th>
                                    <th class="text-center">Ação</th>
                                </tr>
                                </thead>
                                <tbody>

                                @forelse (Auth::user()->compras as $assinatura)
                                    <tr>
                                        <td>{{$assinatura->id}}</td>
                                        <td>{{ $assinatura->plano->name }}</td>

                                        <td>
                                            R${{ number_format($assinatura->plano->valor, 2, ',', '.') }}
                                        </td>
                                        <td>
                                            <div class="progress">

                                                <div class="progress-bar bg-red-gradient" role="progressbar"
                                                     style="width: {{$assinatura->diasContados()}}%;"
                                                     aria-valuenow="{{count($assinatura->rendimentos)}}"
                                                     aria-valuemin="0" aria-valuemax="100">
                                                    {{count($assinatura->rendimentos)}}/5
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $assinatura->ativo_formated }}</td>
                                        <td class="text-center">

                                            @if ($assinatura->status == 1)

                                                <a href="{{ url('cancelar', $assinatura->id) }}"
                                                   class="btn">Cancelar Contrato</a>

                                            @endif
                                            @if($assinatura->status == 0)
                                                <a href="{{ url('player/payment', $assinatura->id) }}"
                                                   class="btn">Efetuar Pagamento</a>
                                                <a href="{{ url('cancelship', $assinatura->id) }}"
                                                   class="btn">Excluir</a>
                                            @endif
                                            @if($assinatura->status == 2)
                                                @if($assinatura->saldoRaiz->valor > 0)
                                                    <a href="{{url('sacarraiz',$assinatura->id)}}" class="btn">Sacar
                                                        Raiz</a>
                                                @endif
                                                @if($assinatura->saldoRaiz->valor == $assinatura->plano->valor)
                                                    <a href="{{url('renovar',$assinatura->id)}}" class="btn">Renovar</a>
                                                @endif

                                                @if($assinatura->saldoRaiz->saldoRendimento->valor > 0)
                                                    <a href="{{url('sacarrendimento',$assinatura->id)}}" class="btn">Sacar
                                                        Rendimento</a>
                                                @endif
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
