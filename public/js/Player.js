$(document).ready(function() {

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') //處理csrf token
		}
	});

	////////////////////////////////////////
	//玩家列表							  //
	////////////////////////////////////////

	var playerTable = $('#playerListTable').DataTable({
		"paging":   false,
		"info":     false,
		"searching": false,
		"bAutoWidth": false
	});

	var page=0;
	var pagesNum = Math.ceil(entries / showNum);  // 記錄總共有幾頁
	$("#page").text('1');
	$("#totalPage").text(pagesNum);


	QueryData(0);

	function QueryData(page)
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
			console.log(response);
			console.log("success");
			for(i=0;i<response['players'].length;++i)
			{
				playerTable.row.add([
					'<td style="text-align:center">'+
					'<button class="btn btn-primary mr-1"><i class="fa fa-edit aria-hidden="true"></i></button>'+
					'<button class="btn btn-primary mr-1"><i class="fa fa-remove aria-hidden="true"></i></button>'+
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
					'data-id="'+response['players'].ID+'" data-toggle="modal" data-target=""'+
					'>證件</button>'+

					'<button class="btn btn-primary mr-1" id="edit"' + response['players'][i].ID+'"'+
					'data-id="'+response['players'].ID+'" data-toggle="modal" data-target=""'+
					'>證件反面</button>'+

					'<button class="btn btn-primary mr-1" id="edit"' + response['players'][i].ID+'"'+
					'data-id="'+response['players'].ID+'" data-toggle="modal" data-target=""'+
					'>照片</button>'+
					'</td>'
					]).draw(false);

			}
			
		})
		.fail(function() {
			console.log("error");
		});		
	}

	$('#searchBtn').click(function(event) {
		/* Act on the event */
	});


	$("#nextPage").click(function(){
		page = page +1;
		if( page > pagesNum -1)
		{
			page = page - 1;
		}
		else
			QueryData(page);
	});

	// 按下「上一頁」
	$("#previousPage").click(function(){
		page = page -1;
		if( page < 0)
		{
			page = 0;
		}
		else
			QueryData(page);
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
			QueryData(0);
		}
		else
		{
			pages_num = Math.ceil(entries / showNum);
			page = 0;
			$("#totalPage").text(pagesNum);
			QueryData(0);
		}
	});
});