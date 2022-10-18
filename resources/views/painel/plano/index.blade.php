@extends('admin.padrao')

@section('content')


    <div class="container">
        <br>
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('admin.plano.create') }}" class="btn btn-warning btn-circle btn-lg"><i
                        class="fa fa-plus"></i></a>

            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-8">
                <div  class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">Planos</h3>
                    </div>
                    <div class="panel-body">

                        <table class="table">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Valor</th>
                                <th>Ações</th>

                            </tr>
                            </thead>
                            <tbody>

                            @forelse ($planos as $plano)
                                <tr >
                                    <td>{{ $plano->name }}</td>
                                    <td>$ {{number_format($plano->valor,2,',','.')}}</td>
                                    <td><a href="{{ route('admin.plano.edit', $plano) }}" class="btn"><i
                                                class="fa fa-pencil"></i></a>
                                        <a href="{{ route('admin.plano.show', $plano) }}" class="btn"><i
                                                class="fa fa-eye"></i></a>

                                    </td>

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
