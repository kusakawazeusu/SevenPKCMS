$(function() {
    $('[data-toggle="dropdown"]').dropdown();
    $('.machineCardTooltip').tooltip({
        html: true,
        title: '使用者：無<br>餘額：0',
        placement: 'right'
    });
})

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
            type: "POST",
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

    $('#aCreditIn').click(function() {
        CreditIn($(this).val());
    })

    function CreditIn(id) {
        console.log("CreaditIn");
        $('#CreditInModal').modal('show');
    }


})