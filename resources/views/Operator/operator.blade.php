@extends('wireframe')

@section('title','員工管理')

@section('content')



<script>
var NumberOfEntries = 0;
var Page = 0;
var totalPage = 0;
var ShowEntries = 5;
var SeachText = "%";
var t;
var AjaxUrl;

var ChangePasswordFlag;
var ChangeFormFlag;

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



    var CreateForm = document.getElementById("OperatorForm");
    var AccountDepulicatedFlag = 0;
    CreateForm.novalidate = false;

    $("#Account").focusout(function(){
        if( $(this).val() != "" && AjaxUrl == "{{ route('CreateOperator') }}" )
        {
            $.ajax({
                url: "{{ route('CheckDepulicatedAccount') }}",
                method: "POST",
                data: { "Account": $(this).val() },
                statusCode: {
                    506: function() {
                            $("#Account").css('border','1px solid brown');
                            $("#DepulicatedAccountText").show();
                            AccountDepulicatedFlag = 1;
                    },
                    200: function() {
                            $("#Account").css('border','1px solid green');
                            $("#DepulicatedAccountText").hide();
                            AccountDepulicatedFlag = 0;
                    }
                }
            });
        }
    });

    $("#OperatorSubmit").click(function(){

        var IntroToggle;
        if($("#IntroCheck").is(":checked"))
            IntroToggle = '&IntroToggle=1';
        else
            IntroToggle = '&IntroToggle=0';

        console.log($("#OperatorForm").serialize()+IntroToggle);

        if( CreateForm.checkValidity() == false || AccountDepulicatedFlag == 1)
        {
            $("#OperatorForm").addClass("was-validated");
        }
        else
        {
            $("#OperatorSubmit").prop('disabled',true);

            $.ajax({
               url: AjaxUrl,
               method: "POST",
               data: $("#OperatorForm").serialize()+IntroToggle+'&ChangePasswordFlag='+ChangePasswordFlag,
               success: function(result) {
                    ChangeFormFlag = 0;
                    $("#OperatorSubmit").prop('disabled',false);
                    $("#OperatorModal").modal('hide');
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
                       $("#OperatorSubmit").prop('disabled',false);
                   }
               }
            });
        }
    });

    $("#IntroCheck").click(function(){

        if($(this).is(":checked"))
        {
            $("input[name*='Intro'").prop('readonly',false);
            $("select[name='BonusPeriod']").prop('disabled',false);
        }
        else
        {
            $("input[name*='Intro'").prop('readonly',true);
            $("select[name='BonusPeriod']").prop('disabled',true);
        }
            

    });

    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        language: 'zh-TW',
        startView: 'decades',
        defaultViewDate: {year: 1900}
    });





    $("input[name='Password']").on('input',function(){
        ChangePasswordFlag = 1
    });

    $("input[name='IDCardNumber']").on('input',function(){
        $("select[name='Gender']").children().eq( parseInt($(this).val().charAt(1))-1 ).prop('selected',true);
    });

    $("input").on('input',function(){
        ChangeFormFlag = 1;
    });

    $("select").change(function(){
        ChangeFormFlag = 1;
    });

    $("input[name='Birthday']").click(function(){
        ChangeFormFlag = 0;
    })

    $('#OperatorModal').on('hide.bs.modal',function(e){
        

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
                $('#OperatorModal').modal('toggle');
            });
        }

        $("#OperatorForm").removeClass("was-validated");
        $("#DepulicatedAccountText").hide();
        
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
        url: "{{route('GetOperators')}}",
        method: "GET",
        data: SendingData,
        success: function(data) {
            t.clear().draw();
            NumberOfEntries = data['count'];
            totalPage = Math.ceil(NumberOfEntries / ShowEntries);

            if( ShowEntries == 'ALL' )
            {
                totalPage = 1;
            }

            Page = 0;
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
                    data[i].Cellphone,
                    "<button onclick='OpenUpdateOperatorModal("+data[i].id+")' class='btn btn-success mr-2'><i class='fa fa-pencil'></i></button><button onclick='DeleteOperator("+data[i].id+")' class='btn btn-danger'><i class='fa fa-trash'></i></button>"
                ]).draw(false);
            }
        },


 
    });
}

function DeleteOperator(id)
{
    swal({
        title: '刪除員工',
        text: "你確定要刪除此員工嗎？",
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

            });
        }
    })
}

