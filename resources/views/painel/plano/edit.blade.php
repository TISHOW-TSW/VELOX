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
                <div style="background-color: transparent" class="panel ">
                    <div class="panel-heading">Editar Plano</div>
                    <div class="panel-body">

                        <form action="{{ route('plano.update', $plano) }}" method="POST">
                            @method('PUT')
                            @csrf

                            <div class="form-group">
                                <label for="name">Nome</label>
                                <input type="text" value="{{ $plano->name }}" name="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Valor</label>
                                <input value="{{ $plano->valor }}" type="text"
                                    name="valor" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Direto</label>
                                <input type="text" value="{{ $plano->direto }}" name="direto" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Primeiro Nivel</label>
                                <input type="text" value="{{ $plano->primeiro }}" name="primeiro" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="">Segundo Nivel</label>
                                <input type="text" value="{{ $plano->segundo }}" name="segundo" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Terceiro Nivel</label>
                                <input type="text" value="{{ $plano->terceiro }}" name="terceiro" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Quarto Nivel</label>
                                <input type="text" value="{{ $plano->quarto }}" name="quarto" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Pontos</label>
                                <input type="text" value="{{ $plano->pontos }}" name="pontos" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Vantagens</label><br>

                                @forelse ($vantagens as $vantagem)
                                    <label>

                                        <input type="checkbox" name="vantagem_id[]" value="{{ $vantagem->id }}"
                                            @if (count($plano->vantagems->where('id', $vantagem->id))) checked @endif>

                                        {{ $vantagem->name }} </label><br>
                                @empty
                                @endforelse

                            </div>
                            <div class="form-group">
                                <button class="btn">Salvar</button>
                                <a class="btn" href="{{ url('admin/plano/cadfoto', $plano->id) }}">Cadastrar
                                    Foto</a>
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
