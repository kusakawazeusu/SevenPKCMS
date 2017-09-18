var NumberOfEntries = 0;
var Page = 0;
var totalPage = 0;
var ShowEntries = 5;
var SeachText = "%";
var t;
var AjaxUrl;
var ChangeFormFlag;

$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') //處理csrf token
        }
    });

    t = $('#MachineProbabilityTable').DataTable({
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

    $("#AgentID").change(function(event) {
        SeachText = $(this).val() != -1 ? $(this).val() : "%";
        GetData(ShowEntries, Page, SeachText);
    });

    GetAgent('AgentID');

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

    /*
        對資料進行的操作
    */

    var CreateForm = document.getElementById("MachineProbabilityForm");
    CreateForm.novalidate = false;

    $("#MachineProbabilitySubmit").click(function() {
        $.ajax({
            url: AjaxUrl,
            method: "POST",
            data: $("#MachineProbabilityForm").serialize(),
            success: function(result) {
                $("#MachineProbabilitySubmit").prop('disabled', false);
                swal({
                    title: "操作成功！",
                    text: "列表將自動更新。",
                    type: "success",
                    animation: true
                });
                GetData(ShowEntries, Page, SeachText);
            },
            statusCode: {
                500: function() {
                    swal("操作失敗", "請確認欄位是否填寫正確！", "error");
                }
            }
        });
        ChangeFormFlag = 0;
        $('#MachineProbabilityModal').modal('toggle');
    });

    $('#MachineProbabilityModal').on('hide.bs.modal', function(e) {
        if (ChangeFormFlag == 1) {
            e.preventDefault();
            swal({
                title: '哈囉！',
                text: '我們發現有些資料已經被編輯過了，你確定要離開這個視窗嗎？',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: '放棄編輯',
                cancelButtonText: '留在此視窗'
            }).then(function() {
                ChangeFormFlag = 0;
                $('#MachineProbabilityModal').modal('toggle');
            });
        }
    });

    $('.range').on('input change', function() {
        $('#' + $(this).attr('name') + 'RangeText').text($(this).val());
        ChangeFormFlag = 1;
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

    $.ajax({
        url: 'Probability/GetTableData',
        method: "GET",
        data: SendingData,
        success: function(data) {
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

                t.row.add([
                    "<button onclick='OpenUpdateProbabilityModal(" + data[i].ID + ")' class='btn btn-success mr-2'><i class='fa fa-pencil'></i>",
                    data[i].ID,
                    data[i].Name,
                    data[i].MachineName,
                    section,
                    data[i].TwoPairs,
                    data[i].ThreeOfAKind,
                    data[i].Straight,
                    data[i].Flush,
                    data[i].FullHouse,
                    data[i].FourOfAKind,
                    data[i].STRFlush,
                    data[i].FiveOfAKind,
                    data[i].RoyalFlush,
                    data[i].BonusDifficulty,
                    data[i].WildCard,
                    data[i].RealFourOfAKind,
                    data[i].RealSTRFlush,
                    data[i].RealFiveOfAKind,
                    data[i].RealRoyalFlush,
                    data[i].Turtle,
                    data[i].DoubleStar,
                    data[i].Water
                ]).draw(false);
            }
        },
    });
}

function OpenUpdateProbabilityModal(id) {
    $.ajax({
        url: 'Probability/GetMachineByID',
        data: { "id": id },
        method: "GET",
        success: function(data) {

            var section;
            switch (data.SectionID) {
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

            $("#MachineProbabilityModalTitle").text('正在編輯： 經銷商' + data.AgentID + ' 第' + data.ID + '台 ' + section + '分區');
            $("input[name='id']").val(data.ID);

            $("input[name='TwoPairs']").val(data.TwoPairs);
            $('#TwoPairsRangeText').text(data.TwoPairs);
            $("input[name='ThreeOfAKind']").val(data.ThreeOfAKind);
            $('#ThreeOfAKindRangeText').text(data.ThreeOfAKind);
            $("input[name='Straight']").val(data.Straight);
            $('#StraightRangeText').text(data.Straight);
            $("input[name='Flush']").val(data.Flush);
            $('#FlushRangeText').text(data.Flush);
            $("input[name='FullHouse']").val(data.FullHouse);
            $('#FullHouseRangeText').text(data.FullHouse);
            $('input[name="FourOfAKind"]').val(data.FourOfAKind);
            $('#FourOfAKindRangeText').text(data.FourOfAKind);

            $('input[name="STRFlush"]').val(data.STRFlush);
            $('#STRFlushRangeText').text(data.STRFlush);
            $('input[name="FiveOfAKind"]').val(data.FiveOfAKind);
            $('#FiveOfAKindRangeText').text(data.FiveOfAKind);
            $('input[name="RoyalFlush"]').val(data.RoyalFlush);
            $('#RoyalFlushRangeText').text(data.RoyalFlush);
            $('input[name="RealFourOfAKind"]').val(data.RealFourOfAKind);
            $('#RealFourOfAKindRangeText').text(data.RealFourOfAKind);
            $('input[name="RealSTRFlush"]').val(data.RealSTRFlush);
            $('#RealSTRFlushRangeText').text(data.RealSTRFlush);
            $('input[name="RealFiveOfAKind"]').val(data.RealFiveOfAKind);
            $('#RealFiveOfAKindRangeText').text(data.RealFiveOfAKind);

            $('input[name="RealRoyalFlush"]').val(data.RealRoyalFlush);
            $('#RealRoyalFlushRangeText').text(data.RealRoyalFlush);
            $('input[name="Turtle"]').val(data.Turtle);
            $('#TurtleRangeText').text(data.Turtle);
            $('input[name="DoubleStar"]').val(data.DoubleStar);
            $('#DoubleStarRangeText').text(data.DoubleStar);
            $('input[name="BonusDifficulty"]').val(data.BonusDifficulty);
            $('#BonusDifficultyRangeText').text(data.BonusDifficulty);
            $('input[name="WildCard"]').val(data.WildCard);
            $('#WildCardRangeText').text(data.WildCard);
            $('input[name="Water"]').val(data.Water);
            $('#WaterRangeText').text(data.Water);

            $("#MachineProbabilityForm").removeClass("was-validated");
            $("#MachineProbabilityModal").modal('show');
            AjaxUrl = 'Probability/Edit';
        }
    });
}

function GetAgent(appenTo) {
    $.ajax({
        url: 'GetAgent',
        method: "GET",
        success: function(data) {
            for (var field in data) {
                $('<option value="' + data[field].ID + '">' + data[field].Name + '</option>').appendTo('#' + appenTo);
            }
        }
    });
}