function OpenUpdateOperatorModal(id)
{
    $("#CreatePassword").hide();
    $("#ChangePassword").show();

    $("#changePasswordBtn").attr('name',id);

    PasswordChangeFlag = 0;
    ChangeFormFlag = 0;
    $.ajax({
        url: "{{ route('GetOperatorById') }}",
        data: { "id": id },
        method: "GET",
        success: function(data)
        {
            console.log(data);

            $("#OperatorModalTitle").text('正在編輯： '+data.Name);
            
            $("#Account").val(data.Account);
            $("input[name='Name']").val(data.Name);
            $("input[name='Password']").val(data.password);
            $("input[name='IDCardNumber']").val(data.IDCardNumber);
            $("input[name='Cellphone']").val(data.Cellphone);
            $("input[name='Birthday']").val(data.Birthday);
            $("input[name='Address']").val(data.Address);
            $("input[name='Phone']").val(data.Phone);
            $("input[name='Memo']").val(data.Memo);

            if(data.IntroducerID != 0)
            {
                $("#IntroCheck").prop('checked',true);
                $("#IntroCheckDiv").hide();
                $("input[name='IntroBonus']").prop('readonly',false);
                $("input[name='IntroBonusThreshold']").prop('readonly',false);
                $("input[name='IntroBonusRate']").prop('readonly',false);
                $("select[name='BonusPeriod']").prop('disabled',false);

                $("input[name='IntroBonusThreshold']").val(data.ReturnThreshold);
                $("input[name='IntroBonusRate']").val(data.ReturnCreditRate);
                $("input[name='IntroBonus']").val(data.IntroBonus);

                $("select[name='BonusPeriod']").children().eq(data.CalcWeeks).prop('selected',true);
            }
            else
            {
                $("#IntroCheck").prop('checked',false);
                $("#IntroCheckDiv").show();
                $("input[name='IntroBonus']").prop('readonly',true);
                $("input[name='IntroBonusThreshold']").prop('readonly',true);
                $("input[name='IntroBonusRate']").prop('readonly',true);
                $("input[name='IntroBonus']").val('');
                $("input[name='IntroBonusThreshold']").val('');
                $("input[name='IntroBonusRate']").val('');
                $("select[name='BonusPeriod']").prop('disabled',true);
            }

            $("select[name='Type']").children().eq(data.Type).prop('selected',true);
            $("select[name='Session']").children().eq(data.Session).prop('selected',true);
            $("select[name='Gender']").children().eq(data.Gender).prop('selected',true);
            
            $("#OperatorModal").modal('show');
        }
    });

    AjaxUrl = "{{ route('UpdateOperator') }}";
}

function OpenCreateOperatorModal()
{
    ChangeFormFlag = 0;
    AjaxUrl = "{{ route('CreateOperator') }}";
    $("#OperatorModalTitle").text('新增一名員工');
    $("input").val('');
    $("#Account").css('border','1px solid #ddd');
    $("#OperatorModal").modal('show');
    $("#CreatePassword").show();
    $("#ChangePassword").hide();
    $("#IntroCheckDiv").show();
}

function ChangePassword()
{
    
    fixBootstrapModal();

    console.log($("#changePasswordBtn").attr('name'));

    swal({
		title: '變更使用者密碼',
		input: 'text',  
		showCancelButton: true,
		cancelButtonColor: '#d33',
		showLoaderOnConfirm: true,
		cancelButtonText: '取消變更',
        allowOutsideClick:false,
        preConfirm: function(password)
        {
            return new Promise(function(resolve,reject){
                $.ajax({
                    url: "{{ route('ChangePasswordOperator') }}",
                    type: "patch",
                    data: {
                        ID: $("#changePasswordBtn").attr('name'),
                        Password: password},
                    })
                    .done(function(response){
                        if(response.valid == true)
                            resolve();
                        else
                            reject('操作錯誤');
                    })
                    .fail(function() {
					    console.log("CheckPassword Error");
				    });
                })
        } 
    }).then(function(){
        swal({
            title: "密碼變更完成",
            text: "請使用新密碼登入。",
            type: "success"
        })

        restoreBootstrapModal();

    },function(dismiss){
		swal({
			type: 'error',
			title: '取消變更密碼'
        })
        
        restoreBootstrapModal();
	});
}

function fixBootstrapModal() {
  var modalNode = document.querySelector('.modal[tabindex="-1"]');
  if (!modalNode) return;

  modalNode.removeAttribute('tabindex');
  modalNode.classList.add('js-swal-fixed');
}

// call this before hiding SweetAlert (inside done callback):
function restoreBootstrapModal() {
  var modalNode = document.querySelector('.modal.js-swal-fixed');
  if (!modalNode) return;

  modalNode.setAttribute('tabindex', '-1');
  modalNode.classList.remove('js-swal-fixed');
}
</script>

<h1>員工管理</h1>
<hr>
<br>

<div class="row justify-content-between">

    <div class="col-md-2">
        <button class="btn btn-primary" onclick="OpenCreateOperatorModal()"><i class="fa fa-user-plus"></i> 新增員工</button>
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
            <div class="text-left"><a id="previousPage" class="btn btn-light" role="button"><i class="fa fa-arrow-left"></i> 上一頁</a></div>
        </div>
        <div class="col-4">
            <p class="text-center">資料共<font id="NumberOfEntries"></font>筆，總共<font id="totalPage"></font>頁，目前在第<font id="page"></font>頁。</p>
        </div>
        <div class="col-4">
            <div class="text-right"><a id="nextPage" class="btn btn-light" role="button">下一頁 <i class="fa fa-arrow-right"></i> </a></div>
        </div>
