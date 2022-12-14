@extends('painel.padrao')

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
                <div  class="panel panel-primary ">
                    <div class="panel-heading"><h3 class="panel-title">Cadastrar Plano</h3></div>
                    <div class="panel-body">

                        <form action="{{ route('plano.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Nome</label>
                                <input type="text" name="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Valor</label>
                                <input id="currency" data-thousands="" type="text" name="valor" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Primeiro</label>
                                <input type="text" name="primeiro" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="">Segundo Nivel</label>
                                <input type="text" name="segundo" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Terceiro Nivel</label>
                                <input type="text" name="terceiro" class="form-control">
                            </div>


                            <div class="form-group">
                                <button class="btn btn-primary btn-block">Salvar</button>
                            </div>

                        </form>

                    </div>
                </div>
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
