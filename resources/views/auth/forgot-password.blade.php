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

</style>

<body style="
background-image: url('{{ url('bg.jpg') }}');
height: 100%;
background-position: center;
background-repeat: no-repeat;
background-size: cover;">
    <div class="container">

        <div class="row">

            <div class="col-md-12" style="margin-top: 45px">
                <center>
                    <img width="300x" class="img img-responsive" src="{{ asset(url('logo4.png')) }}" alt="">
                </center>




            </div>


            <form class="m-t" method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">


                    <input placeholder="EMAIL" class="form-control inputfundo" id="email" type="email" name="email" value="{{ old('email') }}"
                        required autofocus />
                </div>

                <div class="form-group">
                    <button type="submit" style="background:"
                        class="btn bg-success block full-width m-b btn-rounded">
                        {{ __('
                        SEND LINK TO RESET PASSWORD BY EMAIL') }}
                    </button><br>
                    <a href="{{ url('login') }}" style="" class="btn bg-primary block full-width m-b btn-rounded">
                        Login
                    </a>


            </form>

        </div>
    </div>
</body>
<script>
    < script src = "{{ asset('admin/bower_components/jquery/dist/jquery.min.js') }}" >
</script>

</script>

</html>
