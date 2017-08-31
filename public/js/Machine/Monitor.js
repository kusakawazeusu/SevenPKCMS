$(function() {
    $('[data-toggle="dropdown"]').dropdown();
    $('.machineCardTooltip').tooltip({
        html: true,
        title: '使用者：無<br>餘額：0',
        placement: 'right'
    });
})

function CreditIn(id) {
    $('.CreditInInputplayerCellphone #playerCellphone').val('');
    $('.CreditInInputCreditIn #CreaditIn').val('');
    $('.CreditInInputplayerCellphone #playerCellphone').prop('disabled', false);
    $('#CreditInAccept').val(id);
    var playerCellphone = 0;
    $.ajax({
        url: 'Machine/Monitor/GetCur',
        type: 'post',
        async: false,
        data: {
            id: id
        },
        success: function(data) {
            playerCellphone = data.Cellphone;
        }
    })
    if (playerCellphone != null) {
        $('.CreditInInputplayerCellphone #playerCellphone').val(playerCellphone);
        $('.CreditInInputplayerCellphone #playerCellphone').prop('disabled', true);
    }
    $('#CreditInModal').modal('show');
}

function CreditOut(id) {
    console.log(id);
    $.ajax({
            url: 'Machine/Monitor/GetCur',
            type: 'POST',
            data: {
                id: id
            },
        })
        .done(function(data) {
            console.log(data);
            if (data.Status != 0) {
                swal({
                    title: '確定要鍵出嗎?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '是, 鍵出!',
                    cancelButtonText: '取消',
                    showLoaderOnConfirm: true
                }).then(function() {
                    swal({
                        title: '請選擇交易方式',
                        text: '要取消交易直接點選空白處',
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '存入帳戶',
                        cancelButtonText: '兌現',
                        showLoaderOnConfirm: true,
                        allowEscapeKey: false,
                        allowEnterKey: false
                    }).then(function() {
                            return new Promise(function(resolve) {
                                $.ajax({
                                        url: 'Machine/Monitor/CreditOut',
                                        type: 'POST',
                                        data: {
                                            ID: id,
                                            type: 'ToCredit'
                                        },
                                    })
                                    .done(function(data) {
                                        if (data.done == 'success') {
                                            swal('鍵出成功!', '會員餘額' + data.credit, 'success');
                                            $('#machine' + data.machineID).attr("src", src = "img/machine/offline.png");
                                            $('#machineStatus' + data.machineID).text("離線中");
                                        } else
                                            swal('I have no idea', '', 'error');
                                    })
                                    .fail(function() {
                                        console.log("error");
                                    });
                            });
                        },
                        function(dismiss) {
                            if (dismiss === 'cancel') {
                                $.ajax({
                                        url: 'Machine/Monitor/CreditOut',
                                        type: 'POST',
                                        data: {
                                            ID: id,
                                            type: 'ToCash'
                                        },
                                    })
                                    .done(function(data) {
                                        console.log(data);
                                        if (data.done == 'success') {
                                            swal('兌現', '應付金額' + data.credit, 'info');
                                            $('#machine' + data.machineID).attr("src", src = "img/machine/offline.png");
                                            $('#machineStatus' + data.machineID).text("離線中");
                                        } else
                                            swal('I have no idea', '', 'error');
                                    })
                                    .fail(function() {
                                        console.log("error");
                                    });
                            } else {
                                CreditOut(id);
                            }
                        })
                }, function(dismiss) {
                    swal('取消!', '', 'error');
                });
            } else {
                console.log("a");
                return false;
            }
        });
}

function GameReserved(id) {
    console.log(GameReserved);
}

$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') //處理csrf token
        }
    });

    $('.machineCard').on('show.bs.tooltip', function() {
        $.ajax({
            url: 'Machine/Monitor/GetCur',
            type: 'post',
            async: false,
            data: {
                id: this.id
            },
            success: function(data) {
                if (data.Name != null) {
                    $('.machineCardTooltip').attr('data-original-title', '使用者：' + data.Name.toString() + '<br>餘額：' + data.CurCredit.toString());
                } else {
                    $('.machineCardTooltip').attr('data-original-title', '使用者：無<br>餘額：0');
                }
            },
            error: function(data) {
                console.log(data);
            },
        });
    });

    $('#CreditInAccept').click(function() {
        $.ajax({
            url: 'Machine/Monitor/CreditIn',
            type: 'post',
            data: {
                playerCellphone: $('.CreditInInputplayerCellphone #playerCellphone').val(),
                credit: $('.CreditInInputCreditIn #CreaditIn').val(),
                machineID: $('#CreditInAccept').val()
            },
            success: function(data) {
                if (data.done == 'success') {
                    swal("鍵入成功", "", "success");
                } else {
                    swal("鍵入失敗", data.errorMsg, "error");
                }
                $('#machine' + data.machineID).attr("src", src = "img/machine/online.png");
                $('#machineStatus' + data.machineID).text("連線中");
            },
            error: function(data) {
                console.log(data);
            },
        });
    });
})