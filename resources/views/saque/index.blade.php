@extends('painel.padrao')

@section('content')
    <br>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header text-center">
                        <h3 class="box-title">Comissões Disponiveis</h3>
                    </div>
                    <div class="box-body text-center">
                        <h1>R${{number_format(Auth::user()->sobra,2,',','.')}}</h1>


                    </div>
                    <div class="box-footer">
                        @if(number_format(Auth::user()->sobra,1,',','.') > 0)
                            <a href="{{url('realizar/saque')}}" class="btn btn-success btn-block btn-lg">Solicitar Saque
                                Total</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
