var PlayerLogByIDTable;
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


	PlayerLogByIDTable = $('#PlayerLogByIDTable').DataTable({
		"paging":   false,
		"info":     false,
		"searching": false,
		"bAutoWidth": false
	});
	GetData(page);


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

	$('#StartTime').datepicker({
		format: 'yyyy-mm-dd',
		language: 'zh-TW',
		autoclose: 1,
		todayHighlight:true
	});

	$('#EndTime').datepicker({
		format: 'yyyy-mm-dd',
		language: 'zh-TW',
		autoclose: 1,
		todayHighlight:true
	});

	$("#StartTime").keypress(function (e)
	{
		e.preventDefault();
	});

	$("#EndTime").keypress(function (e)
	{
		e.preventDefault();
	});

	$('#StartTime').datepicker().on('changeDate', function(event) {
		var startTime = $('#StartTime').datepicker().val();
		$('#EndTime').datepicker('setStartDate', startTime);
	});

	$('#EndTime').datepicker().on('changeDate', function(event) {
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

});


function GetData(page)
{
	$.ajax({
		url: 'PlayerLogData/'+page+'/'+showNum,
		type: 'GET',
		data:{
			playerID:playerID,
			StartTime:$('#StartTime').val(),
			EndTime:$('#EndTime').val()
		}
	})
	.done(function(response) {
		console.log(response);
		PlayerLogByIDTable.clear().draw();
		for(i=0;i<response['playerLogDatasByID'].length;++i)
		{
			var section;
			switch(response['playerLogDatasByID'][i].SectionID)
			{
				case 0:
					section = 2;
					break;
				case 1:
					section = 3;
					break;
				case 2:
					section = 5;
					break;
				case 3:
					section = 10;
					break;
				default:
				break;
			}

			PlayerLogByIDTable.row.add([
				response['playerLogDatasByID'][i].ID,
				response['playerLogDatasByID'][i].Name,
				response['playerLogDatasByID'][i].MachineName,
				section,
				response['playerLogDatasByID'][i].Credit,
				response['playerLogDatasByID'][i].DealID,
				'牌型倍率',
				'比倍次數',
				response['playerLogDatasByID'][i].BonusRate,
				response['playerLogDatasByID'][i].Jackpot,
				response['playerLogDatasByID'][i].WinCredit,
				response['playerLogDatasByID'][i].Created_at
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

function Export()
{
	if($('#StartTime').val()=='' && $('#EndTime').val()=='')
		location.href='PlayerLogData/Export'+'/'+playerID;
	else if($('#StartTime').val()!='' && $('#EndTime').val()!='')
		location.href='PlayerLogData/Export'+'/'+playerID+'/'+$('#StartTime').val()+'/'+$('#EndTime').val();
}