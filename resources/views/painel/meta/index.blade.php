@extends('painel.padrao')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('metas.create') }}" class="btn btn-warning btn-circle btn-lg"><i
                        class="fa fa-plus"></i></a>

            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel ">
                    <div class="panel-heading">
                        <h3 class="panel-title">METAS</h3>
                    </div>
                    <div class="panel-body">

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Pontos</th>
                                <th>Ações</th>

                            </tr>
                            </thead>
                            <tbody>

                            @forelse ($metas as $meta)
                                <tr>
                                    <td>{{ $meta->name }}</td>
                                    <td>{{ $meta->pontuacao }}</td>
                                    <td><a href="{{ route('metas.edit', $meta) }}" class="btn btn-success"><i
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
