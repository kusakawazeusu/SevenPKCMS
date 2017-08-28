@extends('wireframe')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" /><!-- 切記這兩行伊定要放在body最下面---->
<script src ="{{asset('js/Machine/Monitor.js')}}"></script>

<style>
    .modal-header, h4, .close {
        background-color: #36648b;
        color:white !important;
        text-align: center;
        font-size: 30px;
    }

    .modal-body {
        background-color: #f9f9f9;
    }

    .modal-footer {
        background-color: #f9f9f9;
    }
    /* Set gray background color and 100% height */
    .sidenav {
      background-color: #f1f1f1;
      height: 100%;
    }    
</style>

<div class="jumbotron">
    <div class="row">
        @for ($i = 0; $i < count($machines); $i++)
            <div class="col-lg-1 col-md-2 mb-5">
                <div id="{{$machines[$i]->ID}}" type="button" class="card machineCard machineCardTooltip" data-toggle="dropdown" >
                    @if($machines[$i]->Status == 0)   {{-- 未連線 --}}
                        <img class="card-img-top" src="{{asset('img/machine/offline.png')}}" alt="Card image cap">
                    @elseif($machines[$i]->Status == 1)   {{-- 連線中 --}}
                        <img class="card-img-top" src="{{asset('img/machine/online.png')}}" alt="Card image cap">
                    @elseif($machines[$i]->Status == 2)   {{-- 保留中 --}}
                        <img class="card-img-top" src="{{asset('img/machine/event.png')}}" alt="Card image cap">
                    @else   {{-- 有問題 --}}
                        <img class="card-img-top" src="{{asset('img/machine/none.png')}}" alt="Card image cap">
                    @endif
                    <div class="card-body">
                        <h6 class="card-title">第{{$machines[$i]->ID}}台</h6>
                        @if($machines[$i]->Status == 0)   {{-- 未連線 --}}
                        <p id = "test" class="card-text test">離線中</p>
                        @elseif($machines[$i]->Status == 1)   {{-- 連線中 --}}
                        <p class="card-text">連線中</p>
                        @elseif($machines[$i]->Status == 2)   {{-- 保留中 --}}
                        <p class="card-text">保留中</p>
                        @else   {{-- 有問題 --}}
                        <p class="card-text">有問題</p>
                        @endif
                    </div>
                </div>                 
                
                <!-- dropdown menu id=machine's id -->       
                <div class="dropdown-menu" style="width:100%" id="{{$machines[$i]->ID}}">
                    <a class="dropdown-item" id="{{$machines[$i]->ID}}" onclick ="CreditIn(this.id)">鍵入</a>
                    <a class="dropdown-item" id="{{$machines[$i]->ID}}" onclick ="CreditOut(this.id)">鍵出</a>
                    <a class="dropdown-item" id="{{$machines[$i]->ID}}" onclick="GameReserved(this.id)">保留</a>
                    <a class="dropdown-item">取消</a>
                </div>
            </div>
        @endfor
    </div>
</div>

<!-- CreditIn -->
<div id="CreditInModal" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">            
                <h4 class="modal-title d-block mx-auto">鍵入</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="CreditInForm" class="form-horizontal">
            <div class="modal-body CreditInFormBody">
                <div class="flexbox">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <!-- PlayerID -->
                            <div class="form-group CreditInFormBodyPlayerID">
                                <div class="input-group CreditInInputPlayerID">
                                    <span class="input-group-addon">會員編號</span>
                                    <input id="PlayerID" class="form-control" type="text" name="PlayerID" placeholder="會員編號">
                                </div>
                            </div>

                            <!-- CreaditIn -->
                            <div class="form-group CreditInFormBodyCreditIn">
                                <div class="input-group CreditInInputCreditIn">
                                    <span class="input-group-addon">鍵入點數</span>
                                    <input id="CreaditIn" class="form-control" type="text" name="CreaditIn" placeholder="鍵入點數">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" name="add">           
            <div>
                <button id='CreaditInCancel' type="button" data-dismiss="modal" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>取消</button>
                <button id='CreaditInAccept' class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span>確認</button>
            </div> 
        </form>  
        </div>
    </div>    
</div>
<!-- Create Machine -->
@endsection