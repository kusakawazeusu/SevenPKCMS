@extends('wireframe')

@section('title','會員遊玩紀錄')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}" /><!-- 切記這兩行伊定要放在body最下面---->
<script>
	
	var showNum = 5;
	
	var entries = {{ $numOfEntries }};  // 紀錄總共有幾筆data
</script>
<script src="{{asset('js/CardBuff.js')}}"></script>



<div>
	<h1>會員遊玩紀錄</h1>
	<hr>
	<br>
	<div class="row justify-content-between">
		<div class="col-md-6">
			
		</div>

		<div class="col-md-5 mr-3">
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

				<div class="col-md-6">
					<div class="input-group mb-2">
						<div class="input-group-addon">姓名</div>
						<input type="text" class="form-control" id="Name" placeholder="要搜尋的姓名 ...">
					</div>
				</div>
			</div>
		</div>
	</div>


	<br>
	<table class="table display table-striped" id="CardBuffTable" style="text-align:center">
		<thead>
			<th>功能</th>
			<th>編號</th>
			<th>牌型編號</th>
			<th>牌型種類</th>
			<th>開始時間</th>
			<th>結束時間</th>
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

<div class="modal fade" id="cardBuffModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 id="cardBuffModalTitle" class="modal-title d-block mx-auto"></h4>
			</div>
			<div class="modal-body">

				<h5>必填資訊</h5>
				<hr>

				<form id="cardBuffForm">
					<input type="hidden" id="ID" name="ID">
					<div class="row">
						<div class="col-md-3 form-group">
							<label class="FormLabel">牌型總類</label>
							<select id="CardTypeID" name="CardTypeID" class="form-control create update" required>
							</select>
						</div>						

						<div class="col-md-3 form-group">
							<label class="FormLabel">開始時間</label>
							<input type="time" id="StartTime" name="StartTime" class="form-control create createInput update" required>
						</div>

						<div class="col-md-3 form-group">
							<label class="FormLabel">結束時間</label>
							<input type="time" id="EndTime" name="EndTime" class="form-control create createInput update" required>
						</div>						
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" id="cardBuffSubmit" class="btn btn-primary btn-lg">送出</button>
					<button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">取消</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection