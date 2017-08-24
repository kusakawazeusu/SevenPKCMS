$(document).ready(function() {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') //處理csrf token
		}
	});
	
	$('#addPlayer').click(function(event) {
		/* Act on the event */
	});
});