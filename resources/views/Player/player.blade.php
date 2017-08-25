@extends('wireframe')

@section('content')
<script src="{{asset('js/Player.js')}}"></script>
<meta name="csrf-token" content="{{ csrf_token() }}" /><!-- 切記這兩行伊定要放在body最下面---->
<style>
	.input-group-addon.addPlayer {
		min-width:120px;
		text-align:center;
	}
</style>
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
			<form id="addPlayerForm" name="addPlayerForm">
				<div class="modal-body">
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon addPlayer">名字</span>
							<input id="name" name="name" type="text" class="form-control player">
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon addPlayer">暱稱</span>
							<input id="nickName" name="nickName" type="text" class="form-control player">
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon addPlayer">卡號</span>
							<input id="cardNumber" name="cardNumber" type="text" class="form-control player">
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon addPlayer">身分證字號</span>
							<input id="playerIDCardNumber" name="playerIDCardNumber" type="text" class="form-control player">
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon addPlayer">性別</span>
							<select id="gender" name="gender" class="form-control player">
								<option value=0>男性</option>
								<option value=1>女性</option>
								<option value=2>其他</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon addPlayer">生日</span>
							<input id="birthday" name="birthday" type="text" class="form-control player">
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon addPlayer">職業</span>
							<input id="career" name="career" type="text" class="form-control player">
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon addPlayer">介紹人名稱</span>
							<select id="introducerName" name="introducerName" class="form-control player"  >
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon addPlayer">住宅地址</span>
							<input id="address" name="address" type="text" class="form-control player">
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon addPlayer">住宅電話</span>
							<select id="telephone" name="telephone" class="form-control player"  >
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon addPlayer">行動電話</span>
							<input id="cellphone" name="cellphone" type="text" class="form-control player">
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon addPlayer">來店方式</span>
							<label class="form-check-label mr-2">
								<input class="form-check-input" type="radio" name="inlineRadioOptions" value="0">自行開車
							</label>
							<label class="form-check-label mr-2">
								<input class="form-check-input" type="radio" name="inlineRadioOptions" value="1">交通車
							</label>
							<label class="form-check-label mr-2">
								<input class="form-check-input" type="radio" name="inlineRadioOptions" value="2">客運
							</label>
							<label class="form-check-label mr-2">
								<input class="form-check-input" type="radio" name="inlineRadioOptions" value="3">火車
							</label>
							<label class="form-check-label mr-2">
								<input class="form-check-input" type="radio" name="inlineRadioOptions" value="4">其他
							</label>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon addPlayer">廣告接收</span>
							<label class="form-check-label">
								<input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">可電話通知
							</label>
							<label class="form-check-label">
								<input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">手機簡訊
							</label>
							<label class="form-check-label">
								<input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">都不收廣告
							</label>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon addPlayer">婚姻狀況</span>
							<select id="marry" name="marry" class="form-control player">
								<option value=0>未婚</option>
								<option value=1>已婚</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon addPlayer">備註</span>
							<textarea class="form-control addProInput" rows="5" id="memo" name="memo"></textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon addPlayer">是否啟動此帳號</span>
							<label class="form-check-label mr-2">
								<input class="form-check-input" type="radio" name="inlineRadioOptions" value="0">使用
							</label>
							<label class="form-check-label mr-2">
								<input class="form-check-input" type="radio" name="inlineRadioOptions" value="1">凍結
							</label>
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