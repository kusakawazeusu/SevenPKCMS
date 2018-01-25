var NumberOfEntries = 0;
var Page = 0;
var totalPage = 0;
var ShowEntries = 5;
var SeachText = "%";
var t;
var AjaxUrl;

$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') //處理csrf token
        }
    });

    t = $('#MachineMeterTable').DataTable({
        "paging": false,
        "info": false,
        "searching": false,
        "bAutoWidth": false,
    });

    // Initialize the table
    GetData(ShowEntries, Page, SeachText);

    /*
        對表格進行的操作。
    */

    $("#AgentID").keyup(function (event) {
        SeachText = $(this).val();
        GetData(ShowEntries, Page, SeachText);
    });

    $(".ShowEntries").change(function () {
        ShowEntries = $(this).val();
        if (ShowEntries == 'ALL') {
            ShowEntries = NumberOfEntries;
            totalPage = 1;
            Page = 0;
        } else {
            Page = 0;
        }
        GetData(ShowEntries, Page, SeachText);
    });

    $("#nextPage").click(function (event) {

        if (Page >= totalPage - 1) {
            swal({
                title: "已到最後一頁！",
                type: 'warning'
            });
        } else {
            Page += 1;
            GetData(ShowEntries, Page, SeachText);
        }
    });

    $("#previousPage").click(function () {
        if (Page < 1) {
            swal({
                title: "已到第一頁！",
                type: 'warning'
            });
        } else {
            Page -= 1;
            GetData(ShowEntries, Page, SeachText);
        }
    });
});

/*
    This function is used for getting data from API.
 
    @params
        - ShowEntries : Indicated the number of records we query from the API.
        - Page : We needs this to calculate the data offset.
        - SearchText : The keyword we're searching for.
 
*/
function GetData(ShowEntries, Page, SearchText) {
    var SendingData = { "ShowEntries": ShowEntries, "Page": Page, "SearchText": SearchText };

    swal({
        html: '<strong id="progressText">loading...</strong>',
        imageUrl: '../img/waiting.gif',
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
    })
    $.ajax({
        url: 'Meter/GetTableData',
        method: "GET",
        data: SendingData,
        success: function (data) {
            swal.close();
            t.clear().draw();
            NumberOfEntries = data['count'];
            totalPage = Math.ceil(NumberOfEntries / ShowEntries);
            $("#NumberOfEntries").text(NumberOfEntries);
            $("#totalPage").text(totalPage);
            $("#page").text(Page + 1);

            var NumOfData = Object.keys(data).length - 1;

            for (var i = 0; i < NumOfData; i++) {

                var section;
                switch (data[i].SectionID) {
                    case 0:
                        section = 2;
                        break;
                    case 1:
                        section = 3;
                        break;
                    case 2:
                        section = 5;
                        break;
                    case 3:
                        section = 10;
                        break;
                    default:
                        break;
                }

                var water = Math.round(data[i].Credit / data[i].BetCredit * 100).toString();
                var throughput = Math.round(data[i].TotalCreditOut / data[i].TotalCreditIn * 100).toString();
                console.log(data[i]);
                t.row.add([
                    data[i].ID,
                    data[i].MachineName,
                    section,
                    data[i].Games.toLocaleString("en-US"),
                    data[i].DoubleStar.toLocaleString("en-US"),
                    data[i].TwoPairs.toLocaleString("en-US"),
                    data[i].ThreeOfAKind.toLocaleString("en-US"),
                    data[i].Straight.toLocaleString("en-US"),
                    data[i].Flush.toLocaleString("en-US"),
                    data[i].FullHouse.toLocaleString("en-US"),
                    data[i].FourOfAKind.toLocaleString("en-US"),
                    data[i].RealFourOfAKind.toLocaleString("en-US"),
                    data[i].STRFlush.toLocaleString("en-US"),
                    // data[i].RealSTRFlush.toLocaleString("en-US"),
                    data[i].FiveOfAKind.toLocaleString("en-US"),
                    data[i].RoyalFlush.toLocaleString("en-US"),
                    // data[i].RealRoyalFlush.toLocaleString("en-US"),
                    data[i].BetCredit.toLocaleString("en-US"),
                    data[i].Credit.toLocaleString("en-US"),
                    water.toLocaleString("en-US") + '%',
                    data[i].TotalCreditIn.toLocaleString("en-US"),
                    data[i].TotalCreditOut.toLocaleString("en-US"),
                    throughput.toLocaleString("en-US") + '%',
                    "<button onclick='CleanMachineMeter(" + data[i].ID + ")' class='btn btn-danger'><i class='fa fa-trash'></i></button>",
                    "<a href='Meter/" + data[i].ID + "' class='btn btn-success mr-2'><i class='fa fa-search'></i></a>"
                ]).draw(false);
            }
        },
    });
}

function CleanMachineMeter(id) {
    swal({
        title: '清除機台紀錄',
        text: "你確定要清除此機台紀錄嗎？",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '是！',
        cancelButtonText: '取消',
        showLoaderOnConfirm: true,
        allowEscapeKey: false,
        allowOutsideClick: false,
        allowEnterKey: false
    }).then(function () {
        $.ajax({
            url: 'Meter/Clean',
            data: { "id": id },
            method: "post",
            statusCode: {
                200: function () {
                    swal({
                        title: "清除成功！",
                        type: "success"
                    });
                    GetData(ShowEntries, Page, SeachText);
                }
            }
        });
    }, function (dismiss) {
        swal('取消!', '', 'error');
    });
}