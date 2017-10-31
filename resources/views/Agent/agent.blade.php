@extends('wireframe')

@section('title','經銷商管理')

@section('content')

<style>

i
{
    pointer-events: none;
}

</style>

<script>
var NumberOfEntries = 0;
var Page = 0;
var totalPage = 0;
var ShowEntries = 5;
var SeachText = "%";
var t;
var CreditLogTable;
var AjaxUrl;
var AjaxMethod;

var ChangeFormFlag;

$(document).ready(function() {
    
    t = $('#AgentTable').DataTable({
        "paging":   false,
        "info":     false,
        "searching": false,
        "bAutoWidth": false,
        "columnDefs": [
            {"render": function ( data, type, full, meta ) {if(data) return data.toLocaleString("en-US"); else return data;}, "className": 'text-right', "targets": [4,5]}
        ]
    });

    CreditLogTable = $("#CreditLogTable").DataTable({
        "paging":   false,
        "info":     false,
        "searching": false,
        "bAutoWidth": false,
        "columnDefs": [
            {"render": function ( data, type, full, meta ) {if(data) return data.toLocaleString("en-US"); else return data;}, "className": 'text-right', "targets": [2]}
        ]
    });

    // Initialize the table
    GetData(ShowEntries,Page,SeachText);

    /*
        對表格進行的操作。
    */

    $("#Name").on('input',function(event){
        SeachText = $(this).val();
        GetData(ShowEntries,Page,SeachText);
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

    var CreateForm = document.getElementById("AgentForm");
    CreateForm.novalidate = false;

    $("#AgentSubmit").click(function(){

        if( CreateForm.checkValidity() == false)
        {
            $("#AgentForm").addClass("was-validated");
        }
        else
        {
            $("#AgentSubmit").prop('disabled',true);
            console.log($("#AgentForm").serialize());

            $.ajax({
               url: AjaxUrl,
               method: AjaxMethod,
               data: $("#AgentForm").serialize(),
               success: function(result) {
                    ChangeFormFlag = 0;
                    $("#AgentSubmit").prop('disabled',false);
                    $("#AgentModal").modal('hide');
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

    $("input").on('input',function(){
        ChangeFormFlag = 1;
    });

    $("select").change(function(){
        ChangeFormFlag = 1;
    });

    $('#AgentModal').on('hide.bs.modal',function(e){
        
        if(ChangeFormFlag == 1)
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
                $('#AgentModal').modal('toggle');
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
        url: "{{route('GetAgents')}}",
        method: "GET",
        data: SendingData,
        success: function(data) {
            t.clear().draw();
            NumberOfEntries = data['count'];
            totalPage = Math.ceil(NumberOfEntries / ShowEntries);
            //Page = 0;
            $("#NumberOfEntries").text(NumberOfEntries);
            
            $("#page").text(Page+1);

            if( ShowEntries == 'ALL' )
            {
                totalPage = 1;
            }

            $("#totalPage").text( totalPage );

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

                t.row.add([
                    data[i].Name,
                    data[i].Cellphone,
                    Gender,
                    data[i].DiscountRate+"%",
                    data[i].Credit,
                    data[i].OweCredit,
                    data[i].Create_at,
                    "<button onclick=ManipulateCredit("+data[i].ID+",'increase') class='btn btn-info mr-2'><i class='fa fa-plus'></i></button><button onclick=ManipulateCredit("+data[i].ID+",'decrease') class='btn btn-warning'><i class='fa fa-minus'></i></button><button onclick=ClearOweCredit("+data[i].ID+") class='btn btn-success ml-2'><i class='fa fa-check'></i></button>",
                    "<button onclick='DetailedCredit("+data[i].ID+")' class='btn btn-dark'><i class='fa fa-search'></i></button>",
                    "<button onclick='OpenUpdateAgentModal("+data[i].ID+",event)' class='btn btn-success mr-2'><i class='fa fa-pencil'></i></button><button onclick='DeleteAgent("+data[i].ID+",event)' class='btn btn-danger'><i class='fa fa-trash'></i></button>"
                ]).draw(false);
            }
        },


 
    });
}



function ManipulationHTMLGenerator(Verb,credit,AgentName,DiscountRate)
{
    if(Verb == '增加')
        return Verb+" <font class='text-danger'>"+credit+"點</font> 給 <font class='text-success'>"+AgentName+"</font><br>他的未繳額度會增加 "+credit+" * (100%-"+DiscountRate+"%) = "+Math.floor(credit * (100-DiscountRate) * 0.01)+" 點。";
    else
        return Verb+" <font class='text-danger'>"+credit+"點</font> 給 <font class='text-success'>"+AgentName+"</font>";
}

function ManipulateCredit(id,manipulation)
{
    let AgentName = event.target.parentNode.parentNode.childNodes[0].innerHTML;
    let DiscountRate = event.target.parentNode.parentNode.childNodes[3].innerHTML;
    DiscountRate = DiscountRate.slice(0,DiscountRate.indexOf("%"));

    let Verb;
    if(manipulation == 'increase')
        Verb = '增加'
    else
        Verb = '減少'

    swal({
        title: Verb+'額度',
        text: "請輸入你要"+Verb+"給 "+ AgentName +" 的額度。",
        showCancelButton: true,
        input: 'number',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '計算',
        cancelButtonText: '取消'
    }).then(function(credit) {
        swal({
            title: Verb+"額度確認",
            html: ManipulationHTMLGenerator(Verb,credit,AgentName,DiscountRate),
            showCancelButton: true,
            confirmButtonText: '增加',
            cancelButtonText: '取消',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            showLoaderOnConfirm: true,
            preConfirm: function() {
                return new Promise(function(resolve) {
                    $.ajax({
                        url: "{{ route('ManipulateCredit') }}",
                        data: { "operatorID": "{{ Auth::user()->id }}","id": id, "OweCredit": Math.floor(credit * (100-DiscountRate) * 0.01),"credit": credit, "manipulation":manipulation },
                        method: "PATCH",
                        statusCode: {
                            200: function() {
                                swal({
                                    title: "操作成功！",
                                    type: "success"
                                });
                                GetData(ShowEntries,Page,SeachText);
                            }
                        }
                    });
                });
            }
        })
    });
}



function ClearOweCredit(id)
{
    
    let AgentName = event.target.parentNode.parentNode.childNodes[0].innerHTML;
    swal({
        title: '結清未繳額度',
        html: "你確定要結清 <font class='text-success'>"+AgentName+"</font> 的未繳額度嗎？<br>這會使他的未繳額度<font class='text-danger'>歸零</font>！",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '確定',
        cancelButtonText: '取消',
        showLoaderOnConfirm: true,
            preConfirm: function() {
                return new Promise(function(resolve) {
                    $.ajax({
                        url: "{{ route('ManipulateCredit') }}",
                        data: { "operatorID": "{{ Auth::user()->id }}","id": id,"manipulation":'clear' },
                        method: "PATCH",
                        statusCode: {
                            200: function() {
                                swal({
                                    title: "操作成功！",
                                    type: "success"
                                });
                                GetData(ShowEntries,Page,SeachText);
                            }
                        }
                    });
                });
            }
    });
}

function DeleteAgent(id)
{
    let AgentName = event.target.parentNode.parentNode.childNodes[0].innerHTML;

    swal({
        title: '刪除經銷商',
        text: "你確定要刪除 "+ AgentName +" 嗎？",
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
                    url: "{{ route('DeleteAgent') }}",
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

function OpenUpdateAgentModal(id,event)
{
    var genderIndex = 0;
    var discountIndex = 0;

    for(var i=0;i<event.target.parentNode.parentNode.childNodes[3].innerHTML.length;i++)
    {
        if( event.target.parentNode.parentNode.childNodes[3].innerHTML.charCodeAt(i) > 58 || event.target.parentNode.parentNode.childNodes[3].innerHTML.charCodeAt(i) < 47)
            break;
        
        discountIndex++;
    }

    if( event.target.parentNode.parentNode.childNodes[2].innerHTML == '男' )
        genderIndex = 0;
    else
        genderIndex = 1;

    $("#AgentModalTitle").text('正在編輯： '+ event.target.parentNode.parentNode.childNodes[0].innerHTML);
    $("input[name='Name']").val(event.target.parentNode.parentNode.childNodes[0].innerHTML);
    $("input[name='Cellphone']").val(event.target.parentNode.parentNode.childNodes[1].innerHTML);
    $("input[name='DiscountRate']").val(event.target.parentNode.parentNode.childNodes[3].innerHTML.slice(0,discountIndex));
    $("select[name='Gender']").children().eq(genderIndex).prop('selected',true);
    $("input[name='id']").val(id);

    $("#AgentModal").modal('show');
    AjaxUrl = "{{ route('UpdateAgent') }}";
    AjaxMethod = "PATCH";
}

function OpenCreateAgentModal()
{
    AjaxUrl = "{{ route('CreateAgent') }}";
    AjaxMethod = "POST";
    $("#AgentModalTitle").text('新增一名經銷商');
    $("input").val('');
    $("#AgentModal").modal('show');
}

function DetailedCredit(id)
{
    let AgentName = event.target.parentNode.parentNode.childNodes[0].innerHTML;
    $("#CreditLogModalTitle").text( "查看額度操作紀錄： "+AgentName );
    

    $.ajax({
        url: "{{ route('GetCreditLog') }}",
        data: { "id":id },
        success: function(data) {

            CreditLogTable.clear().draw();
            let NumOfData = Object.keys(data).length;
            let Operation;

            if(NumOfData == 0)
            {
                swal({
                    title: "目前沒有資料！",
                    text: "該經銷商目前沒有任何額度操作紀錄。",
                    type: "error"
                });
            }
            else
            {
                for( let i=0; i<NumOfData; i++ )
                {
                    switch(data[i].Operate)
                    {
                        case 0:
                            Operation = '增加額度';
                            break;
                        
                        case 1:
                            Operation = '減少額度';
                            break;

                        case 2:
                            Operation = '結清額度';
                            break;
                    }

                    CreditLogTable.row.add([
                        data[i].Name,
                        Operation,
                        data[i].Credit,
                        data[i].Create_at
                    ]).draw(false);
                }
                $("#CreditLogModal").modal("show");
            }

            
            
        }
    });
}
</script>

<h1>經銷商</h1>
<hr>
<br>

<div class="row justify-content-between">

    <div class="col-md-2">
        <button class="btn btn-primary" onclick="OpenCreateAgentModal()"><i class="fa fa-user-plus"></i>  新增經銷商</button>
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
    <table id="AgentTable"  class="table table-striped text-center" cellspacing="0">
            <thead>
                <tr>
                    <th>經銷商姓名</th>
                    <th>手機號碼</th>
                    <th>性別</th>
                    <th>折扣率</th>
                    <th>當前額度</th>
                    <th>未繳額度</th>
                    <th>建立時間</th>
                    <th>增/減/結清額度</th>
                    <th>增減額度紀錄</th>
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

<!-- Agent Modal -->
<div class="modal" id="AgentModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 id="AgentModalTitle" class="modal-title d-block mx-auto"></h4>
        </div>
        <div class="modal-body">

            <form id="AgentForm">
                {{ csrf_field() }}
                <input type="hidden" name="id" class="form-control" required>

                <div class="row">

                    <div class="col-md-6 form-group">
                        <label class="FormLabel">姓名</label>
                        <input type="text" name="Name" class="form-control" required>
                    </div>


                    <div class="col-md-6 form-group">
                        <label class="FormLabel">性別</label>
                        <select name="Gender" class="form-control" required>
                            <option value="0">男</option>
                            <option value="1">女</option>
                        </select>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="FormLabel">行動電話</label>
                        <input type="text" name="Cellphone" class="form-control" required pattern="^(09)[0-9]{8}">
                    </div>

                    <div class="col-md-6 form-group">
                        <label class="FormLabel">折扣率</label>
                        <div class="input-group">
                            <input type="number" min="0" max="100" name="DiscountRate" class="form-control" required>
                            <span class="input-group-addon">%</span>
                        </div>

                    </div>
                </div>

        </div>
        <div class="modal-footer">
            <button type="button" id="AgentSubmit" class="btn btn-primary btn-lg mx-auto">送出</button>
        </div>
        </form>
    </div>
</div>
</div>


<!-- Agent Credit Log Modal -->
<div class="modal" id="CreditLogModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="CreditLogModalTitle" class="modal-title d-block mx-auto"></h4>
                </div>
                <div class="modal-body">
                    <table id="CreditLogTable" class="table table-striped text-center" cellspacing="0">
                        <thead>
                            <tr>
                                <th>操作者</th>
                                <th>操作行為</th>
                                <th>修改額度</th>
                                <th>時間</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-danger btn-lg mx-auto">關閉</button>
                </div>
                </form>
            </div>
        </div>
        </div>
@endsection