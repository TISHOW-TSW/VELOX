@extends('painel.padrao')
@section('content')
    <br>
    <br>
    <br>
    <div class="container">
        <a href="{{ url('docs/create') }}" class="btn btn-success">Adicionar Documentos</a>
        <br><br>
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Documentos</h3>
            </div>
            <div class="panel-body">

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Documento</th>
                            <th>Verso</th>
                            <th>Ação</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($docs as $doc)
                            <tr>
                                <td>{{ $doc->name }}</td>
                                <td>{{ $doc->verso_formated }}</td>
                                <td><a href="{{ url('docs/edit', $doc->id) }}" class="btn btn-primary">Editar</button></td>

                            </tr>
                        @empty
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
