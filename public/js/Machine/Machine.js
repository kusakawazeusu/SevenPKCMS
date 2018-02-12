var NumberOfEntries = 0;
var Page = 0;
var totalPage = 0;
var ShowEntries = 5;
var SeachText = "%";
var MachineName = "%";
var t;
var AjaxUrl;
var ChangeFormFlag;

$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') //處理csrf token
        }
    });

    t = $('#MachineTable').DataTable({
        "paging": false,
        "info": false,
        "searching": false,
        "bAutoWidth": false,
        "columns": [
            null,
            null,
            null,
            null,
            null,
            { className: "text-right" },
            { className: "text-right" },
            { className: "text-right" },
            { className: "text-right" },
            { className: "text-right" },
            { className: "text-right" },
            { className: "text-right" },
            { className: "text-right" },
            { className: "text-right" },
            { className: "text-right" },
            { className: "text-right" },
            { className: "text-right" },
            { className: "text-right" },
            { className: "text-right" },
            { className: "text-right" },
            { className: "text-right" }
        ]
    });

    // Initialize the table
    GetData(ShowEntries, Page, SeachText, MachineName);

    /*
        對表格進行的操作。
    */
    $("#AgentID").change(function(event) {
        SeachText = $(this).val() != -1 ? $(this).val() : "%";
        GetData(ShowEntries, Page, SeachText, MachineName);
    });

    $("#MachineName").change(function(event) {
        MachineName = $(this).val() != -1 ? $(this).val() : "%";
        GetData(ShowEntries, Page, SeachText, MachineName);
    });

    GetAgent('AgentID');

    $(".ShowEntries").change(function() {
        ShowEntries = $(this).val();
        if (ShowEntries == 'ALL') {
            ShowEntries = NumberOfEntries;
            totalPage = 1;
            Page = 0;
        } else {
            Page = 0;
        }
        GetData(ShowEntries, Page, SeachText, MachineName);
    });

    $("#nextPage").click(function(event) {

        if (Page >= totalPage - 1) {
            swal({
                title: "已到最後一頁！",
                type: 'warning'
            });
        } else {
            Page += 1;
            GetData(ShowEntries, Page, SeachText, MachineName);
        }
    });

    $("#previousPage").click(function() {
        if (Page < 1) {
            swal({
                title: "已到第一頁！",
                type: 'warning'
            });
        } else {
            Page -= 1;
            GetData(ShowEntries, Page, SeachText, MachineName);
        }
    });

    /*
        對資料進行的操作
    */

    var CreateForm = document.getElementById("MachineForm");
    CreateForm.novalidate = false;

    $("#MachineSubmit").click(function() {
        var $inputs = $('#MachineForm :input');
        var valid = true;
        $inputs.each(function() {
            $(this).focusout();
            if ($(this).hasClass('error')) {
                valid = false;
            }
        });
        if (valid == false) {
            CheckValid();
        } else {
            $.ajax({
                url: AjaxUrl,
                method: "POST",
                data: $("#MachineForm").serialize(),
                success: function(result) {
                    $("#MachineSubmit").prop('disabled', false);
                    $("#MachineModal").modal('hide');
                    swal({
                        title: "操作成功！",
                        text: "列表將自動更新。",
                        type: "success",
                        animation: true
                    });
                    GetData(ShowEntries, Page, SeachText, MachineName);
                },
                statusCode: {
                    500: function() {
                        swal("操作失敗", "請確認欄位是否填寫正確！", "error");
                    }
                }
            });
            ChangeFormFlag = 0;
            $('#MachineModal').modal('toggle');
        }
    });

    $("input").on('input', function() {
        ChangeFormFlag = 1;
    });

    $("select").change(function() {
        ChangeFormFlag = 1;
    });

    $('#MachineModal').on('hide.bs.modal', function(e) {
        if (ChangeFormFlag == 1) {
            e.preventDefault();
            swal({
                title: '哈囉！',
                text: '我們發現有些資料已經被編輯過了，你確定要離開這個視窗嗎？',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: '放棄編輯',
                cancelButtonText: '留在此視窗'
            }).then(function() {
                ChangeFormFlag = 0;
                $('#MachineModal').modal('toggle');
            });
        }
    });

    function CheckValid() {
        var $inputs = $('#MachineForm :input');
        var valid = true;
        $inputs.each(function() {
            if ($(this).hasClass('error')) {
                valid = false;
            }
        });
        if (valid)
            $('#MachineSubmit').attr('disabled', false);
        else
            $('#MachineSubmit').attr('disabled', true);
    }

    $('select[name="AgentID"]').focusout(function() {
        CheckAgent($(this).val());
        CheckValid();
    });

    /* 左邊補0 */
    function padLeft(str, len) {
        str = '' + str;
        if (str.length >= len) {
            return str;
        } else {
            return padLeft("0" + str, len);
        }
    }

    $('input[name="MachineName"]').focusout(function() {
        var checkMachineNameResponse = CheckNumeric($(this).val(), 1, 1000);
        if (checkMachineNameResponse.valid) $(this).val(padLeft($(this).val(), 3));
        if (CheckAgent($('select[name="AgentID"]').val()).valid == false) {
            checkMachineNameResponse = { valid: false, errMsg: '請先輸入經銷商編號' };
        }
        if (checkMachineNameResponse.valid) {
            var type = AjaxUrl;
            $.ajax({
                    url: "Machine/CheckDepulicatedMachineName",
                    method: "POST",
                    async: false,
                    data: {
                        "AgentID": $('select[name="AgentID"]').val(),
                        "MachineName": $(this).val(),
                        "Type": type,
                        "ID": $("input[name='id']").val()
                    },
                })
                .done(function(response) {
                    checkMachineNameResponse = response;
                })
                .fail(function() {
                    console.log("error");
                });
        }
        CheckStyle(checkMachineNameResponse, 'MachineName');
        CheckValid();
    });

    $('input[name="MinCoinOut"]').focusout(function() {
        var checkMinCoinOutResponse = CheckNumeric($(this).val(), 1);
        if (checkMinCoinOutResponse.valid)
            if ($(this).val() % 100 !== 0)
                checkMinCoinOutResponse = { valid: false, errMsg: '請以100為一個單位' };
        CheckStyle(checkMinCoinOutResponse, 'MinCoinOut');
        CheckValid();
    });

    $('.numberic').focusout(function() {
        var checkResponse = CheckNumeric($(this).val(), 1);
        CheckStyle(checkResponse, $(this).attr('name'));
        CheckValid();
    });

    function CheckNumeric(data, min = 0, max = 0) {
        if (data == '')
            return { valid: false, errMsg: '不可為空!' }
        var regexNumber = /\D/;
        if (regexNumber.test(data))
            return { valid: false, errMsg: '請輸入數字!' };
        if (max != 0 && data > max)
            return { valid: false, errMsg: '請輸入小於' + (max - 1) + '的值!' };
        if (min != 0 && data < min)
            return { valid: false, errMsg: '請輸入大於' + (min - 1) + '的值!' };
        return { valid: true, errMsg: '' };
    }

    function CheckAgent(data) {
        var checkAgentIDResponse = CheckNumeric(data);
        if (checkAgentIDResponse.valid) {
            $.ajax({
                    url: "Machine/CheckExistAgentID",
                    method: "POST",
                    async: false,
                    data: { "AgentID": data },
                })
                .done(function(response) {
                    checkAgentIDResponse = response;
                })
                .fail(function() {
                    console.log("error");
                });
        }
        CheckStyle(checkAgentIDResponse, 'AgentID');
        return checkAgentIDResponse;
    }

    function CheckStyle(response, filed) {
        if (response.valid == false) {
            $('input[name="' + filed + '"]').removeClass('correct');
            $('input[name="' + filed + '"]').addClass('error');
            $('#ErrorMsg' + filed).text(response.errMsg);
            $('#ErrorMsg' + filed).show();
        } else { //true
            $('input[name="' + filed + '"]').removeClass('error');
            $('input[name="' + filed + '"]').addClass('correct');
            $('#ErrorMsg' + filed).hide();
        }
    }

});

