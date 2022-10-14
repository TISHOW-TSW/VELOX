@extends('admin.padrao')
@section('css')
    <style>



        .dataTables_wrapper .dataTables_paginate .paginate_button {
            box-sizing: border-box;
            display: inline-block;
            min-width: 1.5em;
            padding: 0.5em 1em;
            margin-left: 2px;
            text-align: center;
            text-decoration: none !important;
            cursor: pointer;
            *cursor: hand;
            color: white !important;
            border: 1px solid transparent;
            border-radius: 2px;
        }


    </style>
@endsection
@section('content')
    <div class="modal fade blur" id="modalPadrao" aria-labelledby="modalPadraoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST" id="form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalPadraoLabel"></h5>
                    </div>
                    <div class="modal-body" id="modalBody">
                        <p id="descricao"></p>
                        <div id="error"></div>
                        @csrf
                        <div id="descricao"></div>
                        <div id="form-body">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onClick="closeModal()">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="" class="btn btn-warning btn-circle btn-lg"><i class="fa fa-plus"></i></a>

            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel caixa">
                    <div class="panel-heading">
                        <h3 class="panel-title">Usuarios</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table style="background-color: transparent" id="myTable" class="table table-striped">
                                <thead>

                                    <tr>
                                        <th>Nome</th>
                                        <th>Login</th>
                                        <th>Patrocinador</th>
                                        <th>Email</th>
                                        <th>CPF</th>
                                        <th>Telefone</th>
                                        <th>Saldo</th>
                                        <th>Ações</th>

                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Login</th>
                                        <th>Patrocinador</th>
                                        <th>Email</th>
                                        <th>CPF</th>
                                        <th>Telefone</th>
                                        <th>Saldo</th>
                                        <th>Ações</th>
                                    </tr>
                                </tfoot>
                                <tbody>

                                    @forelse ($users as $user)
                                        <tr style="background-color: transparent">
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->login }}</td>
                                            <td>
                                                @if ($user->meindica)
                                                    {{ $user->meindica->name }}
                                                @else
                                                @endif
                                            </td>
                                            <td>{{$user->email}}</td>
                                            <td>{{ $user->cpf }}</td>
                                            <td>{{ $user->telefone }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a onClick="openModal({{ $user }}, 'add')" class="btn btn-success" title="Adicionar saldo indicação"><i class="fa fa-plus"></i> </a>
                                                    <a onClick="openModal({{ $user }}, 'rm')" class="btn btn-warning" title="Remover saldo indicação"><i class="fa fa-times"></i> </a>
                                                </div>
                                            </td>
                                            <td><a href="{{ url('admin/user/edit', $user->id) }}"
                                                    class="btn btn-success btn-block">Editar</a>
                                                <a href="{{ url('admin/user/visualizar', $user->id) }}"
                                                    class="btn btn-primary btn-block">Visualizar</a>
                                                <a onClick="openModal({{ $user }}, 'alterar-patrocinador')" class="btn btn-light">Alterar patrocinador</a>

                                                <a target="_blank" href="{{url('admin/user/backoffice',$user)}}"
                                                   class="btn btn-warning btn-block">BackOffice</a>


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

    </div>
@endsection
@section('js')
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>

    <script>
        function openModal(usuario, acao) {
            // Do this before you initialize any of your modals
            // $.fn.modal.Constructor.prototype.enforceFocus = function() {};
            $('#modalPadrao').modal({ backdrop: false });
            $('#modalPadrao').modal('show');
            $('#descricao').text(`Usuário: ${usuario.name} - ${usuario.login}`);
            let formBody = '';

            if (acao == 'add' || acao == 'rm') {
                formBody = `
                    <div class="mb-3">
                        <label for="observacoes" class="form-label">Valor (R$)</label>
                        <input type="text" class="form-control" id="valor" name="valor"/>
                    </div>
                    <div class="mb-3">
                        <label for="observacoes" class="form-label">Justificativa</label>
                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3"></textarea>
                    </div>
                `;
            } else {
                formBody = `
                    <div class="mb-3">
                        <label for="patrocinador_id" class="form-label">Patrocinador</label>
                        <select class="form-select form-select-sm" id="patrocinador_id" name="patrocinador_id">
                            <option selected disabled>Selecione...</option>
                        </select>
                    </div>
                `;

            }

            $('#form-body').html(formBody);

            if (acao == 'add') {
                $('#modalPadraoLabel').text(`Adicionar Saldo de Rede`);
                $('#form').attr('action', `/admin/user/${usuario.id}/add-saldo`);
            } else if (acao == 'rm') {
                $('#modalPadraoLabel').text(`Retirar Saldo de Rede`);
                $('#form').attr('action', `/admin/user/${usuario.id}/remove-saldo`);
            } else if (acao == 'alterar-patrocinador') {
                $('#patrocinador_id').select2({
                    width: '100%',
                    ajax: {
                        url: '/api/options/get-users',
                        dataType: 'json',
                        data: function (params) {
                            var query = {
                                search: params.term,
                                type: 'public'
                            }

                            // Query parameters will be ?search=[term]&type=public
                            return query;
                        }
                    }
                });

                $('#form').attr('action', `/admin/user/edit/${usuario.id}/sponsor`);
            }
        }

        function closeModal() {
            $('#modalPadrao').modal('hide');
            $('#modalPadraoLabel').text('');
            $('#descricao').text('');
            $('#form').attr('action', '');
        }

        $('#myTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.4/i18n/pt_br.json'
            },
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            "columns": [{
                    "searchable": true
                },
                {
                    "searchable": true
                },
                {
                    "searchable": false
                },
                {
                    "searchable": true
                },
                {
                    "searchable": true
                },
                {
                    "searchable": true
                },
                {
                    "searchable": false
                },
                {
                    "searchable": false
                },

            ]

        });
    </script>
@endsection
