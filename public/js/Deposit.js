function Deposit(ID,Type=null)
{
	$.ajax({
		url: 'Player/PlayerData',
		type: 'GET',
		data: {ID: ID},
	})
	.done(function(PlayerData) {

		swal({
			title: '儲值帳戶：'+PlayerData.Account,
			input: 'number',
			inputAttributes:{min:1},
			showCancelButton: true,
			confirmButtonText: '儲值',
			cancelButtonText:'取消儲值',
			cancelButtonColor:'#d33',
			showLoaderOnConfirm: true,
			allowOutsideClick: false,			
			preConfirm: function (number){
				return new Promise(function(resolve,reject){
					if(number<0)
						reject('儲值金額不可為負！');
					else if(number=='')
						reject('儲值金額不可為空！');
					else if(number==0)
						reject('儲值金額不可為零！');
					resolve();

				})
			}
		}).then(function (credit) {
			$.ajax({
				url: 'Player/Deposit',
				type: 'POST',
				data: {ID:ID,credit: credit}
			})
			.done(function(response) {
				console.log(response);		
				swal({
					type: 'success',
					title: '儲值成功'
				})
				if(Type=='Reload')
					location.reload();
			})
			.fail(function() {
				console.log("error");
			});
		},function(dismiss)
		{
			swal({
				type: 'error',
				title: '取消儲值!'
			})
		});
	})
	.fail(function() {
		console.log("error");
	});	
}