</div>

<!-- Operator Modal -->
<div class="modal" id="OperatorModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 id="OperatorModalTitle" class="modal-title d-block mx-auto"></h4>
        </div>
        <div class="modal-body">

            <h5>必填資訊</h5>
            <hr>

            <form id="OperatorForm">
                {{ csrf_field() }}
                <div class="row">
                    <div id="AccountDiv" class="col-md-6 form-group">
                        <label class="FormLabel">帳號</label>
                        <input id="Account" type="text" name="Account" class="form-control" required>
                        <small id="DepulicatedAccountText" style="display:none;color:brown !important" class="form-text text-muted">請取另外一個帳號名稱，此名稱重複了！</small>
                    </div>

                    <div id="CreatePassword" style="display:none" class="col-md-6 form-group">
                        <label class="FormLabel">密碼</label>
                        <input type="password" name="Password" class="form-control" required>
                    </div>

                    <div id="ChangePassword" style="display:none" class="col-md-6 form-group">
                        <label class="FormLabel">密碼</label>
                        <button name="0" id="changePasswordBtn" type="button" class="form-control btn btn-warning" onclick=ChangePassword()>更改密碼</button>
                    </div>     

                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="FormLabel">姓名</label>
                        <input type="text" name="Name" class="form-control" required>

                    </div>

                    <div class="col-md-3 form-group">
                        <label class="FormLabel">帳號類別</label>
                        <select name="Type" class="form-control" required>
                            <option value="0">工作人員</option>
                            <option value="1">管理員</option>
                            <option value="2">最高管理員</option>
                        </select>

                    </div>

                    <div class="col-md-3 form-group">
                            <label class="FormLabel">班別</label>
                            <select name="Session" class="form-control" required>
                                <option value="0">早班</option>
                                <option value="1">午班</option>
                                <option value="2">晚班</option>
                            </select>

                    </div>
                </div>

                <div class="row">
                        <div class="col-md-3 form-group">
                            <label class="FormLabel">身分證字號</label>
                            <input type="text" name="IDCardNumber" class="form-control" style="text-transform:uppercase" required pattern="[A-Z]([1,2])([0-9]){8}">

                        </div>

                        <div class="col-md-3 form-group">
                            <label class="FormLabel">行動電話</label>
                            <input type="text" name="Cellphone" class="form-control" required pattern="^(09)[0-9]{8}">

                        </div>

                        <div class="col-md-3 form-group">
                            <label class="FormLabel">性別</label>
                            <select name="Gender" class="form-control" required>
                                <option value="0">男</option>
                                <option value="1">女</option>
                                <option value="2">其他</option>
                            </select>

                        </div>

                        <div class="col-md-3 form-group">
                            <label class="FormLabel">生日</label>
                            <input type="text" name="Birthday" class="datepicker form-control" required>
                        </div>
                    </div>


                    <h5 class="mt-4">額外資訊</h5>
                    <hr>
                    <div id="IntroCheckDiv" class="form-check form-check-inline">
                        <label id="IntroLebel" class="form-check-label">
                            <input class="form-check-input" id="IntroCheck" type="checkbox"> 成為介紹人
                        </label>
                    </div>
                    <div class="row">
                            <div class="col-md-3 form-group">
                                <label class="FormLabel">員工介紹獎金</label>
                                <input type="text" name="IntroBonus" class="form-control" readonly>
                            </div>

                            <div class="col-md-3 form-group">
                                    <label class="FormLabel">退碼底額</label>
                                    <input type="text" name="IntroBonusThreshold" class="form-control" readonly>
                            </div>

                            <div class="col-md-3 form-group">
                                    <label class="FormLabel">退碼抽成數</label>
                                    <input type="text" name="IntroBonusRate" class="form-control" readonly>
                            </div>
    
                            <div class="col-md-3 form-group">
                                <label class="FormLabel">結算週期</label>
                                <select name="BonusPeriod" class="form-control" disabled>
                                    <option value="0">每周</option>
                                    <option value="1">每月</option>
                                </select>
                            </div>
                    </div>

                    <div class="row">
                            <div class="col-md-3 form-group">
                                <label class="FormLabel">住宅地址</label>
                                <input type="text" name="Address" class="form-control">
                            </div>

                            <div class="col-md-3 form-group">
                                    <label class="FormLabel">住宅電話</label>
                                    <input type="text" name="Phone" class="form-control">
                            </div>
    
                            <div class="col-md-6 form-group">
                                    <label class="FormLabel">備註</label>
                                    <textarea type="text" name="Memo" class="form-control"></textarea>
                            </div>
                    </div>
        </div>
        <div class="modal-footer">
            <button type="button" id="OperatorSubmit" class="btn btn-primary btn-lg mx-auto"><i class="fa fa-check"></i> 送出</button>
        </div>
        </form>
    </div>
</div>
</div>


@endsection