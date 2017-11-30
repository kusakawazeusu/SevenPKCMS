@extends('wireframe')
@Section('title','機台公共設定')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" /><!-- 切記這兩行伊定要放在body最下面 -->
<script src="{{asset('js/Deposit.js')}}"></script>
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
<script>
var operatorID = {{ Auth::user()->id }};
</script>

<h1>機台公共設定</h1>
<hr>
<br>

<div>
<div class="col-md-6">遇見鬼牌是否一定中獎</div>
<div class="col-md-3">是</div>
<div calss="col-md-3">
    <button>更改</button>
</div>
</div>


@endsection