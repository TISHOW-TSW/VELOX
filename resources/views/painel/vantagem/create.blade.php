@extends('painel.padrao')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('vantagem.index') }}" class="btn btn-warning btn-circle btn-lg"><i
                    class="fa fa-angle-left"></i></a>

        </div>
    </div>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="box ">
                    <div class="box-header">Cadastrar Vantagem</div>
                    <div class="box-body">

                        <form action="{{route('vantagem.store')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Nome</label>
                                <input type="text" name="name" class="form-control">
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
