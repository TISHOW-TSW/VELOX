@extends('painel.padrao')

@section('css')
    <style>
        .progress {
            position: relative;
            width: 100%;
            border: 1px solid #7F98B2;
            padding: 1px;
            border-radius: 3px;
        }

        .bar {
            background-color: #B4F5B4;
            width: 0%;
            height: 25px;
            border-radius: 3px;
        }

        .percent {
            position: absolute;
            display: inline-block;
            top: 3px;
            left: 48%;
            color: #7F98B2;
        }

    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('plano.index') }}" class="btn btn-warning btn-circle btn-lg"><i
                        class="fa fa-angle-left"></i></a>

            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-8">
                <div class="panel ">
                    <div class="panel-heading">Cadastrar Plano</div>
                    <div class="panel-body text-center">
                        @if (isset($produto->img))
                            <img width="20%" src="{{ url('arquivos/produtos', $produto->img) }}" alt="">
                            <br>

                        @else
                        @endif
                        <form method="post" action="{{ url('api/file-upload/produto/upload') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="produto_id" value="{{ $produto->id }}">
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="file" name="file" id="file" />
                                </div>
                                <div class="col-md-4">
                                    <input type="submit" name="upload" value="Upload" class="btn btn-success" />
                                </div>
                                <div class="col-md-12">
                                    <small id="file-help" class="form-text text-muted" tabindex="0">
                                        <strong>Imagem da foto</strong> <br>
                                        Tamanho máximo de cada anexo: 5MB.
                                    </small>
                                </div>
                            </div>
                        </form>
                        <br />

                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="" aria-valuemin="0"
                                aria-valuemax="100" style="width: 0%">
                                0%
                            </div>
                        </div>
                        <br />
                        <div id="success">

                        </div>
                        <a href="{{ url('admin/produto/caddoc', $produto->id) }}" class="btn btn-success">Adicionar
                            Termo</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>

    <script>
        $(document).ready(function() {

            $('form').ajaxForm({
                beforeSend: function() {
                    $('#success').empty();
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    $('.progress-bar').text(percentComplete + '%');
                    $('.progress-bar').css('width', percentComplete + '%');
                },
                success: function(data) {
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
@endsection
