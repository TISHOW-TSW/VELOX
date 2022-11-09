@extends('admin.padrao')

@section('content')

    <div class="container">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Adicionar Contatos dos grupos</h3>
            </div>
            <div class="panel-body">
                <form action="{{url('admin/consultarcontato')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="">Cole os numeros dos contatos</label>
                        <textarea  class="form-control" name="contatos" id="" cols="30" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success">Consultar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
