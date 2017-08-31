var NumberOfEntries = 0;
var Page = 0;
var totalPage = 0;
var ShowEntries = 5;
var SeachText = "%";
var t;
var AjaxUrl;

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

    $("#Name").keyup(function(event){
        if( event.keyCode > 64 && event.keyCode < 91 || event.keyCode == 8 || event.keyCode == 46 )
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


    var CreateForm = document.getElementById("OperatorForm");
    var AccountDepulicatedFlag = 0;
    CreateForm.novalidate = false;

    $("#Account").focusout(function(){
        if( $(this).val() != "" )
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

        if( CreateForm.checkValidity() == false || AccountDepulicatedFlag == 1)
        {
            $("#OperatorForm").addClass("was-validated");
        }
        else
        {
            $.ajax({
               url: AjaxUrl,
               method: "POST",
               data: $("#OperatorForm").serialize(),
               success: function(result) {
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
                   }
               }
            });
        }
    });

    $("input[name='IntroToggle']").click(function(){

        if($(this).is(":checked"))
            $("input[name*='Intro'").prop('readonly',false);
        else
            $("input[name*='Intro'").prop('readonly',true);

    });

    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        language: 'zh-TW',
        startView: 'decades',
        defaultViewDate: {year: 1900}
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
        cancelButtonText: '取消'
    }).then(function () {
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
    })
}

function OpenUpdateOperatorModal(id)
{
    $.ajax({
        url: "{{ route('GetOperatorById') }}",
        data: { "id": id },
        method: "GET",
        success: function(data)
        {
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
    AjaxUrl = "{{ route('CreateOperator') }}";
    $("#OperatorModalTitle").text('新增一名員工');
    $("input").val('');
    $("#Account").css('border','1px solid #ddd');
    $("#OperatorModal").modal('show');
}