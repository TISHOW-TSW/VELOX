<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>veloxfive | REGISTER</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

</head>

<style>
    @font-face {
        font-family: GraublauWeb;
        src: url("{{ url('fontnova.otf') }}") format("opentype");
    }

    .caixa {
        border-radius: 15px;
        /*opacity: 55%*/
        background-color: rgba(39, 34, 40, 0.6);
        border: 3px solid rgba(233, 0, 0, 0.4);
        color: white;
        padding-bottom: 30px;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        text-transform: uppercase;
        padding: 15px;
    }


    .btn {
        border-width: 2px;
        border-radius: 10px;
        text-transform: uppercase;
        width: 170px;
        border: 3px solid rgba(233, 0, 0, 0.4);
        background-color: transparent;
        color: white;
        box-shadow: 0px 2px 6px white;

    }

    .btn2 {
        border-width: 2px;

        text-transform: uppercase;
        font-weight: bolder;
        width: 250px;
        padding: 10px;
        border: 3px solid rgba(233, 0, 0, 0.4);
        background-color: transparent;
        color: yellow;
        box-shadow: 0px 2px 6px rgba(233, 0, 0, 0.4);

    }

    .btn:hover {
        color: white;
    }


    .inputfundo {
        border-width: 2px;

        border-color: white;
        background-color: transparent;
        border: 3px solid rgba(233, 0, 0, 0.4);
        color: white;



    }

    .inputfundo:focus {


        border: 1px solid white;
        border-radius: 10px;
    }

    .inputfundo:hover {
        border: 1px solid white;
        border-radius: 10px;
    }

    .inputfundo:focus:hover {


        border: 1px solid white;
        border-radius: 10px;

    }

</style>

<body style="background-image: url('{{ url('fundo2.jpeg') }}');


background-repeat: no-repeat;
background-position: center;
background-attachment: fixed;
webkit-background-size: cover;
-moz-background-size: cover;
-o-background-size: cover;
background-size: cover;
height: 100%;
width: 100%;">

<div style="margin-top: 45px" class="container">
    <center>
        <img width="300px" class="img img-responsive" src="{{ asset(url('logo.png')) }}" alt="">
    </center>
</div>
<div style="margin-top: 20px" class="container">
    <center>
        <h2 class="btn2">{{ $user->name }}</h2>
    </center>



    <div class="col-md-offset-4 col-md-4">
        <div class="panel caixa">


            <form class="m-t" method="POST" action="{{ url('registerindicado') }}">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <input placeholder="NAME" class="form-control inputfundo" id="name" type="text" name="name"
                           value="{{ old('name') }}" required autofocus/>
                </div>
                <!-- Email Address -->
                <div class="form-group">
                    <input class="form-control inputfundo" placeholder="EMAIL" id="email" type="email" name="email"
                           value="{{ old('email') }}" required/>
                </div>
                <input type="hidden" name="quem" value="{{ $user->link }}">

                <div class="form-group">

                    <input class="form-control inputfundo" placeholder="PHONE" id="telefone" type="teste"
                           name="telefone" value="{{ old('telefone') }}" required/>
                </div>
                <!-- Password -->
                <div class="form-group">
                    <input placeholder="PASSWORD" class="form-control inputfundo" id="password" type="password"
                           name="password" required autocomplete="new-password"/>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <input class="form-control inputfundo" placeholder="CONFIRM PASSWORD" id="password_confirmation"
                           type="password" name="password_confirmation" required/>
                </div>
                <br>
                <br>
                <br>
                <button type="submit" class="btn  btn-rounded block full-width m-b">REGISTER</button>
                <p style="color:white" class="text-muted text-center"><small>
                        Already have an account?</small></p>
                <a class="btn  block full-width m-b btn-rounded" href="{{ url('login') }}">Login</a>
            </form>
            <p class="m-t"><small>veloxfive &copy; 2022</small></p>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


        </div>
    </div>
</div>

    <!-- Mainly scripts -->
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="js/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
        $("#cep").change(function () {

            var valor = document.getElementById('cep').value;

            // alert(valor);
            $.ajax({
                type: 'GET',
                url: 'https://viacep.com.br/ws/' + valor + '/json/',

                success: function (data) {
                    var names = data.bairro
                    $('input[name="endereco"]').val(data.logradouro);
                    $('input[name="bairro"]').val(data.bairro);
                    $('input[name="cidade"]').val(data.localidade);
                    $('input[name="uf"]').val(data.uf);
                    //alert(names);
                    // $('#cand').html(data);
                }
            });
        });
    </script>
</body>

</html>
