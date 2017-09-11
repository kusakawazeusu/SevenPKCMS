@extends('wireframe')

@section('title','會員遊玩紀錄')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}" /><!-- 切記這兩行伊定要放在body最下面---->
<script>
	
	var showNum = 5;
	var ID = {{$ID}};
	var entries = 0;  // 紀錄總共有幾筆data
</script>
<script src="{{asset('js/PlayerLogByID.js')}}"></script>

<div>
	<h1>會員遊玩紀錄</h1>
	<hr>
	<br>
	<div class="row justify-content-between">

		<div class="col-md-2">
		<button id="exportExcel" name="exportExcel" class="btn btn-success" onclick=Test()><i class="fa fa-download"></i> 匯出EXCEL</button>
		</div>

		<div class="col-md-6">
			<div class="row">
				<form class="form-inline">
					<div class=" input-group mr-2">
						<label class="input-group-addon">顯示筆數</label>
						<select class="form-control input-sm" id="show">
							<option value="5">5</option>
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="all">全部</option>
						</select>
					</div>
					<div class=" input-group mr-2">
						<div class="input-group-addon">開始時間</div>
						<input type="text" id="StartTime" name="StartTime" class="form-control search" placeholder="開始時間" aria-label="開始時間">
					</div>
					<div class="input-group mr-2">
						<div class="input-group-addon">結束時間</div>
						<input type="text" id="EndTime" name="EndTime" class="form-control search" placeholder="結束時間" aria-label="結束時間">
					</div>
				</form>
			</div>
		</div>
	</div>
	<br>
	<table class="table display table-striped" id="PlayerLogByIDTable" style="text-align:center">
		<thead>
			<th>編號</th>
			<th>會員名稱</th>
			<th>機台編號</th>
			<th>分區編號</th>
			<th>押注金額</th>
			<th>牌型名稱</th>
			<th>牌型贏得倍率</th>
			<th>比倍次數</th>
			<th>比倍贏得倍率</th>
			<th>JP贏錢金額</th>
			<th>總贏錢金額</th>
			<th>建立時間</th>
		</thead>
		<tbody>
		</tbody>
	</table>
	<div class="row justify-content-between">
		<div class="col-4">
			<div class="text-left"><a id="previousPage" class="btn btn-light" role="button"><i class="fa fa-arrow-left"></i> 上一頁</a></div>
		</div>
		<div class="col-4">
			<p class="text-center">資料共<font id="NumberOfEntries"></font>筆，總共<font id="totalPage"></font>頁，目前在第<font id="page"></font>頁。</p>
		</div>
		<div class="col-4">
			<div class="text-right"><a id="nextPage" class="btn btn-light" role="button">下一頁 <i class="fa fa-arrow-right"></i> </a></div>
		</div>
	</div>
</div>
@endsection