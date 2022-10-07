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
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('/ms-icon-144x144.png') }}">
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
        background-color: transparent;
        border-radius: 10px;
    }

    .inputfundo:hover {
        border: 1px solid #381897;
        background-color: transparent;
        border-radius: 10px;
    }

    .inputfundo:focus:hover {


        border: 1px solid #381897;
        background-color: transparent;
        border-radius: 10px;

    }

    .goog-te-banner-frame {
        display: none;
        background-color: transparent;
        margin-top -40px:
    }

    .goog-te-combo {
        color: #381897;
        font-size: 22px;
    }

    .goog-logo-link {
        display: none !important;
    }

    .goog-te-gadget {
        color: transparent !important;

    }

    .goog-te-gadget .goog-te-combo {

        font-size: 22px;
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

            <div class="col-md-12" style="margin-top: 45px">
                <center>
                    <img width="300x" class="img img-responsive" src="{{ asset(url('logo.png')) }}" alt="">
                </center>




            </div>
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <form class="m-t" method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="form-group">
                        <input placeholder="LOGIN" class="form-control inputfundo" id="login" type="text" name="login"
                               value="{{ old('login') }}" required />
                    </div>

                    <!-- Password -->
                    <div class="form-group">

                        <input placeholder="PASSWORD" class="form-control boleado input-lg inputfundo" id="password"
                            type="password" name="password" required />
                    </div>

                    <!-- Remember Me -->
                    <div class="row text-center">
                        <div style="margin-top: 10px" class="col-md-6">
                            <button type="submit" class="btn intas">Login
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

                    <center>
                        <h2 style="color: white">Language</h2>
                        <div id="google_translate_element"></div>
                    </center>



                </form>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>
</body>

<script src="{{ asset('admin/bower_components/jquery/dist/jquery.min.js') }}" >


<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en'
        }, 'google_translate_element');
    }
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
</script>
<script>

</script>

</html>
