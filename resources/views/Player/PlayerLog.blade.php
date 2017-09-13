@extends('wireframe')

@section('title','會員遊玩紀錄')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}" /><!-- 切記這兩行伊定要放在body最下面---->
<script>
	
	var showNum = 5;
	
	var entries = {{ $numOfEntries }};  // 紀錄總共有幾筆data
</script>
<script src="{{asset('js/PlayerLog.js')}}"></script>

<div>
	<h1>會員遊玩紀錄</h1>
	<hr>
	<br>
	<div class="row justify-content-between">

		<div class="col-md-2">
		</div>

		<div class="col-md-5 mr-3">
			<div class="row">
				<div class="col-md-6">
					<div class="input-group mb-2">
						<div class="input-group-addon">顯示筆數</div>
						<select class="form-control input-sm" id="show">
							<option value="5">5</option>
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="all">全部</option>
						</select>
					</div>
				</div>

				<div class="col-md-6">
					<div class="input-group mb-2">
						<div class="input-group-addon">姓名</div>
						<input type="text" class="form-control search" id="Name" placeholder="要搜尋的姓名 ...">
					</div>
				</div>
			</div>
		</div>
	</div>
	<br>
	<table class="table display table-striped" id="PlayerLogTable" style="text-align:center">
		<thead>
			<th>編號</th>
			<th>名字</th>
			<th>遊玩次數</th>
			<th>雙星次數</th>
			<th>總押注金額</th>
			<th>總贏錢金額</th>
			<th>水位率</th>
			<th>最後遊玩時間</th>
			<th>紀錄查詢</th>
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