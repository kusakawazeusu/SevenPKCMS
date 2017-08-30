@extends('wireframe')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}" /><!-- 切記這兩行伊定要放在body最下面---->
<script>
	
	var showNum = 5;
	
	var entries = {{ $numOfEntries }};  // 紀錄總共有幾筆data
</script>
<script src="{{asset('js/Player.js')}}"></script>
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
			<button id="addPlayer" name="addPlayer" class="btn btn-primary" data-toggle="modal" data-target="#createPlayer">新增會員</button>
		</div>
		<div class="col-5">
			<div class="row">
				<form class="form-inline">
					<div class=" input-group mr-2">
						<label class="input-group-addon">姓名:</label>
						<input type="text" id="name" name="name" class="form-control search" placeholder="輸入姓名" aria-label="輸入姓名">
					</div>
					<div class="input-group mr-2">
						<label class="input-group-addon">卡片編號:</label>
						<input type="text" id="cardNumber" name="cardNumber" class="form-control search" placeholder="輸入卡片編號" aria-label="輸入卡片編號">
					</div>
				</form>
				<button type="button" id="searchBtn" class="btn btn-secondary"><i class="fa fa-search" aria-hidden="true"></i>搜尋</button>	
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
		</tbody>
	</table>
	
</div> 
<div class="row justify-content-between">
	<div class="col-4">
		<div class="text-left"><a id="previousPage" class="btn btn-light" role="button">返回上一頁</a></div>
	</div>
	<div class="col-4">
		<p class="text-center">總共<font id="totalPage"></font>頁，目前<font id="page"></font>頁。</p>
	</div>
	<div class="col-4">
		<div class="text-right"><a id="nextPage" class="btn btn-light" role="button">前往下一頁</a></div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header ">
				<h5 class="modal-title" id="exampleModalLabel">新增</h5>
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
				<h4 class="modal-title d-block mx-auto">新增玩家</h4>
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


<div class="modal fade" id="createPlayer" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title d-block mx-auto">新增會員</h4>
			</div>
			<div class="modal-body">

				<h5>必填資訊</h5>
				<hr>

				<form id="createPlayerForm">
					<div class="row">
						<div class="col-md-3 form-group">
							<label class="FormLabel">姓名</label>
							<input type="text" id="createName" name="createName" class="form-control createInput" required>
						</div>						

						<div class="col-md-3 form-group">
							<label class="FormLabel">身分證字號</label>
							<input type="text" id="createIDCardNumber" name="createIDCardNumber" class="form-control createInput" required>
						</div>

						<div class="col-md-3 form-group">
							<label class="FormLabel">生日</label>
							<input type="text" id="createBirthday" name="createBirthday" class="form-control createInput" required>
						</div>

						<div class="col-md-3 form-group">
							<label class="FormLabel">性別</label>
							<select id="createGender" name="createGender" class="form-control" required>
								<option value="0">男</option>
								<option value="1">女</option>
							</select>
						</div>						
					</div>

					<div class="row">

						<div class="col-md-3 form-group">
							<label class="FormLabel">行動電話</label>
							<input type="text" id="createCellphone" name="CreateCellphone" class="form-control createInput" required>
						</div>

						<div class="col-md-3 form-group">
							<label class="FormLabel">卡號</label>
							<input type="text" id="createCardNumber" name="createCardNumber" class="form-control createInput" required>
						</div>

						<div class="col-md-3 form-group">
							<label class="FormLabel">介紹人名稱</label>
							<input type="text" id="createrIntroducerName" name="createrIntroducerName" class="form-control createInput" required>
						</div>							
						
						<div class="col-md-3 form-group">
							<label class="FormLabel">是否啟動此帳號</label>
							<select name="createEnable" class="form-control" required>
								<option value="1">啟用</option>
								<option value="0">凍結</option>
							</select>
						</div>
					</div>

					<h5 class="mt-4">額外資訊</h5>
					<hr>
					<div class="row">
						<div class="col-md-3 form-group">
							<label class="FormLabel">暱稱</label>
							<input type="text" id="createNickName" name="createNickName createInput" class="form-control">
						</div>

						<div class="col-md-3 form-group">
							<label class="FormLabel">職業</label>
							<input type="text" id="createCareer" name="createCareer createInput" class="form-control">
						</div>

						<div class="col-md-3 form-group">
							<label class="FormLabel">住宅地址</label>
							<input type="text" id="createAddress" name="createAddress createInput" class="form-control">
						</div>

						<div class="col-md-3 form-group">
							<label class="FormLabel">住宅電話</label>
							<input type="text" id="createTelephone" name="createTelephone createInput" class="form-control">
						</div>
					</div>

					<div class="row">
						<div class="col-md-3 form-group">
							<label class="FormLabel">婚姻狀況</label>
							<select id="createMarry" name="createMarry" class="form-control">
								<option value="0">未婚</option>
								<option value="1">已婚</option>
							</select>
						</div>

						<div class="col-md-3 form-group">
							<label class="FormLabel">來店方式</label>
							<select id="createComing" name="createComing" class="form-control">
								<option value="0">自行開車</option>
								<option value="1">交通車</option>
								<option value="2">客運</option>
								<option value="3">火車</option>
								<option value="4">其他</option>
							</select>
						</div>

						<div class="col-md-3 form-group">
							<label class="FormLabel">廣告接收</label>
							<select id="createAd" name="createAd" class="form-control">
								<option value="0">可電話通知</option>
								<option value="1">手機簡訊</option>
								<option value="2">都不收廣告</option>
							</select>
						</div>
						
						<div class="col-md-3 form-group">
							<label class="FormLabel">備註</label>
							<textarea type="text" id="createMemo" name="createMemo" class="form-control createInput"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" id="createPlayerSubmit" class="btn btn-primary btn-lg">送出</button>
					<button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">取消</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection