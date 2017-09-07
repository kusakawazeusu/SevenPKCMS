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

	/*$('#TakePictureModal').on('shown.bs.modal', function() { //
		// do something...
		console.log('open');
		Webcam.set({
			width: 400,
			height: 400,
			image_format: 'jpeg',
			jpeg_quality: 90
		});
		Webcam.attach( '#my_camera' );
	});*/

	$('#TakePictureModal').on('hide.bs.modal', function() { //當一個modal關閉時，要把所以有的值恢復起始
		// do something...
		Webcam.reset()
	});



	$('#createPlayerBtn').click(function(event) {
		/* Act on the event */
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

	$("#nextPage").click(function(){
		page = page +1;
		if( page > pagesNum -1)
		{
			page = page - 1;
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
			GetData(0);
		}
		else
		{
			pages_num = Math.ceil(entries / showNum);
			page = 0;
			$("#totalPage").text(pagesNum);
			GetData(0);
		}
	});

	function GetInput(className)
	{
		var dataTest={};
		$('.'+className).each(function(index, el) {
			console.log(el.id);
			dataTest[el.id] =$("#"+el.id+"").val();
			console.log(dataTest);
		});
		return dataTest;
	}

	$('#playerSubmit').click(function(event) {
		$.ajax({
			url: ajaxUrl,
			type: 'POST',
			data:$('#createPlayerForm').serialize(),
		})
		.done(function(response) {
			console.log(response);
			console.log("success");
			swal("新增員工成功","列表將自動更新。","success");
			GetData(page);
			$('#playerModal').modal('toggle');
			$('.createInput').val('');
			
		})
		.fail(function() {
			console.log("error");
		});		
	});	
});

var page=0;
var pagesNum = Math.ceil(entries / showNum);  // 記錄總共有幾頁

function GetData(page)
{
	$.ajax({
		url: 'Player/'+page+'/'+showNum,
		type: 'GET',
		data: {
			name: $('.search#name').val(),
			cardNumber:$('.search#cardNumber').val()
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
				response['players'][i].IntroducerID,
				'<td style="text-align:center">'+

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

			$("#totalPage").text(pagesNum);

			$("#page").text(page+1);

		})
	.fail(function() {
		console.log("error");
	});
}

function GetPlayerData(ID)
{
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
				.done(function() {
					swal(
						'刪除成功!',
						'此會員已刪除.',
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
			$('#TakePictureModal').modal('toggle');
			globalType = Type;
			globalID = ID;

			//TakePhoto(Type,ID);
		}
		else
		{			
			document.getElementById('my_camera').innerHTML = '<img id="Photo" src="'+response['Photo']+'"/>';
			$('#TakePictureModal').modal('toggle');

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