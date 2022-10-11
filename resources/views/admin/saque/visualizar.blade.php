@extends('painel.padrao')

@section('content')
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ url('admin/saque') }}" class="btn btn-warning btn-circle btn-lg"><i
                        class="fa fa-angle-left"></i> Voltar</a>

            </div>
        </div>

    </div>

    <br>
    <div class="container">


        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">VISUALIZAR SAQUE</h3>
            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">
                <h3>Dados Para Pagamento</h3>
            </div>
            <div class="panel-body">
                <table id="myTable" class="table table-striped">

                    <tbody>
                    <tr>
                        <td>NOME</td>
                        <td>{{$saque->user->name}}</td>

                    </tr>

                    @forelse($saque->user->contas as $conta)


                        <tr>
                            <td>Agencia</td>
                            <td>{{$conta->agencia}}</td>

                        </tr>


                    @empty



                    @endforelse
                    <tr>
                        <td>Valor Solicitado</td>
                        <td>$ {{number_format($saque->valor,2,',','.')}}</td>
                    </tr>
                    <tr>
                        <td>Meio saque</td>
                        <td>{{ $saque->meio_saque == 2 ? 'PIX: '.$saque->user->pix->chave : 'BANKON: '.$saque->user->bankon->cod_bankon }}</td>

                    </tr>

                    </tbody>
                </table>
                @if($tipo == 0)
                <a href="{{url('pagar/rendimento/saque',$saque->id)}}" class="btn btn-success btn-rounded btn-block">Pagar</a>
                @endif
                @if($tipo == 1)
                    <a href="{{url('pagar/indica/saque',$saque->id)}}" class="btn btn-success btn-rounded btn-block">Pagar</a>
                @endif
            </div>
        </div>
    </div>
@endsection
