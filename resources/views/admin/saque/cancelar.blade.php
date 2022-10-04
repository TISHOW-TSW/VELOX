@extends('painel.padrao')

@section('content')
    <br>
    <br>
    <br>
    <div class="container">

        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">CANCELAMENTO DE SAQUE</h3>
            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">
                <h3>DADOS DO SOLICITANTE</h3>
            </div>
            <div class="panel-body">
                <table id="myTable" class="table table-striped">

                    <tbody>
                    <tr>
                        <td>NOME</td>
                        <td>{{$saque->user->name}}</td>

                    </tr>
                    <tr>
                        <td>CPF</td>
                        <td>{{$saque->user->cpf}}</td>

                    </tr>
                    @forelse($saque->user->contas as $conta)
                        <tr>
                            <td>Banco</td>
                            <td>{{$conta->name}}</td>
                        </tr>
                        <tr>
                            <td>Cod. Banco</td>
                            <td>{{$conta->code}}</td>

                        </tr>

                        <tr>
                            <td>Agencia</td>
                            <td>{{$conta->agencia}}</td>

                        </tr>
                        <tr>
                            <td>Conta</td>
                            <td>{{$conta->conta}}</td>
                        </tr>

                    @empty



                    @endforelse
                    <tr>
                        <td>Valor Solicitado</td>
                        <td>R$ {{number_format($saque->valor,2,',','.')}}</td>
                    </tr>


                    </tbody>
                </table>
                <form action="">
                    <div class="form-group">
                        <label for="">MOTIVO DO CANCELAMENTO</label>
                        <textarea class="form-control" name="motivo" id="" cols="30" rows="10"></textarea>
                    </div>
                </form>
                <a href="" class="btn btn-danger btn-rounded btn-block">Cancelar</a>
            </div>
        </div>
    </div>
@endsection
