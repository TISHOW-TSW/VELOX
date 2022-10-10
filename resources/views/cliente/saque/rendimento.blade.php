@extends('painel.padrao')


@section('content')

    <div class="pcoded-content">


        <div class="row">
            <div class="col-md-12">
                <div class="card prod-p-card bg-success">
                    <div class="card-body">
                        <div class="row align-items-center m-b-25">
                            <div class="col">
                                <h6 class="m-b-5 text-white">{{trans('sistema.saq_valor_liberado')}}</h6>

                                <h3 class="m-b-0 text-white">
                                    R${{ number_format($fatura->totalRendimento(), 2, ',', '.')}}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign text-white"></i>
                            </div>
                        </div>
                        <p class="m-b-0 text-white">{{trans('sistema.saq_valor_liberado_desc')}}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>{{trans('sistema.saq_selecione_valor')}}</h5>
            </div>
            <div class="card-body table-border-style">

                <form action="{{url('saquerendimento')}}" method="post">
                    @csrf
                    <input type="hidden" value="{{$fatura->id}}" name="fatura_id">
                    <input type="hidden" name="meio_saque" id="meio_saque" value="">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                aria-orientation="vertical">


                                @if(isset(Auth::user()->bankon))
                                    <li><a class="nav-link text-left select_account" id="v-pills-bankon-tab"
                                           data-toggle="pill" href="#v-pills-bankon" role="tab"
                                           aria-controls="v-pills-bankon" aria-selected="true"
                                           data-account="1">{{trans('sistema.saq_tipo_bankon')}}</a></li>

                                @endif
                                @if(isset(Auth::user()->contaBancaria))
                                    <li><a class="nav-link text-left select_account" id="v-pills-conta_bancaria-tab"
                                           data-toggle="pill" href="#v-pills-conta_bancaria" role="tab"
                                           aria-controls="v-pills-conta_bancaria" aria-selected="false"
                                           data-account="3">{{trans('sistema.saq_tipo_conta_bancaria')}}</a></li>

                                @endif

                                @if(isset(Auth::user()->pix))
                                    <li><a class="nav-link text-left select_account" id="v-pills-pix-tab"
                                           data-toggle="pill"
                                           href="#v-pills-pix" role="tab" aria-controls="v-pills-pix"
                                           aria-selected="false"
                                           data-account="5">{{trans('sistema.saq_tipo_pix')}}</a></li>

                                @endif


                            </ul>
                        </div>
                        <div class="col-md-9 col-sm-12">
                            <div class="tab-content" id="v-pills-tabContent">


                                @if(isset(Auth::user()->bankon))
                                    <div class="tab-pane fade" id="v-pills-bankon" role="tabpanel"
                                         aria-labelledby="v-pills-bankon-tab">


                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <tr>
                                                    <td><strong>{{trans('sistema.saq_form_bankon')}}</strong></td>
                                                    <td>
                                                        {{\Illuminate\Support\Facades\Auth::user()->bankon->cod_bankon}}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <br/>

                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            data-feather="dollar-sign"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" name="valor_bankon"
                                                           placeholder="{{trans('sistema.saq_informe_valor')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endif


                                @if(isset(Auth::user()->contaBancaria))
                                    <div class="tab-pane fade" id="v-pills-conta_bancaria" role="tabpanel"
                                         aria-labelledby="v-pills-conta_bancaria-tab">


                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <tr>
                                                    <td><strong>{{trans('sistema.saq_form_banco')}}</strong></td>
                                                    <td>
                                                        {{\Illuminate\Support\Facades\Auth::user()->contaBancaria->codbanco}}

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{trans('sistema.saq_form_agencia')}}</strong></td>
                                                    <td>
                                                        {{Auth::user()->contaBancaria->agencia}}


                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{trans('sistema.saq_form_conta')}}</strong></td>
                                                    <td>
                                                        {{Auth::user()->contaBancaria->conta}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{trans('sistema.saq_form_tipo_conta')}}</strong></td>
                                                    <td>
                                                        {{Auth::user()->contaBancaria->tipo_formated}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{trans('sistema.saq_form_titular')}}</strong></td>
                                                    <td>
                                                        {{Auth::user()->contaBancaria->titular_name}}

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{trans('sistema.saq_form_documento')}}</strong></td>
                                                    <td>
                                                        {{Auth::user()->contaBancaria->titular_documento}}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <br/>

                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            data-feather="dollar-sign"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" name="valor_banco"
                                                           placeholder="{{trans('sistema.saq_informe_valor')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endif


                                @if(isset(Auth::user()->pix))
                                    <div class="tab-pane fade" id="v-pills-pix" role="tabpanel"
                                         aria-labelledby="v-pills-pix-tab">

                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <tr>
                                                    <td><strong>{{trans('sistema.saq_form_pix')}}</strong></td>
                                                    <td>
                                                        {{Auth::user()->pix->chave}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{trans('sistema.saq_form_pix_conta')}}</strong></td>
                                                    <td>
                                                        {!! Auth::user()->pix->banco()!!}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <br/>

                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            data-feather="dollar-sign"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" name="valor_pix"
                                                           placeholder="{{trans('sistema.saq_informe_valor')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">

                            <button type="submit" name="submit"
                                    class="btn btn-success btn-block text-uppercase">{{trans('sistema.saq_solicitar_saque_button')}}</button>


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
