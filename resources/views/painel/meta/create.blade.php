@extends('painel.padrao')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('metas.index') }}" class="btn btn-warning btn-circle btn-lg"><i
                        class="fa fa-angle-left"></i></a>

            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-8">
                <div class="panel ">
                    <div class="panel-heading">Cadastrar Meta</div>
                    <div class="panel-body">

                        <form action="{{route('metas.store')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Nome</label>
                                <input type="text" name="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="name">Valor</label>
                                <input type="text" name="valor" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="name">Pontuação</label>
                                <input type="number" name="pontuacao" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="name">Ordem</label>
                                <input type="number" name="ordem" class="form-control">
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
