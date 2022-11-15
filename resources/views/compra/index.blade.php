@extends('painel.padrao')

@section('css')
    <style>


        body {
            color: white;
        }

        .caixa {
            border-radius: 15px;

            background-color: rgba(233, 0, 0, 0.4);
            color: white
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            box-sizing: border-box;
            display: inline-block;
            min-width: 1.5em;
            padding: 0.5em 1em;
            margin-left: 2px;
            text-align: center;
            text-decoration: none !important;
            cursor: pointer;
            *cursor: hand;
            color: white !important;
            border: 1px solid transparent;
            border-radius: 2px;
        }

        label {
            color: white;
        }

        span {
            color: white;
        }

        #myTable_info {
            color: white;
        }

        #myTable1_info {
            color: white;
        }

        #myTable2_info {
            color: white;
        }

        #myTable3_info {
            color: white;
        }

        #myTable4_info {
            color: white;
        }

        .dataTables_empty {
            background-color: transparent;
        }

    </style>
@endsection

@section('content')
    <div class="container">


    </div>

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title">PIX</h4>
                </div>
                <div class="modal-body">
                    <center>
                        <div id="qrcode"></div>
                    </center>
                    <input type="text" class="form-control" id="codigo">

                    <center>
                        <button class="btn btn-dark btn-block" id="copiado" data-clipboard-target="#info_block" onclick="copiarQRPIX()">Copiar QRCODE</button>
                    </center>
                    <p style="opacity: 0;margin-bottom: -20px" id="p2"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-solid">
                    <div class="card-body">
                        <div class="row">

                            <h3 style="color: white" class="d-inline-block d-sm-none">{{ $compra->plano->name }}</h3>
                            <div class="col-12">
                                <img class="img img-responsive"
                                     src="{{ "https://nftcash.sfo3.digitaloceanspaces.com/". $compra->plano->img }}"
                                     class="product-image" alt="Product Image">

                            </div>


                        </div>

                        <!-- /.card-body -->
                    </div>
                </div>

            </div>
            <div class="col-md-6">
                <div style="border-radius: 10px" class="panel caixa">
                    <div class="panel-heading">

                    </div>
                    <div class="panel-body text-center">

                        <center>
                            <h2 class="mb-0">
                                R$ {{ number_format($compra->plano->valor, 2, ',', '.') }}
                            </h2>
                        </center>
                        <h2>Selecionar Metodo de Pagamento</h2>
                        <a target="_blank" href="{{url('gerarpix',$compra->id)}}" class="btn intas">
                            CREDITO
                        </a>

                        <a class="btn btn-primary m-t-5" id="pixnovo">PIX Automatico</a>


                        <br>
                        <br>

                        <br>
                        <br>

                        <br>


                    </div>
                </div>


            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 style="color: #0a0a0a" class="modal-title">PIX</h4>
                </div>
                <div class="modal-body" style="background-color: #0a0a0a">
                <!--    <iframe src="{{url('geraroix2',$compra->id)}}" width="100%" height="500" frameborder="0" sandbox="allow-same-origin allow-scripts allow-popups allow-forms allow-top-navigation"
                            allowtransparency="true"></iframe> -->


                    <div id="pix-card"  >
                        <div class="card-body">
                            <form method="post" action="{{ url('api/file-upload/comprovante') }}">
                                @csrf
                                <h2>- Ativação via Pix</h2>


                                <strong>
                                    REGRA DOS CENTAVOS.

                                    (Pagamento via Pix, para podermos agilizar pedimos para usar a regra dos centavos,
                                    por exemplo, dois dígitos finais do
                                    ID da fatura devem ser enviados convertidos em centavos. (Ex: ID:725, Pack.100 deve
                                    ser enviado R$100,25).

                                </strong>


                                Para realizar o pagamento via pix basta acessar o menu do seu banco e clicar para fazer a
                                transferência via Pix. Feito isso é só escanear o QR Code abaixo e pronto, ele já está com o
                                valor definido do seu plano e o destinatário, basta confirmar a transação! Facilidade na
                                palma de sua mão :)


                                <div align="center">

                                    <h2>
                                        <strong>Chave Pix:</strong> veloxfive2023@gmail.com

                                    </h2>
                                </div>

                                <hr/>

                                <h3 class="text-center">Envie seu comprovante</h3>

                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="hidden" name="compra_id" value="{{$compra->id}}">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    data-feather="file"></i></span></div>
                                        <input type="file" name="img" class="form-control" required/>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="" aria-valuemin="0"
                                             aria-valuemax="100" style="width: 0%">
                                            0%
                                        </div>
                                    </div>
                                    <small id="file-help" class="form-text text-muted" tabindex="0">
                                        <strong>Imagem da foto</strong> <br>
                                        Tamanho máximo de cada anexo: 5MB.
                                    </small>
                                </div>


                                <button type="submit" class="btn btn-success btn-block">
                                    Enviar Comprovante
                                </button>


                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
@endsection


@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.6/clipboard.min.js" integrity="sha512-hDWGyh+Iy4Mr9AHOzUP2+Y0iVPn/BwxxaoSleEjH/i1o4EVTF/sh0/A1Syii8PWOae+uPr+T/KHwynoebSuAhw==" crossorigin="anonymous"></script>


    <script>
        $(document).ready(function () {
            $("#pixnovo").click(function () {
                document.getElementById("pixnovo").disabled = true;

                $.get("{{url('api/compra',$compra->id)}}", function (data, status) {

                    document.getElementById("codigo").value = data.pix_qrcode_copiaecola;
                    document.getElementById("p2").value = data.pix_qrcode_copiaecola;
                    $('#qrcode').html("<img src='" + data.pix_qrcode_imagemurl + "' >");
                   // alert("Data: " + data.qrcode + "\nStatus: " + status);
                    $("#myModal").modal();
                });

            });

            $('form').ajaxForm({
                beforeSend: function () {
                    $('#success').empty();
                },
                uploadProgress: function (event, position, total, percentComplete) {
                    $('.progress-bar').text(percentComplete + '%');
                    $('.progress-bar').css('width', percentComplete + '%');
                },
                success: function (data) {
                    if (data.errors) {
                        $('.progress-bar').text('0%');
                        $('.progress-bar').css('width', '0%');
                        $('#success').html('<span class="text-danger"><b>' + data.errors +
                            '</b></span>');
                    }
                    if (data.success) {
                        $('.progress-bar').text('Uploaded');
                        $('.progress-bar').css('width', '100%');
                        $('#success').html('<span class="text-success"><b>' + data.success +
                            '</b></span><br /><br />');
                        $('#success').append(data.image);

                        location.reload();
                    }
                }
            });

        });
    </script>
    <script>
        function fracao(ship) {

            $(".intas").css("background-color", "transparent");

            document.getElementById('sel' + ship).style.backgroundColor = '#055863';
            $('input[name="moeda"]').attr('value', ship);
            // calculate percent of progress per second


        }
    </script>
    <script>

        function copiarQRPIX() {
            var copyText = document.getElementById("codigo");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");

            alert("QRCODE PIX COPIADO");
        }

    </script>

@endsection
