$(function() {
    $('[data-toggle="dropdown"]').dropdown();
    $('.machineCardTooltip').tooltip({
        html: true,
        title: '使用者：無<br>餘額：0',
        placement: 'right'
    });
})

function CreditIn(id) {

    var maxDepositCredit, depositCreditOnce, playerPhone = 'null',
        needCode = 'true';

    $.ajax({
        url: 'Machine/Monitor/GetDepositCredit',
        type: 'POST',
        async: false,
        data: {
            id: id
        },
        success: function(data) {
            maxDepositCredit = data.MaxDepositCredit;
            depositCreditOnce = data.DepositCreditOnce;
            playerPhone = data.Cellphone;
            if (playerPhone != null) needCode = 'false';
        },
    });

    var htmlCode = playerPhone != null ?
        '<input type="text" id="PlayerPhone" class="swal2-input" value ="' + playerPhone + '" disabled>' + '<input type="number" id="Credit" class="swal2-input" min="0" max="' + maxDepositCredit + '" step="' + depositCreditOnce + '" value="0">' : '<input type="text" id="PlayerPhone" class="swal2-input" placeholder="會員電話">' + '<input type="number" id="Credit" class="swal2-input" min="0" max="' + maxDepositCredit + '" step="' + depositCreditOnce + '" value="0">';

    swal({
        title: "鍵入第" + id + "台",
        html: htmlCode,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showCancelButton: true,
        cancelButtonText: '取消',
        preConfirm: function() {
            return new Promise(function(resolve, reject) {
                $('#Credit').removeClass('swal2-inputerror');
                $('#PlayerPhone').removeClass('swal2-inputerror');
                if ($('#PlayerPhone').val() == '') {
                    $('#PlayerPhone').addClass('swal2-inputerror');
                    reject('請輸入會員');
                } else if ($('#Credit').val() == 0) {
                    $('#Credit').addClass('swal2-inputerror');
                    reject('請輸入金額');
                } else {
                    $.ajax({
                            url: 'Machine/Monitor/CheckCreditIn',
                            type: 'POST',
                            data: {
                                PlayerPhone: $('#PlayerPhone').val(),
                                Credit: $('#Credit').val(),
                                machineID: id
                            },
                        })
                        .done(function(response) {
                            if (response.valid == 'true')
                                resolve();
                            else if (response.errMsg == 'phone') {
                                $('#PlayerPhone').addClass('swal2-inputerror');
                                reject('查無此會員');
                            } else if (response.errMsg == 'creditNoEnough') {
                                $('#Credit').addClass('swal2-inputerror');
                                reject('餘額不足');
                            } else if (response.errMsg == 'enable') {
                                $('#PlayerPhone').addClass('swal2-inputerror');
                                reject('會員未啟用');
                            } else if (response.errMsg == 'creditToMore') {
                                $('#Credit').addClass('swal2-inputerror');
                                reject('鍵入過多');
                            }
                        })
                        .fail(function() {
                            console.log("CheckCreditIn Error");
                        });
                }
            });
        }
    }).then(function() {
            $.ajax({
                url: 'Machine/Monitor/CreditIn',
                type: 'post',
                async: false,
                data: {
                    playerCellphone: $('#PlayerPhone').val(),
                    credit: $('#Credit').val(),
                    machineID: id,
                    needCode: needCode,
                    operatorID: operatorID //{{ Auth::user()->id }}
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
        },
        function(dismiss) {
            swal({
                type: 'warning',
                title: '取消鍵入'
            })
        });
}

function CreditOut(id) {
    $.ajax({
            url: 'Machine/Monitor/GetCur',
            type: 'POST',
            data: {
                id: id
            },
        })
        .done(function(data) {
            if (data.Status != 0) {
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
                        text: '要取消交易直接點選空白處',
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '存入帳戶',
                        cancelButtonText: '兌現',
                        showLoaderOnConfirm: true,
                        allowEscapeKey: false,
                        allowEnterKey: false
                    }).then(function() {
                            return new Promise(function(resolve) {
                                $.ajax({
                                        url: 'Machine/Monitor/CreditOut',
                                        type: 'POST',
                                        async: false,
                                        data: {
                                            ID: id,
                                            type: 'ToCredit',
                                            operatorID: operatorID //{{ Auth::user()->id }}
                                        },
                                    })
                                    .done(function(data) {
                                        if (data.done == 'success') {
                                            swal('鍵出成功!', '會員餘額' + data.credit, 'success');
                                        } else
                                            swal('I have no idea', '', 'error');
                                        RefreshStatus();
                                    })
                                    .fail(function() {
                                        console.log("error");
                                    });
                            });
                        },
                        function(dismiss) {
                            if (dismiss === 'cancel') {
                                $.ajax({
                                        url: 'Machine/Monitor/CreditOut',
                                        type: 'POST',
                                        async: false,
                                        data: {
                                            ID: id,
                                            type: 'ToCash',
                                            operatorID: operatorID //{{ Auth::user()->id }}
                                        },
                                    })
                                    .done(function(data) {
                                        if (data.done == 'success') {
                                            swal('兌現', '應付金額' + data.credit, 'info');
                                        } else
                                            swal('I have no idea', '', 'error');
                                        RefreshStatus();
                                    })
                                    .fail(function() {
                                        console.log("error");
                                    });
                            } else {
                                CreditOut(id);
                            }
                        })
                }, function(dismiss) {
                    swal('取消!', '', 'error');
                });
            } else {
                return false;
            }
        });
}

