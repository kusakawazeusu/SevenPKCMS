var CardBuffTable;
var page=0;
var pagesNum = Math.ceil(entries / showNum);  // 記錄總共有幾頁
var ajaxUrl;
var ChangeFormFlag;
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
		$('.createInput').val('');
		$('.checkText').hide();
		$('.checkInput').removeAttr('style');
		$('.checkInput').removeClass('error');
		$('#cardBuffSubmit').attr('disabled', false);
	});

	$('#cardBuffModal').on('hide.bs.modal',function(e){
		if(ChangeFormFlag == 1 && ajaxUrl=='CardBuff/UpdateCardBuff')
		{
			e.preventDefault();
			swal({
				title: '哈囉！',
				text: '我們發現有些資料已經被編輯過了，你確定要離開這個視窗嗎？',
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: '放棄編輯',
				cancelButtonText: '留在此視窗'
			}).then(function(){
				ChangeFormFlag = 0;
				$('#cardBuffModal').modal('toggle');
			});
		}
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

	$("input").on('input',function(){
		ChangeFormFlag = 1;
	});

	$("select").change(function(){
		ChangeFormFlag = 1;
	});



	$("#StartTime").focusout(function(){
		if($(this).val()!='')
		{
			$.ajax({
				url: 'CardBuff/CheckStartTime',
				type: 'GET',
				data: {
					ID:$('#ID').val(),
					StartTime: $(this).val()},
				})
			.done(function(response) {
				console.log("success");
				CheckStyle($('#StartTime'),$('#ErrStartTimeText'),response,'時間衝突！');
			})
			.fail(function() {
				console.log("error");
			});
		}
		else if($(this).val()=='')
			CheckStyle($('#StartTime'),$('#ErrStartTimeText'),CheckNotEmpty($(this).val()),'請填寫資料！');

	});

	$("#EndTime").focusout(function(){
		if($(this).val()=='')
		{
			CheckStyle($('#EndTime'),$('#ErrEndTimeText'),CheckNotEmpty($(this).val()),'請填寫資料！');
		}
		else if($(this).val!='' && ajaxUrl=='CardBuff/UpdateCardBuff')
		{
			$.ajax({
				url: 'CardBuff/CheckEndTime',
				type: 'GET',
				async:false,
				data: {
					ID:$('#ID').val(),
					EndTime: $(this).val()},
				})
			.done(function(response) {
				console.log("success");
				console.log(response);
				CheckStyle($('#EndTime'),$('#ErrEndTimeText'),response,'時間衝突！');
			})
			.fail(function() {
				console.log("error");
			});
		}		
		else if($(this).val()!='')
		{
			var response = {'valid':true};
			if( $(this).val()<$('#StartTime').val())
				response.valid=false;
			CheckStyle($('#EndTime'),$('#ErrEndTimeText'),response,'時間衝突！');
		}
	});

	$('.checkInput').focusout(function() {
		CheckValid();
	});

	$('#cardBuffSubmit').click(function(event) {
		var $inputs = $('#cardBuffForm :input');
		var valid = true;
		$inputs.each(function () {
			$(this).focusout();
			if ($(this).hasClass('error')) {
				valid = false;
			}
		});
		if(valid)
		{
			ChangeFormFlag = 0;
			SubmitData();
		}
	});
});

function SubmitData()
{
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
}

function GetCardType()
{
	$.ajax({
		url: 'CardType/CardTypeDatas',
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
	$('.checkInput').removeAttr('style');
	$('.checkInput').removeClass('error');
	$('.checkText').hide();
	$('#cardBuffSubmit').attr('disabled', false);
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

function CheckStyle(element,elementText,response,errMsg)
{
	if(response.valid==false)
	{
		element.css('border','1px solid brown');
		element.addClass('error');
		elementText.text(errMsg);
		elementText.show();
	}
	else
	{
		element.css('border','1px solid green');
		element.removeClass('error');
		elementText.hide();
	}
}

function CheckNotEmpty(elementValue)
{
	if(elementValue=='')
		return {'valid':false};
	return {'valid':true};
}
function CheckValid() {
	var $inputs = $('#cardBuffForm :input');
	var valid = true;
	$inputs.each(function () {
		if ($(this).hasClass('error')) 
		{
			valid = false;
		}
	});
	console.log(valid);
	if (valid)
		$('#cardBuffSubmit').attr('disabled', false);
	else
		$('#cardBuffSubmit').attr('disabled', true);
}