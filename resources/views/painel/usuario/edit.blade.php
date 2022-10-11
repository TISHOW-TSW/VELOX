@extends('painel.padrao')
@section('css')

@endsection


@section('content')
    <div id="myModal" class="modal fade" role="dialog">
        <div class=" modal-dialog">

            <!-- Modal content-->
            <div class=" caixa modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Adicionar Conta</h4>
                </div>

                <div class="panel-header">


                </div>
                <div class="modal-body">
                    <form action="{{ url('admin/cadconta') }}" method="post">
                        @csrf


                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <div class="form-group">
                            <label for="">WALLET</label>
                            <input type="text" id="agencia" name="agencia" class="form-control">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-success btn-block">Cadastrar</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
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
                <a href="{{ url('admin/usuarios') }}" class="btn btn-warning btn-circle btn-lg"><i
                        class="fa fa-angle-left"></i></a>

            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-8">
                <div class="panel caixa">
                    <div class="panel-heading">

                        <h3 class="panel-title">Editar Usuario teste</h3>
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
                                <label class="col-sm-2" for="">CPF</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="cpf" value="{{ $user->cpf }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2" for="">Telefone</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="telefone"
                                        value="{{ $user->telefone }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2" for="">Data de Nascimento</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="date" name="nascimento"
                                        value="{{ $user->nascimento }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2" for="">Senha</label>
                                <div class="col-sm-10">
                                <input type="password" name="password" class="form-control">
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



        <div class="row">
            <div class="col-lg-8">
                <div class="panel caixa">

                    <div class="box-header">
                        <h3 style="color: white" class="box-title"> Editar Contas Bancarias</h3>
                        <div class="box-tools pull-right">
                            <button data-toggle="modal" data-target="#myModal" class="btn btn-success">Adicionar
                                Conta</button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>WALLET</th>

                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>


                                @forelse($user->contas as $conta)
                                    <tr style="background-color: transparent">

                                        <td>{{ $conta->agencia }}</td>

                                        <td><a href="{{ url('admin/delete/conta', $conta->id) }}" class="btn btn-danger">
                                                Excluir</a></td>

                                    </tr>

                                @empty
                                @endforelse

                            </tbody>
                        </table>



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
    <script>
        $(document).ready(function() {
            $('.patrocinador').select2();
        });
    </script>
@endsection
