<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>VELOX5</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('adminlte/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/AdminLTE.min.css') }}">

    <link rel="stylesheet" href="{{asset('adminlte/dist/css/skins/_all-skins.min.css')}}">


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


    @yield('css')
    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>


<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->

<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">


    <!-- /.navbar-collapse -->
    <!-- Navbar Right Menu -->

    <!-- /.navbar-custom-menu -->

    <!-- /.container-fluid -->


    <!-- Full Width Column -->
    <div class="content-wrapper">

        <header class="main-header">
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <a href="{{url('admin/dashboard')}}" class="navbar-brand"><b>SGM</b></a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle"
                                   data-toggle="dropdown"><i class="fa fa-users"></i>
                                    Usuarios
                                    <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ url('admin/usuarios') }}">Todos
                                            Usuarios</a></li>
                                    <li><a
                                            href="{{ url('admin/usuarios/ativos') }}">Ativos</a></li>
                                    <li><a
                                            href="{{ url('admin/usuarios/pendentes') }}">Pendentes</a>
                                    </li>

                                    <li><a
                                            href="{{ url('admin/addcontato') }}">Add Grupo</a>
                                    </li>




                                </ul>
                            </li>

                            <li>
                                <a href="{{ url('admin/faturas') }}">
                                    <i
                                        class="fa fa-plus"></i>
                                    Faturas</a>
                            </li>
                            <li><a href="{{ url('admin/comprovantes') }}"><i class="fa fa-plus"></i>
                                    Comprovantes</a>
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle"
                                   data-toggle="dropdown"><i class="fa fa-dollar"></i>
                                    Saques
                                    <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ url('admin/saque') }}">Todos os
                                            Saques</a></li>
                                    <li><a
                                            href="{{ url('admin/saque/pendentes') }}">Saques
                                            Pendentes</a>
                                    </li>

                                    <li><a
                                            href="{{ url('admin/saque/pendentesgrupo') }}">Saques
                                            Pendentes Grupo</a>
                                    </li>
                                    <li><a href="{{ url('admin/saque/ativos') }}">Saques
                                            Pagos</a></li>


                                </ul>
                            </li>

                            <li><a href="{{ url('admin/caixa') }}"><i
                                        class="fa faq-item"></i> Caixa</a>
                            <li>
                                <a href="{{ url('admin/chat') }}"> <span
                                        class="label label-success">{{ $suporte ?? '' }}</span><i
                                        class="fa faq-item"></i> Chat</a>
                            <li><a href="{{ url('admin/video') }}"><i
                                        class="fa faq-item"></i> Video</a>
                            </li>


                            <li id="novoteste" class="dropdown">
                                <a  href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    Relatorios
                                    <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a
                                            href="{{ url('admin/relatorios') }}">Relatorio Diario</a>
                                    </li>
                                    <li><a
                                            href="{{ url('admin/relatorioplanos') }}">Relatorio de
                                            Planos</a>
                                    </li>
                                    <li><a  href="{{ url('admin/logs') }}">Log de
                                            Sistema</a></li>


                                </ul>
                            </li>


                            <li class="dropdown">
                                <a  href="#" class="dropdown-toggle"
                                    data-toggle="dropdown"><i class="fa fa-gear"></i>
                                    Configura????es
                                    <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a  href="{{ url('admin/users') }}">Usuarios</a>
                                    </li>
                                    <li><a
                                            href="{{ route('admin.vantagem.index') }}">Beneficios</a></li>
                                    <li><a  href="{{ route('admin.plano.index') }}">Planos</a>
                                    </li>

                                    <li><a  href="{{ route('admin.user.admin') }}">Admin</a>
                                    </li>
                                    <li><a  href="{{ url('admin/produtos') }}">
                                            Produtos</a></li>
                                    <li><a  href="{{ route('metas.index') }}">Metas</a>
                                    </li>
                                    <li><a  href="{{ url('docs') }}">Documentos</a>
                                    </li>
                                    <li><a
                                            href="{{ url('admin/treinamentos') }}">Treinamentos</a>
                                    </li>

                                </ul>
                            </li>


                        </ul>

                    </div>
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
                                    <span class="hidden-xs">{{Auth::guard('admin')->user()->name}}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- The user image in the menu -->
                                    <li class="user-header">
                                        <img src="{{ url('user.jpg') }}" class="img-circle" alt="">

                                        <p>
                                            {{Auth::guard('admin')->user()->name}}
                                            <small>Membro desde
                                                {{ Auth::guard('admin')->user()->created_at->format('M-Y') }}</small>
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

                                            <form method="POST" action="{{ route('admin.logout') }}">
                                                @csrf

                                                <a class="btn btn-default btn-flat" href="route('admin.logout')"
                                                   onclick="event.preventDefault();
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
        </header>


        <br>

        @include('flash-message')
        @yield('content')

        <!-- /.container -->
    </div>

    <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="{{ asset('adminlte/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ asset('adminlte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('adminlte/bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('admin/dist/js/adminlte.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('admin/dist/js/demo.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@yield('js')
</body>

</html>
