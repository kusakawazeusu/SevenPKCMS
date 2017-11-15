var NumberOfEntries = 0;
var Page = 0;
var totalPage = 0;
var ShowEntries = 5;
var SeachText = "%";
var t;
var AjaxUrl;

$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') //處理csrf token
        }
    });

    t = $('#MachineMeterTableByID').DataTable({
        "paging": false,
        "info": false,
        "searching": false,
        "bAutoWidth": false,
        "order": [
            [10, "desc"]
        ]
    });

    // Initialize the table
    GetData(ShowEntries, Page, SeachText);

    /*
        對表格進行的操作。
    */

    $(".ShowEntries").change(function() {
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

    $("#nextPage").click(function(event) {

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

    $("#previousPage").click(function() {
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

    $('#StartTime').datepicker({
        format: 'yyyy-mm-dd',
        language: 'zh-TW',
        autoclose: 1,
        todayHighlight: true
    });

    $('#EndTime').datepicker({
        format: 'yyyy-mm-dd',
        language: 'zh-TW',
        autoclose: 1,
        todayHighlight: true
    });

    $("#StartTime").keypress(function(e) {
        e.preventDefault();
    });

    $("#EndTime").keypress(function(e) {
        e.preventDefault();
    });

    $('#StartTime').datepicker().on('changeDate', function(event) {
        var startTime = $('#StartTime').datepicker().val();
        $('#EndTime').datepicker('setStartDate', startTime);
    });

    $('#EndTime').datepicker().on('changeDate', function(event) {
        GetData(ShowEntries, Page, SeachText);
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
        html:'<strong id="progressText">loading...</strong>',
        imageUrl: '../../img/waiting.gif',
        showConfirmButton: false,
        allowOutsideClick:false,
        allowEscapeKey:false,
        allowEnterKey:false,
    })
    $.ajax({
        url: 'GetTableDataByID',
        method: "GET",
        data: {
            ShowEntries: ShowEntries,
            Page: Page,
            SearchText: SearchText,
            ID: ID,
            StartTime: $('#StartTime').val(),
            EndTime: $('#EndTime').val()
        },
        success: function(data) {
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

                var CardResultRate;
                var CardResult;
                switch (data[i].GameResult) {
                    case 0:
                        {
                            CardResult = '沒中';
                            CardResultRate = 0;
                        }
                        break;
                    case 1:
                        {
                            CardResult = '同花大順';
                            CardResultRate = 500;
                        }
                        break;
                    case 2:
                        {
                            CardResult = '五梅';
                            CardResultRate = 200;
                        }
                        break;
                    case 3:
                        {
                            CardResult = '同花順';
                            CardResultRate = 120;
                        }
                        break;
                    case 4:
                        {
                            CardResult = '四梅';
                            CardResultRate = 50;
                        }
                        break;
                    case 5:
                        {
                            CardResult = '葫蘆';
                            CardResultRate = 7;
                        }
                        break;
                    case 6:
                        {
                            CardResult = '同花';
                            CardResultRate = 5;
                        }
                        break;
                    case 7:
                        {
                            CardResult = '順子';
                            CardResultRate = 3;
                        }
                        break;
                    case 8:
                        {
                            CardResult = '三條';
                            CardResultRate = 2;
                        }
                        break;
                    case 9:
                        {
                            CardResult = '大兩對';
                            CardResultRate = 1
                        }
                        break;
                }

                var DoubleStar = data[i].DoubleStar == '1' ? 'Yes' : 'No';
                CardResultRate += CardResultRate * data[i].DoubleStar;

                t.row.add([
                    NumberOfEntries - i  - (ShowEntries * Page),
                    data[i].MachineName,
                    section,
                    data[i].Credit,
                    CardResult,
                    CardResultRate,
                    DoubleStar,
                    'NaN',
                    data[i].BonusRate,
                    data[i].WinCredit,
                    data[i].Created_at
                ]).draw(false);
            }
        },
    });
}