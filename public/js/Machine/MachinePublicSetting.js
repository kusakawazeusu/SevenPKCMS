var GameResult = {
    RoyalFlushOdd: 0,
    FiveOfAKindOdd: 1,
    STRFlushOdd: 2,
    FourOfAKindOdd: 3,
    FullHouseOdd: 4,
    FlushOdd: 5,
    StrightOdd: 6,
    ThreeOfAKindOdd: 7,
    TwoPairsOdd: 8,
    Nothing: 9
};

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

    $("#MachinePublicSettingJokerWinSubmit").click(function() {
        $.ajax({
            url: AjaxUrl,
            method: "POST",
            data: {
                JokerWin: $('input[name="JokerWin"]:checked').val()
            },
            success: function(result) {
                $("#MachinePublicSettingJokerWinSubmit").prop('disabled', false);
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
        $('#MachinePublicSettingJokerWinModal').modal('toggle');
    });

    $("#MachineProbabilitySubmit").click(function() {
        if (this.value != 0) {
            if ($('input[name="Water"]').val() >= 100) {
                swal({
                    title: '水位值超過100',
                    text: "是否繼續",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '是',
                    cancelButtonText: '否',
                }).then(function() {
                        $.ajax({
                            url: AjaxUrl,
                            method: "POST",
                            data: $("#MachineProbabilityForm").serialize(),
                            success: function(result) {
                                $("#MachineProbabilitySubmit").prop('disabled', false);
                                swal({
                                    title: "操作成功！",
                                    text: "列表將自動更新。",
                                    type: "success",
                                    animation: true
                                });
                            },
                            statusCode: {
                                500: function() {
                                    swal("操作失敗", "請確認欄位是否填寫正確！", "error");
                                }
                            }
                        });
                        ChangeFormFlag = 0;
                        $('#MachinePublicSettingProbilityModal').modal('toggle');
                        $('#MachineProbabilitySubmit').text('關閉');
                    },
                    function(dismiss) {
                        return;
                    });
            } else {
                $.ajax({
                    url: AjaxUrl,
                    method: "POST",
                    data: $("#MachineProbabilityForm").serialize(),
                    success: function(result) {
                        $("#MachineProbabilitySubmit").prop('disabled', false);
                        swal({
                            title: "操作成功！",
                            text: "列表將自動更新。",
                            type: "success",
                            animation: true
                        });
                        ChangeFormFlag = 0;
                        $('#MachinePublicSettingProbilityModal').modal('toggle');
                        $('#MachineProbabilitySubmit').text('關閉');
                    },
                    statusCode: {
                        500: function() {
                            swal("操作失敗", "請確認欄位是否填寫正確！", "error");
                        }
                    }
                });
            }
        } else {
            ChangeFormFlag = 0;
            $('#MachinePublicSettingProbilityModal').modal('toggle');
            $('#MachineProbabilitySubmit').text('關閉');
        }
    });

    $('.modal').on('hide.bs.modal', function(e) {
        var target = '#' + $(this).attr("id");
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
                $(target).modal('toggle');
            });
        }
    });

    $("#Unlock").click(function() {
        $('.needChange').prop("disabled", false);
        $('#MachineProbabilitySubmit').val(1);
        $('#MachineProbabilitySubmit').text('修正');
        swal(
            '已解除鎖定',
            '可開始編輯機率',
            'success'
        );
    });

    $('.isChange').on('input change', function() {
        ChangeFormFlag = 1;
    });

    $('.range').on('input change', function() {
        $('#' + $(this).attr('name') + 'RangeText').text($(this).val());
        ChangeFormFlag = 1;
        CountWater();
    });
    /*
        $('#MachinePublicSettingProbilityModal').on('click', function() {
            if ($('#MachineProbabilitySubmit').val() == 0) {
                swal(
                    '鎖定中',
                    '請先解除鎖定',
                    'error'
                );
            }
        }) */
});

