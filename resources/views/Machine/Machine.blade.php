@extends('wireframe')
@Section('title','機台分區管理')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<!-- 切記這兩行伊定要放在body最下面---->
<script src="{{asset('js/Machine/Machine.js')}}"></script>

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

    .correct {
        border-style: solid;
        border-color: green;
        border-width: 1px;
    }

    .error {
        border-style: solid;
        border-color: brown;
        border-width: 1px;
    }
</style>

<h1>機台分區管理</h1>
<hr>
<br>

<div class="row justify-content-between">

    <div class="col-md-2">
        <button class="btn btn-primary" onclick="OpenCreateMachineModal()">新增機台</button>
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
    <table id="MachineTable" class="table table-striped text-center" cellspacing="0">
        <thead>
            <tr>
                <th>功能</th>
                <th>編號</th>
                <th>經銷商</th>
                <th>機台名稱</th>
                <th>分區編號</th>
                <th>一注金額</th>
                <th>開分最大金額</th>
                <th>開分一次加多少金額</th>
                <th>最少可以上分金額</th>
                <th>最大可以下分金額</th>
                <th>下分金額</th>
                <th>下分額外的贈分</th>
                <th>兩對賠率</th>
                <th>三條賠率</th>
                <th>順子賠率</th>
                <th>同花賠率</th>
                <th>葫蘆賠率</th>
                <th>四枚賠率</th>
                <th>同花順賠率</th>
                <th>五枚賠率</th>
                <th>同花大順賠率</th>
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

<!-- Machine Modal -->
<div class="modal" id="MachineModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="MachineModalTitle" class="modal-title d-block mx-auto"></h4>
            </div>
            <div class="modal-body">

                <form id="MachineForm">
                    <input type="hidden" name="id" class="form-control" required>

                    <div class="row">

                        <div class="col-md-4 form-group">
                            <label class="FormLabel">經銷商編號</label>
                            <select id="AgentIDSelect" class="form-control" name="AgentID"></select>
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="FormLabel">機台名稱(編號)</label>
                            <input type="text" name="MachineName" class="form-control check" required>
							<small id="ErrorMsgMachineName" style="display:none;color:brown !important" class="form-text text-muted errmsg"></small>
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="FormLabel">分區編號</label>
                            <select name="SectionID" class="form-control" required>
                                <option value="0">2</option>
                                <option value="1">3</option>
                                <option value="2">5</option>
                                <option value="3">10</option>
                            </select>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-4 form-group">
                            <label class="FormLabel">開分最大金額</label>
                            <input type="text" name="MaxDepositCredit" class="form-control check numberic" required>
							<small id="ErrorMsgMaxDepositCredit" style="display:none;color:brown !important" class="form-text text-muted errmsg"></small>
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="FormLabel">開分一次加多少金額</label>
                            <input type="text" name="DepositCreditOnce" class="form-control check numberic" required>
							<small id="ErrorMsgDepositCreditOnce" style="display:none;color:brown !important" class="form-text text-muted errmsg"></small>
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="FormLabel">最少可以上分的金額</label>
                            <input type="text" name="MinCoinOut" class="form-control check" required>
							<small id="ErrorMsgMinCoinOut" style="display:none;color:brown !important" class="form-text text-muted errmsg"></small>
                        </div>

                    </div>                    
                    
                    <div class="row">

                        <div class="col-md-4 form-group">
                            <label class="FormLabel">最大下分金額</label>
                            <input type="text" name="MaxCoinIn" class="form-control check numberic" required>
							<small id="ErrorMsgMaxCoinIn" style="display:none;color:brown !important" class="form-text text-muted errmsg"></small>
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="FormLabel">一次下分金額</label>
                            <input type="text" name="CoinInOnce" class="form-control check numberic" required>
							<small id="ErrorMsgCoinInOnce" style="display:none;color:brown !important" class="form-text text-muted errmsg"></small>
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="FormLabel">下分額外的贈分</label>
                            <input type="text" name="CoinInBonus" class="form-control check numberic" required>
							<small id="ErrorMsgCoinInBonus" style="display:none;color:brown !important" class="form-text text-muted errmsg"></small>
                        </div>

                    </div>               

                    <div class="row">

                        <div class="col-md-3 form-group">
                            <label class="FormLabel">兩對賠率</label>
                            <input type="text" name="TwoPairsOdd" class="form-control check numberic" required>
							<small id="ErrorMsgTwoPairsOdd" style="display:none;color:brown !important" class="form-text text-muted errmsg"></small>
                        </div>

                        <div class="col-md-3 form-group">
                            <label class="FormLabel">三條賠率</label>
                            <input type="text" name="ThreeOfAKindOdd" class="form-control check numberic" required>
							<small id="ErrorMsgThreeOfAKindOdd" style="display:none;color:brown !important" class="form-text text-muted errmsg"></small>
                        </div>

                        <div class="col-md-3 form-group">
                            <label class="FormLabel">順子賠率</label>
                            <input type="text" name="StraightOdd" class="form-control check numberic" required>
							<small id="ErrorMsgStraightOdd" style="display:none;color:brown !important" class="form-text text-muted errmsg"></small>
                        </div>

                        <div class="col-md-3 form-group">
                            <label class="FormLabel">同花賠率</label>
                            <input type="text" name="FlushOdd" class="form-control check numberic" required>
							<small id="ErrorMsgFlushOdd" style="display:none;color:brown !important" class="form-text text-muted errmsg"></small>
                        </div>

                    </div>               

                    <div class="row">

                        <div class="col-md-3 form-group">
                            <label class="FormLabel">葫蘆賠率</label>
                            <input type="text" name="FullHouseOdd" class="form-control check numberic" required>
							<small id="ErrorMsgFullHouseOdd" style="display:none;color:brown !important" class="form-text text-muted errmsg"></small>
                        </div>

                        <div class="col-md-3 form-group">
                            <label class="FormLabel">四枚賠率</label>
                            <input type="text" name="FourOfAKindOdd" class="form-control check numberic" required>
							<small id="ErrorMsgFourOfAKindOdd" style="display:none;color:brown !important" class="form-text text-muted errmsg"></small>
                        </div>

                        <div class="col-md-3 form-group">
                            <label class="FormLabel">同花順賠率</label>
                            <input type="text" name="STRFlushOdd" class="form-control check numberic" required>
							<small id="ErrorMsgSTRFlushOdd" style="display:none;color:brown !important" class="form-text text-muted errmsg"></small>
                        </div>

                        <div class="col-md-3 form-group">
                            <label class="FormLabel">五枚賠率</label>
                            <input type="text" name="FiveOfAKindOdd" class="form-control check numberic" required>
							<small id="ErrorMsgFiveOfAKindOdd" style="display:none;color:brown !important" class="form-text text-muted errmsg"></small>
                        </div>

                    </div>          

                    <div class="row">
                    
                        <div class="col-md-3 form-group">
                            <label class="FormLabel">同花大順賠率</label>
                            <input type="text" name="RoyalFlushOdd" class="form-control check numberic" required>
							<small id="ErrorMsgRoyalFlushOdd" style="display:none;color:brown !important" class="form-text text-muted errmsg"></small>
                        </div>

                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="MachineSubmit" class="btn btn-primary btn-lg mx-auto">送出</button>
            </div>
            </form>
        </div>
    </div>
</div>


@endsection