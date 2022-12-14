<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>veloxfive LOGIN</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

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
<div class="container">

    <div class="row">

        <div class="col-md-12" style="margin-top: 45px;margin-bottom: 35px">
            <center>
                <img width="300x" class="img img-responsive" src="{{ asset(url('logo.png')) }}" alt="">
            </center>


        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4 caixa">
            <form id="myForm" class="m-t" method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <input placeholder="LOGIN" class="form-control inputfundo" id="login" type="text" name="login"
                           value="{{ old('login') }}" required/>
                </div>

                <!-- Password -->
                <div class="form-group">

                    <input placeholder="PASSWORD" class="form-control boleado input-lg inputfundo" id="password"
                           type="password" name="password" required/>
                </div>

                <!-- Remember Me -->
                <div class="row text-center">
                    <div style="margin-top: 10px" class="col-md-6">
                        <button id="submitBtn" type="submit" class="btn intas">Login
                        </button>
                    </div>
                    <div style="margin-top: 10px" class="col-md-6">
                        <a class="btn intas" href="{{ url('register') }}">REGISTER
                        </a>
                    </div>
                </div>
                <div class="row text-center">
                    <div style="margin-top: 10px" class="col-md-12">
                        <a href="{{ route('password.request') }}" class="btn intas">Forget Password
                        </a>
                    </div>

                </div>
                <br>
                <br>


            </form>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>
</body>

<script src="{{ asset('admin/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $("#submitBtn").click(function () {
            $('#submitBtn').attr('disabled', 'disabled');
            $("#myForm").submit(); // Submit the form
        });
    });
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
</script>
<script>

</script>

</html>