/*
    This function is used for getting data from API.
 
    @params
        - ShowEntries : Indicated the number of records we query from the API.
        - Page : We needs this to calculate the data offset.
        - SearchText : The keyword we're searching for.
 
*/
function GetData(ShowEntries, Page, SearchText, MachineName) {
    var SendingData = { "ShowEntries": ShowEntries, "Page": Page, "SearchText": SearchText, "MachineName": MachineName };

    swal({
        html: '<strong id="progressText">loading...</strong>',
        imageUrl: 'img/waiting.gif',
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
    })
    $.ajax({
        url: 'Machine/GetTableData',
        method: "GET",
        data: SendingData,
        success: function(data) {
            swal.close();
            t.clear().draw();
            NumberOfEntries = data['count'];
            totalPage = Math.ceil(NumberOfEntries / ShowEntries);
            $("#NumberOfEntries").text(NumberOfEntries);
            $("#totalPage").text(totalPage);
            $("#page").text(Page + 1);

            var NumOfData = Object.keys(data).length - 1;

            for (var i = 0; i < NumOfData; i++) {

                var oneBet, section;
                switch (data[i].SectionID) {
                    case 0:
                        oneBet = 20;
                        section = 2;
                        break;
                    case 1:
                        oneBet = 30;
                        section = 3;
                        break;
                    case 2:
                        oneBet = 50;
                        section = 5;
                        break;
                    case 3:
                        oneBet = 100;
                        section = 10;
                        break;
                    default:
                        break;
                }

                t.row.add([
                    "<button onclick='OpenUpdateMachineModal(" + data[i].ID + ")' class='btn btn-success mr-2'><i class='fa fa-pencil'></i></button><button onclick='DeleteMachine(" + data[i].ID + ")' class='btn btn-danger'><i class='fa fa-trash'></i></button>",
                    data[i].ID,
                    data[i].Name,
                    data[i].MachineName,
                    section,
                    oneBet.toLocaleString("en-US"),
                    data[i].MaxDepositCredit.toLocaleString("en-US"),
                    data[i].DepositCreditOnce.toLocaleString("en-US"),
                    data[i].MinCoinOut.toLocaleString("en-US"),
                    data[i].MaxCoinIn.toLocaleString("en-US"),
                    data[i].CoinInOnce.toLocaleString("en-US"),
                    data[i].CoinInBonus.toLocaleString("en-US"),
                    data[i].TwoPairsOdd.toLocaleString("en-US"),
                    data[i].ThreeOfAKindOdd.toLocaleString("en-US"),
                    data[i].StraightOdd.toLocaleString("en-US"),
                    data[i].FlushOdd.toLocaleString("en-US"),
                    data[i].FullHouseOdd.toLocaleString("en-US"),
                    data[i].FourOfAKindOdd.toLocaleString("en-US"),
                    data[i].STRFlushOdd.toLocaleString("en-US"),
                    data[i].FiveOfAKindOdd.toLocaleString("en-US"),
                    data[i].RoyalFlushOdd.toLocaleString("en-US")
                ]).draw(false);
            }
        },
    });
}

