var NumberOfEntries = 0;
var Page = 0;
var totalPage = 0;
var ShowEntries = 5;
var SeachText = "%";
var t;
var AjaxUrl;

$(document).ready(function () {

    t = $('#MachineTable').DataTable({
        "paging": false,
        "info": false,
        "searching": false,
        "bAutoWidth": false,
    });

    // Initialize the table
    GetData(ShowEntries, Page, SeachText);

    /*
        對表格進行的操作。
    */

    $("#Name").keyup(function (event) {
        if ((event.keyCode > 64 && event.keyCode < 91) || event.keyCode == 8 || event.keyCode == 46 || (event.keyCode < 58 && event.keyCode > 47)) {
            SeachText = $(this).val();
            GetData(ShowEntries, Page, SeachText);
        }
    });

    $(".ShowEntries").change(function () {
        ShowEntries = $(this).val();
        GetData(ShowEntries, Page, SeachText);
    });

    $("#nextPage").click(function (event) {

        if (Page >= totalPage - 1) {
            swal({
                title: "已到最後一頁！",
                type: 'warning'
            });
        }
        else {
            Page += 1;
            GetData(ShowEntries, Page, SeachText);
        }
    });

    $("#previousPage").click(function () {
        if (Page < 1) {
            swal({
                title: "已到第一頁！",
                type: 'warning'
            });
        }
        else {
            Page -= 1;
            GetData(ShowEntries, Page, SeachText);
        }
    });

    /*
        對資料進行的操作
    */

    var CreateForm = document.getElementById("MachineForm");
    CreateForm.novalidate = false;

    $("#MachineSubmit").click(function () {

        $.ajax({
            url: 'Machine/Create',
            method: "POST",
            data: $("#MachineForm").serialize(),
            success: function (result) {
                $("#MachineSubmit").prop('disabled', false);
                $("#MachineModal").modal('hide');
                swal({
                    title: "操作成功！",
                    text: "列表將自動更新。",
                    type: "success",
                    animation: true
                });
                GetData(ShowEntries, Page, SeachText);
            },
            statusCode: {
                500: function () {
                    swal("操作失敗", "請確認欄位是否填寫正確！", "error");
                }
            }
        });

        if (CreateForm.checkValidity() == false) {
            $("#MachineForm").addClass("was-validated");
        }
        else {
            $("#MachineSubmit").prop('disabled', true);
            console.log($("#MachineForm").serialize());

            $.ajax({
                url: 'Machine/Create',
                method: "POST",
                data: $("#MachineForm").serialize(),
                success: function (result) {
                    $("#MachineSubmit").prop('disabled', false);
                    $("#MachineModal").modal('hide');
                    swal({
                        title: "操作成功！",
                        text: "列表將自動更新。",
                        type: "success",
                        animation: true
                    });
                    GetData(ShowEntries, Page, SeachText);
                },
                statusCode: {
                    500: function () {
                        swal("操作失敗", "請確認欄位是否填寫正確！", "error");
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
function GetData(ShowEntries, Page, SearchText) {
    var SendingData = { "ShowEntries": ShowEntries, "Page": Page, "SearchText": SearchText };

    $.ajax({
        url: "{{route('GetIntroducers')}}",
        method: "GET",
        data: SendingData,
        success: function (data) {
            t.clear().draw();
            NumberOfEntries = data['count'];
            totalPage = Math.ceil(NumberOfEntries / ShowEntries);
            $("#NumberOfEntries").text(NumberOfEntries);
            $("#totalPage").text(totalPage);
            $("#page").text(Page + 1);

            var NumOfData = Object.keys(data).length - 1;

            for (var i = 0; i < NumOfData; i++) {
                var Gender, CalcMethod;

                switch (data[i].Gender) {
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

                switch (data[i].CalcWeeks) {
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
                    "<button onclick='OpenUpdateIntroducerModal(" + data[i].ID + ")' class='btn btn-success mr-2'><i class='fa fa-pencil'></i></button><button onclick='DeleteIntroducer(" + data[i].ID + ")' class='btn btn-danger'><i class='fa fa-trash'></i></button>"
                ]).draw(false);
            }
        },



    });
}

function DeleteIntroducer(id) {
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
        preConfirm: function () {
            return new Promise(function (resolve) {

                $.ajax({
                    url: "{{ route('DeleteIntroducer') }}",
                    data: { "id": id },
                    method: "DELETE",
                    statusCode: {
                        200: function () {
                            swal({
                                title: "刪除成功！",
                                type: "success"
                            });
                            GetData(ShowEntries, Page, SeachText);
                        }
                    }
                });

            });
        }
    })
}

function OpenUpdateIntroducerModal(id) {
    $.ajax({
        url: "{{ route('GetIntroducerById') }}",
        data: { "id": id },
        method: "GET",
        success: function (data) {
            $("#IntroducerModalTitle").text('正在編輯： ' + data.IntroducerName);
            $("input[name='Name']").val(data.IntroducerName);
            $("input[name='Cellphone']").val(data.Cellphone);
            $("input[name='Address']").val(data.Address);
            $("textarea[name='Memo']").val(data.Memo);
            $("input[name='BonusRate']").val(data.ReturnCreditRate);
            $("input[name='BonusThreshold']").val(data.ReturnThreshold);
            $("select[name='BonusPeriod']").children().eq(data.CalcWeeks).prop('selected', true);
            $("select[name='Gender']").children().eq(data.Gender).prop('selected', true);

            $("input[name='id']").val(id);

            $("#IntroducerModal").modal('show');
        }
    });

    AjaxUrl = "{{ route('UpdateIntroducer') }}";
}

function OpenCreateMachineModal() {
    $("#MachineModalTitle").text('新增一台機台');
    $("input").val('');
    $("#MachineModal").modal('show');
}