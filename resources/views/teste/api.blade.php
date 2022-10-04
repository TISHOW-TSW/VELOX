@extends('painel.padrao')



@section('content')
    <div class="container">
        <button class="btn btn-success" onclick="simulatorAPI.openModal('993c4728-9865-479f-b749-4079263edd06')">Simule seu
            financiamento</button>
    </div>
@endsection


@section('js')

    <script src="https://simulador-credito-imobiliario.cred.loft.com.br/loader/loader.js"></script>

@endsection
