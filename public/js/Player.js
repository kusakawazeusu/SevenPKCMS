

var playerTable;
$(document).ready(function() {

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') //處理csrf token
		}
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

	$('#createPlayerSubmit').click(function(event) {
		$.ajax({
			url: 'Player/CreatePlayer',
			type: 'POST',
			data: {
				createName:$('#createName').val(),
				createIDCardNumber:$('#createIDCardNumber').val(),
				createBirthday:$('#createBirthday').val(),
				createGender:$('#createGender').val(),
				createCellphone:$('#createCellphone').val(),
				createCardNumber:$('#createCardNumber').val(),
				createrIntroducerName:$('#createrIntroducerName').val(),
				createEnable:$('#createEnable').val()
			},
		})
		.done(function(response) {
			console.log("success");
			swal("新增員工成功","列表將自動更新。","success");
			GetData(page);
			$('#createPlayer').modal('toggle');
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
				'<button class="btn btn-warning mr-1"><i class="fa fa-edit aria-hidden="true"></i></button>'+
				'<button class="btn btn-danger deletePlayer mr-1" onclick=DeletePlayer("'+response['players'][i].ID+'")><i class="fa fa-remove" aria-hidden="true"></i></button>'+
				'</td>',
				response['players'][i].CardNumber,
				response['players'][i].Name,
				response['players'][i].Balance,
				response['players'][i].CardType,
				response['players'][i].IDCardNumber,
				response['players'][i].Cellphone,
				response['players'][i].IntroducerID,
				'<td style="text-align:center">'+

				'<button class="btn btn-primary mr-1" id="edit"' + response['players'][i].ID+'"'+
				'data-id="'+response['players'][i].ID+'" data-toggle="modal" data-target=""'+
				'>證件</button>'+

				'<button class="btn btn-primary mr-1" id="edit"' + response['players'][i].ID+'"'+
				'data-id="'+response['players'][i].ID+'" data-toggle="modal" data-target=""'+
				'>證件反面</button>'+

				'<button class="btn btn-primary mr-1" id="edit"' + response['players'][i].ID+'"'+
				'data-id="'+response['players'][i].ID+'" data-toggle="modal" data-target=""'+
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

function DeletePlayer(ID)
{
	console.log(ID);
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
						'Deleted!',
						'Your file has been deleted.',
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