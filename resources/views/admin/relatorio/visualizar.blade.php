@extends('painel.padrao')

@section('content')
    <br>
    <br>
    <div class="container">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Planos</h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>


                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($assinaturas as $assinatura)
                            <tr>
                                <td>{{ $assinatura->user->name }}</td>
                                <td>{{ $assinatura->user->cpf }}</td>

                                </td>
                            </tr>

                        @empty
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
