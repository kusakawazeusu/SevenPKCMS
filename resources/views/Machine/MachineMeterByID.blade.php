@extends('wireframe') @Section('title','機台紀錄') @section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<!-- 切記這兩行伊定要放在body最下面---->
<script src="{{asset('js/Machine/MachineMeterByID.js')}}"></script>
<script>
	var ID = {{$id}};

</script>

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

	<div class="col-md-7 mr-3">
		<div class="row">
			<div class="col-md-4">
				<div class="input-group mb-2">
					<div class="input-group-addon">顯示筆數</div>
					<select class="form-control ShowEntries">
						<option value="5">5</option>
						<option value="10">10</option>
						<option value="ALL">全部</option>
					</select>
				</div>
			</div>

			<div class="col-md-4">
				<div class="input-group mb-2">
					<div class="input-group-addon">開始時間</div>
					<input type="text" id="StartTime" name="StartTime" class="form-control search" placeholder="開始時間" aria-label="開始時間">
				</div>
			</div>

			<div class="col-md-4">
				<div class="input-group mb-2">
					<div class="input-group-addon">結束時間</div>
					<input type="text" id="EndTime" name="EndTime" class="form-control search" placeholder="結束時間" aria-label="結束時間">
				</div>
			</div>

		</div>
	</div>

</div>

<div class="row">
	<table id="MachineMeterTableByID" class="table table-striped text-center" cellspacing="0">
		<thead>
			<tr>
				<th>項次</th>
				<th>機台名稱(編號)</th>
				<th>分區編號</th>
				<th>押注金額</th>
				<th>牌型名稱</th>
				<th>牌型贏得倍率</th>
				<th>是否雙星</th>
				<th>比倍次數</th>
				<th>比倍贏得賠率</th>
				<th>總贏錢金額</th>
				<th>建立時間</th>
			</tr>
		</thead>
	</table>
</div>

<div class="row justify-content-between mt-4">
	<div class="col-4">
		<div class="text-left">
			<a id="previousPage" class="btn btn-light" role="button">返回上一頁</a>
		</div>
	</div>
	<div class="col-4">
		<p class="text-center">資料共
			<font id="NumberOfEntries"></font>筆，總共
			<font id="totalPage"></font>頁，目前在第
			<font id="page"></font>頁。</p>
	</div>
	<div class="col-4">
		<div class="text-right">
			<a id="nextPage" class="btn btn-light" role="button">前往下一頁</a>
		</div>
	</div>
</div>


@endsection