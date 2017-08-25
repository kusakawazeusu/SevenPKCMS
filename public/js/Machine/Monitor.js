$(function() {
    $('.machineCard').tooltip()
    $('[data-toggle="dropdown"]').dropdown()
})

function CreditIn(id) {
    console.log(id)
}

function CreditOn(id) {
    console.log(id)
}

function GameReserved(id) {
    console.log(id)
}

$(document).ready(function() {
    $('.machineCard').on('show.bs.tooltip', function() {
        $('.machineCard').attr('data-original-title', '使用者：<br>餘額：0')
    })
})