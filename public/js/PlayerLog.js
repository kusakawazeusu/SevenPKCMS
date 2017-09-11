var PlayerLogTable;
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


	PlayerLogTable = $('#PlayerLogTable').DataTable({
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

	$('.search').on('keyup change', function(event) {
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
		url: 'PlayerLog/'+page+'/'+showNum,
		type: 'GET',
		data:{Name:$('#Name').val()}
	})
	.done(function(response) {
		console.log(response);
		PlayerLogTable.clear().draw();
		for(i=0;i<response['playerLogs'].length;++i)
		{
			PlayerLogTable.row.add([
				response['playerLogs'][i].PlayerID,
				response['playerLogs'][i].Name,
				response['playerLogs'][i].Games,
				response['playerLogs'][i].DoubleStar,
				response['playerLogs'][i].TotalCoinIn,
				response['playerLogs'][i].TotalWin,
				response['playerLogs'][i].TotalWin/response['playerLogs'][i].TotalCoinIn+'%',
				response['playerLogs'][i].Updated_at,
				'<td style="text-align:center">'+
				'<a class="btn btn-success mr-1 searchBtn" href="PlayerLog/'+response['playerLogs'][i].PlayerID+'"><i class="fa fa-search aria-hidden="true"></i></a>'+ 
				'</td>',
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
