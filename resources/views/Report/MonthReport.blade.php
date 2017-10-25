@extends('Report.Report')

@section('report-title','月營業報表')

@section('report')

<style>
    #MonthTable thead tr th
    {
        text-align: center;
    }
</style>

<script>
    
    var MonthTable;
    var Page=0;
    var ShowEntries=5;
    var TotalPage;
    var SearchBundle = {};

    $(document).ready(function(){

        MonthTable = $("#MonthTable").DataTable({
                "paging":   false,
                "info":     false,
                "searching": false,
                "bAutoWidth": false,
                "columnDefs": [
                    {"render": function ( data, type, full, meta ) {if(data) return data.toLocaleString("en-US"); else return data;}, "className": 'text-right', "targets": [1,2,3]}
                ]
        });
        
        GetData(ShowEntries,Page,SearchBundle);

        $('.datepicker').datepicker({
            format: 'yyyy-mm',
            language: 'zh-TW',
            minViewMode: 1
        })

        $(".ShowEntries").change(function(){
            ShowEntries = $(this).val();
            Page = 0;
            GetData(ShowEntries,Page,SearchBundle);
        });

        $("#nextPage").click(function(event){
        
            if(Page >= TotalPage-1 )
            {
                swal({
                    title: "已到最後一頁！",
                    type: 'warning'
                });
            }
            else
            {
                Page += 1;
                GetData(ShowEntries,Page,SearchBundle);
            }
        });

        $("#previousPage").click(function(){
            if(Page < 1 )
            {
                swal({
                    title: "已到第一頁！",
                    type: 'warning'
                });
            }
            else
            {
                Page -= 1;
                GetData(ShowEntries,Page,SearchBundle);
            }
        });


    });

    function GetData(ShowEntries, Page, SearchBundle)
    {
        MonthTable.clear().draw();

        $.ajax({
            url: "{{ route('GetMonthData') }}",
            method: "GET",
            data: { StartTime: SearchBundle.StartTime, EndTime: SearchBundle.EndTime, SearchName: SearchBundle.SearchName, Page: Page, ShowEntries: ShowEntries},
            success: function(response) {

                var NumOfData = Object.keys(response).length -1;

                $("#NumberOfEntries").text(response.count)
                TotalPage = Math.ceil(response.count / ShowEntries);

                if(ShowEntries == 'ALL')
                    TotalPage = 1;

                $("#totalPage").text( TotalPage );
                $("#page").text(Page+1);



                for(let i=0;i<NumOfData;i++)
                {
                    MonthTable.row.add([
                        response[i].Date.slice(0,7),
                        response[i].CreditIn,
                        response[i].CreditOut,
                        response[i].Throughput,
                        response[i].create_at
                    ]).draw(false);
                }
            }
        })
    }

    function Search()
    {
        SearchBundle = { StartTime: $("#StartTime").val(), EndTime: $("#EndTime").val(), SearchName: $("#SearchName").val()}
        page = 0;
        GetData(ShowEntries,Page,SearchBundle);
    }

    function Sync()
    {
        $("#SyncBtn").prop('disabled',true);
        $("#SyncBtn").html(`<i class="fa fa-refresh fa-spin fa-fw"></i> 請稍後...<span class="sr-only">Loading...</span>`)

        $.ajax({
            url: "{{ url('/syncmonthreport') }}",
            success: function(response) {

                if(response.length > 0)
                {
                    swal({
                        type: 'success',
                        title: '同步成功',
                        text: '新增已下資料：'+response
                    })
                    GetData(ShowEntries,Page,SearchBundle);
                }
                else
                {
                    swal({
                        type: 'error',
                        title: '操作失敗',
                        text: '沒有需要同步的資料'
                    })
                }
                $("#SyncBtn").prop('disabled',false);
                $("#SyncBtn").html(`<i class="fa fa-refresh" aria-hidden="true"></i> 同步資料`)
            }
        })
    }
        
    function Regenerate()
    {
        $("#RegenerateBtn").prop('disabled',true);
        $("#RegenerateBtn").html(`<i class="fa fa-cog fa-spin fa-fw"></i> 請稍後...<span class="sr-only">Loading...</span>`)

        $.ajax({
            url: "{{ url('/regeneratemonthreport') }}",
            success: function(response) {
                swal({
                    type: 'success',
                    title: '成功',
                    text: '報表已重新產生，總共有'+response+'筆資料！'
                })
                $("#RegenerateBtn").prop('disabled',false);
                $("#RegenerateBtn").html(`<i class="fa fa-file-text" aria-hidden="true"></i> 重新產生報表`)
                GetData(ShowEntries,Page,SearchBundle);
            }
        })
    }

    function PrintThis()
    {
            var headstr = "<html><head><title></title></head><body>";
            var footstr = "</body>";
            var newstr = document.getElementById('PrintDiv').innerHTML;
            var oldstr = document.body.innerHTML;
            document.body.innerHTML = headstr+newstr+footstr;
            window.print();
            location.reload();
    }
</script>

<div class="row mb-3">
    <div class="col-md-12">
            <button onclick="PrintThis()" class="btn btn-secondary ml-2"><i class="fa fa-print" aria-hidden="true"></i> 列印</button>
            <button onclick="Regenerate()" id="RegenerateBtn" class="btn btn-danger ml-2"><i class="fa fa-file-text" aria-hidden="true"></i> 重新產生報表</button>
            <button onclick="Sync()" id="SyncBtn" class="btn btn-warning ml-2"><i class="fa fa-refresh" aria-hidden="true"></i> 同步資料</button>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col-md-2">
        <div class="input-group mb-2">
            <div class="input-group-addon">顯示筆數</div>
            <select class="form-control ShowEntries">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="ALL">全部</option>
            </select>
        </div>
    </div>

    <div class="col-md-7">
        <div class="row">
            <div class="col-md-4">
            </div>
            <div class="col-md-3">
                <div class="input-group mb-2">
                    <div class="input-group-addon">開始日期</div>
                    <input type="text" class="datepicker form-control" id="StartTime">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group mb-2">
                    <div class="input-group-addon">結束日期</div>
                        <input type="text" class="datepicker form-control" id="EndTime">
                    </div>
            </div>

            <div class="col-md-2">
                <button onclick="Search()" type="button" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> 搜尋</button>
            </div>
        </div>
    </div>

</div>
<br>
<div id="PrintDiv">
        <table id="MonthTable"  class="table table-striped text-center section-to-print" cellspacing="0">
                <thead>
                    <tr>
                        <th>日期</th>
                        <th>鍵入</th>
                        <th>鍵出</th>
                        <th>吞吐差額</th>
                        <th>資料產生時間</th>
                    </tr>
                </thead>
            </table>
</div>


<div class="row justify-content-between mt-4">
        <div class="col-4">
            <div class="text-left"><a id="previousPage" class="btn btn-light" role="button"><i class="fa fa-arrow-left"></i> 上一頁</a></div>
        </div>
        <div class="col-4">
            <p class="text-center">資料共<font id="NumberOfEntries"></font>筆，總共<font id="totalPage"></font>頁，目前在第<font id="page"></font>頁。</p>
        </div>
        <div class="col-4">
            <div class="text-right"><a id="nextPage" class="btn btn-light" role="button">下一頁 <i class="fa fa-arrow-right"></i> </a></div>
        </div>
</div>



@endsection