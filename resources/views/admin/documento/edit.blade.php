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
                <div class="panel ">
                    <div class="panel-heading">Cadastrar Documento</div>
                    <div class="panel-body">

                        <form action="{{ url('docs/edit') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Nome</label>
                                <input type="text" value="{{ $doc->name }}" name="name" class="form-control">
                            </div>

                            <input type="hidden" name="id" value="{{ $doc->id }}">
                            <div class="form-group">
                                <label for="">Esse documento terá verso?</label>
                                <br>
                                <label class="radio-inline">
                                    <input type="radio" value="1" name="verso"
                                        @if ($doc->verso == 1) checked @endif> SIM
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" value="0" name="verso"
                                        @if ($doc->verso == 0) checked @endif>NÃO
                                </label>
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
