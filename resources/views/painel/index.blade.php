@extends('painel.padrao')
@section('css')
    <style>
        p {
            color: white;
        }

        h5 {
            color: white;
        }

        h3 {
            color: white;
        }

        i {
            color: white;
        }

        th {
            color: white;
        }

        tr {
            color: white;
        }


        .boleado {
            border-radius: 25px;
        }

        .borda {
            border: 5px solid;
            border-color: #ffb100;
            background-color: transparent;
            border-radius: 25px;


        }

        .fundo {
            color: white;
            background-color: transparent;
        }

        h1 {
            font-size: 35px;
        }

    </style>
@endsection
@section('content')
    <br>
    <br>

    <br>

    @if (Auth::user()->verifica_fatura == 0)
        <div class="row">
            <div class="container">
                <div class="row row-flex">
                    @forelse ($planos as $plano)
                        <div style="margin-top:30px" class="col- col-md-3">
                            <div id="selecionado{{ $plano->id }}" class="panel borda intas">
                                <div class="panel-body outro">
                                    <h1 style="color: #ffb100;font-size:25px" class="panel-title text-center">{{ $plano->name }}
                                    </h1>
                                    <h6 style="color: white" class="descricao">

                                        @forelse ($plano->vantagems as $vantagem)
                                            <li style="font-size: 19px"> +{{ $vantagem->name }} </li>
                                        @empty
                                        @endforelse

                                    </h6>

                                </div>
                                <div class="panel-footer text-center fundo">

                                    <h4><s> de R$
                                            @if ($plano->id == 3)
                                                499,00
                                            @else
                                                {{ number_format($plano->valor * 2, 2, ',', '.') }}
                                            @endif

                                        </s></h4>
                                    <h3> Por apenas </h3>
                                    <h2> R${{ number_format($plano->valor, 2, ',', '.') }}/mês</h2>
                                    <div class="d-grid gap-2">

                                        <a style="background-color: #ffb100"
                                            class="btn btn-lg btn-success boleado btn-block"
                                            href="{{ url('assinatura', $plano->id) }}">Assine Agora</a>

                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                    @endforelse


                </div>
            </div>

        </div>
    @else
        @if (Auth::user()->mes_ativo == 0)
            <div class="container">
                <div style="margin-top: -50px" class="row">
                    <div class="col-md-12">
                        <div class="panel ">
                            <div class="panel-body">
                                <a class="btn btn-danger btn-block btn-rounded"
                                    href="{{ url('cliente/faturas') }}">Consultar Faturas em
                                    Aberto</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="container">
                <div style="margin-top: -50px" class="row">
                    <div class="col-md-6">
                        <div style="background-color: ;border-style: solid;border-color: #ffb100;" class="small-box novo ">
                            <div class="box-header">
                                <h5 class="box-title">Seus link's de Indicação</h5>

                            </div>
                            <div style="margin-bottom: -30px" class="box-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>Cadastro Direto</p>

                                        <h5 class="box-title">{{ Auth::user()->link }}</h5>

                                        <button style="background-color: #ffb100" onclick="copyToClipboard('#p1')"
                                            class="btn btn-primary btn-rounded btn-block">
                                            Copiar link de
                                            Indicação
                                        </button>
                                    </div>

                                    <div style="margin-top: 25px" class="col-md-12">
                                        <p>Cadastro por LandingPage</p>

                                        <h5 class="box-title">{{ Auth::user()->link }}</h5>

                                        <button style="background-color: #ffb100" onclick="copyToClipboard('#p2')"
                                            class="btn btn-primary btn-rounded btn-block">
                                            Copiar link de
                                            Indicação
                                        </button>
                                    </div>
                                </div>



                                <p style="opacity: 0;" id="p1">{{ url('indicacao', Auth::user()->link) }}</p>
                                <p style="opacity: 0;" id="p2">{{ url('indicacao/v2', Auth::user()->link) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">


                        <div style="color: #fefeff ;border-style: solid;border-color: #ffb100;" class="small-box  novo">
                            <div class="box-header">
                                <h5 class="box-title">Vendas Diarias</h5>

                            </div>
                            <div class="box-content">

                                <div class="chart">
                                    <canvas id="bar-chart" width="800" height="400"></canvas>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">


                <div class="row">
                    <div class="col-lg-3">
                        <div style="background-img: url('{{ asset('bg.png') }}');border-style: solid;border-color: #ffb100;"
                            class="small-box bg-white">
                            <div class="inner">
                                <h3>{{ Auth::user()->contador }}</h3>

                                <p>Cliques de Indicação</p>
                                <i class="fa fa-rocket"></i>
                            </div>
                            <div style="font-size: 20px" class="icon">

                            </div>

                        </div>

                    </div>


                    <div class="col-lg-3">
                        <div style="background-color: ;border-style: solid;border-color: #ffb100;" class="small-box  ">
                            <div class="inner">
                                <h3> {{ count(
                                    Auth::user()->indicados()->whereHas('assinaturas')->get(),
                                ) }}
                                </h3>

                                <p>Afiliados</p>
                                <i class="fa fa-diamond"></i>
                            </div>
                            <div class="icon">

                            </div>

                        </div>

                    </div>
                    <div class="col-lg-3">

                        <div style="background-color: ;border-style: solid;border-color: #ffb100;" class="small-box  ">
                            <div class="inner">
                                <h3>

                                    {{ number_format(Auth::user()->saldo, 2, ',', '.') }}</h3>

                                <p>Comissões</p>
                                <i class="fa fa-money"></i>
                            </div>
                            <div class="icon">

                            </div>

                        </div>

                    </div>
                    <div class="col-lg-3">
                        <div style="background-color: ;border-style: solid;border-color: #ffb100;"
                            class="small-box bg-white">
                            <div class="inner">
                                <h3> {{ number_format(Auth::user()->total, 2, ',', '.') }}</h3>

                                <p>Total de Vendas</p>
                                <i class="fa fa-dollar"></i>
                            </div>
                            <div class="icon">

                            </div>

                        </div>

                    </div>
                </div>
                <div class="row">

                    <div class="col-md-4">


                        <div style="background-color: ;border-style: solid;border-color: #ffb100;" class="small-box  outro">
                            <div class="box-header">
                                <h5 class="box-title">Top 10 Afiliados</h5>

                            </div>
                            <div class="box-body">

                                <div class="table-responsive">
                                    <table class="table">
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

                                                        {{ number_format($indicado->saldo, 2, ',', '.') }}</td>
                                                    <td>{{ count($indicado->indicados) }}</td>

                                                </tr>
                                            @empty
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">

                        <div class="row">

                            <div class="col-md-12">
                                <div style="background-color: ;border-style: solid;border-color: #ffb100;"
                                    class="small-box  outro">
                                    <div class="box-header">
                                        <h5 class="box-title">Carreira</h5>

                                    </div>


                                    <div class="box-body">

                                        @if (Auth::user()->ordem == 0)
                                            <p>Você está rumo ao <strong>{{ $proximo->name }}</strong></p>
                                            <center>
                                                <img @if (Auth::user()->pontos < $proximo->pontuacao && Auth::user()->ordem == 0) style="filter: grayscale({{ 100 - Auth::user()->perc }}%)" @endif
                                                    class="img img-responsive" width="65%"
                                                    src="{{ asset('pin/' . $proximo->getImgAttribute($proximo->id)) }}"
                                                    alt="">

                                            </center>
                                        @else
                                            @if (Auth::user()->ordem == 12)
                                                <p>PIN atual <strong>{{ $pin->name }}</strong></p>
                                            @else
                                                @if (Auth::user()->ordem > 0 && Auth::user()->ordem < 12)
                                                    <h4 style="color: #ffb100"> Parabéns agora você é
                                                        <strong>{{ $pin->name }}</strong>
                                                    </h4>
                                                @endif
                                                <p>Você está rumo ao <strong>{{ $proximo->name }}</strong></p>
                                                <h1></h1>
                                                <center>
                                                    <img @if (Auth::user()->pontos < $pin->pontuacao && Auth::user()->ordem == 0) style="filter: grayscale({{ 100 - Auth::user()->perc }}%)" @endif
                                                        class="img img-responsive" width="65%"
                                                        src="{{ asset('pin/' . $pin->getImgAttribute($pin->id)) }}"
                                                        alt="">

                                                </center>
                                            @endif

                                        @endif


                                        <div class="progress">
                                            <div class="progress-bar progress-bar-warning" role="progressbar"
                                                aria-valuenow="{{ Auth::user()->perc }}" aria-valuemin="0"
                                                aria-valuemax="100" style="width:{{ Auth::user()->perc }}%">
                                                <span class="sr-only">70% Complete</span>
                                            </div>
                                        </div>

                                        <center>
                                            <h3>{{ Auth::user()->pontos }}</h3>
                                        </center>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div style="background-color: ;border-style: solid;border-color: #ffb100;" class="small-box  outro">
                            <div class="box-header">
                                <h5 class="box-title">Histórico de pontos acumulados</h5>

                            </div>
                            <div class="box-body">


                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Mês</th>
                                            <th>Pontos</th>


                                        </tr>
                                    </thead>
                                    <tbody>


                                        @forelse (Auth::user()->historicos as $historico)
                                            <tr>
                                                <td>{{ Carbon\Carbon::create($historico->inicio)->isoFormat('MMMM') }}
                                                </td>
                                                <td>{{ $historico->pontos }}</td>

                                            </tr>
                                        @empty
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                </div>

                <div class="row">

                </div>


            </div>
        @endif
    @endif








@endsection


@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script>
        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
        }


        $(document).ready(function() {
            var heights = $(".outro").map(function() {
                    return $(this).height();
                }).get(),

                maxHeight = Math.max.apply(null, heights);

            $(".outro").height(maxHeight);
        });
        $(document).ready(function() {
            var heights = $(".novo").map(function() {
                    return $(this).height();
                }).get(),

                maxHeight = Math.max.apply(null, heights);

            $(".novo").height(maxHeight);
        });


        $(function() {
            var ctx = document.getElementById("bar-chart").getContext("2d");
            // examine example_data.json for expected response data
            var json_url = "{{ url('api/total') }}";

            // draw empty chart
            var myChart = new Chart(ctx, {
                scaleFontColor: "white",
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [

                        {
                            label: "Total de Vendas",
                            fill: false,
                            lineTension: 0.1,
                            backgroundColor: "#ffb100",
                            borderColor: "#ffb100",
                            borderCapStyle: 'butt',
                            borderDash: [],
                            borderDashOffset: 0.0,
                            borderJoinStyle: 'miter',
                            pointBorderColor: "rgba(75,192,192,1)",
                            pointBackgroundColor: "#ffb100",
                            pointBorderWidth: 1,
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: "rgba(75,192,192,1)",
                            pointHoverBorderColor: "rgba(220,220,220,1)",
                            pointHoverBorderWidth: 2,
                            pointRadius: 1,
                            pointHitRadius: 10,
                            data: [],
                            spanGaps: false,
                        },

                    ]
                },
                options: {
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    legend: {
                        labels: {
                            fontColor: "white",
                            fontSize: 18
                        }
                    },

                    scales: {
                        yAxes: [{
                            ticks: {
                                fontColor: "white",
                                beginAtZero: true
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontColor: "white",
                                fontSize: 14,
                                stepSize: 1,
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });

            ajax_chart(myChart, json_url);

            // function to update our chart
            function ajax_chart(chart, url, data) {
                //   console.log(data);
                // var data = data || [];

                $.get("{{ url('api/dados', Auth::user()->id) }}", function(resultado) {
                    var datas = resultado;
                    var nomes = [],
                        total = [],
                        insc = [],
                        feitos = [];
                    $.each(datas, function(i, data) {
                        nomes.push(data.name);
                        total.push(data.total);
                        insc.push(data.total);
                        feitos.push(data.total)
                    });

                    chart.data.labels = nomes;
                    chart.data.datasets[0].data = insc; // or you can iterate for multiple datasets
                    // or you can iterate for multiple datasets
                    chart.update(); // finally update our chart
                });
            }
        });
    </script>
@endsection