function DeleteMachine(id) {
    swal({
        title: '刪除機台',
        text: "你確定要刪除此機台嗎？",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '是！',
        cancelButtonText: '取消',
        showLoaderOnConfirm: true,
        allowEscapeKey: false,
        allowOutsideClick: false,
        allowEnterKey: false
    }).then(function() {
        $.ajax({
            url: 'Machine/Delete',
            data: { "id": id },
            method: "post",
            statusCode: {
                200: function() {
                    swal({
                        title: "刪除成功！",
                        type: "success"
                    });
                    GetData(ShowEntries, Page, SeachText, MachineName);
                }
            }
        });
    }, function(dismiss) {
        swal('取消!', '', 'error');
    });
}

function OpenUpdateMachineModal(id) {
    GetAgent('AgentIDSelect');
    ChangeFormFlag = 0;
    $.ajax({
        url: 'Machine/GetMachineByID',
        data: { "id": id },
        method: "GET",
        success: function(data) {
            $("#MachineModalTitle").text('正在編輯： 第' + data.ID + '台');
            $("input[name='id']").val(data.ID);
            $("select[name='AgentID']").val(data.AgentID);
            $("input[name='MachineName']").val(data.MachineName);
            $("select[name='SectionID']").val(data.SectionID);
            $("input[name='MaxDepositCredit']").val(data.MaxDepositCredit);
            $("input[name='DepositCreditOnce']").val(data.DepositCreditOnce);
            $("input[name='MinCoinOut']").val(data.MinCoinOut);
            $("input[name='MaxCoinIn']").val(data.MaxCoinIn);
            $("input[name='CoinInOnce']").val(data.CoinInOnce);
            $('input[name="CoinInBonus"]').val(data.CoinInBonus);
            $('input[name="TwoPairsOdd"]').val(data.TwoPairsOdd);
            $('input[name="ThreeOfAKindOdd"]').val(data.ThreeOfAKindOdd);
            $('input[name="StraightOdd"]').val(data.StraightOdd);
            $('input[name="FlushOdd"]').val(data.FlushOdd);
            $('input[name="FullHouseOdd"]').val(data.FullHouseOdd);
            $('input[name="FourOfAKindOdd"]').val(data.FourOfAKindOdd);
            $('input[name="STRFlushOdd"]').val(data.STRFlushOdd);
            $('input[name="FiveOfAKindOdd"]').val(data.FiveOfAKindOdd);
            $('input[name="RoyalFlushOdd"]').val(data.RoyalFlushOdd);
            $("#MachineForm").removeClass("was-validated");
            $('.errmsg').hide();
            $('.check').removeClass('error');
            $('.check').removeClass('correct');
            $('#MachineSubmit').attr('disabled', false);
            AjaxUrl = 'Machine/Edit';
            $("#MachineModal").modal('show');
        }
    });
}

function OpenCreateMachineModal() {
    GetAgent('AgentIDSelect');
    ChangeFormFlag = 0;
    $("#MachineModalTitle").text('新增一台機台');
    $("input").val('');
    $('input[name="TwoPairsOdd"]').val('1');
    $('input[name="ThreeOfAKindOdd"]').val('2');
    $('input[name="StraightOdd"]').val('3');
    $('input[name="FlushOdd"]').val('5');
    $('input[name="FullHouseOdd"]').val('7');
    $('input[name="FourOfAKindOdd"]').val('50');
    $('input[name="STRFlushOdd"]').val('120');
    $('input[name="FiveOfAKindOdd"]').val('200');
    $('input[name="RoyalFlushOdd"]').val('500');
    $("#MachineForm").removeClass("was-validated");
    $('.errmsg').hide();
    $('.check').removeClass('error');
    $('.check').removeClass('correct');
    $('#MachineSubmit').attr('disabled', false);
    AjaxUrl = 'Machine/Create';
    $("#MachineModal").modal('show');
}

function GetAgent(appenTo) {
    $.ajax({
        url: 'Machine/GetAgent',
        method: "GET",
        success: function(data) {
            for (var field in data) {
                $('<option value="' + data[field].ID + '">' + data[field].Name + '</option>').appendTo('#' + appenTo);
            }
        }
    });
}