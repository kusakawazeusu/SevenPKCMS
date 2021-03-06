function Deposit(ID, Type = null, Data = null) {
    var num = 0;
    $.ajax({
            url: 'Player/PlayerData',
            type: 'GET',
            data: { ID: ID },
        })
        .done(function(PlayerData) {

            swal({
                title: '儲值帳戶：' + PlayerData.Name,
                html: '<input type="text" id="DepositNum" class="swal2-input" placeholder="金額" aria-label="金額" >',
                showCancelButton: true,
                confirmButtonText: '儲值',
                cancelButtonText: '取消儲值',
                cancelButtonColor: '#d33',
                showLoaderOnConfirm: true,
                allowOutsideClick: false,
                onOpen: function() {
                    $('#DepositNum').on('keyup', function(event) {
                        num = CommaNumToNum($('#DepositNum').val());
                        $('#DepositNum').val(num.toLocaleString("en-US"));
                    });
                },
                preConfirm: function() {
                    return new Promise(function(resolve, reject) {
                        if ($('#DepositNum').val() == 'NaN') {
                            $('#DepositNum').addClass('swal2-inputerror');
                            reject('請輸入正確的金額！');
                        } else if ($('#DepositNum').val() == '') {
                            $('#DepositNum').addClass('swal2-inputerror');
                            reject('儲值金額不可為空！');
                        } else if (CommaNumToNum($('#DepositNum').val()) == 0) {
                            $('#DepositNum').addClass('swal2-inputerror');
                            reject('儲值金額不可為零！');
                        }
                        resolve();

                    })
                }
            }).then(function() {
                    $.ajax({
                            url: 'Player/Deposit',
                            type: 'POST',
                            data: { ID: ID, credit: CommaNumToNum($('#DepositNum').val()) }
                        })
                        .done(function(response) {
                            console.log(response);
                            swal({
                                type: 'success',
                                title: '儲值成功',
                                showConfirmButton: false,
                                timer: 1000
                            }).then(
                                function() {},
                                function() {
                                    if (Type == 'Reload')
                                        location.reload();
                                    else if (Type == 'CoinIn') {
                                        $.ajax({
                                            url: 'Machine/Monitor/CreditIn',
                                            type: 'post',
                                            async: false,
                                            data: {
                                                playerCellphone: Data.PlayerPhone,
                                                credit: num,
                                                machineID: Data.id,
                                                needCode: Data.needCode,
                                                operatorID: Data.operatorID //{{ Auth::user()->id }}
                                            },
                                            success: function(data) {
                                                if (data.done == 'success') {
                                                    if (data.code != 0) swal("鍵入成功", "驗證碼：" + data.code, "success");
                                                    else swal("鍵入成功", "", "success");
                                                } else {
                                                    swal("鍵入失敗", data.errorMsg, "error");
                                                }
                                            },
                                            error: function(data) {
                                                console.log(data);
                                            },
                                        });
                                        RefreshStatus();
                                    }
                                });
                        })
                        .fail(function() {
                            console.log("error");
                        });
                },
                function(dismiss) {
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

function CommaNumToNum(x) {
    return x.replace(/[^\d.-]/g, '') * 1;
}