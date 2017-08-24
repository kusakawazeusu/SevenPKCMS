@extends('wireframe')

@section('content')
<script src="{{asset('js/Player.js')}}"></script>
<meta name="csrf-token" content="{{ csrf_token() }}" /><!-- 切記這兩行伊定要放在body最下面---->
<div>
	<h1>帳號系統</h1>
	<hr>
	<br>
	<div class="row justify-content-between">
		<div class="col-4">
			<button id="addPlayer" name="addPlayer" class="btn btn-primary" data-toggle="modal" data-target="#addProModal">新增</button>
		</div>
		<div class="col-5">
			<div class="row">
				<form class="form-inline">
					<div class=" input-group mr-2">
						<label class="input-group-addon">名稱:</label>
						<input type="text" class="form-control" placeholder="輸入名稱" aria-label="輸入名稱">
					</div>
					<div class="input-group mr-2">
						<label class="input-group-addon">卡片編號:</label>
						<input type="text" class="form-control" placeholder="輸入名稱" aria-label="輸入名稱">
					</div>
				</form>
				<button type="button" class="btn btn-secondary" onclick="myFunction()"><i class="fa fa-search" aria-hidden="true"></i>搜尋</button>	
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-2">
		<form class="form-inline">
			<div class="form-group">
				<label for="show">顯示筆數：</label>
				<select class="form-control input-sm" id="show">
					<option value="5">5</option>
					<option value="10">10</option>
					<option value="20">20</option>
					<option value="all">ALL</option>
				</select>
			</div>
		</form>
	</div>
</div>
<br>
<div>
	<table class="table display table-striped" id="playerListTable" style="text-align:center">
		<thead>
			<th>功能</th>
			<th>卡號</th>
			<th>名字</th>
			<th>儲值金額</th>
			<th>卡別</th>
			<th>身分證號</th>
			<th>行動電話</th>
			<th>介紹人</th>
			<th>操作</th>
		</thead>
		<tbody>
			<tr>
			</tr>
		</tbody>
	</table>
	
</div> 
<div class="row justify-content-between">
	<div class="col-4">
		<div class="text-left"><a id="previous_page" class="btn btn-light" role="button">返回上一頁</a></div>
	</div>
	<div class="col-4">
		<p class="text-center">總共<font id="total_page"></font>頁，目前<font id="page"></font>頁。</p>
	</div>
	<div class="col-4">
		<div class="text-right"><a id="next_page" class="btn btn-light" role="button">前往下一頁</a></div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header ">
				<h5 class="modal-title" id="exampleModalLabel">新增玩家</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="addProModal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">新增玩家</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="addProductForm" name="addProductForm">
				<div class="modal-body">
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon addProduct">產品名稱</span>
							<input id="productName" name="productName" type="text" class="form-control addProInput" >
						</div>
					</div>
					<div class="form-group">

						<div class="input-group">
							<span class="input-group-addon addProduct">產品種類</span>
							<select id="proType" name="proType" class="form-control"  >
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon addProduct">批發價</span>
							<input id="wholesalePrice" name="wholesalePrice" type="test" class="form-control addProInput" >
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon addProduct">售價</span>
							<input id="salePrice" name="salePrice" type="text" class="form-control addProInput" >
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon addProduct">廠商</span>						
							<select id="company" name="company" class="form-control" >
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon addProduct">備註</span>
							<textarea class="form-control addProInput" rows="5" id="memo" name="memo"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit"  id="addConfirm" class="btn btn-success" >新增</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">關閉</button>
				</div>

			</form>
		</div>
	</div>
</div>
@endsection