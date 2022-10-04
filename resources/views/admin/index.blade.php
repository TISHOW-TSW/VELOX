@extends('painel.padrao')

@section('content')
    <br>
    <br>
    <div class="container">


        <div class="row">
            <div class="col-lg-3">
                <div class="small-box bg-gray">
                    <div class="inner">
                        <h3 style="font-size: 25px">$ {{ number_format($entrada, 2, ',', '.') }}</h3>

                        <p>TOTAL DE ENTRADA</p>
                        <i class="fa fa-line-chart"></i>
                    </div>


                </div>

            </div>
            <div class="col-lg-3">
                <div class="small-box bg-gray">
                    <div class="inner">
                        <h3 style="font-size: 25px"> $ {{ number_format($saques, 2, ',', '.') }}
                        </h3>

                        <p>TOTAL DE SAQUES</p>
                        <i class="fa fa-bar-chart"></i>
                    </div>


                </div>

            </div>
            <div class="col-lg-3">

                <div class="small-box bg-gray">
                    <div class="inner">
                        <h3 style="font-size: 25px">


                            $ {{ number_format($totalpendente, 2, ',', '.') }}
                        </h3>

                        <p>VALORES PENDENTES DE FATURAS</p>
                        <i class="fa fa-area-chart"></i>
                    </div>
                    <div style="font-size: 60px" class="icon">

                    </div>

                </div>

            </div>
            <div class="col-lg-3">
                <div class="small-box bg-gray">
                    <div class="inner">
                        <h3 style="font-size: 25px">$ {{ number_format($saida, 2, ',', '.') }}</h3>

                        <p>DESPESAS PAGAS</p>
                        <i class="fa fa-sign-out"></i>
                    </div>
                    <div style="font-size: 60px" class="icon">

                    </div>

                </div>

            </div>
            <div class="col-lg-3">
                <div class="small-box bg-gray">
                    <div class="inner">
                        <h3 style="font-size: 25px">{{ count($ativos) }}</h3>

                        <p>USUARIOS ATIVOS</p>
                        <i class="fa fa-users"></i>
                    </div>
                    <div style="font-size: 60px" style="color: green" class="icon">

                    </div>

                </div>

            </div>
            <div class="col-lg-3">
                <div class="small-box bg-gray">
                    <div class="inner">
                        <h3 style="font-size: 25px"> {{ count($inativos) }}</h3>

                        <p>USUARIOS PENDENTES</p>
                        <i class="fa fa-user-plus"></i>
                    </div>
                    <div style="font-size: 60px" style="color: red" class="icon">

                    </div>

                </div>

            </div>
            <div class="col-lg-3">

                <div class="small-box bg-gray">
                    <div class="inner">
                        <h3 style="font-size: 25px">

                            {{ $faturaspagas }}
                        </h3>

                        <p>FATURAS PAGAS</p>
                        <i class="fa fa-check "></i>
                    </div>
                    <div style="font-size: 60px" class="icon">

                    </div>

                </div>

            </div>
            <div class="col-lg-3">
                <div class="small-box bg-gray">
                    <div class="inner">
                        <h3 style="font-size: 25px"> {{ $faturaspendentes }}</h3>

                        <p>FATURAS PENDENTES</p>
                        <i class="fa fa-plus"></i>


                    </div>
                    <div style="font-size: 60px" class="icon">
                    </div>

                </div>

            </div>
        </div>

        <div class="row">


            <div class="row">
                <div class="col-md-4">

                    <div class="box well">
                        <div class="box-header">
                            <h5 class="box-title">Top 10 Afiliados</h5>

                        </div>
                        <div class="box-body">


                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Indicado</th>
                                        <th>Faturamento</th>
                                        <th>Afiliados</th>

                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse ($users as $indicado)
                                        <tr>
                                            <td>{{ $indicado->name }}</td>
                                            <td>

                                                {{ number_format($indicado->saldo, 2, ',', '.') }}
                                            </td>
                                            <td>{{ $indicado->indicados_count }}</td>

                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">

                    <div class="box well">
                        <div class="box-header">
                            <h5 class="box-title">Estados</h5>

                        </div>
                        <div class="box-body">


                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Estado</th>
                                        <th>QTD</th>


                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse ($estados as $estado)
                                        <tr>
                                            <td>{{ $estado->name }}</td>
                                            <td>{{ $estado->enderecos_count }}</td>


                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <div class="col-md-4">

                    <div class="box well">
                        <div class="box-header">
                            <h5 class="box-title">Meios de Pagamento</h5>

                        </div>
                        <div class="box-body">


                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Meios</th>
                                        <th>QTD</th>


                                    </tr>
                                </thead>
                                <tbody>


                                    <tr>
                                        <td>CARTÃO DE CREDITO</td>
                                        <td>{{ App\Models\Assinatura::where('tipo', 'CREDIT_CARD')->count() }}</td>


                                    </tr>
                                    <tr>
                                        <td>BOLETO</td>
                                        <td>{{ App\Models\Assinatura::where('tipo', 'BOLETO')->count() }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>


        </div>

    </div>


    </div>
@endsection


@section('js')
    <script>
        $(document).ready(function() {
            var heights = $(".well").map(function() {
                    return $(this).height();
                }).get(),

                maxHeight = Math.max.apply(null, heights);

            $(".well").height(maxHeight);
        });
    </script>
@endsection
