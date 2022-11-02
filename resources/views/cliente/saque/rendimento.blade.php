@extends('painel.padrao')
@section('css')
    <style>
        body {
            color: white
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

    </style>
@endsection

@section('content')

    <div class="container">


        <div class="row">
            <div class="col-md-12">
                <div class="panel caixa bg-success">
                    <div class="panel-heading">
                        <h3 class="m-b-5 text-white">Valor liberado</h3>
                    </div>
                    <div class="panel-body">

                            <div class="col">


                                <h3 class="m-b-0 text-white">
                                    R${{ number_format($fatura->saldoRaiz->saldoRendimento->valor, 2, ',', '.')}}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign text-white"></i>
                            </div>

                        <p class="m-b-0 text-white">Valor disponível</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel caixa">

            <div class="panel-body table-border-style">
                <h3>SELECIONE:</h3>
                <form action="{{url('saquerendimento')}}" method="post">
                    @csrf
                    <input type="hidden" value="{{$fatura->id}}" name="compra_id">
                    <input type="hidden" name="meio_saque" id="meio_saque" value="">
                    <input type="hidden" name="valor" id="valor" value="{{ $fatura->saldoRaiz->saldoRendimento->valor }}">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                aria-orientation="vertical">


                                @if(isset(Auth::user()->bankon))
                                    <li><a class="nav-link text-left select_account" style="color:white" id="v-pills-bankon-tab"
                                           data-toggle="pill" href="#v-pills-bankon" role="tab"
                                           aria-controls="v-pills-bankon" aria-selected="true"
                                           data-account="1">Bankon</a></li>

                                @endif

                                @if(isset(Auth::user()->pix))
                                    <li><a class="nav-link text-left select_account" style="color:white" id="v-pills-pix-tab"
                                           data-toggle="pill"
                                           href="#v-pills-pix" role="tab" aria-controls="v-pills-pix"
                                           aria-selected="false"
                                           data-account="5">Pix</a></li>

                                @endif


                            </ul>
                        </div>
                        <div class="col-md-9 col-sm-12">
                            <div class="tab-content" id="v-pills-tabContent">


                                @if(isset(Auth::user()->bankon))
                                    <div class="tab-pane fade" id="v-pills-bankon" role="tabpanel"
                                         aria-labelledby="v-pills-bankon-tab">


                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <td><strong>cod_bankon</strong></td>
                                                    <td>
                                                        {{\Illuminate\Support\Facades\Auth::user()->bankon->cod_bankon}}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <br/>


                                        </div>
                                    </div>

                                @endif





                                @if(isset(Auth::user()->pix))
                                    <div class="tab-panel fade" id="v-pills-pix" role="tabpanel"
                                         aria-labelledby="v-pills-pix-tab">

                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <td><strong>chave pix</strong></td>
                                                    <td>
                                                        {{Auth::user()->pix->chave}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Banco</strong></td>
                                                    <td>
                                                        {!! Auth::user()->pix->banco !!}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <br/>


                                    </div>

                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            @if(!isset(Auth::user()->pix) && !isset(Auth::user()->bankon))
                                <a href="{{ url('/myaccount') }}" class="btn btn-success btn-block text-uppercase">Cadastrar Meio saque</a>
                            @else

                                @php

                                    $agora = \Carbon\Carbon::now()->format('H:i:s');
                                  //  dd($agora);

                                @endphp

                                @if(!isset(Auth::user()->pix) && !isset(Auth::user()->bankon))
                                    <a href="{{ url('/myaccount') }}" class="btn btn-success btn-block text-uppercase">Cadastrar Meio saque</a>
                                @else

                                    <label for="">Saque de Rendimento sera a partir de Quinta-feira</label>

                                @endif

                            @endif


                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection

@section('js')
    <script>
        // conta bancaria - 3 | pix - 2 | bankon - 1
        $('#v-pills-pix-tab').on('click', () => {
            $('#meio_saque').val(2);
        });
        $('#v-pills-bankon-tab').on('click', () => {
            $('#meio_saque').val(1);
        });
        $('#v-pills-conta_bancaria-tab').on('click', () => {
            $('#meio_saque').val(3);
        });

    </script>
@endsection
