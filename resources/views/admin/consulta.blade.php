@extends('painel.padrao')

@section('content')
    <br>
    <div class="container">


        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Documentos cliente: {{ $user->name }}</h3>
            </div>
        </div>
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Documentos</h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>DOCUMENTO</th>
                            <th>ARQUIVO</th>
                            <th>Status</th>
                            <th>AÇÃO</th>

                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($docs as $doc)
                            <tr>
                                <td>{{ $doc->name }}</td>
                                <td>

                                    @if (count($user->anexos->where('doc_id', $doc->id)) > 0)
                                        <label for="">Frente</label>
                                        <a target="_blank"
                                            href="{{ url('arquivos', $user->anexos->where('doc_id', $doc->id)->first()->frente) }}">Vizualizar</a>
                                        @if ($doc->verso == 0)
                                        @else
                                            @if ($doc->verso == 1)
                                                @if ($user->anexos->where('doc_id', $doc->id)->first()->verso)
                                                    <br>
                                                    <label for="">Verso</label>
                                                    <a target="_blank"
                                                        href="{{ url('arquivos', $user->anexos->where('doc_id', $doc->id)->first()->verso) }}">Vizualizar</a>
                                                @else
                                                    <form method="post" action="{{ url('api/file-upload/frente') }}"
                                                        enctype="multipart/form-data">
                                                        <div class="col-md-6">
                                                            <label for="Frente">Verso</label>
                                                            <input type="file" name="file" id="file" />
                                                            <input type="hidden" name="possui" value="1">
                                                            <input type="hidden" name="doc_id" value="{{ $doc->id }}">
                                                        </div>
                                                        <input type="hidden" name="cliente_id" value="{{ $user->id }}">
                                                        <div class="col-md-4">
                                                            <input type="submit" name="upload" value="Upload"
                                                                class="btn btn-success" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <small id="file-help" class="form-text text-muted" tabindex="0">
                                                                <strong>Imagem da foto</strong> <br>
                                                                Tamanho máximo de cada anexo: 5MB.
                                                            </small>
                                                        </div>
                                                    </form>
                                                @endif
                                            @endif
                                        @endif
                                    @else
                                        <form method="post" action="{{ url('api/file-upload/frente') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">

                                                <div class="col-md-6">
                                                    <label for="Frente">Frente</label>
                                                    <input type="file" name="file" id="file" />
                                                    <input type="hidden" name="doc_id" value="{{ $doc->id }}">
                                                    <input type="hidden" name="possui" value="0">
                                                </div>
                                                <input type="hidden" name="cliente_id" value="{{ $user->id }}">
                                                <div class="col-md-4">
                                                    <input type="submit" name="upload" value="Upload"
                                                        class="btn btn-success" />
                                                </div>
                                                <div class="col-md-12">
                                                    <small id="file-help" class="form-text text-muted" tabindex="0">
                                                        <strong>Imagem da foto</strong> <br>
                                                        Tamanho máximo de cada anexo: 5MB.
                                                    </small>
                                                </div>
                                        </form>
                                        @if ($doc->verso == 1)
                                            <form method="post" action="{{ url('api/file-upload/frente') }}"
                                                enctype="multipart/form-data">
                                                <div class="col-md-6">
                                                    <label for="Frente">Verso</label>
                                                    <input type="file" name="file" id="file" />
                                                    <input type="hidden" name="possui" value="1">
                                                    <input type="hidden" name="doc_id" value="{{ $doc->id }}">
                                                </div>
                                                <input type="hidden" name="cliente_id" value="{{ $user->id }}">
                                                <div class="col-md-4">
                                                    <input type="submit" name="upload" value="Upload"
                                                        class="btn btn-success" />
                                                </div>
                                                <div class="col-md-12">
                                                    <small id="file-help" class="form-text text-muted" tabindex="0">
                                                        <strong>Imagem da foto</strong> <br>
                                                        Tamanho máximo de cada anexo: 5MB.
                                                    </small>
                                                </div>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if (count($user->anexos->where('doc_id', $doc->id)) > 0)
                                    <label for="">Status:
                                        {{ $user->anexos->where('doc_id', $doc->id)->first()->status }}</label>
                                    @else
                                    Não enviado
                                    @endif
                                </td>
                                <td>
                                    @if (count($user->anexos->where('doc_id', $doc->id)) > 0)
                                        <a href="{{ url('validar', $user->anexos->where('doc_id', $doc->id)->first()) }}"
                                            class="btn btn-primary btn-block">Validar</a>
                                        <a href="{{ url('invalidar', $user->anexos->where('doc_id', $doc->id)->first()) }}"
                                            class="btn btn-danger btn-block">Invalidar</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
@endsection
