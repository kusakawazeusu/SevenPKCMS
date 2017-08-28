$(function() {
    $('[data-toggle="dropdown"]').dropdown();
})

function CreditIn(id) {
    console.log("CreaditIn");
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

    $('.machineCardTooltip').tooltipster({
        content: 'WTF',
        // 'instance' is basically the tooltip. More details in the "Object-oriented Tooltipster" section.
        functionReady: function(instance, helper) {
            console.log($(this).val());
        }
    });


    /*
        $('.machineCard').on('show.bs.tooltip', function() {
            $.ajax({
                url: 'Machine/Monitor/GetCur',
                type: "POST",
                data: {
                    id: this.id
                },
                success: function(data) {
                    console.log(data);
                    if (data.CurPlayer != 0) {
                        $('.machineCardTooltip').attr('data-original-title', '使用者：' + data.CurPlayer.toString() + '<br>餘額：' + data.CurCredit.toString());
                    } else {
                        $('.machineCardTooltip').attr('data-original-title', '使用者：無<br>餘額：0');
                    }
                },
                error: function(data) {
                    console.log(data);
                    $('.machineCard').attr('data-original-title', '使用者：無<br>餘額：0');
                },
            });
        });
    */


})