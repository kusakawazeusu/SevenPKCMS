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
		"bAutoWidth": false,
		"order": [[ 11, "desc" ]]
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
			var CardResultRate;
			var CardResult;
			switch(response['playerLogDatasByID'][i].GameResult)
			{
				case 0:
				{
					CardResult = '沒中';
					CardResultRate=0;
				}
				break;
				case 1:
				{
					CardResult = '同花大順';
					CardResultRate=500;
				}
				break;
				case 2:
				{
					CardResult = '五梅';
					CardResultRate=200;
				}
				break;
				case 3:
				{
					CardResult = '同花順';
					CardResultRate=120;
				}
				break;
				case 4:
				{
					CardResult = '四梅';
					CardResultRate=50;
				}
				break;
				case 5:
				{
					CardResult = '葫蘆';
					CardResultRate=7;
				}
				break;
				case 6:
				{
					CardResult = '同花';
					CardResultRate=5;
				}
				break;
				case 7:
				{
					CardResult = '順子';
					CardResultRate=3;
				}
				break;
				case 8:
				{
					CardResult = '三條';
					CardResultRate=2;
				}
				break;
				case 9:
				{
					CardResult = '大兩對';
					CardResultRate=1
				}
				break;
			}
			if(response['playerLogDatasByID'][i].DoubleStar==1)
			{
				CardResultRate*=2;
			}
			PlayerLogByIDTable.row.add([
				i+1,
				response['playerLogDatasByID'][i].Name,
				response['playerLogDatasByID'][i].MachineName,
				section,
				response['playerLogDatasByID'][i].Credit.toLocaleString("en-US"),
				CardResult,
				CardResultRate.toLocaleString("en-US"),
				'0',
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