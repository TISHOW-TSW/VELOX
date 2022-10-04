@extends('painel.padrao')
@section('css')
    <style>
        body {
            color: white;
        }

        .caixa {
            border-radius: 15px;
            opacity: 55%;
            background-color: #43308a;
            color: white
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


    @if (Auth::user()->tipo == 1)
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div style="background-color: #43308a;border-radius: 10px" class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">New Chat</h4>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('admin/newchat') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="">User</label>
                                <select class="form-control" name="user_id" id="">
                                    <option value=""></option>
                                    @foreach (App\Models\User::all() as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Message</label>
                                <textarea style="background-color: transparent;color:white" class="form-control" name="message" id="" cols="30"
                                    rows="10"></textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn">Send</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
    @else
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div style="background-color: #43308a;border-radius: 10px" class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">New Chat</h4>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('newchat') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="">Message</label>
                                <textarea style="background-color: transparent;color:white" class="form-control" name="message" id="" cols="30"
                                    rows="10"></textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn">Send</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
    @endif
    <br>
    <br>
    <div class="container">

        <div class="col-md-12">
            <button data-toggle="modal" data-target="#myModal" class="btn">New Chat</button>
        </div>
        <br>
        <br>
        <br>
        <div class="col-md-12">


            <div class="panel caixa">
                <div class="panel-heading">
                    <h5 class="panel-title">My Chats</h5>

                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="myTable" class="cell-border compact stripe">
                            <thead>
                                <tr>
                                    @if (Auth::user()->tipo == 1)
                                        <th>User</th>
                                    @endif
                                    <th>Date</th>
                                    <th>Hour</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    <th>Message</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($chats as $chat)
                                    <tr style="background-color: transparent">
                                        @if (Auth::user()->tipo == 1)
                                            <td>{{ $chat->user->name }}</td>
                                        @endif
                                        <td>{{ $chat->created_at->toFormattedDateString() }}</td>
                                        <td>{{ $chat->created_at->toTimeString() }}</td>
                                        <td>{{ $chat->status() }}</td>


                                        <td>


                                            @if ($chat->aberto == 0)
                                                <a class="btn" href="{{ url('reply', $chat->id) }}">Reply</a>
                                            @else
                                                @if (Auth::user()->tipo == 1)
                                                    <a class="btn"
                                                        href="{{ url('chat/open', $chat->id) }}">Open</a>
                                                @endif
                                            @endif

                                        </td>
                                        <td>{{count($chat->respostas->where("visto",0)->where('user_id','!=',Auth::user()->id))}}</td>
                                    </tr>
                                @empty
                                    <tr style="background-color: transparent">
                                        <td></td>
                                        <td></td>
                                        <td></td>


                                        <td></td>
                                    </tr>
                                @endforelse


                            </tbody>
                        </table>
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
        $('#myTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.4/i18n/pt_br.json'
            },
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            "order": [],
            "columns": [{
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


            ]

        });
    </script>
@endsection
