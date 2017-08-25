$(function() {
    $('.machineCard').tooltip()
    $('[data-toggle="dropdown"]').dropdown()
})

function CreditIn(id) {
    console.log(CreaditIn)
}

function CreditOut(id) {
    console.log(CreditOut)
}

function GameReserved(id) {
    console.log(GameReserved)
}

$(document).ready(function() {
    $('.machineCard').on('show.bs.tooltip', function() {
        $('.machineCard').attr('data-original-title', '使用者：<br>餘額：0')
    })
})