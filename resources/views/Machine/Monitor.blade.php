@extends('wireframe')

@section('content')
<script src ="{{asset('js/Machine/Monitor.js')}}"></script>
<div class="jumbotron">
    <div class="row">
        @for ($i = 0; $i <= $counters; $i++)
            <div class="col-sm-1 mb-5">
                <div id="{{$i}}" type="button" class="card machineCard" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="right" title="使用者：餘額：">
                    <img class="card-img-top" src="{{asset('img/machine/online.png')}}" alt="Card image cap">
                    <div class="card-body">
                        <h6 class="card-title">第{{$i}}台</h6>
                        <p class="card-text">上線中</p>
                    </div>
                </div>                 
                       
                <div class="dropdown-menu" style="width:100%">
                    <a class="dropdown-item" href="#">鍵入</a>
                    <a class="dropdown-item" href="#">鍵出</a>
                    <a class="dropdown-item" href="#">保留</a>
                    <a class="dropdown-item" href="#">取消</a>
                </div>
            </div>
        @endfor
    </div>
</div>
@endsection