@extends('painel.padrao')

@section('content')

    <br>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('vantagem.index') }}" class="btn btn-warning btn-circle btn-lg"><i
                        class="fa fa-angle-left"></i></a>

            </div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-lg-8">
                <div class="panel ">
                    <div class="panel-heading">
                        <h3 class="panel-title">Editar Vantagem</h3>
                    </div>
                    <div class="panel-body">

                        <form action="{{ route('vantagem.update', $vantagem) }}" method="POST">
                            @method('PUT')
                            @csrf

                            <div class="form-group">
                                <label for="name">Nome</label>
                                <input type="text" value="{{ $vantagem->name }}" name="name" class="form-control">
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
