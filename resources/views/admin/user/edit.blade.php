@extends('painel.padrao')

@section('content')

    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <br>
            <div class="col-md-12">
                <a href="{{ url('admin/users') }}" class="btn btn-warning btn-circle btn-lg"><i
                        class="fa fa-angle-left"></i></a>

            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-8">
                <div class="panel ">
                    <div class="panel-heading">

                        <h3 class="panel-title">Editar Usuario</h3>
                    </div>
                    <div class="panel-body">

                        <form class="form-horizontal" action="{{ url('admin/user/edit') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="col-sm-2" for="">Nome</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="name" value="{{ $user->name }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2" for="">EMail</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="email" name="email" value="{{ $user->email }}">
                                </div>
                            </div>
                            <input type="hidden" name="id" value="{{ $user->id }}">

                            <div class="form-group">


                                <div class="form-group">
                                    <label class="col-sm-2" for="">TIPO</label>
                                    <div class="col-sm-10">
                                        <select name="tipo" id="" class="form-control">
                                            <option value=""></option>
                                            <option @if ($user->tipo == 0) selected @endif value="0">Cliente
                                            </option>
                                            <option @if ($user->tipo == 1) selected @endif value="1">
                                                Administrador</option>
                                            <option @if ($user->tipo == 2) selected @endif value="2">Financeiro
                                            </option>
                                            <option @if ($user->tipo == 3) selected @endif value="3">Suporte
                                            </option>



                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button class="btn btn-success btn-block">Salvar</button>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>


        </div>


    </div>
@endsection
@section('js')
    <script>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {

            $('.js-example-basic-single').select2();
            $.ajax({
                type: 'GET',
                url: 'https://brasilapi.com.br/api/banks/v1',

                success: function(dados) {

                    if (dados.length > 0) {
                        var option = '<option>Selecione seu banco</option>';
                        $.each(dados, function(i, obj) {
                            option += '<option value="' + obj.code + '">' + obj.name +
                                '</option>';
                        })
                        $('#mensagem').html('<span class="mensagem">Total de paises encontrados.: ' +
                            dados.length + '</span>');
                        $('#cmbPais').html(option).show();
                    } else {
                        Reset();
                        $('#mensagem').html(
                            '<span class="mensagem">Não foram encontrados paises!</span>');
                    }
                }
            });
            $("#cmbPais").change(function() {

                var valor = document.getElementById('cmbPais').value;

                //  alert(valor);
                // alert(valor);
                $.ajax({
                    type: 'GET',
                    url: 'https://brasilapi.com.br/api/banks/v1/' + valor,

                    success: function(data) {
                        // alert(data.code);
                        $('input[name="code"]').val(data.code);
                        $('input[name="name"]').val(data.name);
                    }
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
        });
    </script>
@endsection
