@extends('painel.padrao')

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
                <div class="ibox-title">
                    <h3>{{ $plano->name }}</h3>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Valor:</h3>
                            <p>R${{ $plano->valor }}</p>
                            <h3>Indicação Direta</h3>
                            <p>{{ $plano->direto }} %</p>
                            <h3>Primeiro Nivel</h3>
                            <p>{{ $plano->primeiro }} %</p>

                            <h3>Segundo Nivel</h3>
                            <p>{{ $plano->segundo }} %</p>

                            <h3>Terceiro Nivel</h3>
                            <p>{{ $plano->terceiro }} %</p>

                        </div>
                        <div class="col-md-6">
                            <h3>Vantagens</h3>
                            @forelse ($plano->vantagems as $vantagem )
                                <p>{{$vantagem->name}}</p>
                            @empty

                            @endforelse
                        </div>
                    </div>




                </div>
            </div>
        </div>


    </div>
@endsection
