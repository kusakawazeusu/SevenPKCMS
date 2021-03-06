@extends('wireframe')
@Section('title','機台歷史紀錄')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<!-- 切記這兩行伊定要放在body最下面---->
<script src="{{asset('js/Machine/MachineMeter.js')}}"></script>

<style>
    .modal-header,
    h4,
    .close {
        background-color: #36648b;
        color: white !important;
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

<h1>機台歷史紀錄</h1>
<hr>
<br>

<div class="row justify-content-between">

    <div class="col-md-2">
    </div>    

    <div class="col-md-7 mr-7">
        <div class="row">
            <div class="col-md-3">
                <div class="input-group mb-2">
                    <div class="input-group-addon">顯示筆數</div>
                    <select class="form-control ShowEntries">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="ALL">全部</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-5">
                <div class="input-group mb-2">
                    <div class="input-group-addon">廠商編號</div>
                    <select id="AgentID" class="form-control">
                        <option value="-1"> -- 選擇經銷商 -- </option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="input-group mb-2">
                    <div class="input-group-addon">機台名稱</div>
                    <input type="text" class="form-control" id="MachineName" placeholder="要搜尋的名稱 ...">
                </div>
            </div>            
        </div>
    </div>

</div>

<div class="row">
    <table id="MachineMeterTable" class="table table-striped text-center" cellspacing="0">
        <thead>
            <tr>
                <th>編號</th>
                <th>機台名稱(編號)</th>
                <th>分區編號</th>
                <th>遊戲次數</th>
                <th>雙星次數</th>
                <th>兩對次數</th>
                <th>三條次數</th>
                <th>順子次數</th>
                <th>同花次數</th>
                <th>葫蘆次數</th>
                <th>副四梅次數</th>
                <th>正四梅次數</th>
                <th>同花順次數</th>
                {{--  <th>正同花順次數</th>  --}}
                <th>五枚次數次數</th>
                <th>同花大順次數</th>
                {{--  <th>正同花大順次數</th>  --}}
                <th>押注金額</th>
                <th>得分金額</th>
                <th>水位率</th>
                <th>總鍵入金額</th>
                <th>總鍵出金額</th>
                <th>吞吐率</th>
                <th>清空紀錄</th>
                <th>紀錄查詢</th>
            </tr>
        </thead>
    </table>
</div>

<div class="row justify-content-between mt-4">
    <div class="col-4">
        <div class="text-left"><a id="previousPage" class="btn btn-light" role="button">返回上一頁</a></div>
    </div>
    <div class="col-4">
        <p class="text-center">資料共
            <font id="NumberOfEntries"></font>筆，總共
            <font id="totalPage"></font>頁，目前在第
            <font id="page"></font>頁。</p>
    </div>
    <div class="col-4">
        <div class="text-right"><a id="nextPage" class="btn btn-light" role="button">前往下一頁</a></div>
    </div>
</div>


@endsection