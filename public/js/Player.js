var playerTable;
var ajaxUrl;
var globalType;
var globalID;
$(document).ready(function() {

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') //處理csrf token
		}
	});

	$('#takePictureConfirm').click(function() {
		/* Act on the event */
		TakePhoto(globalType,globalID);
	});

	$('#TakePictureModal').on('hide.bs.modal', function() { //當一個modal關閉時，要把所以有的值恢復起始
		// do something...
		Webcam.reset()
	});

	$('#playerModal').on('hide.bs.modal', function() {
		// do something...
	});

	$('#updatePicture').click(function(event) {
		/* Act on the event */
		console.log('update');
		InitCamera();
		$('#updatePicture').hide();
		$('#takePictureConfirm').show();
	});

















$('#createPlayerBtn').click(function(event) {
	/* Act on the event */
	$('.createInput').val('');
	$('#PasswordLabel').text('密碼');
	$('#Password').show();
	$('#PasswordBtn').hide();
	$('#playerModalTitle').text('新增會員');
	ajaxUrl = 'Player/CreatePlayer';
	$('#Account').attr('readonly', false);
});


	////////////////////////////////////////
	//玩家列表							  //
	////////////////////////////////////////

	playerTable = $('#playerListTable').DataTable({
		"paging":   false,
		"info":     false,
		"searching": false,
		"bAutoWidth": false
	});

	$('#Birthday').datepicker({
		format: 'yyyy-mm-dd',
		language: 'zh-TW',
		startView: 'decades',
		autoclose: 1,
		defaultViewDate: {year: 1900}
	});
	
	$("#page").text('1');
	$("#totalPage").text(pagesNum);


	GetData(page);

	$('.deletePlayer').click(function(event) {
		/* Act on the event */
		DeletePlayer($(this).val());
	});

	

	$('.search').on('keyup change', function(event) {
		GetData(page);
	});

	$("#nextPage").click(function(){
		page = page +1;
		if( page > pagesNum -1)
		{
			page = page - 1;
			swal({
				title: "已到最後一頁！",
				type: 'warning'
			});
		}
		else
			GetData(page);
	});

	// 按下「上一頁」
	$("#previousPage").click(function(){
		page = page -1;
		if( page < 0)
		{
			page = 0;			
			swal({
				title: "已到第一頁！",
				type: 'warning'
			});
		}
		else
			GetData(page);
	});

	// 顯示筆數
	$("#show").change(function(){
		showNum = $(this).val();

		if( showNum == 'all' )
		{
			showNum = entries;
			pagesNum = 1;
			page = 0;
			$("#totalPage").text(pagesNum);
			GetData(page);
		}
		else
		{
			pages_num = Math.ceil(entries / showNum);
			page = 0;
			$("#totalPage").text(pagesNum);
			GetData(page);
		}
	});


	$('#playerSubmit').click(function(event) {
		SubmitData();
	});	
});

function SubmitData()
{
	$.ajax({
		url: ajaxUrl,
		type: 'POST',
		data:$('#PlayerForm').serialize(),
	})
	.done(function(response) {
		console.log(response);
		console.log("success");
		swal(response,"列表將自動更新。","success");
		GetData(page);
		$('#playerModal').modal('toggle');

	})
	.fail(function() {
		console.log("error");
	});	
}

var page=0;
var pagesNum = Math.ceil(entries / showNum);  // 記錄總共有幾頁

function GetData(page)
{
	$.ajax({
		url: 'Player/'+page+'/'+showNum,
		type: 'GET',
		data: {
			name: $('#searchName').val(),
			cardNumber:$('#searchCardNumber').val()
		},
	})
	.done(function(response) {
		playerTable.clear().draw();
		for(i=0;i<response['players'].length;++i)
		{
			playerTable.row.add([
				'<td style="text-align:center">'+
				'<button class="btn btn-success mr-1 updateBtn" onclick=GetPlayerData("'+response['players'][i].ID+'")><i class="fa fa-pencil aria-hidden="true"></i></button>'+
				'<button class="btn btn-danger deletePlayer mr-1" onclick=DeletePlayer("'+response['players'][i].ID+'")><i class="fa fa-trash" aria-hidden="true"></i></button>'+
				'</td>',
				response['players'][i].CardNumber,
				response['players'][i].Name,
				response['players'][i].Balance,
				response['players'][i].CardType,
				response['players'][i].IDCardNumber,
				response['players'][i].Cellphone,
				response['players'][i].IntroducerName,
				'<td style="text-align:center">'+
				'<button class="btn btn-info mr-1" onclick=PlayerDeposit('+response['players'][i].ID+')><i class="fa fa-plus"></i></button>'+

				'<button class="btn btn-primary mr-1" id="IDCardPhoto"' + response['players'][i].ID+'"'+
				'data-id="'+response['players'][i].ID+'" onclick=CheckPhoto("Front",'+response['players'][i].ID+')'+
				'>證件</button>'+

				'<button class="btn btn-primary mr-1" id="IDCardBackPhoto"' + response['players'][i].ID+'"'+
				'data-id="'+response['players'][i].ID+'" onclick=CheckPhoto("Back",'+response['players'][i].ID+')'+
				'>證件反面</button>'+

				'<button class="btn btn-primary mr-1" id="Photo"' + response['players'][i].ID+'"'+
				'data-id="'+response['players'][i].ID+'" onclick=CheckPhoto("Photo",'+response['players'][i].ID+')'+
				'>照片</button>'+
				'</td>'
				]).draw(false);
		}

		entries = response['numOfEntries'];
			pagesNum = Math.ceil(entries / showNum);  // 記錄總共有幾頁
			$('#NumberOfEntries').text(entries);
			$("#totalPage").text(pagesNum);

			$("#page").text(page+1);

		})
	.fail(function() {
		console.log("error");
	});
}

function GetPlayerData(ID)
{
	$('#PasswordLabel').text('');
	$('#Password').hide();
	$('#PasswordBtn').show();
	$('.createInput').val('');
	$.ajax({
		url: 'Player/PlayerData',
		type: 'GET',
		data: {ID: ID},
	})
	.done(function(response) {
		console.log(response);
		$('#playerModalTitle').text('正在編輯： ' +response.Name );
		for (var key in response) 
			$('#'+key).val(response[key]);
		$('#Account').attr('readonly', true);
		$('#playerModal').modal('toggle');
		ajaxUrl = 'Player/UpdatePlayer';

	})
	.fail(function() {
		console.log("error");
	});
	
}

function DeletePlayer(ID)
{
	swal({
		title: '確定刪除嗎?',
		text: "將無法回復此人資料",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: '是, 刪除!',
		cancelButtonText:'取消',
		showLoaderOnConfirm: true,
		preConfirm: function() {
			return new Promise(function(resolve) {
				$.ajax({
					url: 'Player/DeletePlayer',
					type: 'POST',
					data: {ID: ID},
				})
				.done(function(response) {
					swal(
						response,
						'此會員已刪除',
						'success'
						)
					GetData(page);
				})
				.fail(function() {
					console.log("error");
				});					
			});
		}   
	}).catch(swal.noop);
}

function TakePhoto(Type,ID)
{
	Webcam.snap( function(data_uri) {
		document.getElementById('my_camera').innerHTML = '<img id="memberInfoPhoto" src="'+data_uri+'"/>';
		$.ajax({
			url: 'Player/CreatePhoto',
			type: 'POST',
			data: {photo: data_uri,ID: ID,Type:Type},
		})
		.done(function() {
			console.log("success");
			$('#takePictureConfirm').hide();
			$('#updatePicture').show();
		})
		.fail(function() {
			console.log("error");
		});
	});
}

function CheckPhoto(Type,ID)
{
	$.ajax({
		url: 'Player/CheckPhoto',
		type: 'POST',
		dataType: 'json',
		data: {ID:ID,Type:Type},
	})
	.done(function(response) {
		console.log(response);
		if(response['valid']==true)
		{
			InitCamera();
			$('#takePictureConfirm').show();
			$('#updatePicture').hide();
			$('#TakePictureModal').modal('toggle');
			globalType = Type;
			globalID = ID;
		}
		else
		{			
			document.getElementById('my_camera').innerHTML = '<img id="Photo" src="'+response['Photo']+'"/>';
			$('#TakePictureModal').modal('toggle');
			$('#takePictureConfirm').hide();			
			$('#updatePicture').show();

		}
	})
	.fail(function() {
		console.log("error");
	});	
}

function InitCamera()
{
	Webcam.set({
		width: 400,
		height: 400,
		image_format: 'jpeg',
		jpeg_quality: 90
	});
	Webcam.attach( '#my_camera' );
}

function PlayerDeposit(ID)
{
	Deposit(ID,'Reload');
}

function ChangePassword()
{
	console.log($('#ID').val());

	swal({
		title: '請輸入舊密碼',
		input: 'password',  
		showCancelButton: true,
		cancelButtonColor: '#d33',
		showLoaderOnConfirm: true,
		cancelButtonText: '取消變更',
		allowOutsideClick:false,
		inputPlaceholder:
		'請輸入舊密碼',
		confirmButtonText:
		'繼續 <i class="fa fa-arrow-right"></i>',
		preConfirm: function (password){
			return new Promise(function(resolve,reject){
				$.ajax({
					url: 'Player/CheckPassword',
					type: 'POST',
					data: {
						ID:$('#ID').val(),
						Password: password},
					})
				.done(function(response) {
					if(response.valid==true)
						resolve();
					else 
						reject('密碼錯誤');
				})
				.fail(function() {
					console.log("CheckPassword Error");
				});

			})
		}
	}).then(function(){
		

		swal({
			title: '輸入新密碼及確認',
			html:
			'<input type="password" id="NewPasswrod" class="swal2-input">' +
			'<input type="password" id="ConfirmNewPasswrod" class="swal2-input">',
			preConfirm: function () {
				return new Promise(function (resolve,reject) {
					if($('#NewPasswrod').val()!=$('#ConfirmNewPasswrod').val())
					{
						reject('請確認密碼相同！');						
						$('#NewPasswrod').addClass('swal2-inputerror');
						$('#ConfirmNewPasswrod').addClass('swal2-inputerror');
					}
					else if($('#NewPasswrod').val()===''|| $('#ConfirmNewPasswrod').val()==='')
					{
						reject('密碼不可為空');
						$('#NewPasswrod').addClass('swal2-inputerror');
						$('#ConfirmNewPasswrod').addClass('swal2-inputerror');
					}
					else
						resolve();
				})
			},
			onOpen: function () {
				$('#NewPasswrod').focus()
			}
		}).then(function () {

			$.ajax({
				url: 'Player/UpdatePassword',
				type: 'POST',
				data: {
					ID:$('#ID').val(),
					Password: $('#NewPasswrod').val()},
				})
			.done(function(response) {
				console.log(response);
				swal({
					type: 'success',
					title: response
				})
			})
			.fail(function() {
				console.log("error");
			});
			


		}).catch(swal.noop)
	},function(dismiss){
		swal({
			type: 'error',
			title: '取消變更密碼'
		})
	});

}