function CountWater() {
    var weight = [];
    weight[GameResult.RoyalFlushOdd] = $("input[name='RoyalFlush']").val();
    weight[GameResult.FiveOfAKindOdd] = $("input[name='FiveOfAKind']").val();
    weight[GameResult.STRFlushOdd] = $("input[name='STRFlush']").val();
    weight[GameResult.FourOfAKindOdd] = $("input[name='FourOfAKind']").val();
    weight[GameResult.FullHouseOdd] = $("input[name='FullHouse']").val();
    weight[GameResult.FlushOdd] = $("input[name='Flush']").val();
    weight[GameResult.StrightOdd] = $("input[name='Straight']").val();
    weight[GameResult.ThreeOfAKindOdd] = $("input[name='ThreeOfAKind']").val();
    weight[GameResult.TwoPairsOdd] = $("input[name='TwoPairs']").val();

    double = 1 + Number($("input[name='DoubleStar']").val()) / 100;

    var water = 0;
    for (var i = 0; i <= 8; ++i) {
        water += Number(weight[i]);
    }
    water *= double;
    $('input[name="Water"]').val(water.toFixed(3));
    return water.toFixed(3);
}

function OpenUpdateJokerWinModal(type, s) {
    $.ajax({
        url: 'PublicSetting/GetPublicSetting',
        data: { "type": type },
        method: "GET",
        success: function(data) {
            $("#MachinePublicSettingModalTitle").text(s);
            $('input[name="JokerWin"][value=' + data + ']').prop("checked", true);
            $("#MachinePublicSettingForm").removeClass("was-validated");
            $("#MachinePublicSetting" + type + "Modal").modal('show');
            AjaxUrl = 'PublicSetting/Edit' + type;
        }
    });
}

function OpenUpdateProbabilityModal(type, s) {
    $.ajax({
        url: 'PublicSetting/GetPublicSetting',
        data: { "type": type },
        method: "GET",
        success: function(data) {
            $("#MachinePublicSettingProbabilityModalTitle").text(s);

            $("input[name='TwoPairs']").val(data.TwoPairs);
            $('#TwoPairsRangeText').text(data.TwoPairs);
            $("input[name='ThreeOfAKind']").val(data.ThreeOfAKind);
            $('#ThreeOfAKindRangeText').text(data.ThreeOfAKind);
            $("input[name='Straight']").val(data.Straight);
            $('#StraightRangeText').text(data.Straight);
            $("input[name='Flush']").val(data.Flush);
            $('#FlushRangeText').text(data.Flush);
            $("input[name='FullHouse']").val(data.FullHouse);
            $('#FullHouseRangeText').text(data.FullHouse);
            $('input[name="FourOfAKind"]').val(data.FourOfAKind);
            $('#FourOfAKindRangeText').text(data.FourOfAKind);
            $('input[name="STRFlush"]').val(data.STRFlush);
            $('#STRFlushRangeText').text(data.STRFlush);
            $('input[name="FiveOfAKind"]').val(data.FiveOfAKind);
            $('#FiveOfAKindRangeText').text(data.FiveOfAKind);
            $('input[name="RoyalFlush"]').val(data.RoyalFlush);
            $('#RoyalFlushRangeText').text(data.RoyalFlush);
            $('input[name="DoubleStar"]').val(data.DoubleStar);
            $('#DoubleStarRangeText').text(data.DoubleStar);
            $('input[name="Water"]').val(data.Water.toFixed(3));
            $('#WaterRangeText').text(data.Water);

            $("#MachineProbabilityForm").removeClass("was-validated");
            $("#MachinePublicSetting" + type + "Modal").modal('show');
            AjaxUrl = 'PublicSetting/Edit' + type;

            DisableRange();
        }
    });
}

function DisableRange() {
    $('.needChange').attr("disabled", "disabled");
    $('#MachineProbabilitySubmit').val(0);
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

function Refersh(type) {
    $.ajax({
        url: 'PublicSetting/GetPublicSetting',
        data: { "type": type },
        method: "GET",
        success: function(data) {
            console.log(data);
            console.log('#' + type + 'text')
            if (data == 1)
                $('#' + type + 'Text').text('是');
            else
                $('#' + type + 'Text').text('否');
        }
    });
}