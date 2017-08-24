$(function() {
    $('[data-toggle="tooltip"]').tooltip()
})
$(document).ready(function() {
    $('.machine').on('show.bs.tooltip', function() {
        // do something…
        console.log('h');
    })
})