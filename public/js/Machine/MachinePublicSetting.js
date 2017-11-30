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
            data: $("#MachinePublicSettingForm").serialize(),
            success: function(result) {
                $("#MachinePublicSettingSubmit").prop('disabled', false);
                swal({
                    title: "操作成功！",
                    text: "列表將自動更新。",
                    type: "success",
                    animation: true
                });
                window.reload();
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

    $('.range').on('input change', function() {
        $('#' + $(this).attr('name') + 'RangeText').text($(this).val());
        ChangeFormFlag = 1;
    });
});

function OpenUpdateProbabilityModal(type, s) {
    $.ajax({
        url: 'PublicSetting/GetPublicSetting',
        data: { "type": type },
        method: "GET",
        success: function(data) {
            console.log(data);
            $("#MachinePublicSettingModalTitle").text(s);
            $('input[name="JokerWin"]').val(data);
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