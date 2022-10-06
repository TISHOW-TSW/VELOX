@extends('painel.padrao')
@section('css')
    <style>
        .boleado {
            border-radius: 25px;
        }

        .panel-title {
            font-family: 'Inter', sans-serif, bold;
            font-weight: bold;
        }


        .borda {
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

        .gogo {
            font-family: 'Inter', sans-serif, bold;
            font-weight: bold;
        }



        .fundo {
            color: white;
            background-color: transparent;

        }

        h1 {
            font-size: 70px;
            font-family: GraublauWeb;
        }

    </style>
@endsection

@section('content')

    <div class="row">
        <div class="container">

            @forelse ($planos as $plano)
                <div style="margin-top:30px" class="col- col-md-3">
                    <div id="selecionado{{ $plano->id }}" class="panel  borda ">
                        <div class="panel-body outro text-center">
                            <h2 style="color: #ffd700;" class=" text-center">{{ $plano->name }}</h2>


                                @forelse ($plano->vantagems as $vantagem)
                                    <li style="font-size: 19px"> +{{ $vantagem->name }} </li>
                                @empty
                                @endforelse

                            </h6>
                            <center>

                                <img style="height: 150px"
                                    @if ($busca = App\Models\Compra::where('user_id', Auth::user()->id)->where('plano_id', $plano->id)->first()) @if ($busca->campanha2() != 0)
                                style="filter: grayscale(100%);"
                                    @endif
                                @else

                                @endif


                                 class="img img-responsive nave"
                                src="{{ "https://nftcash.sfo3.digitaloceanspaces.com/" . $plano->img }}" alt="">

                            </center>
                        </div>


                        <center>

                            @if ($busca = App\Models\Compra::where('user_id', Auth::user()->id)->where('plano_id', $plano->id)->first())
                                @if ($busca->ativo == 1)

                                    @if ($busca->campanha2() == 1)
                                        <div style="border-radius: 10px" class="progress">
                                            <div class="progress">
                                                <div style="background-color: red" class="progress-bar"
                                                    role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                                    aria-valuemax="100" style="width: 0%;"
                                                    id="current_progress{{ $busca->id }}" data-current="0">
                                                    0%
                                                </div>
                                            </div>
                                        </div>
                                        <button id="ship{{ $plano->id }}" class="btn"
                                            onclick="carreganave({{ $busca->id }})">
                                            Play
                                        </button>
                                    @endif

                                    @if ($busca->campanha2() == 0)
                                        <br>
                                        <br>
                                        <button id="ship{{ $plano->id }}" class="btn">



                                            Abastecendo
                                        </button>
                                    @endif

                                    @if ($busca->campanha2() == 2)

                                            <img style="display: none" id="aparecer" class="img img-responsive" src="{{url('acelera.gif')}}" alt="">
                                        <div style="border-radius: 10px" class="progress">
                                            <div class="progress">
                                                <div style="background-color: purple" class="progress-bar"
                                                    role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                                    aria-valuemax="100" style="width: 0%;"
                                                    id="current_progress{{ $busca->id }}" data-current="0">
                                                    0%
                                                </div>
                                            </div>
                                        </div>
                                        <button id="ship{{ $plano->id }}" class="btn"
                                            onclick="carreganave({{ $busca->id }})">
                                            Play
                                        </button>
                                    @endif
                                @endif

                                @if ($busca->ativo == 0)
                                    <br><br>
                                    <a class="btn" href="{{ url('customer/invoices') }}">
                                        {{ $busca->ativo_formated }}
                                    </a>
                                @endif
                                @if ($busca->ativo == 2)
                                    <br><br>
                                    <a class="btn" href="{{ url('purchase', $plano->id) }}">
                                        $ {{ number_format($plano->valor, 2, ',', '.') }}
                                    </a>
                                @endif
                            @else
                                <br><br>
                                @if ($plano->id == 5 || $plano->id == 6 || $plano->id == 7 || $plano->id == 8)
                                    @if (Auth::user()->ordem > 0 && $plano->id == 5)
                                        <a class="btn" href="{{ url('purchase', $plano->id) }}">
                                            $ {{ number_format($plano->valor, 2, ',', '.') }}
                                        </a>
                                    @else
                                        @if (Auth::user()->ordem > 1 && $plano->id == 6)
                                            <a class="btn" href="{{ url('purchase', $plano->id) }}">
                                                $ {{ number_format($plano->valor, 2, ',', '.') }}
                                            </a>
                                        @else
                                            @if (Auth::user()->ordem > 2 && $plano->id == 7)
                                                <a class="btn" href="{{ url('purchase', $plano->id) }}">
                                                    $ {{ number_format($plano->valor, 2, ',', '.') }}
                                                </a>
                                            @else
                                                @if (Auth::user()->ordem > 3 && $plano->id == 8)
                                                    <a class="btn" href="{{ url('purchase', $plano->id) }}">
                                                        $ {{ number_format($plano->valor, 2, ',', '.') }}
                                                    </a>
                                                @endif
                                                <a class="btn" href="#">
                                                    BLOCKED
                                                </a>
                                            @endif
                                        @endif
                                    @endif
                                @else
                                    <a class="btn" href="{{ url('purchase', $plano->id) }}">
                                        $ {{ number_format($plano->valor, 2, ',', '.') }}
                                    </a>
                                @endif
                            @endif


                        </center>


                    </div>
                </div>


            @empty
            @endforelse
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3 text-center">
                    <span id="remaining"></span>
                </div>
            </div>


        </div>
    </div>



@endsection


@section('js')
    <script>
        $(document).ready(function() {

            document.getElementsByClassName("nave").style.filter = "grayscale(100%)";
            var heights = $(".intas").map(function() {
                    return $(this).height();
                }).get(),

                maxHeight = Math.max.apply(null, heights);

            $(".intas").height(maxHeight);
        });


        function carreganave(ship) {
            $('#aparecer').show();
            $("#ship" + ship).attr("disabled", "disabled");
            var interval = setInterval(updateProgress, 1000); // run updateProgress() every second

            var progress = $('#current_progress' + ship); // progress bar

            var current = progress.data('current'); // data-current attribute value

            var remaining_span = $('#remaining'); // remaining time span

            var max = 100; // max progress bar width

            var time = 5; // time in seconds

            var remaining = parseFloat(max) - parseFloat(current); // calculate remaining of progress bar width

            var progress_second = remaining / time; // calculate percent of progress per second

            /**
             * update progress bar & remaining time
             *
             * @return void
             */
            function updateProgress() {
                var width = current + progress_second; // calculate width of progress bar

                current = width; // set current width

                time += 1; // subtract time every second

                progress.data('current', width.toFixed(2)); // set data-current attribute value

                progress.css('width', width.toFixed(2) + '%'); // set progress bar width

                progress.text(width.toFixed(2) + '%'); // set progress text



                remaining_span.text(time); // set remaining time text
                // filter: grayscale(100%)

                // if progress bar width is 100% or more
                // then stop the interval
                if (width >= 100) {
                    clearInterval(interval);
                    window.location.href = "{{ url('getupship') }}/" + ship;
                    //location.reload();
                }

            }

        }
    </script>
@endsection
