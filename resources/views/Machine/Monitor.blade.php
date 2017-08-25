@extends('wireframe')

@section('content')
<script src ="{{asset('js/Machine/Monitor.js')}}"></script>
<div class="jumbotron">
    <div class="row">
        @for ($i = 0; $i <= $counters; $i++)
            <div class="col-sm-2 mb-5">
                <div id="{{$i}}" type="button" class="card machineCard" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="right" data-html="true"
 title="使用者：<br/>餘額：">
                    <img class="card-img-top" src="{{asset('img/machine/offline.png')}}" alt="Card image cap">
                    <div class="card-body">
                        <h6 class="card-title">第{{$i}}台</h6>
                        <p class="card-text">離線中</p>
                    </div>
                </div>                 
                       
                <div class="dropdown-menu" style="width:100%" id="{{$i}}">
                    <a class="dropdown-item" id="{{$i}}" onclick="CreditIn(this.id)">鍵入</a>
                    <a class="dropdown-item" id="{{$i}}" onclick="CreditOn(this.id)">鍵出</a>
                    <a class="dropdown-item" id="{{$i}}" onclick="GameReserved(this.id)">保留</a>
                    <a class="dropdown-item">取消</a>
                </div>
            </div>
        @endfor
    </div>
</div>
@endsection