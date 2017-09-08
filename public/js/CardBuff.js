var CardBuffTable;
var page=0;
var pagesNum = Math.ceil(entries / showNum);  // 記錄總共有幾頁
var ajaxUrl;
$(document).ready(function() 
{
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') //處理csrf token
		}
	});

	$('#createCardBuffBtn').click(function(event) {
		/* Act on the event */
		$('#cardBuffModalTitle').text('新增時間牌型');
		ajaxUrl = 'CardBuff/CreateCardBuff';
	});

	CardBuffTable = $('#CardBuffTable').DataTable({
		"paging":   false,
		"info":     false,
		"searching": false,
		"bAutoWidth": false
	});
	GetData(page);
	GetCardType();


	$("#page").text('1');
	$("#totalPage").text(pagesNum);

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

	$('#cardBuffSubmit').click(function(event) {
		$.ajax({
			url: ajaxUrl,
			type: 'POST',
			data:$('#cardBuffForm').serialize(),
		})
		.done(function(response) {
			console.log(response);
			console.log("success");
			if(ajaxUrl=='CardBuff/CreateCardBuff')
				swal("新增時間成功","列表將自動更新。","success");
			else
				swal("更新時間成功","列表將自動更新。","success");
			GetData(page);
			$('#cardBuffModal').modal('toggle');
			$('.createInput').val('');
			
		})
		.fail(function() {
			console.log("error");
		});		
	});	
});

function GetCardType()
{
	$.ajax({
		url: 'CardType/CardTypeData',
		type: 'GET',
	})
	.done(function(response) {
		console.log("success");
		console.log(response);
		for(var i=0;i<response.length;++i)
		{
			$("#CardTypeID").append($("<option></option>").attr("value",response[i].ID).text(response[i].CardType));
		}
	})
	.fail(function() {
		console.log("error");
	});
}

function GetData(page)
{
	$.ajax({
		url: 'CardBuff/'+page+'/'+showNum,
		type: 'GET',
	})
	.done(function(response) {
		CardBuffTable.clear().draw();
		for(i=0;i<response['cardBuffs'].length;++i)
		{
			CardBuffTable.row.add([
				'<td style="text-align:center">'+
				'<button class="btn btn-success mr-1 updateBtn" onclick=GetCardBuffData("'+response['cardBuffs'][i].ID+'")><i class="fa fa-pencil aria-hidden="true"></i></button>'+
				'</td>',
				response['cardBuffs'][i].ID,
				response['cardBuffs'][i].CardTypeID,
				response['cardBuffs'][i].CardType,
				response['cardBuffs'][i].StartTime,
				response['cardBuffs'][i].EndTime
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

function GetCardBuffData(ID)
{
	$.ajax({
		url: 'CardBuff/CardBuffData',
		type: 'GET',
		data: {ID: ID},
	})
	.done(function(response) {
		console.log(response);
		$('#cardBuffModalTitle').text('正在編輯： 編號' +response.ID );
		for (var key in response) 
			$('#'+key).val(response[key]);
		$('#cardBuffModal').modal('toggle');
		ajaxUrl = 'CardBuff/UpdateCardBuff';

	})
	.fail(function() {
		console.log("error");
	});
}