@extends('painel.padrao')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('vantagem.create') }}" class="btn btn-warning btn-circle btn-lg"><i
                        class="fa fa-plus"></i></a>

            </div>
        </div>
        <br>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="box ">
                    <div class="box-header">
                        <h3 class="box-title">Vantagens</h3>
                    </div>
                    <div class="box-body">

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Ações</th>

                            </tr>
                            </thead>
                            <tbody>

                            @forelse ($vantagens as $vantagem)
                                <tr>
                                    <td>{{ $vantagem->name }}</td>
                                    <td><a href="{{route('vantagem.edit',$vantagem)}}" class="btn btn-success"><i
                                                class="fa fa-pencil"></i></a></td>

                                </tr>
                            @empty
                                <p>Vazio</p>
                            @endforelse


                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
           

        </div>

    </div>

@endsection
