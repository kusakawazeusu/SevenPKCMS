$(function() {
    $('[data-toggle="dropdown"]').dropdown();
    $('.machineCardTooltip').tooltip({
        html: true,
        title: '使用者：無<br>餘額：0',
        placement: 'right'
    });
})

function CreditIn(id) {
    $('.CreditInInputPlayerID #PlayerID').val('');
    $('.CreditInInputPlayerID #PlayerID').prop('disabled', false);
    console.log(id);
    var playerID = 0;
    $.ajax({
        url: 'Machine/Monitor/GetCur',
        type: 'post',
        async: false,
        data: {
            id: id
        },
        success: function(data) {
            console.log(data);
            playerID = data.CurPlayer;
        }
    })
    console.log(playerID);
    if (playerID != 0) {
        $('.CreditInInputPlayerID #PlayerID').val(playerID);
        $('.CreditInInputPlayerID #PlayerID').prop('disabled', true);
    }
    $('#CreditInModal').modal('show');
}

function CreditOut(id) {
    console.log(CreditOut);
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
                if (data.CurPlayer != 0) {
                    $('.machineCardTooltip').attr('data-original-title', '使用者：' + data.CurPlayer.toString() + '<br>餘額：' + data.CurCredit.toString());
                } else {
                    $('.machineCardTooltip').attr('data-original-title', '使用者：無<br>餘額：0');
                }
            },
            error: function(data) {
                console.log(data);
            },
        });
    });

    $('#CreaditInAccept').click(function(event) {
        $.ajax({
                url: 'Machine/Monitor/CreditIn',
                type: 'post',
                data: {
                    playerID: $('.CreditInInputPlayerID #PlayerID').val(),
                    credit: $('.CreditInInputCreditIn #CreaditIn').val()
                },
            })
            .done(function(response) {
                swal("鍵入成功", "", "success");
            })
            .fail(function() {
                console.log("error");
            });
    });
})