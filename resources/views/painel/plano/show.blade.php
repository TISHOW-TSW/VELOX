@extends('admin.padrao')

@section('content')
    <div class="container">
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('admin.plano.index') }}" class="btn btn-warning btn-circle btn-lg"><i
                    class="fa fa-angle-left"></i></a>

        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-8">
            <div class="panel ">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $plano->name }}</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Valor:</h3>
                            <p>R${{ $plano->valor }}</p>

                            <h3>Primeiro Nivel</h3>
                            <p>{{ $plano->primeiro }} %</p>

                            <h3>Segundo Nivel</h3>
                            <p>{{ $plano->segundo }} %</p>

                            <h3>Terceiro Nivel</h3>
                            <p>{{ $plano->terceiro }} %</p>

                        </div>
                        <div class="col-md-6">
                            @if(isset($plano->img))
                                <img class="img img-responsive" src="{{ "https://nftcash.sfo3.digitaloceanspaces.com/" . $plano->img }}" alt="">

                            @endif
                        </div>
                    </div>




                </div>
            </div>
        </div>


    </div>

    </div>
@endsection
