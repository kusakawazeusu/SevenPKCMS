var CardTypeTable;
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

	$('#createCardTypeBtn').click(function(event) {
		/* Act on the event */
		$('#cardTypeModalTitle').text('新增控制牌型');
		ajaxUrl = 'CardType/CreateCardType';
	});

	CardTypeTable = $('#CardTypeTable').DataTable({
		"paging":   false,
		"info":     false,
		"searching": false,
		"bAutoWidth": false
	});
	GetData(page);
	//GetCardType();

	$('#test').click(function(event) {
		/* Act on the event */
		console.log(GetCheckBoxID('.card'));
	});

	$('#cardTypeModal').on('hide.bs.modal', function() { //當一個modal關閉時，要把所以有的值恢復起始
		// do something...		
		$('.radio').prop('checked', false);
		$('.checkbox').prop('checked', false);
	});

	$('.checkbox').change(function(event) {
		/* Act on the event */
		$('.radio').prop('checked', false);
	});

	$('.radio').change(function(event) {
		/* Act on the event */
		$('.checkbox').prop('checked', false);
	});

	$("#page").text('1');
	$("#totalPage").text(pagesNum);

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

	$('#cardTypeSubmit').click(function(event) {
		$.ajax({
			url: ajaxUrl,
			type: 'POST',
			data:
			{
				CardType:GetCheckBoxID('.card'),
				ID:$('#ID').val()
			}
		})
		.done(function(response) {
			console.log(response);
			console.log("success");
			if(ajaxUrl=='CardType/CreateCardType')
				swal("新增牌型成功","列表將自動更新。","success");
			else
				swal("更新牌型成功","列表將自動更新。","success");
			GetData(page);
			$('#cardTypeModal').modal('toggle');
			$('.card').prop('checked', false);
			
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
		url: 'CardType/'+page+'/'+showNum,
		type: 'GET',
	})
	.done(function(response) {
		CardTypeTable.clear().draw();
		var cardType;
		var button;
		for(i=0;i<response['cardTypes'].length;++i)
		{			
			button='';			
			if(response['cardTypes'][i].CardType!="一般" && response['cardTypes'][i].CardType!="隨機")
			{
				button = '<td style="text-align:center">'+
				'<button class="btn btn-success mr-1 updateBtn" onclick=GetCardTypeData("'+response['cardTypes'][i].ID+'")><i class="fa fa-pencil aria-hidden="true"></i></button>'+
				'</td>'; 
			}
			cardType=response['cardTypes'][i].CardType;
			CardTypeTable.row.add([
				button,
				response['cardTypes'][i].ID,
				cardType
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

function GetCardTypeData(ID)
{
	$.ajax({
		url: 'CardType/CardTypeData',
		type: 'GET',
		data: {ID: ID},
		dataType:'json'
	})
	.done(function(response) {
		console.log(response);
		$('#cardTypeModalTitle').text('正在編輯： 牌型編號' +response.ID );
		$('#ID').val(response.ID);
		for(var i =0;i<response.CardType.length;++i)
			$('#'+response.CardType[i]).prop('checked',true);
		$('#cardTypeModal').modal('toggle');
		ajaxUrl = 'CardType/UpdateCardType';
	})
	.fail(function() {
		console.log("error");
	});
}

function GetCheckBoxID(type)
{
	var checkBoxValue = "";
	$(type).each(function() {
		var ischecked = $(this).is(":checked");
		if (ischecked) {
			checkBoxValue += $(this).val() + ",";
		};
	});
	return checkBoxValue.substr(0,checkBoxValue.length-1);
}