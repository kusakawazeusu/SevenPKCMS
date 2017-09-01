@extends('wireframe')

@section('title','介紹人管理')

@section('content')


<script>
var NumberOfEntries = 0;
var Page = 0;
var totalPage = 0;
var ShowEntries = 5;
var SeachText = "%";
var t;
var AjaxUrl;

$(document).ready(function() {
    
    t = $('#IntroducerTable').DataTable({
                "paging":   false,
                "info":     false,
                "searching": false,
                "bAutoWidth": false,
    });

    // Initialize the table
    GetData(ShowEntries,Page,SeachText);

    /*
        對表格進行的操作。
    */

    $("#Name").keyup(function(event){
        if( (event.keyCode > 64 && event.keyCode < 91) || event.keyCode == 8 || event.keyCode == 46 || (event.keyCode < 58 && event.keyCode > 47) )
        {
            SeachText = $(this).val();
            GetData(ShowEntries,Page,SeachText);
        }
    });

    $(".ShowEntries").change(function(){
        ShowEntries = $(this).val();
        GetData(ShowEntries,Page,SeachText);
    });

    $("#nextPage").click(function(event){
        
        if(Page >= totalPage-1 )
        {
            swal({
                title: "已到最後一頁！",
                type: 'warning'
            });
        }
        else
        {
            Page += 1;
            GetData(ShowEntries,Page,SeachText);
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
            GetData(ShowEntries,Page,SeachText);
        }
    });

    /*
        對資料進行的操作
    */

    var CreateForm = document.getElementById("IntroducerForm");
    CreateForm.novalidate = false;

    $("#IntroducerSubmit").click(function(){

        if( CreateForm.checkValidity() == false)
        {
            $("#IntroducerForm").addClass("was-validated");
        }
        else
        {
            $("#IntroducerSubmit").prop('disabled',true);
            console.log($("#IntroducerForm").serialize());

            $.ajax({
               url: AjaxUrl,
               method: "POST",
               data: $("#IntroducerForm").serialize(),
               success: function(result) {
                    $("#IntroducerSubmit").prop('disabled',false);
                    $("#IntroducerModal").modal('hide');
                    swal({
                        title: "操作成功！",
                        text: "列表將自動更新。",
                        type: "success",
                        animation: true});
                    GetData(ShowEntries,Page,SeachText);
               },
               statusCode: {
                   500: function() {
                       swal("操作失敗","請確認欄位是否填寫正確！","error");
                   }
               }
            });
        }
    });
});

/*
    This function is used for getting data from API.

    @params
        - ShowEntries : Indicated the number of records we query from the API.
        - Page : We needs this to calculate the data offset.
        - SearchText : The keyword we're searching for.

*/
function GetData(ShowEntries, Page, SearchText)
{
    var SendingData = { "ShowEntries":ShowEntries, "Page":Page, "SearchText":SearchText };

    $.ajax({
        url: "{{route('GetIntroducers')}}",
        method: "GET",
        data: SendingData,
        success: function(data) {
            t.clear().draw();
            NumberOfEntries = data['count'];
            totalPage = Math.ceil(NumberOfEntries / ShowEntries);
            Page = 0;
            $("#NumberOfEntries").text(NumberOfEntries);
            $("#totalPage").text( totalPage );
            $("#page").text(Page+1);

            var NumOfData = Object.keys(data).length - 1;

            for( var i=0; i<NumOfData; i++ )
            {
                var Gender,CalcMethod;

                switch (data[i].Gender)
                {
                    case 0:
                        Gender = "男";
                        break;
                    case 1:
                        Gender = "女";
                        break;
                    case 2:
                        Gender = "其他";
                        break;
                }

                switch (data[i].CalcWeeks)
                {
                    case 0:
                        CalcMethod = "每周";
                        break;
                    case 1:
                        CalcMethod = "每月";
                        break;
                }

                t.row.add([
                    data[i].IntroducerName,
                    data[i].Cellphone,
                    Gender,
                    data[i].Address,
                    data[i].ReturnThreshold,
                    data[i].ReturnCreditRate,
                    CalcMethod,
                    data[i].Create_at,
                    "<button onclick='OpenUpdateIntroducerModal("+data[i].ID+")' class='btn btn-success mr-2'><i class='fa fa-pencil'></i></button><button onclick='DeleteIntroducer("+data[i].ID+")' class='btn btn-danger'><i class='fa fa-trash'></i></button>"
                ]).draw(false);
            }
        },


 
    });
}

function DeleteIntroducer(id)
{
    swal({
        title: '刪除介紹人',
        text: "你確定要刪除此介紹人嗎？",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '是！',
        cancelButtonText: '取消',
        showLoaderOnConfirm: true,
        preConfirm: function() {
            return new Promise(function(resolve) {

                $.ajax({
                    url: "{{ route('DeleteIntroducer') }}",
                    data: { "id": id },
                    method: "DELETE",
                    statusCode: {
                        200: function() {
                            swal({
                                title: "刪除成功！",
                                type: "success"
                            });
                            GetData(ShowEntries,Page,SeachText);
                        }
                    }
                });

            });
        }
    })
}

