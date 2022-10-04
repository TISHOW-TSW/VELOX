@extends('painel.padrao')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body {
            color: white;
        }

        .caixa {
            border-radius: 15px;
            /*opacity: 55%*/
            background-color:rgba(39, 34, 40, 0.9);
            border: 3px solid rgba(233, 0, 0,0.4);
            color: white;
            padding-bottom: 30px;
            display:flex;
            flex-direction: column;
            justify-content: space-around;
            text-transform: uppercase;
        }

        h3 {
            color: white;
        }

        input {
            background-color: transparent
        }

    </style>
@endsection
@section('content')
    <br><br>

    <div class="container">
        <div class="box collapsed-box caixa">
            <div class="box-header">
                <h3 class="box-title"> My Account</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">

                <div class="form-group">
                    <label for="">Name:</label>
                    <label for="">{{ Auth::user()->name }}</label>
                </div>

                <div class="form-group">
                    <label for="">Email:</label>
                    <label for="">{{ Auth::user()->email }}</label>
                </div>
                <div class="form-group">
                    <label for="">Phone:</label>
                    <label for="">{{ Auth::user()->telefone }}</label>
                </div>


            </div>
        </div>



        <div class="box collapsed-box caixa">
            <div class="box-header">
                <h3 class="box-title"> Pix Key</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">

                @if(isset(Auth::user()->pix))
                    <h1>Tem</h1>
                @else
                    <h1>Não tem</h1>
                    <form action="{{ url('cadconta') }}" method="post">
                        @csrf

                        <div class="form-group">
                            <label for="cod_id">Bank</label>
                            <select name="cod_id" class="form-control" id="select" required>

                                <option value="">Selecione...</option>

                            </select>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-success btn-block">register</button>
                        </div>
                    </form>
                @endif

                <br>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Wallet </th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse(Auth::user()->contas as $conta)
                            <tr>

                                <td>{{ $conta->agencia }}</td>


                            </tr>

                        @empty
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
        <div class="box collapsed-box caixa">
            <div class="box-header">
                <h3 class="box-title"> Video</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">

                @if (Auth::user()->videodisponivel() == 1)
                    <form action="{{ url('cadvideo') }}" method="post">
                        @csrf

                        <div class="form-group">
                            <label for="">video link</label>
                            <input style=" background-color: transparent;color:white" type="url" id="video" name="video"
                                class="form-control">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-success btn-block">register</button>
                        </div>
                    </form>
                @endif
                <br>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Video </th>
                            <th>Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse(Auth::user()->videos as $video)
                            <tr>

                                <td>{{ $video->video }}</td>

                                <td>{{ $video->status }}</td>
                            </tr>

                        @empty
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>


        <div style="border-radius: 15px" class="box collapsed-box caixa">
            <div class="panel-header">
                <div class="box-header">
                    <h3 class="box-title"> Pagamentos</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>

            </div>
            <div style="opacity: 100%" class="box-body">
                @php
                    $saldo = Auth::user()->creditos->sum('valor');
                    $controle = 12;
                    $sobra = 0;
                @endphp
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>

                                <th>CARROS</th>

                                <th>Value</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse (Auth::user()->compras as $assinatura)
                                <tr>

                                    <td>{{ $assinatura->plano->name }}</td>

                                    <td>
                                        ${{ number_format($assinatura->plano->valor, 2, ',', '.') }}
                                    </td>
                                    <td>{{ $assinatura->ativo_formated }}</td>
                                    <td class="text-center">

                                        @if ($assinatura->ativo == 1)
                                            <button class="btn">Paid</button>
                                        @else
                                            <a href="{{ url('player/payment', $assinatura->id) }}"
                                                class="btn">Payment</a>
                                            <a href="{{ url('cancelship', $assinatura->id) }}"
                                                class="btn">Cancel</a>
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



@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#select').select2()
            const bancoSelect = document.getElementById('select')

            console.log(bancoSelect)



            $.ajax({
                type: 'GET',
                url: 'https://brasilapi.com.br/api/banks/v1',
                success: function(response) {
                    $.each(response, function(i, obj) {
                        bancoSelect.options[bancoSelect.length] =  new Option(obj.name, obj.code)
                    })

                }
            })



        })
    </script>
@endsection
