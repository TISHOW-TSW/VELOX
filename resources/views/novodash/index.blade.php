@extends('painel.padrao')

@section('css')
    <style>
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
    <br>
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
                    <p style="opacity: 0;margin-bottom: -20px" id="p2">{{ url('recruit/v2', Auth::user()->link) }}</p>
                </div>
            </div>
        </div>
        <a href="{{ url('ships') }}" style="font-size: 20px" class="btn">Jogar</a>
    </div>
    <div class="row text-center">

        <div class="col-md-4"></div>
        <div style="" class="col-md-4"> <img class="img img-responsive" src="" alt="">

        </div>
        <div class="col-md-4"></div>
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
