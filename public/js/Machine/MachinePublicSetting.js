$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') //處理csrf token
        }
    });

    /*
        對資料進行的操作
    */
    var ChangeFormFlag = 0;
    var CreateForm = document.getElementById("MachinePublicSettingForm");
    CreateForm.novalidate = false;

    $("#MachinePublicSettingSubmit").click(function() {
        $.ajax({
            url: AjaxUrl,
            method: "POST",
            data: {
                JokerWin: $('input[name="JokerWin"]:checked').val()
            },
            success: function(result) {
                $("#MachinePublicSettingSubmit").prop('disabled', false);
                swal({
                    title: "操作成功！",
                    text: "列表將自動更新。",
                    type: "success",
                    animation: true
                });
                Refersh('JokerWin');
            },
            statusCode: {
                500: function() {
                    swal("操作失敗", "請確認欄位是否填寫正確！", "error");
                }
            }
        });
        ChangeFormFlag = 0;
        $('#MachinePublicSettingModal').modal('toggle');
    });

    $('#MachinePublicSettingModal').on('hide.bs.modal', function(e) {
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
                $('#MachinePublicSettingModal').modal('toggle');
            });
        }
    });

    $('.isChange').on('input change', function() {
        ChangeFormFlag = 1;
    });
});

function OpenUpdateProbabilityModal(type, s) {
    $.ajax({
        url: 'PublicSetting/GetPublicSetting',
        data: { "type": type },
        method: "GET",
        success: function(data) {
            $("#MachinePublicSettingModalTitle").text(s);
            $('input[name="JokerWin"][value='+data+']').prop("checked", true);      
            $("#MachinePublicSettingForm").removeClass("was-validated");
            $("#MachinePublicSettingModal").modal('show');
            AjaxUrl = 'PublicSetting/Edit';
        }
    });
}

function GetAgent(appenTo) {
    $.ajax({
        url: 'GetAgent',
        method: "GET",
        success: function(data) {
            for (var field in data) {
                $('<option value="' + data[field].ID + '">' + data[field].Name + '</option>').appendTo('#' + appenTo);
            }
        }
    });
}

function Refersh(type){
    $.ajax({
        url: 'PublicSetting/GetPublicSetting',
        data: { "type": type },
        method: "GET",
        success: function(data) {
            console.log(data);
            console.log('#'+type+'text')
            if(data == 1)
            $('#'+type+'Text').text('是');
            else
            $('#'+type+'Text').text('否');
        }
    });
}