<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>VELOX5</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('admin/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admin/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('admin/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('admin/dist/css/AdminLTE.min.css') }}">
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
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->


    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->




    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100&display=swap');

    </style>
    <style type="text/css">
        .goog-logo-link {
            display: none !important;
        }


        #google_translate_element {
        }

        .goog-te-banner-frame {
            display: none !important;
        }

        body {
            position: static !important;
            top: 0 !important;
        }

    </style>
    <style>
        body {
            position: block;
        }

    </style>


    @if (Auth::user()->tipo == 0)
        <style>
            .btn {
                border-width: 2px;
                border-radius: 10px;
                text-transform: uppercase;
                width: 170px;
                border-color: #e90000;
                background-color: #000000;
                /*  background-color: transparent; */
                color: white;
                box-shadow: 0px 2px 6px white;
                margin-left: 15px;
                font-family: 'Inter', sans-serif, bold;
                font-weight: bold;
            }

            li:hover {
                background-color: transparent;
            }

            a:hover {
                background-color: transparent;
            }

            .btn:hover {
                /*      color: white;
                      background-color: transparent */
            }

            .dropdown-menu {
            }

        </style>
    @endif
    @if (Auth::user()->tipo == 1)
        <style>
            #novoteste {

                background-color: #b20a5d;

            }


            #novoteste::before {

                background-color: #b20a5d
            }

            li {
                background-color: #b20a5d;
            }

            .dropdown {
                background-color: #b20a5d
            }

            .dropdown-menu {
                background-color: #b20a5d;
            }

        </style>
    @endif

    @yield('css')
    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>


<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->

