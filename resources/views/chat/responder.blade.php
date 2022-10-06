@extends('painel.padrao')
@section('css')
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

        label {
            color: white;
        }

        span {
            color: white;
        }

        #myTable_info {
            color: white;
        }

        #myTable1_info {
            color: white;
        }

        #myTable2_info {
            color: white;
        }

        #myTable3_info {
            color: white;
        }

        #myTable4_info {
            color: white;
        }

        .dataTables_empty {
            background-color: transparent;
        }

    </style>
@endsection

@section('content')
    <br>
    <div class="container">
        <div class="box caixa">
            <div class="box-header with-border">
                <div class="user-block">
                    <img class="img-circle" src="{{ asset('user.jpg') }}" alt="User Image">
                    <span class="username"><a href="#" style="color: white">User: {{ $chat->user->name }}</a></span>

                    @if (Auth::user()->tipo == 1)
                        <a class="btn" href="{{ url('chat/close', $chat->id) }}">Close Chat</a>
                    @endif

                </div>



            </div>

            <div style="background-color: transparent" class="box-body">

                <p>{{ $chat->message }}</p>

            </div>

            <div style="background-color: transparent" class="box-footer box-comments">
                @forelse ($chat->respostas as $resposta)
                    <div class="box-comment">

                        <img class="img-circle img-sm" src="{{ asset('user.jpg') }}" alt="User Image">
                        <div class="comment-text">
                            <span style="color: white" class="username">
                                {{ $resposta->user->name }}
                                <span class="text-muted pull-right">8:03 PM Today</span>
                            </span>
                            <p style="color: white"> {{ $resposta->message }}</p>
                        </div>

                    </div>
                @empty
                @endforelse



            </div>

            <div style="background-color: transparent" class="box-footer">
                <form action="{{ url('suport/reply') }}" method="post">
                    @csrf

                    <img class="img-responsive img-circle img-sm" src="{{ asset('user.jpg') }}" alt="Alt Text">
                    <input type="hidden" name="chat_id" value="{{ $chat->id }}">
                    <div class="img-push">

                        <input style="background-color: transparent;color:white" type="text" name="message"
                            class="form-control input-sm" placeholder="Press enter to post comment">
                        <br>

                        <button class="btn btn-success btn-block">Send</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
@endsection
