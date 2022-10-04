@extends('painel.padrao')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('vantagem.index') }}" class="btn btn-warning btn-circle btn-lg"><i
                    class="fa fa-angle-left"></i></a>

        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-8">
            <div class="ibox ">
                <div class="ibox-title">Editar Meta</div>
                <div class="ibox-content">

                    <form action="{{ route('metas.update', $meta) }}" method="POST">
                        @method('PUT')
                        @csrf

                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input type="text"  value="{{$meta->name}}" name="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="name">Valor</label>
                            <input type="text" value="{{$meta->valor}}" name="valor" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="name">Pontuação</label>
                            <input type="number" value="{{$meta->pontuacao}}" name="pontuacao" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="name">Ordem</label>
                            <input type="number" value="{{$meta->ordem}}" name="ordem" class="form-control">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary btn-block">Salvar</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>


    </div>
@endsection