function RemoveReserved(id) {
    $.ajax({
        url: 'Machine/Monitor/GetCur',
        type: 'POST',
        data: {
            id: id
        },
    }).done(function(data) {
        if (data.Status == 2) {
            swal({
                title: '確定要解除保留嗎?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '是, 解除保留!',
                cancelButtonText: '取消',
                showLoaderOnConfirm: true
            }).then(function() {
                swal({
                    title: '請選擇交易方式',
                    text: '要取消交易直接點選空白處',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '清空機台',
                    cancelButtonText: '解除保留',
                    showLoaderOnConfirm: true,
                    allowEscapeKey: false,
                    allowEnterKey: false
                }).then(function() {
                        return new Promise(function(resolve) {
                            $.ajax({
                                    url: 'Machine/Monitor/RemoveReserved',
                                    type: 'POST',
                                    async: false,
                                    data: {
                                        ID: id,
                                        type: 'FreeMachine',
                                        operatorID: operatorID //{{ Auth::user()->id }}
                                    },
                                })
                                .done(function(data) {
                                    if (data.done == 'success') {
                                        swal('鍵出成功!', '會員餘額' + data.credit, 'success');
                                    } else
                                        swal('I have no idea', '', 'error');
                                    RefreshStatus();
                                })
                                .fail(function() {
                                    console.log("error");
                                });
                        });
                    },
                    function(dismiss) {
                        if (dismiss === 'cancel') {
                            $.ajax({
                                    url: 'Machine/Monitor/RemoveReserved',
                                    type: 'POST',
                                    async: false,
                                    data: {
                                        ID: id,
                                        type: 'RemoveReserved',
                                        operatorID: operatorID //{{ Auth::user()->id }}
                                    },
                                })
                                .done(function(data) {
                                    if (data.done == 'success') {
                                        swal('解除保留成功', '', 'success');
                                    } else
                                        swal('I have no idea', '', 'error');
                                    RefreshStatus();
                                })
                                .fail(function() {
                                    console.log("error");
                                });
                        } else {
                            CreditOut(id);
                        }
                    })
            }, function(dismiss) {
                swal('取消!', '', 'error');
            });
        } else {
            return false;
        }
    });
}

$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') //處理csrf token
        }
    });

    RefreshStatus();

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
});

function RefreshStatus() {
    $.ajax({
        url: "Machine/Monitor/RefreshMachineStatus",
        type: 'POST',
        success: function(data) {
            for (var i = 0; i < data.length; i++) {
                if (data[i].Status == 2) {
                    $('#machine' + data[i].ID).attr("src", src = "img/machine/event.png");
                    $('#machineStatus' + data[i].ID).text("保留中");
                } else if (data[i].Status == 1) {
                    $('#machine' + data[i].ID).attr("src", src = "img/machine/online.png");
                    $('#machineStatus' + data[i].ID).text("連線中");
                } else {
                    $('#machine' + data[i].ID).attr("src", src = "img/machine/offline.png");
                    $('#machineStatus' + data[i].ID).text("離線中");
                }
            }
        },
        error: function(data) {}
    });

    setTimeout(function() {
        RefreshStatus();
        console.log("SetTimeOut");
    }, 180000);
}