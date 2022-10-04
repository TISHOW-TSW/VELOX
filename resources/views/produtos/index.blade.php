@extends('painel.padrao')

@section('css')
    <link rel="stylesheet" href="{{ asset('js/popupyoutube/grt-youtube-popup.css') }}">
    <style>
        .video-list-thumbs {}

        .video-list-thumbs>li {
            margin-bottom: 12px;
        }

        .video-list-thumbs>li:last-child {}

        .video-list-thumbs>li>a {
            display: block;
            position: relative;
            background-color: #111;
            color: #fff;
            padding: 8px;
            border-radius: 3px transition:all 500ms ease-in-out;
            border-radius: 4px
        }

        .video-list-thumbs>li>a:hover {
            box-shadow: 0 2px 5px rgba(0, 0, 0, .3);
            text-decoration: none
        }

        .video-list-thumbs h2 {
            bottom: 0;
            font-size: 14px;
            height: 33px;
            margin: 8px 0 0;
        }

        .video-list-thumbs .glyphicon-play-circle {
            font-size: 40px;
            opacity: 0.6;
            position: absolute;
            right: 39%;
            top: 31%;
            text-shadow: 0 1px 3px rgba(0, 0, 0, .5);
            transition: all 500ms ease-in-out;
        }

        .video-list-thumbs>li>a:hover .glyphicon-play-circle {
            color: #fff;
            opacity: 1;
            text-shadow: 0 1px 3px rgba(0, 0, 0, .8);
        }

        .video-list-thumbs .duration {
            background-color: rgba(0, 0, 0, 0.4);
            border-radius: 2px;
            color: #fff;
            font-size: 11px;
            font-weight: bold;
            left: 12px;
            line-height: 13px;
            padding: 2px 3px 1px;
            position: absolute;
            top: 12px;
            transition: all 500ms ease;
        }

        .video-list-thumbs>li>a:hover .duration {
            background-color: #000;
        }

        @media (min-width:320px) and (max-width: 480px) {
            .video-list-thumbs .glyphicon-play-circle {
                font-size: 35px;
                right: 36%;
                top: 27%;
            }

            .video-list-thumbs h2 {
                bottom: 0;
                font-size: 12px;
                height: 22px;
                margin: 8px 0 0;
            }
        }

    </style>
@endsection

@section('content')
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Termos de uso</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <object data="{{ url('termos/pet.pdf') }}" type="application/pdf" width="100%" height="678">


                        </object>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>

        </div>
    </div>
    <div id="myModal1" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Termos de uso</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <object data="{{ url('termos/telemedicina.pdf') }}" type="application/pdf" width="100%"
                            height="678">


                        </object>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>

        </div>
    </div>
    <div id="myModal2" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Termos de uso</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <object data="{{ url('termos/residencial.pdf') }}" type="application/pdf" width="100%"
                            height="678">


                        </object>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>

        </div>
    </div>
    <div id="myModal3" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Termos de uso</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <object data="{{ url('termos/funeral.pdf') }}" type="application/pdf" width="100%" height="678">


                        </object>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>

        </div>
    </div>
    <br>
    <br>
    <div class="container">
        <div class="panel">
            <div class="panel-body">



                <ul class="list-unstyled video-list-thumbs row">

                    <li class="col-lg-1 col-sm-4 col-xs-12">
                    </li>

                    <li class="col-lg-2 col-sm-4 col-xs-12">

                        <a href="#" class="youtube-link" youtubeid="Obs4F_1ELJo" title="Somos Assistência Pet.">
                            <center>
                                <img src="http://i.ytimg.com/vi/Obs4F_1ELJo/mqdefault.jpg" alt="Barca"
                                    class="img-responsive" height="250px" />
                                    <span class="glyphicon glyphicon-play-circle"></span>
                            </center>
                            <h2><span class="youtube-link" youtubeid="Obs4F_1ELJo">Somos Assistência Pet.</span>
                            </h2>

                            </h2>
                            <center>

                            </center>
                        </a>

                        <br>
                        <button type="button" class="btn btn-info btn-block" data-toggle="modal"
                            data-target="#myModal">Acessar Termos</button>

                    </li>

                    <li class="col-lg-2 col-sm-4 col-xs-12">
                        <a href="#" class="youtube-link" youtubeid="YV2Gg6PyPAM" title="Somos Telemedicina.">
                            <img src="http://i.ytimg.com/vi/YV2Gg6PyPAM/mqdefault.jpg" alt="Barca" class="img-responsive"
                                height="130px" />
                            <center>
                                <h2><span class="youtube-link" youtubeid="YV2Gg6PyPAM">Somos Telemedicina.</span></h2>
                            </center>
                            <span class="glyphicon glyphicon-play-circle"></span>


                        </a>

                        <br>
                        <button type="button" class="btn btn-info btn-block" data-toggle="modal"
                            data-target="#myModal1">Acessar Termos</button>
                    </li>
                    <li class="col-lg-2 col-sm-4 col-xs-12">

                        <a href="#" class="youtube-link" youtubeid="OPnppHhGKOo" title="Somos Assistência Residencial.">
                            <img src="http://i.ytimg.com/vi/OPnppHhGKOo/mqdefault.jpg" alt="Barca" class="img-responsive"
                                height="130px" />
                            <center>
                                <h2><span class="youtube-link" youtubeid="OPnppHhGKOo">Somos Assistência
                                        Residencial.</span></h2>
                            </center>
                            <span class="glyphicon glyphicon-play-circle"></span>

                        </a>
                        <br>
                        <button type="button" class="btn btn-info btn-block" data-toggle="modal"
                            data-target="#myModal2">Acessar Termos</button>

                    </li>
                    <li class="col-lg-2 col-sm-4 col-xs-12">

                        <a href="#" class="youtube-link" youtubeid="rmIrMVIZrU0"
                            title="Somos Assistência Funeral + Seguro de Vida..">
                            <img src="http://i.ytimg.com/vi/rmIrMVIZrU0/mqdefault.jpg" alt="Barca" class="img-responsive"
                                height="130px" />
                            <center>
                                <h2><span class="youtube-link" youtubeid="rmIrMVIZrU0">Somos Assistência Funeral + Seguro
                                        de Vida.</span></h2>
                            </center>
                            <span class="glyphicon glyphicon-play-circle"></span>

                        </a>
                        <br>
                        <button type="button" class="btn btn-info btn-block" data-toggle="modal"
                            data-target="#myModal3">Acessar Termos</button>

                    </li>
                    <li class="col-lg-2 col-sm-4 col-xs-12">

                        <a href="#" class="youtube-link" youtubeid="IXsI0SrVXvs" title="Somos Serviços Financeiros">
                            <img src="http://i.ytimg.com/vi/IXsI0SrVXvs/mqdefault.jpg" alt="Barca" class="img-responsive"
                                height="130px" />
                            <center>
                                <h2><span class="youtube-link" youtubeid="IXsI0SrVXvs">Somos Serviços Financeiros</span>
                                </h2>
                            </center>
                            <span class="glyphicon glyphicon-play-circle"></span>

                        </a>

                    </li>
                </ul>
            </div>
        </div>





    </div>
@endsection
@section('js')
    <script src="{{ asset('js/popupyoutube/grt-youtube-popup.js') }}"></script>

    <script>
        $(".youtube-link").grtyoutube();
    </script>
@endsection