<body class="skin-black layout-top-nav">
<div class="wrapper">

    @php

        $abertomeus = App\Models\Chat::where('user_id', Auth::user()->id)
            ->whereHas('respostas', function ($query) {
                return $query->where('visto', 0);
            })
            ->get();
    @endphp

        <!-- /.navbar-collapse -->
    <!-- Navbar Right Menu -->

    <!-- /.navbar-custom-menu -->

    <!-- /.container-fluid -->


    <!-- Full Width Column -->
    <div class="content-wrapper"


         style="
              background-image: url('{{ asset('fundo2.jpeg')}}');




         background-repeat: no-repeat;
  background-position: center;
  background-attachment: fixed;
  webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
  height: 100%;
  width: 100%;">

        @if (Auth::user()->tipo == 0)
            <nav
                style="display: flex; justify-content: center; background-color:rgba(233, 0, 0,0.4);border-color:transparent"
                class="navbar navbar-default text-center">
                <div style="background-color: transparent;border-color:transparent" class="container text-center">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div style="border-color: transparent" class="navbar-header text-center">
                        <button style="background-color: transparent;border-color:transparent" type="button"
                                class="navbar-toggle collapsed" data-toggle="collapse"
                                data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                <span style="border-color: transparent" class="sr-only">Toggle
                                    navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a style="margin-top: 5px" class="navbar-brand" href="{{ url('/') }}"><img
                                width="145px" src="{{ asset('logo.png') }}" alt=""></a>
                    </div>

                    <center>
                        @if (Auth::user()->tipo == 0)
                            <!-- Collect the nav links, forms, and other content for toggling -->
                            <div style="background-color: transparent;border-color:transparent;border-width: 0;"
                                 class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                <center>
                                    <ul style="background-color: transparent;border-color:transparent"
                                        style="margin-top: 25px" class="nav navbar-nav text-center">
                                        <br>


                                        <a style="margin-bottom: 8px" href="{{ url('ships') }}"
                                           class="btn" href="">Carros</a>
                                        <a style="margin-bottom: 8px" class="btn"
                                           href="{{ url('rewards') }}">Rendimentos</a>
                                        <a style="margin-bottom: 8px" class="btn"
                                           href="{{ url('player/bonus') }}">Bonus</a>
                                        <a style="margin-bottom: 8px" class="btn"
                                           href="{{ url('player') }}">Equipe</a>
                                    </ul>
                                </center>

                                <ul style="background-color: transparent; margin-top:10px"
                                    class="nav navbar-nav navbar-right text-center">
                                    <li style="background-color: transparent" class="dropdown text-center">
                                        <a style="background-color: transparent" href="#" class="dropdown-toggle"
                                           data-toggle="dropdown" role="button" aria-haspopup="true"
                                           aria-expanded="false"><i style="font-size: 30px;color: rgba(233, 0, 0,1)"
                                                                    class="fa fa-adjust"></i></a>

                                        <ul style="background-color: transparent;border-color: transparent"
                                            class="dropdown-menu text-center">
                                            <center>
                                                <li><a
                                                        class="btn" href="{{ url('myaccount') }}">My
                                                        account</a></li>

                                                <li style="margin-top: 4px; margin-bottom: 15px">
                                                    <form method="POST" action="{{ route('logout') }}">
                                                        @csrf

                                                        <a
                                                            class="btn" href="route('logout')" onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                                            Log out
                                                        </a>
                                                    </form>

                                                </li>
                                            </center>
                                        </ul>

                                </ul>
                            </div><!-- /.navbar-collapse -->
                        @endif
                        @if (Auth::user()->tipo == 1)
                            <div style="color: white">

                            </div>
                            <ul style="background-color: transparent; margin-top:10px"
                                class="nav navbar-nav navbar-right text-center">
                                <li style="background-color: transparent" class="dropdown text-center">
                                    <a style="background-color: transparent" href="#" class="dropdown-toggle"
                                       data-toggle="dropdown" role="button" aria-haspopup="true"
                                       aria-expanded="false"><img width="35px"
                                                                  src="{{ asset('iconlogin.png') }}" alt=""></a>

                                    <ul style="background-color: transparent;border-color: transparent"
                                        class="dropdown-menu text-center">
                                        <center>
                                            <li><a style="color: white;background-color:transparent"
                                                   class="btn" href="{{ url('myaccount') }}">My
                                                    account</a></li>

                                            <li style="margin-top: 4px; margin-bottom: 15px">
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf

                                                    <a style="color: white;background-color:transparent"
                                                       class="btn" href="route('logout')" onclick="event.preventDefault();
                                            this.closest('form').submit();">
                                                        Log out
                                                    </a>
                                                </form>

                                            </li>
                                        </center>
                                    </ul>

                            </ul>
                        @endif


                    </center>
                </div><!-- /.container-fluid -->
            </nav>
        @else
            <nav style="background-image:" class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">

                        @if (Auth::user()->tipo != 1)
                            <a href="{{ url('dashboard') }}" class="navbar-brand"
                               style="margin-top: -10px"><img width="156px" src="{{ url('logo.png') }}" alt="">
                                @else
                                    <a href="{{ url('admin/dashboard') }}" class="navbar-brand"
                                       style="margin-top: -10px"><img width="156px" src="{{ url('logo.png') }}"
                                                                      alt="">
                                        @endif
                                        <br>
                                    </a>
                                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                            data-target="#navbar-collapse">
                                        <i style="color: white" class="fa fa-bars"></i>
                                    </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->

                    @if (Auth::user()->tipo != 1)

                        @if (Auth::user()->tipo == 0)
                            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                                <ul class="nav navbar-nav">

                                    <li><a href="{{ url('cliente/combos') }}"><i class="fa fa-plus"></i>
                                            Combos</a></li>
                                    <li><a href="{{ url('produto') }}"><i class="fa fa-shopping-bag"></i>
                                            Produtos</a></li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                                                class="fa fa-users"></i>
                                            Afiliados
                                            <span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="{{ url('diretos') }}">DIRETOS</a></li>
                                            <li><a href="{{ url('primeiro') }}">PRIMEIRO</a></li>
                                            <li><a href="{{ url('segundo') }}">SEGUNDO</a></li>
                                            <li><a href="{{ url('terceiro') }}">TERCEIRO</a></li>

                                        </ul>
                                    </li>

                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                                                class="fa fa-dashboard"></i>
                                            <span class="nav-label">Relatorios</span> <span
                                                class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="{{ url('cliente/vendas') }}">VENDAS</a></li>
                                            <li><a href="{{ url('cliente/pontos') }}">PONTOS</a></li>


                                        </ul>
                                    </li>


                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                                                class="fa fa-dollar"></i>
                                            Financeiro
                                            <span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="{{ url('financeiro/geral') }}">GERAL</a></li>
                                            <li><a href="{{ url('cliente/faturas') }}">FATURAS</a></li>
                                            <li><a href="{{ url('cliente/saque') }}">SAQUE</a></li>

                                        </ul>
                                    </li>
                                    <li><a href="{{ url('arquivo') }}"><i class="fa fa-file"></i>
                                            Materiais</a>
                                    <li><a href="{{ url('treinamento') }}">
                                            Treinamentos</a>
                                    </li>


                                </ul>

                            </div>
                        @endif

                        @if (Auth::user()->tipo == 2)
                            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                                <ul class="nav navbar-nav">


                                    <li><a href="{{ url('admin/faturas') }}"><i class="fa fa-plus"></i>
                                            Faturas</a>
                                    </li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                                                class="fa fa-dollar"></i>
                                            Saques
                                            <span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="{{ url('admin/saque') }}">Todos os Saques</a></li>
                                            <li><a href="{{ url('admin/saque/pendentes') }}">Saques Pendentes</a>
                                            </li>
                                            <li><a href="{{ url('admin/saque/ativos') }}">Saques Pagos</a></li>


                                        </ul>
                                    </li>


                                    <li><a href="{{ url('admin/caixa') }}"><i class="fa faq-item"></i>
                                            Caixa</a>
                                    </li>

                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            Relatorios
                                            <span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="{{ url('admin/relatorios') }}">Relatorio Diario</a></li>
                                            <li><a href="{{ url('admin/relatorioplanos') }}">Relatorio de
                                                    Planos</a>
                                            </li>
                                            <li><a href="{{ url('admin/logs') }}">Log de Sistema</a></li>


                                        </ul>
                                    </li>


                                </ul>

                            </div>
                        @endif
                        @if (Auth::user()->tipo == 3)
                            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                                <ul class="nav navbar-nav">


                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                                                class="fa fa-users"></i>
                                            Usuarios
                                            <span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="{{ url('admin/usuarios') }}">Todos Usuarios</a></li>
                                            <li><a href="{{ url('admin/usuarios/ativos') }}">Ativos</a></li>
                                            <li><a href="{{ url('admin/usuarios/pendentes') }}">Pendentes</a>
                                            </li>


                                        </ul>
                                    </li>


                                </ul>

                            </div>
                        @endif
                    @else
                        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                            <ul class="nav navbar-nav">


                                <li style="color: white" class="dropdown">
                                    <a style="color: white" href="#" class="dropdown-toggle"
                                       data-toggle="dropdown"><i class="fa fa-users"></i>
                                        Usuarios
                                        <span class="caret"></span></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a style="color: white" href="{{ url('admin/usuarios') }}">Todos
                                                Usuarios</a></li>
                                        <li><a style="color: white"
                                               href="{{ url('admin/usuarios/ativos') }}">Ativos</a></li>
                                        <li><a style="color: white"
                                               href="{{ url('admin/usuarios/pendentes') }}">Pendentes</a>
                                        </li>


                                    </ul>
                                </li>

                                <li><a style="color: white" href="{{ url('admin/faturas') }}"><i
                                            class="fa fa-plus"></i>
                                        Faturas</a>
                                </li>
                                <li class="dropdown">
                                    <a style="color: white" href="#" class="dropdown-toggle"
                                       data-toggle="dropdown"><i class="fa fa-dollar"></i>
                                        Saques
                                        <span class="caret"></span></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a style="color: white" href="{{ url('admin/saque') }}">Todos os
                                                Saques</a></li>
                                        <li><a style="color: white"
                                               href="{{ url('admin/saque/pendentes') }}">Saques
                                                Pendentes</a>
                                        </li>
                                        <li><a style="color: white" href="{{ url('admin/saque/ativos') }}">Saques
                                                Pagos</a></li>


                                    </ul>
                                </li>

                                <li><a style="color: white" href="{{ url('admin/caixa') }}"><i
                                            class="fa faq-item"></i> Caixa</a>
                                <li>
                                    <a style="color: white" href="{{ url('admin/chat') }}"> <span
                                            class="label label-success">{{ $suporte ?? '' }}</span><i
                                            class="fa faq-item"></i> Chat</a>
                                <li><a style="color: white" href="{{ url('admin/video') }}"><i
                                            class="fa faq-item"></i> Video</a>
                                </li>

                                <li id="novoteste" class="dropdown">
                                    <a style="color: white" href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        Relatorios
                                        <span class="caret"></span></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a style="color: white"
                                               href="{{ url('admin/relatorios') }}">Relatorio Diario</a>
                                        </li>
                                        <li><a style="color: white"
                                               href="{{ url('admin/relatorioplanos') }}">Relatorio de
                                                Planos</a>
                                        </li>
                                        <li><a style="color: white" href="{{ url('admin/logs') }}">Log de
                                                Sistema</a></li>


                                    </ul>
                                </li>

                                <li class="dropdown">

                                <li class="dropdown">
                                    <a style="color: white" href="#" class="dropdown-toggle"
                                       data-toggle="dropdown"><i class="fa fa-gear"></i>
                                        Configurações
                                        <span class="caret"></span></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a style="color: white" href="{{ url('admin/users') }}">Usuarios</a>
                                        </li>
                                        <li><a style="color: white"
                                               href="{{ route('vantagem.index') }}">Beneficios</a></li>
                                        <li><a style="color: white" href="{{ route('plano.index') }}">Planos</a>
                                        </li>
                                        <li><a style="color: white" href="{{ url('admin/produtos') }}">
                                                Produtos</a></li>
                                        <li><a style="color: white" href="{{ route('metas.index') }}">Metas</a>
                                        </li>
                                        <li><a style="color: white" href="{{ url('docs') }}">Documentos</a>
                                        </li>
                                        <li><a style="color: white"
                                               href="{{ url('admin/treinamentos') }}">Treinamentos</a>
                                        </li>

                                    </ul>
                                </li>


                            </ul>

                        </div>

                    @endif
                    <!-- /.navbar-collapse -->
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- Messages: style can be found in dropdown.less-->

                            <!-- User Account Menu -->
                            <li class="dropdown user user-menu">
                                <!-- Menu Toggle Button -->
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <!-- The user image in the navbar-->
                                    <img src="{{ url('user.jpg') }}" class="user-image" alt="User Image">
                                    <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                    <span class="hidden-xs">{{ Auth::user()->name }}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- The user image in the menu -->
                                    <li class="user-header">
                                        <img src="{{ url('user.jpg') }}" class="img-circle" alt="">

                                        <p>
                                            {{ Auth::user()->name }}
                                            <small>Membro desde
                                                {{ Auth::user()->created_at->format('M-Y') }}</small>
                                        </p>
                                    </li>
                                    <!-- Menu Body -->

                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="{{ url('minhaconta') }}"
                                               class="btn btn-default btn-flat">Minha
                                                Conta</a>
                                        </div>
                                        <div class="pull-right">

                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf

                                                <a class="btn btn-default btn-flat" href="route('logout')" onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                                    <i class="fa fa-sign-out"></i> Sair
                                                </a>
                                            </form>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <!-- /.navbar-custom-menu -->
                </div>
                <!-- /.container-fluid -->
            </nav>
        @endif

        <br>
        @if (Auth::user()->tipo != 1)
            <a href="{{ url('player/chat') }}" style="position:fixed;width:60px;height:60px;bottom:40px;right:40px;background-color:white;color:#FFF;border-radius:50px;text-align:center;font-size:30px;box-shadow: 1px 1px 2px #888;
z-index:1000;">
                <center>

                    <img style="margin-top: 6px" class="img-responsive"
                         src="{{ asset('android-icon-48x48.png') }}" alt="">{{ count($abertomeus) }}
                </center>
            </a>

        @endif
@include('flash-message')
        @yield('content')

        <!-- /.container -->
    </div>

    <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="{{ asset('admin/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('admin/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ asset('admin/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('admin/bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('admin/dist/js/adminlte.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('admin/dist/js/demo.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>



@yield('js')
</body>

</html>
