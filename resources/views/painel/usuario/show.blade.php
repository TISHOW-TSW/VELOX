@extends('painel.padrao')
@section('css')
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet"
        href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ url('admin/usuarios') }}" class="btn btn-warning btn-circle btn-lg"><i
                        class="fa fa-angle-left"></i></a>

            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-body">
                        <table class="table table-striped">

                            <tbody>
                                <tr>
                                    <td>Protrocinador</td>
                                    <td>
                                        @if ($user->meindica)
                                            {{ $user->meindica->name }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status da Conta</td>
                                    <td></td>

                                </tr>
                                <tr>
                                    <td>Nome</td>
                                    <td>{{ $user->name }}</td>

                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{ $user->email }}</td>

                                </tr>
                                <tr>
                                    <td>Documento</td>
                                    <td>{{ $user->cpf }}</td>

                                </tr>
                                <tr>
                                    <td>Nascimento</td>
                                    <td>{{ $user->nascimento }}</td>

                                </tr>

                                <tr>
                                    <td>CEP</td>
                                    <td>
                                        @if ($user->endereco)
                                            {{ $user->endereco->cep }}
                                        @endif
                                    </td>

                                </tr>
                                <tr>
                                    <td>Endereço</td>
                                    <td>
                                        @if ($user->endereco)
                                            {{ $user->endereco->endereco }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Bairro</td>
                                    <td>
                                        @if ($user->endereco)
                                            {{ $user->endereco->bairro->name }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Cidade</td>
                                    <td>
                                        @if ($user->endereco)
                                            {{ $user->endereco->cidade->name }}
                                        @endif
                                    </td>

                                </tr>
                                <tr>
                                    <td>Estado</td>
                                    <td>
                                        @if ($user->endereco)
                                            {{ $user->endereco->estado->name }}
                                        @endif
                                    </td>

                                </tr>
                                <tr>
                                    <td>Ultimo Login</td>
                                    <td></td>

                                </tr>
                                <tr>
                                    <td>Data do Cadastro</td>
                                    <td>{{ $user->created_at->format('d-M-Y') }}</td>

                                </tr>
                                <tr>
                                    <td>Ultima Atualização Cadastral</td>
                                    <td>{{ $user->updated_at->format('d-M-Y') }}</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="col-lg-12">
                <div class="panel ">
                    <div class="panel-heading">
                        <h3 class="panel-title">Dados Bancarios</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Banco</th>
                                    <th>Agencia</th>
                                    <th>Conta</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->contas as $conta)
                                    <tr>
                                        <td>{{ $conta->name }}</td>
                                        <td>{{ $conta->agencia }}</td>
                                        <td>{{ $conta->conta }}</td>
                                    </tr>
                                @empty
                                @endforelse


                            </tbody>
                        </table>

                    </div>

                </div>
            </div>


        </div>

    </div>
@endsection
