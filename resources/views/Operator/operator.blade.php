@extends('wireframe')

@section('content')


<script>

    var NumberOfEntries = 0;
    var Page = 0;
    var totalPage = 0;
    var ShowEntries = 5;
    var SeachText = "%";
    var t;

$(document).ready(function() {
    
    t = $('#OperatorTable').DataTable({
                "paging":   false,
                "info":     false,
                "searching": false,
                "bAutoWidth": false,
    });

    // Initialize the table
    GetData(ShowEntries,Page,SeachText);

    /*
        This function is used for getting data from API.

        @params
            - ShowEntries : Indicated the number of records we query from the API.
            - Page : We needs this to calculate the data offset.
            - SearchText : The keyword we're searching for.

    */



    /*
        對表格進行的操作。
    */

    $("#Name").keyup(function(event){
        if( event.keyCode > 64 && event.keyCode < 91 )
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

    var CreateForm = document.getElementById("CreateOperatorForm");
    var AccountDepulicatedFlag = 0;
    CreateForm.novalidate = false;

    $("#CreateAccount").focusout(function(){
        $.ajax({
            url: "{{ route('CheckDepulicatedAccount') }}",
            method: "POST",
            data: { "Account": $(this).val() },
            statusCode: {
                   506: function() {
                        $("#CreateAccount").css('border','1px solid brown');
                        $("#DepulicatedAccountText").show();
                        AccountDepulicatedFlag = 1;
                   },
                   200: function() {
                        $("#CreateAccount").css('border','1px solid green');
                        $("#DepulicatedAccountText").hide();
                        AccountDepulicatedFlag = 0;
                   }
               }
        });
    });

    $("#CreateOperatorSubmit").click(function(){

        if( CreateForm.checkValidity() == false || AccountDepulicatedFlag == 1)
        {
            $("#CreateOperatorForm").addClass("was-validated");
        }
        else
        {
            $.ajax({
               url: "{{ route('CreateOperator') }}",
               method: "POST",
               data: {
                   "CreateAccount": CreateForm.elements['CreateAccount'].value,
                   "CreatePassword": CreateForm.elements['CreatePassword'].value,
                   "CreateName": CreateForm.elements['CreateName'].value,
                   "CreateType": CreateForm.elements['CreateType'].value,
                   "CreateSession": CreateForm.elements['CreateSession'].value,
                   "CreateIDCardNumber": CreateForm.elements['CreateIDCardNumber'].value,
                   "CreateGender": CreateForm.elements['CreateGender'].value,
                   "CreateBirthday": CreateForm.elements['CreateBirthday'].value,
                   "CreateAddress": CreateForm.elements['CreateAddress'].value,
                   "CreatePhone": CreateForm.elements['CreatePhone'].value,
                   "CreateCellphone": CreateForm.elements['CreateCellphone'].value
               },
               success: function(result) {
                    swal("新增員工成功","列表將自動更新。","success");
                    GetData(ShowEntries,Page,SeachText);
                    $("#CreateOperator").modal('toggle');
               },
               statusCode: {
                   500: function() {
                       swal("新增員工失敗","新增員工失敗，請確認欄位是否填寫正確！","error");
                   }
               }
            });
        }
    });



});
function GetData(ShowEntries, Page, SearchText)
{
    t.clear().draw();
    var SendingData = { "ShowEntries":ShowEntries, "Page":Page, "SearchText":SearchText };

    $.get("{{route('GetOperators')}}",SendingData,function(data){

            NumberOfEntries = data['count'];
            totalPage = Math.ceil(NumberOfEntries / ShowEntries);
            $("#NumberOfEntries").text(NumberOfEntries);
            $("#totalPage").text( totalPage );
            $("#page").text(Page+1);

            var NumOfData = Object.keys(data).length - 1;

            for( var i=0; i<NumOfData; i++ )
            {
                var AccountTypeName;

                switch (data[i].Type)
                {
                    case 0:
                        AccountTypeName = "工作人員";
                        break;
                    case 1:
                        AccountTypeName = "管理人員";
                        break;
                    case 2:
                        AccountTypeName = "最高管理員";
                        break;
                }

                t.row.add([
                    data[i].Name,
                    data[i].Account,
                    AccountTypeName,
                    data[i].IDCardNumber,
                    data[i].Phone,
                    "<button id="+data[i].id+" class='btn btn-success mr-2'><i class='fa fa-pencil'></i></button><button onclick='DeleteOperator("+data[i].id+")' class='btn btn-danger'><i class='fa fa-trash'></i></button>"
                ]).draw(false);
            }
    });
}

function DeleteOperator(id)
{
    $.ajax({
        url: "{{ route('DeleteOperator') }}",
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
}


</script>

<h1>員工管理</h1>
<hr>
<br>

<div class="row justify-content-between">

    <div class="col-md-2">
        <button class="btn btn-primary" data-toggle="modal" data-target="#CreateOperator">新增員工</button>
    </div>

    <div class="col-md-4 mr-3">
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

<div class="col-md-12">
    <table id="OperatorTable"  class="table table-striped text-center" cellspacing="0">
            <thead>
                <tr>
                    <th>員工姓名</th>
                    <th>帳號名稱</th>
                    <th>帳號類別</th>
                    <th>身分證字號</th>
                    <th>行動電話</th>
                    <th>操作</th>
                </tr>
            </thead>
    </table>
</div>

<div class="row justify-content-between mt-4">
        <div class="col-4">
            <div class="text-left"><a id="previousPage" class="btn btn-light" role="button">返回上一頁</a></div>
        </div>
        <div class="col-4">
            <p class="text-center">資料共<font id="NumberOfEntries"></font>筆，總共<font id="totalPage"></font>頁，目前在第<font id="page"></font>頁。</p>
        </div>
        <div class="col-4">
            <div class="text-right"><a id="nextPage" class="btn btn-light" role="button">前往下一頁</a></div>
        </div>
</div>

<!-- Modal -->
<div class="modal fade" id="CreateOperator" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title d-block mx-auto">新增一名員工</h4>
            </div>
            <div class="modal-body">

                <h5>必填資訊</h5>
                <hr>

                <form id="CreateOperatorForm">
                    {{ csrf_field() }}
                    <div class="row">
                        <div id="CreateAccountDiv" class="col-md-6 form-group">
                            <label class="FormLabel">帳號</label>
                            <input id="CreateAccount" type="text" name="CreateAccount" class="form-control" required>
                            <small id="DepulicatedAccountText" style="display:none;color:brown !important" class="form-text text-muted">請取另外一個帳號名稱，此名稱重複了！</small>
                        </div>

                        <div class="col-md-6 form-group">
                            <label class="FormLabel">密碼</label>
                            <input type="password" name="CreatePassword" class="form-control" required>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="FormLabel">姓名</label>
                            <input type="text" name="CreateName" class="form-control" required>

                        </div>

                        <div class="col-md-3 form-group">
                            <label class="FormLabel">帳號類別</label>
                            <select name="CreateType" class="form-control" required>
                                <option value="0">工作人員</option>
                                <option value="1">管理員</option>
                                <option value="2">最高管理員</option>
                            </select>

                        </div>

                        <div class="col-md-3 form-group">
                                <label class="FormLabel">班別</label>
                                <select name="CreateSession" class="form-control" required>
                                    <option value="0">早班</option>
                                    <option value="1">午班</option>
                                    <option value="2">晚班</option>
                                </select>

                        </div>
                    </div>

                    <div class="row">
                            <div class="col-md-3 form-group">
                                <label class="FormLabel">身分證字號</label>
                                <input type="text" name="CreateIDCardNumber" class="form-control" required>

                            </div>

                            <div class="col-md-3 form-group">
                                <label class="FormLabel">行動電話</label>
                                <input type="text" name="CreateCellphone" class="form-control" required>

                            </div>

                            <div class="col-md-3 form-group">
                                <label class="FormLabel">性別</label>
                                <select name="CreateGender" class="form-control" required>
                                    <option value="0">男</option>
                                    <option value="1">女</option>
                                </select>

                            </div>
    
                            <div class="col-md-3 form-group">
                                <label class="FormLabel">生日</label>
                                <input type="text" name="CreateBirthday" class="form-control" required>

                            </div>
                        </div>



                        <h5 class="mt-4">額外資訊</h5>
                        <hr>
                        <div class="row">
                                <div class="col-md-3 form-group">
                                    <label class="FormLabel">員工介紹獎金</label>
                                    <input type="text" name="CreateIntroBonus" class="form-control">
                                </div>

                                <div class="col-md-3 form-group">
                                        <label class="FormLabel">退碼底額</label>
                                        <input type="text" name="CreateBonusThreshold" class="form-control">
                                </div>

                                <div class="col-md-3 form-group">
                                        <label class="FormLabel">退碼抽成數</label>
                                        <input type="text" name="CreateBonusRate" class="form-control">
                                </div>
        
                                <div class="col-md-3 form-group">
                                        <label class="FormLabel">結算週期</label>
                                        <input type="text" name="CreateBonusPeriod" class="form-control">
                                </div>
                        </div>

                        <div class="row">
                                <div class="col-md-3 form-group">
                                    <label class="FormLabel">住宅地址</label>
                                    <input type="text" name="CreateAddress" class="form-control">
                                </div>

                                <div class="col-md-3 form-group">
                                        <label class="FormLabel">住宅電話</label>
                                        <input type="text" name="CreatePhone" class="form-control">
                                </div>
        
                                <div class="col-md-6 form-group">
                                        <label class="FormLabel">備註</label>
                                        <textarea type="text" name="CreateMemo" class="form-control"></textarea>
                                </div>
                        </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="CreateOperatorSubmit" class="btn btn-primary btn-lg mx-auto">送出</button>
            </div>
            </form>
        </div>
    </div>
</div>

@endsection