@extends('wireframe')

@section('title','會員管理')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}" /><!-- 切記這兩行伊定要放在body最下面---->
<script>
	
	var showNum = 5;
	
	var entries = {{ $numOfEntries }};  // 紀錄總共有幾筆data
</script>
<script src="{{asset('js/Player.js')}}"></script>
<script src="{{asset('js/Deposit.js')}}"></script>
<script src="{{asset('js/webcam.min.js')}}"></script>
<script src="{{asset('js/webcam.swf')}}"></script>
<div>
	<h1>帳號系統</h1>
	<hr>
	<br>
	<div class="row justify-content-between">
		<div class="col-4">
			<button id="createPlayerBtn" name="createPlayerBtn" class="btn btn-primary" data-toggle="modal" data-target="#playerModal"><i class="fa fa-user-plus"></i> 新增會員</button>
		</div>
		<div class="col-6">
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
						<div class="input-group-addon">姓名</div>
						<input type="text" id="searchName" name="searchName" class="form-control search" placeholder="輸入姓名" aria-label="輸入姓名">
					</div>
					<div class="input-group mr-2">
						<div class="input-group-addon">卡片編號</div>
						<input type="text" id="searchCardNumber" name="searchCardNumber" class="form-control search" placeholder="輸入卡片編號" aria-label="輸入卡片編號">
					</div>
				</form>
			</div>
		</div>
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
		<div class="text-left"><a id="previousPage" class="btn btn-light" role="button"><i class="fa fa-arrow-left"></i> 上一頁</a></div>
	</div>
	<div class="col-4">
		<p class="text-center">資料共<font id="NumberOfEntries"></font>筆，總共<font id="totalPage"></font>頁，目前在第<font id="page"></font>頁。</p>
	</div>
	<div class="col-4">
		<div class="text-right"><a id="nextPage" class="btn btn-light" role="button">下一頁 <i class="fa fa-arrow-right"></i> </a></div>
	</div>
</div>





<div class="modal fade" id="playerModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 id="playerModalTitle" class="modal-title d-block mx-auto"></h4>
			</div>
			<div class="modal-body">

				<h5>必填資訊</h5>
				<hr>

				<form id="createPlayerForm">
					<input type="hidden" id="ID" name="ID">
					<div class="row">
						<div id="createAccountDiv" class="col-md-6 form-group">
							<label class="FormLabel">帳號(行動電話)</label>
							<input id="Account" type="text" name="Account" class="form-control create createInput update" required>
						</div>

						<div class="col-md-6 form-group">
							<label class="FormLabel">密碼</label>
							<input type="password" id="Password" name="Password" class="form-control create createInput update" required>

						</div>
					</div>
					<div class="row">
						<div class="col-md-3 form-group">
							<label class="FormLabel">姓名</label>
							<input type="text" id="Name" name="Name" class="form-control create createInput update" required>
						</div>						

						<div class="col-md-3 form-group">
							<label class="FormLabel">身分證字號</label>
							<input type="text" id="IDCardNumber" name="IDCardNumber" class="form-control create createInput update" required>
						</div>

						<div class="col-md-3 form-group">
							<label class="FormLabel">生日</label>
							<input type="text" id="Birthday" name="Birthday" class="form-control create createInput update" required>
						</div>

						<div class="col-md-3 form-group">
							<label class="FormLabel">性別</label>
							<select id="Gender" name="Gender" class="form-control create update" required>
								<option value="0">男</option>
								<option value="1">女</option>
							</select>
						</div>						
					</div>

					<div class="row">

						<div class="col-md-3 form-group">
							<label class="FormLabel">介紹人名稱</label>
							<input type="text" id="IntroducerName" name="IntroducerName" class="form-control create createInput update" required>
						</div>							
						
						<div class="col-md-3 form-group">
							<label class="FormLabel">是否啟動此帳號</label>
							<select id="Enable" name="Enable" class="form-control create update" required>
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
							<input type="text" id="NickName" name="NickName" class="form-control create createInput update">
						</div>

						<div class="col-md-3 form-group">
							<label class="FormLabel">職業</label>
							<input type="text" id="Career" name="Career" class="form-control create createInput update">
						</div>

						<div class="col-md-3 form-group">
							<label class="FormLabel">住宅地址</label>
							<input type="text" id="Address" name="Address" class="form-control create createInput update">
						</div>

						<div class="col-md-3 form-group">
							<label class="FormLabel">住宅電話</label>
							<input type="text" id="Telephone" name="Telephone" class="form-control create createInput update">
						</div>
					</div>

					<div class="row">
						<div class="col-md-3 form-group">
							<label class="FormLabel">婚姻狀況</label>
							<select id="Marry" name="Marry" class="form-control create update">
								<option value="0">未婚</option>
								<option value="1">已婚</option>
							</select>
						</div>

						<div class="col-md-3 form-group">
							<label class="FormLabel">來店方式</label>
							<select id="Coming" name="Coming" class="form-control create update">
								<option value="0">自行開車</option>
								<option value="1">交通車</option>
								<option value="2">客運</option>
								<option value="3">火車</option>
								<option value="4">其他</option>
							</select>
						</div>

						<div class="col-md-3 form-group">
							<label class="FormLabel">廣告接收</label>
							<select id="ReceiveAd" name="ReceiveAd" class="form-control create update">
								<option value="2">都不收廣告</option>
								<option value="0">可電話通知</option>
								<option value="1">手機簡訊</option>
							</select>
						</div>
						
						<div class="col-md-3 form-group">
							<label class="FormLabel">備註</label>
							<textarea type="text" id="Memo" name="Memo" class="form-control create createInput update"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" id="playerSubmit" class="btn btn-primary btn-lg">送出</button>
					<button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">取消</button>
				</div>
			</form>
		</div>
	</div>
</div>



<div class="TakePictureModalDiv">
	<div class="modal fade" id="TakePictureModal" role="dialog" aria-hidden="true" tabindex="-1" aria-labelledby="myModalLabel">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<div class="container-fluid " >
						<div id="my_camera" class="d-block mx-auto" style="width:400px; height:400px;"></div>
					</div>
				</div>
				<div class="modal-footer">
					<button id="takePictureConfirm" type="button" class="btn btn-success">拍照</button>
					<button id="updatePicture" type="button" class="btn btn-success">編輯</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
				</div>
			</div>
		</div>
	</div>  
</div>
@endsection