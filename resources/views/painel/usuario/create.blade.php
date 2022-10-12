@extends('admin.padrao')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('plano.index') }}" class="btn btn-warning btn-circle btn-lg"><i
                    class="fa fa-angle-left"></i></a>

        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-8">
            <div class="ibox ">
                <div class="ibox-title">Cadastrar Plano</div>
                <div class="ibox-content">

                    <form action="{{ route('plano.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input type="text" name="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Valor</label>
                            <input type="text" name="valor" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Direto</label>
                            <input type="text" name="direto" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Primeiro Nivel</label>
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
                            <label for="">Vantagens</label><br>

                            @forelse ($vantagens as $vantagem )
                                <label> <input type="checkbox" name="vantagem_id[]" value="{{ $vantagem->id }}" id="inlineCheckbox1">
                                    {{ $vantagem->name }} </label><br>
                            @empty

                            @endforelse

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
