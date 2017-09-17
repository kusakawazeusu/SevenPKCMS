@extends('Report.Report')

@section('report-title','當班營業報表')

@section('report')

<style>
    #SingleSessionTable thead tr th
    {
        text-align: center;
    }
</style>

<script>
    
    var SingleSessionTable;
    var Page=0;
    var ShowEntries=5;
    var TotalPage;
    var SearchBundle = {};

    $(document).ready(function(){

        SingleSessionTable = $("#SingleSessionTable").DataTable({
                "paging":   false,
                "info":     false,
                "searching": false,
                "bAutoWidth": false,
                "columnDefs": [
                    {"render": function ( data, type, full, meta ) {return data.toLocaleString("en-US");}, "className": 'text-right', "targets": [3,4,5,6,7,8]}
                ]
        });
        
        GetData(ShowEntries,Page,SearchBundle);

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            language: 'zh-TW'
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
        SingleSessionTable.clear().draw();

        $.ajax({
            url: "{{ route('GetSingleSessionData') }}",
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
                    SingleSessionTable.row.add([
                        response[i].StartTime,
                        response[i].EndTime,
                        response[i].OperatorName,
                        response[i].TotalCreditIn,
                        response[i].TotalCreditOut,
                        response[i].Throughput,
                        response[i].TotalCoinIn,
                        response[i].TotalCoinOut,
                        response[i].CoinDiff,
                        `<button onclick="PrintThis(`+response[i].ID+`)" class="btn btn-secondary"><i class="fa fa-print" aria-hidden="true"></i></button>`
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

    function PrintThis(id)
    {
        SingleSessionTable.clear().draw();
        $.ajax({
            url: "{{ route('GetSingleSessionDataByid') }}",
            method: "GET",
            data: { id: id },
            success: function(response) {

                SingleSessionTable.row.add([
                    response[0].StartTime,
                    response[0].EndTime,
                    response[0].OperatorName,
                    response[0].TotalCreditIn,
                    response[0].TotalCreditOut,
                    response[0].Throughput,
                    response[0].TotalCoinIn,
                    response[0].TotalCoinOut,
                    response[0].CoinDiff,
                    `<button onclick="PrintThis(`+response[0].ID+`)" class="btn btn-secondary"><i class="fa fa-print" aria-hidden="true"></i></button>`
                ]).draw(false);
            }
        }).then(function(){
            var headstr = "<html><head><title></title></head><body>";
            var footstr = "</body>";
            var newstr = document.getElementById('PrintDiv').innerHTML;
            var oldstr = document.body.innerHTML;
            document.body.innerHTML = headstr+newstr+footstr;
            window.print();
            location.reload();
        })
    }

    function PrintPage()
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
            <button onclick="PrintPage()" class="btn btn-secondary ml-2"><i class="fa fa-print" aria-hidden="true"></i> 列印</button>
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
            <div class="col-md-4">
                <div class="input-group mb-2">
                    <div class="input-group-addon">姓名</div>
                    <input type="text" class="form-control" id="SearchName">
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
        <table id="SingleSessionTable"  class="table table-striped text-center section-to-print" cellspacing="0">
                <thead>
                    <tr>
                        <th>開始時間</th>
                        <th>結束時間</th>
                        <th>登入員工</th>
                        <th>鍵入</th>
                        <th>鍵出</th>
                        <th>吞吐差額</th>
                        <th>押分</th>
                        <th>得分</th>
                        <th>押得差額</th>
                        <th>操作</th>
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