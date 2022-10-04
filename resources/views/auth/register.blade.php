<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>veloxfive | REGISTER</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('/apple-icon-57x57.png')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{asset('/apple-icon-60x60.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset('/apple-icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('/apple-icon-76x76.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset('/apple-icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{asset('/apple-icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset('/apple-icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{asset('/apple-icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/apple-icon-180x180.png')}}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{asset('/android-icon-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{asset('/favicon-96x96.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('/manifest.json')}}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{asset('/ms-icon-144x144.png')}}">
    <meta name="theme-color" content="#ffffff">
</head>

<style>
    @font-face {
        font-family: GraublauWeb;
        src: url("{{ url('fontnova.otf') }}") format("opentype");
    }



    .btn {
        border-width: 2px;
        border-radius: 10px;
        text-transform: uppercase;
        width: 170px;
        border-color: #b20a5d;
        background-color: transparent;
        color: white;
        box-shadow: 0px 2px 6px white;

    }

    .btn:hover {
        color: white;
    }




    .inputfundo {
        border-width: 2px;
        border-radius: 10px;
        border-color: white;
        background-color: transparent;
        box-shadow: 0px 2px 6px #b20a5d;
        color: white;



    }

    .inputfundo:focus {


        border: 1px solid #381897;
        border-radius: 10px;
    }

    .inputfundo:hover {
        border: 1px solid #381897;
        border-radius: 10px;
    }

    .inputfundo:focus:hover {


        border: 1px solid #381897;
        border-radius: 10px;

    }

</style>

<body style="background-image: url('{{ url('fundo.jpeg') }}');


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
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <form class="m-t" method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <input placeholder="NAME" class="form-control inputfundo" id="name" type="text" name="name"
                        value="{{ old('name') }}" required autofocus />
                </div>
                <!-- Email Address -->
                <div class="form-group">
                    <input class="form-control inputfundo" placeholder="EMAIL" id="email" type="email" name="email"
                        value="{{ old('email') }}" required />
                </div>

                <div class="form-group">

                    <input class="form-control inputfundo" placeholder="PHONE" id="telefone" type="teste"
                        name="telefone" value="{{ old('telefone') }}" required />
                </div>
                <!-- Password -->
                <div class="form-group">
                    <input placeholder="PASSWORD" class="form-control inputfundo" id="password" type="password"
                        name="password" required autocomplete="new-password" />
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <input class="form-control inputfundo" placeholder="CONFIRM PASSWORD" id="password_confirmation"
                        type="password" name="password_confirmation" requ ired />
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
        <div class="col-md-4"></div>

    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="js/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
        $("#cep").change(function() {

            var valor = document.getElementById('cep').value;

            // alert(valor);
            $.ajax({
                type: 'GET',
                url: 'https://viacep.com.br/ws/' + valor + '/json/',

                success: function(data) {
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
