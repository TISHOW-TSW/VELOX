@extends('painel.padrao')
@section('content')
    <br>
    <div class="container">


        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Adicionar Registro ao caixa da Empresa</h3>
            </div>
        </div>
        <a href="{{ url('admin/caixa') }}" class="btn btn-warning"><i class="fa fa-plus"></i> Volvar</a>
        <br>
        <br>
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">CAIXA DA EMPRESA</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" action="{{ url('registro/caixa') }}" method="POST">

                    @csrf
                    <div class="form-group">
                        <label class="col-sm-2" for="">Detalhes</label>
                        <div class="col-sm-10">
                            <textarea class="form-control " name="descricao" id="" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2" for="">Valor</label>
                        <div class="col-sm-10">
                            <input data-thousands="" id="currency" type="text" name="valor" class="form-control ">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2" for="">Tipo</label>
                        <div class="col-sm-10">
                            <select class="form-control " name="tipo" id="">
                                <option value="1">Entrada</option>
                                <option value="0">Saida</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button class="btn btn-success btn-block btn-rounded">Adicionar Registro</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection


@section('js')

    <script src="{{ asset('jquery.maskMoney.js') }}"></script>

    <script>
        $(function() {
            $('#currency').maskMoney();
        })
    </script>

@endsection