function OpenUpdateIntroducerModal(id)
{
    $.ajax({
        url: "{{ route('GetIntroducerById') }}",
        data: { "id": id },
        method: "GET",
        success: function(data)
        {
            $("#IntroducerModalTitle").text('正在編輯： '+data.IntroducerName);
            $("input[name='Name']").val(data.IntroducerName);
            $("input[name='Cellphone']").val(data.Cellphone);
            $("input[name='Address']").val(data.Address);
            $("textarea[name='Memo']").val(data.Memo);
            $("input[name='BonusRate']").val(data.ReturnCreditRate);
            $("input[name='BonusThreshold']").val(data.ReturnThreshold);
            $("select[name='BonusPeriod']").children().eq(data.CalcWeeks).prop('selected',true);
            $("select[name='Gender']").children().eq(data.Gender).prop('selected',true);
            
            $("input[name='id']").val(id);

            $("#IntroducerModal").modal('show');
        }
    });

    AjaxUrl = "{{ route('UpdateIntroducer') }}";
}

function OpenCreateIntroducerModal()
{
    AjaxUrl = "{{ route('CreateIntroducer') }}";
    $("#IntroducerModalTitle").text('新增一名介紹人');
    $("input").val('');
    $("#IntroducerModal").modal('show');
}
</script>

<h1>介紹人</h1>
<hr>
<br>

<div class="row justify-content-between">

    <div class="col-md-2">
        <button class="btn btn-primary" onclick="OpenCreateIntroducerModal()"><i class="fa fa-user-plus"></i> 新增介紹人</button>
    </div>

    <div class="col-md-5 mr-3">
        <div class="row">
            <div class="col-md-6">
                <div class="input-group mb-2">
                    <div class="input-group-addon">顯示筆數</div>
                    <select class="form-control ShowEntries">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="ALL">全部</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group mb-2">
                    <div class="input-group-addon">姓名</div>
                    <input type="text" class="form-control" id="Name" placeholder="要搜尋的姓名 ...">
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <table id="IntroducerTable"  class="table table-striped text-center" cellspacing="0">
            <thead>
                <tr>
                    <th>介紹人姓名</th>
                    <th>手機</th>
                    <th>性別</th>
                    <th>地址</th>
                    <th>退碼底額</th>
                    <th>退碼抽成數</th>
                    <th>結算週期</th>
                    <th>建立時間</th>
                    <th>操作</th>
                </tr>
            </thead>
    </table>
</div>

<div class="row justify-content-between mt-4">
        <div class="col-4">
            <div class="text-left"><a id="previousPage" class="btn btn-light" role="button"><i class="fa fa-arrow-left"></i>  上一頁</a></div>
        </div>
        <div class="col-4">
            <p class="text-center">資料共<font id="NumberOfEntries"></font>筆，總共<font id="totalPage"></font>頁，目前在第<font id="page"></font>頁。</p>
        </div>
        <div class="col-4">
            <div class="text-right"><a id="nextPage" class="btn btn-light" role="button">下一頁 <i class="fa fa-arrow-right"></i> </a></div>
        </div>
</div>

<!-- Introducer Modal -->
<div class="modal" id="IntroducerModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 id="IntroducerModalTitle" class="modal-title d-block mx-auto"></h4>
        </div>
        <div class="modal-body">

            <form id="IntroducerForm">
                {{ csrf_field() }}
                <input type="hidden" name="id" class="form-control" required>

                <div class="row">

                    <div class="col-md-3 form-group">
                        <label class="FormLabel">姓名</label>
                        <input type="text" name="Name" class="form-control" required>
                    </div>


                    <div class="col-md-3 form-group">
                        <label class="FormLabel">性別</label>
                        <select name="Gender" class="form-control" required>
                            <option value="0">男</option>
                            <option value="1">女</option>
                        </select>
                    </div>

                    <div class="col-md-3 form-group">
                        <label class="FormLabel">行動電話</label>
                        <input type="text" name="Cellphone" class="form-control" required>
                    </div>

                    <div class="col-md-3 form-group">
                        <label class="FormLabel">結算週期</label>
                        <select name="BonusPeriod" class="form-control" required>
                            <option value="0">每周</option>
                            <option value="1">每月</option>
                        </select>
                    </div>
                </div>


                <div class="row">

                    <div class="col-md-3 form-group">
                            <label class="FormLabel">退碼底額</label>
                            <input type="text" name="BonusThreshold" class="form-control" required>
                    </div>

                    <div class="col-md-3 form-group">
                        <label class="FormLabel">退碼抽成數</label>
                        <input type="text" name="BonusRate" class="form-control" required>
                    </div>
    
                    <div class="col-md-6 form-group">
                        <label class="FormLabel">住宅地址</label>
                        <input type="text" name="Address" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="FormLabel">備註</label>
                        <textarea type="text" name="Memo" class="form-control"></textarea>
                    </div>
                </div>

        </div>
        <div class="modal-footer">
            <button type="button" id="IntroducerSubmit" class="btn btn-primary btn-lg mx-auto"><i class="fa fa-check"></i> 送出</button>
        </div>
        </form>
    </div>
</div>
</div>


@endsection