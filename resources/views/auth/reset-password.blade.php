<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>veloxfive LOGIN</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/font-awesome.css') }}" rel="stylesheet">
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

<body style="
background-image: url('{{ url('fundo.jpeg') }}');
height: 100%;
background-position: center;
background-repeat: no-repeat;
background-size: cover;">
<div class="container">

    <div class="row">

        <div class="col-md-12 caixa" style="margin-top: 45px">
            <center>
                <img width="300px" class="img img-responsive" src="{{ asset(url('logo.png')) }}" alt="">
            </center>


        </div>

        <div class="caixa">
            <form class="m-t" method="POST" action="{{ route('password.update') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="form-group">


                    <input class="form-control inputfundo" id="email" type="email" name="email"
                           value="{{ old('email', $request->email) }}" autocomplete="off" required autofocus/>
                </div>

                <!-- Password -->
                <div class="form-group">


                    <input placeholder="PASSWORD" class="form-control inputfundo" id="password" type="password"
                           name="password"
                           required/>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">


                    <input placeholder="PASSWORD CONFIRMATION" class="form-control inputfundo"
                           id="password_confirmation"
                           type="password" name="password_confirmation" required/>
                </div>

                <div class="form-group">
                    <button type="submit"
                            class="btn bg-success block full-width m-b btn-rounded">Reset
                        Password
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
</body>
