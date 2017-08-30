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
    console.log(id);
    var playerCellphone = 0;
    $.ajax({
        url: 'Machine/Monitor/GetCur',
        type: 'post',
        async: false,
        data: {
            id: id
        },
        success: function(data) {
            console.log(data);
            playerCellphone = data.Cellphone;
        }
    })
    console.log(playerCellphone);
    if (playerCellphone != null) {
        $('.CreditInInputplayerCellphone #playerCellphone').val(playerCellphone);
        $('.CreditInInputplayerCellphone #playerCellphone').prop('disabled', true);
    }
    $('#CreditInModal').modal('show');
}

function CreditOut(id) {
    console.log("CreditOut");
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
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '存入帳戶',
            cancelButtonText: '兌現',
            showLoaderOnConfirm: true,
            allowEscapeKey: false,
            allowOutsideClick: false,
            allowEnterKey: false
        }).then(function() {
                return new Promise(function(resolve) {
                    $.ajax({
                            url: 'Machine/Monitor/CreditOut',
                            type: 'POST',
                            data: { ID: id },
                        })
                        .done(function(data) {
                            console.log(data);
                            swal(
                                '鍵出成功!',
                                '',
                                'success'
                            );
                        })
                        .fail(function() {
                            console.log("error");
                        });
                });
            },
            function(dismiss) {
                console.log("HH");
                swal(
                    '兌現', '', 'info'
                )
            })
    }, function(dismiss) {
        console.log("HHH");
        swal(
            '取消!',
            '',
            'error'
        );
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
                credit: $('.CreditInInputCreditIn #CreaditIn').val()
            },
            success: function(data) {
                if (data == 1)
                    swal("鍵入成功", "", "success");
                else
                    swal("鍵入失敗", "", "error");
            },
            error: function(data) {
                console.log(data);
            },
        });
    });
})