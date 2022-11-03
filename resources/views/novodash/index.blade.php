@extends('painel.padrao')

@section('css')

    <link href="https://cdn.jsdelivr.net/npm/glider-js@1/glider.min.css" rel="stylesheet">

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
            background-color: rgba(39, 34, 40, 0.9);
            border: 3px solid rgba(233, 0, 0, 0.4);
            color: white;
            padding-bottom: 30px;
            display: flex;
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

        .caixa {
            border-radius: 15px;

            background-color: rgba(233, 0, 0, 0.4);
            color: white
        }

    </style>

    <style>

        .countdown-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .countdown-el {
            text-align: center;
        }

        .countdown-el span {
            font-size: 1.3rem;
        }

    </style>

@endsection

@section('content')
    <br>
    <br>
    <br>

    <div class="row text-center">

        <div class="col-md-4"></div>
        <div style="" class="col-md-4"><img class="img img-responsive" src="" alt="">

        </div>
        <div class="col-md-4"></div>
    </div>


    <div class="container">
        <article class="c-carousel c-carousel--simple">
            <div class="c-carousel__slides js-carousel--simple">

                @forelse($planos as $plano)
                    <article style="margin-right: 100px" class="c-carousel__slide text-center">

                        <div style="height: 500px" id="selecionado{{ $plano->id }}" class="panel  borda intasf">
                            <div class="panel-body outro text-center">
                                <h2 style="color: #ffd700;" class=" text-center">{{ $plano->name }}</h2>


                                </h6>
                                <center>

                                    <img
                                        @if (! $busca = App\Models\Compra::where('user_id', Auth::user()->id)->where('plano_id', $plano->id)->first())

                                            style="height: 150px;filter: grayscale(100%);"

                                        @else

                                            style="height: 150px;"

                                        @endif


                                        class="img img-responsive nave"
                                        src="{{ "https://nftcash.sfo3.digitaloceanspaces.com/" . $plano->img }}" alt="">

                                </center>
                            </div>


                            <center>

                                @if ($busca = App\Models\Compra::where('user_id', Auth::user()->id)->where('plano_id', $plano->id)->where('status',1)->first())

                                    @if(count($busca->rendimentos) == 0)

                                        @if($busca->updated_at->addDay() >= \Carbon\Carbon::now())
                                            <p>Sua Primeira Corrida será</p>
                                            <p id="demo{{$busca->id}}"></p>
                                            <button class="btn">

                                                Abastecendo
                                            </button>



                                            <script>
                                                // Set the date we're counting down to

                                                var countDownDate{{$busca->id}} = new Date("{{\Carbon\Carbon::parse($busca->updated_at->addDay())->format('M d, Y H:i:s')}}").getTime();
                                               // var countDownDate = new Date("").getTime();



                                                // Update the count down every 1 second
                                                var x = setInterval(function() {

                                                    // Get today's date and time
                                                    var now = new Date().getTime();

                                                    // Find the distance between now and the count down date
                                                    var distance{{$busca->id}} =   countDownDate{{$busca->id}} - now;

                                                    // Time calculations for days, hours, minutes and seconds
                                                    var days = Math.floor(distance{{$busca->id}} / (1000 * 60 * 60 * 24));
                                                    var hours = Math.floor((distance{{$busca->id}} % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                    var minutes = Math.floor((distance{{$busca->id}} % (1000 * 60 * 60)) / (1000 * 60));
                                                    var seconds = Math.floor((distance{{$busca->id}} % (1000 * 60)) / 1000);

                                                    // Display the result in the element with id="demo"
                                                    document.getElementById("demo{{$busca->id}}").innerHTML = days + "d " + hours + "h "
                                                        + minutes + "m " + seconds + "s ";

                                                    // If the count down is finished, write some text
                                                    if (distance{{$busca->id}} < 0) {
                                                        clearInterval(x);
                                                        document.getElementById("demo").innerHTML = "EXPIRED";
                                                    }
                                                }, 1000);
                                            </script>

                                        @else
                                            <img style="display: none" id="aparecer{{$busca->id}}" class="img img-responsive"
                                                 src="{{url('acelera.gif')}}" alt="">
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
                                    @else
                                        @if($busca->rendimentos->last()->created_at->addDay() >= \Carbon\Carbon::now())
                                            <p>Sua Proxima Corrida será</p>
                                            <p id="demo{{$busca->id}}"></p>
                                            <button class="btn">

                                                Abastecendo
                                            </button>



                                            <script>
                                                // Set the date we're counting down to

                                                var countDownDate{{$busca->id}} = new Date("{{\Carbon\Carbon::parse($busca->rendimentos->last()->created_at->addDay())->format('M d, Y H:i:s')}}").getTime();
                                                // var countDownDate = new Date("").getTime();



                                                // Update the count down every 1 second
                                                var x = setInterval(function() {

                                                    // Get today's date and time
                                                    var now = new Date().getTime();

                                                    // Find the distance between now and the count down date
                                                    var distance{{$busca->id}} =   countDownDate{{$busca->id}} - now;

                                                    // Time calculations for days, hours, minutes and seconds
                                                    var days = Math.floor(distance{{$busca->id}} / (1000 * 60 * 60 * 24));
                                                    var hours = Math.floor((distance{{$busca->id}} % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                    var minutes = Math.floor((distance{{$busca->id}} % (1000 * 60 * 60)) / (1000 * 60));
                                                    var seconds = Math.floor((distance{{$busca->id}} % (1000 * 60)) / 1000);

                                                    // Display the result in the element with id="demo"
                                                    document.getElementById("demo{{$busca->id}}").innerHTML = days + "d " + hours + "h "
                                                        + minutes + "m " + seconds + "s ";

                                                    // If the count down is finished, write some text
                                                    if (distance{{$busca->id}} < 0) {
                                                        clearInterval(x);
                                                        document.getElementById("demo").innerHTML = "EXPIRED";
                                                    }
                                                }, 1000);
                                            </script>




                                        @else
                                            <img style="display: none" id="aparecer{{$busca->id}}" class="img img-responsive"
                                                 src="{{url('acelera.gif')}}" alt="">
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


                                    @if ($busca->status == 0)
                                        <br><br>
                                        <a class="btn" href="{{ url('customer/invoices') }}">
                                            {{ $busca->ativo_formated }}
                                        </a>
                                    @endif
                                    @if ($busca->status == 2)
                                        <br><br>
                                        <a class="btn" href="{{ url('purchase', $plano->id) }}">
                                            $ {{ number_format($plano->valor, 2, ',', '.') }}
                                        </a>
                                    @endif
                                @else
                                    <br><br>
                                    @if ($plano->id == 6 || $plano->id == 7 || $plano->id == 8 || $plano->id == 9 || $plano->id == 10)
                                        @if (Auth::user()->ordem > 0 && $plano->id == 6)
                                            <a class="btn" href="{{ url('purchase', $plano->id) }}">
                                                $ {{ number_format($plano->valor, 2, ',', '.') }}
                                            </a>
                                        @else
                                            @if (Auth::user()->ordem > 1 && $plano->id == 7)
                                                <a class="btn" href="{{ url('purchase', $plano->id) }}">
                                                    $ {{ number_format($plano->valor, 2, ',', '.') }}
                                                </a>
                                            @else
                                                @if (Auth::user()->ordem > 2 && $plano->id == 8)
                                                    <a class="btn" href="{{ url('purchase', $plano->id) }}">
                                                        $ {{ number_format($plano->valor, 2, ',', '.') }}
                                                    </a>
                                                @else
                                                    @if (Auth::user()->ordem > 3 && $plano->id == 9)
                                                        <a class="btn" href="{{ url('purchase', $plano->id) }}">
                                                            $ {{ number_format($plano->valor, 2, ',', '.') }}
                                                        </a>
                                                    @else
                                                        @if (Auth::user()->ordem > 4 && $plano->id == 10)
                                                            <a class="btn" href="{{ url('purchase', $plano->id) }}">
                                                                $ {{ number_format($plano->valor, 2, ',', '.') }}
                                                            </a>
                                                        @endif
                                                    @endif
                                                    <p style="font-size: 35px;color: yellow">
                                                        R$ {{ number_format($plano->valor, 2, ',', '.') }}</p>
                                                    <a class="btn" href="#">
                                                        BLOQUEADO
                                                    </a>
                                                @endif
                                            @endif
                                        @endif
                                    @else

                                        <p style="font-size: 35px;color: yellow">
                                            R$ {{ number_format($plano->valor, 2, ',', '.') }}</p>
                                        <a class="btn" href="{{ url('purchase', $plano->id) }}">
                                            Comprar
                                        </a>

                                    @endif
                                @endif

                            </center>


                        </div>
                    </article>
                @empty
                @endforelse

            </div>

            <button class="js-carousel--simple-prev">«</button>
            <button class="js-carousel--simple-next">»</button>
            <div class="js-carousel--simple-dots"></div>
        </article>


    </div>


    <div class="container text-center">
        <div class="col-md-12">
            <div class="panel caixa ">
                <div class="box-header">
                    <h5 class="box-title" style="color: white">Link de Indicação</h5>

                </div>
                <div style="margin-bottom: -30px" class="box-body">


                    <h5 class="box-title">{{ Auth::user()->link }}</h5>
                    <center>
                        <button onclick="copyToClipboard('#p1')" class="btn">

                            Copiar Link
                        </button>
                    </center>
                    <br><br>


                    <p style="opacity: 0;margin-bottom: -20px" id="p1">{{ url('recruit', Auth::user()->link) }}</p>

                </div>
            </div>
        </div>

    </div>


    <div class="container">


        <div class="row">
            <div class="col-lg-3">
                <div class="small-box bg-white caixa">
                    <div class="inner">
                        <h3>{{ Auth::user()->contador }}</h3>

                        <p>CLIQUES NO LINK
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

                        <p>DIRETOS</p>
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

                        <p>EQUIPE</p>
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
    <script>
        $(document).ready(function () {

            document.getElementsByClassName("nave").style.filter = "grayscale(100%)";
            var heights = $(".intas").map(function () {
                    return $(this).height();
                }).get(),

                maxHeight = Math.max.apply(null, heights);

            $(".intas").height(maxHeight);
        });

        function carreganave(ship) {
            $('#aparecer' + ship).show();
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

    <script>

        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/glider-js@1/glider.min.js"></script>

    <script src="//https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.0.2/glide.js"></script>

    <script>
        var $simpleCarousel = document.querySelector(".js-carousel--simple");

        new Glider($simpleCarousel, {
            slidesToShow: 2,
            slidesToScroll: 2,
            draggable: true,
            dots: ".js-carousel--simple-dots",
            arrows: {
                prev: ".js-carousel--simple-prev",
                next: ".js-carousel--simple-next",
            },
        });
    </script>



@endsection
