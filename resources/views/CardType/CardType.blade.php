@extends('wireframe')

@section('title','時間牌型選擇系統')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}" /><!-- 切記這兩行伊定要放在body最下面---->
<script>
	
	var showNum = 5;
	
	var entries = {{ $numOfEntries }};  // 紀錄總共有幾筆data
</script>
<script src="{{asset('js/CardType.js')}}"></script>

<div>
	<h1>控制牌型</h1>
	<hr>
	<br>
	<div class="row justify-content-between">
		<div class="col-4">
			<button id="createCardTypeBtn" name="createCardTypeBtn" class="btn btn-primary" data-toggle="modal" data-target="#cardTypeModal"><img src="{{asset('img/three-cards.png')}}"> 新增控制牌型</button>
		</div>
		<div class="col-4">
			<div class="pull-right">
				<form class="form-inline">
					<div class=" input-group ">
						<label class="input-group-addon">顯示筆數</label>
						<select class="form-control input-sm" id="show">
							<option value="5">5</option>
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="all">全部</option>
						</select>
					</div>
				</form>		
			</div>
		</div>
	</div>
	<br>
	<table class="table display table-striped" id="CardTypeTable" style="text-align:center">
		<thead>
			<th>功能</th>
			<th>牌型編號</th>
			<th>牌型種類</th>
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

<div class="modal fade" id="cardTypeModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 id="cardTypeModalTitle" class="modal-title d-block mx-auto"></h4>
			</div>
			<div class="modal-body">

				<h5>選擇牌型</h5>
				<hr>

				<form id="cardTypeForm">
					<input type="hidden" id="ID" name="ID">
					<div class="row">
						<label class="col-md-2 form-group custom-control custom-checkbox ml-2">
							<input type="checkbox" class="custom-control-input card checkbox" id="1" value="1" name="A">
							<span class="custom-control-indicator"></span>
							<span class="custom-control-description">A</span>
						</label>
						<label class="col-md-2 form-group custom-control custom-checkbox ml-2">
							<input type="checkbox" class="custom-control-input card checkbox" id="2" value="2" name="2">
							<span class="custom-control-indicator"></span>
							<span class="custom-control-description">2</span>
						</label>

						<label class="col-md-2 form-group custom-control custom-checkbox ml-2">
							<input type="checkbox" class="custom-control-input card checkbox" id="3" value="3" name="3">
							<span class="custom-control-indicator"></span>
							<span class="custom-control-description">3</span>
						</label>

						<label class="col-md-2 form-group custom-control custom-checkbox ml-2">
							<input type="checkbox" class="custom-control-input card checkbox" id="4" value="4" name="4">
							<span class="custom-control-indicator"></span>
							<span class="custom-control-description">4</span>
						</label>

						<label class="col-md-2 form-group custom-control custom-checkbox ml-2">
							<input type="checkbox" class="custom-control-input card checkbox" id="5" value="5" name="5">
							<span class="custom-control-indicator"></span>
							<span class="custom-control-description">5</span>
						</label>
					</div>
					<div class="row">
						<label class="col-md-2 form-group custom-control custom-checkbox ml-2">
							<input type="checkbox" class="custom-control-input card checkbox" id="6" value="6" name="6">
							<span class="custom-control-indicator"></span>
							<span class="custom-control-description">6</span>
						</label>
						<label class="col-md-2 form-group custom-control custom-checkbox ml-2">
							<input type="checkbox" class="custom-control-input card checkbox" id="7" value="7" name="7">
							<span class="custom-control-indicator"></span>
							<span class="custom-control-description">7</span>
						</label>

						<label class="col-md-2 form-group custom-control custom-checkbox ml-2">
							<input type="checkbox" class="custom-control-input card checkbox" id="8" value="8" name="8">
							<span class="custom-control-indicator"></span>
							<span class="custom-control-description">8</span>
						</label>

						<label class="col-md-2 form-group custom-control custom-checkbox ml-2">
							<input type="checkbox" class="custom-control-input card checkbox" id="9" value="9" name="9">
							<span class="custom-control-indicator"></span>
							<span class="custom-control-description">9</span>
						</label>

						<label class="col-md-2 form-group custom-control custom-checkbox ml-2">
							<input type="checkbox" class="custom-control-input card checkbox" id="10" value="10" name="10">
							<span class="custom-control-indicator"></span>
							<span class="custom-control-description">10</span>
						</label>
					</div>
					<div class="row">
						<label class="col-md-2 form-group custom-control custom-checkbox ml-2">
							<input type="checkbox" class="custom-control-input card checkbox" id="11" value="11" name="J">
							<span class="custom-control-indicator"></span>
							<span class="custom-control-description">J</span>
						</label>
						<label class="col-md-2 form-group custom-control custom-checkbox ml-2">
							<input type="checkbox" class="custom-control-input card checkbox" id="12" value="12" name="Q">
							<span class="custom-control-indicator"></span>
							<span class="custom-control-description">Q</span>
						</label>

						<label class="col-md-2 form-group custom-control custom-checkbox ml-2">
							<input type="checkbox" class="custom-control-input card checkbox" id="13" value="13" name="K">
							<span class="custom-control-indicator"></span>
							<span class="custom-control-description">K</span>
						</label>

						<label class="col-md-2 form-group custom-control custom-radio ml-2">
							<input type="radio" class="custom-control-input card radio" id="14" value="14" name="radio">
							<span class="custom-control-indicator"></span>
							<span class="custom-control-description">隨機</span>
						</label>

						<label class="col-md-2 form-group custom-control custom-radio ml-2">
							<input type="radio" class="custom-control-input card radio" id="15" value="15" name="radio">
							<span class="custom-control-indicator"></span>
							<span class="custom-control-description">一般</span>
						</label>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" id="cardTypeSubmit" class="btn btn-primary btn-lg">送出</button>
					<button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">取消</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection