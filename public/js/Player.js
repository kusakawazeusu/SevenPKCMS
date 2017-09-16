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
		InitCamera();
		$('#updatePicture').hide();
		$('#takePictureConfirm').show();
	});


	TotalTest();


	$('#createPlayerBtn').click(function(event) {
		/* Act on the event */
		$('.createInput').val('');
		$('#PasswordLabel').text('密碼');
		$('#PasswordDiv').show();
		$('#PasswordBtnDiv').hide();
		$('#playerModalTitle').text('新增會員');
		ajaxUrl = 'Player/CreatePlayer';
		$('#Account').attr('readonly', false);
		$('#IntroducerName').attr('readonly', false);
		$('.checkText').hide();
		$('.checkInput').removeAttr('style');
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
		defaultViewDate: {year: 1900},
		onClose: function () {
			console.log('close');
		},
		autoclose: 1
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
		var $inputs = $('#PlayerForm :input');
		var valid = true;
		$inputs.each(function () {
			$(this).focusout();
			if ($(this).hasClass('error')) {
				valid = false;
			}
		});
		if(valid)
			SubmitData();
	});

	var PlayerForm = $("#PlayerForm");
	var AccountDepulicatedFlag = 0;
	PlayerForm.novalidate = false;


	function CheckValid() {
		var $inputs = $('#PlayerForm :input');
		var valid = true;
		$inputs.each(function () {
			if ($(this).hasClass('error')) {
				valid = false;
			}
		});
		if (valid)
			$('#playerSubmit').attr('disabled', false);
		else
			$('#playerSubmit').attr('disabled', true);
	}


	$("#Account").focusout(function(){
		var checkAccountResponse = CheckAccount($(this).val());
		if($(this).val()!= '' && ajaxUrl == 'Player/CreatePlayer')
		{
			CheckStyle($('#Account'),$('#ErrAccountText'),checkAccountResponse,checkAccountResponse.text);
		}
		else if( $(this).val() != '' && ajaxUrl == 'Player/CreatePlayer' && checkAccountResponse.valid)
		{
			$.ajax({
				url: "Player/CheckDepulicatedAccount",
				method: "POST",
				data: { "Account": $(this).val() },
			})
			.done(function(response) {
				CheckStyle($('#Account'),$('#ErrAccountText'),response,'請取另外一個帳號名稱，此名稱重複了！');
			})
			.fail(function() {
				console.log("error");
			});
		}
		else if($(this).val()=='')
			CheckStyle($('#Account'),$('#ErrAccountText'),CheckNotEmpty($(this).val()),'請填寫資料！');

	});

	$('#IDCardNumber').focusout(function(){
		if($(this).val()!='')
		{
			var checkIDCardNumberResponse = CheckIDCardNumber($(this).val());
			CheckStyle($('#IDCardNumber'),$('#ErrIDCardNumberText'),checkIDCardNumberResponse,'請使用合法的身分證字號！');
			if(checkIDCardNumberResponse.valid)
			{
				$('#Gender').val($(this).val().charAt(1)-1);
			}
		}
		else
			CheckStyle($('#IDCardNumber'),$('#ErrIDCardNumberText'),CheckNotEmpty($(this).val()),'請填寫資料！');

	});

	$('#IntroducerName').focusout(function() {
		if($(this).val()!='' && ajaxUrl == 'Player/CreatePlayer')
		{
			$.ajax({
				url: 'Player/CheckIntroducerName',
				type: 'POST',
				data: {IntroducerName: $(this).val()},
			})
			.done(function(response) {
				CheckStyle($('#IntroducerName'),$('#ErrIntroducerNameText'),response,'錯誤介紹人！');
			})
			.fail(function() {
				console.log("error");
			});			
		}
		else if($(this).val()=='')
			CheckStyle($('#IntroducerName'),$('#ErrIntroducerNameText'),CheckNotEmpty($(this).val()),'請填寫資料！');
	});

	$('#Password').focusout(function() {
		CheckStyle($('#Password'),$('#ErrPasswordText'),CheckNotEmpty($(this).val()),'請填寫資料！');
	});
	$('#ConfirmPassword').focusout(function() {

		if($(this).val()!='')
		{
			var response ={'valid':true};
			if($(this).val() != $('#Password').val())
				response.valid=false;
			CheckStyle($('#ConfirmPassword'),$('#ErrPasswordText'),response,'請確認密碼相同！');
			CheckStyle($('#Password'),$('#ErrPasswordText'),response,'請確認密碼相同！');
		}
		else
			CheckStyle($('#ConfirmPassword'),$('#ErrPasswordText'),CheckNotEmpty($(this).val()),'請填寫資料！');
	});
	
	$('#Name').focusout(function() {
		CheckStyle($('#Name'),$('#ErrNameText'),CheckNotEmpty($(this).val()),'請填寫資料！');
	});

	$('#Birthday').focusout(function() {
		CheckStyle($('#Birthday'),$('#ErrBirthdayText'),CheckNotEmpty($(this).val()),'請填寫資料！');
	});

	$('#Birthday').datepicker().on('changeDate', function() {
		CheckStyle($('#Birthday'),$('#ErrBirthdayText'),CheckNotEmpty($(this).val()),'請填寫資料！');
		CheckValid();
	});

	$('.checkInput').focusout(function() {
		CheckValid();
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
	$('#PasswordDiv').hide();
	$('#PasswordBtnDiv').show();
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
		$('#IntroducerName').attr('readonly', true);
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

function CheckIDCardNumber(IDCardNumber)
{
	var tab = 'ABCDEFGHJKLMNPQRSTUVXYWZIO',
	A1 = [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 3, 3, 3, 3, 3, 3],
	A2 = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0, 1, 2, 3, 4, 5],
	Mx = [9, 8, 7, 6, 5, 4, 3, 2, 1, 1];
	if (IDCardNumber.length != 10) return {valid:false};
	var i = tab.indexOf(IDCardNumber.toUpperCase().charAt(0));
	if (i == -1) return {valid:false};
	var sum = A1[i] + A2[i] * 9;
	for (i = 1; i < 10; i++) {
		var v = parseInt(IDCardNumber.charAt(i));
		if (isNaN(v)) return {valid:false};
		sum = sum + v * Mx[i];
	}
	if (sum % 10 != 0) return {valid:false};
	return {valid:true};
}

function CheckAccount(Account)
{
	var regexNumber = /\D/;
	var regexLength = /\d{10}/;
	if(regexNumber.test(Account))
		return {valid:false,text:'帳號僅能為數字！'};
	if(!regexLength.test(Account))
		return {valid:false,text:'長度為10！'};
	return {valid:true,text:''};
}

function Test1(id)
{
	console.log(id);
}
function Test2()
{
	console.log('Test2');
}
function Test3()
{
	console.log('Test3');
}
function Test4()
{
	console.log('Test4');
}

function TotalTest()
{
	var arrayOfFunc=[Test1(123),Test2(),Test3(),Test4()];
	for (var i =0; i<arrayOfFunc.length; ++i) {
		arrayOfFunc[i];
	}
}

function CheckStyle(element,elementText,response,errMsg)
{
	console.log(response);
	if(response.valid==false)
	{
		element.css('border','1px solid brown');
		element.addClass('error');
		elementText.text(errMsg);
		elementText.show();
		AccountDepulicatedFlag = 1;
	}
	else
	{
		element.css('border','1px solid green');
		element.removeClass('error');
		elementText.hide();
		AccountDepulicatedFlag = 0;	
	}
}

function CheckNotEmpty(elementValue)
{
	if(elementValue=='')
		return {'valid':false};
	return {'valid':true};
}