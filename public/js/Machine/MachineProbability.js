var NumberOfEntries = 0;
var Page = 0;
var totalPage = 0;
var ShowEntries = 5;
var SeachText = "%";
var MachineName = "%";
var t;
var AjaxUrl;
var ChangeFormFlag;
var ProbabiltyAdj = [];
var Paytable = [];
var BaseProbabilities = [];

var GameResult = {
    RoyalFlushOdd: 0,
    FiveOfAKindOdd: 1,
    STRFlushOdd: 2,
    FourOfAKindOdd: 3,
    FullHouseOdd: 4,
    FlushOdd: 5,
    StrightOdd: 6,
    ThreeOfAKindOdd: 7,
    TwoPairsOdd: 8,
    Nothing: 9
};

$(document).ready(function () {

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
    GetData(ShowEntries, Page, SeachText, MachineName);

    /*
        對表格進行的操作。
    */

    $("#AgentID").change(function (event) {
        SeachText = $(this).val() != -1 ? $(this).val() : "%";
        GetData(ShowEntries, Page, SeachText, MachineName);
    });

    $("#MachineName").change(function (event) {
        MachineName = $(this).val() != -1 ? $(this).val() : "%";
        GetData(ShowEntries, Page, SeachText, MachineName);
    });

    GetAgent('AgentID');

    $(".ShowEntries").change(function () {
        ShowEntries = $(this).val();
        if (ShowEntries == 'ALL') {
            ShowEntries = NumberOfEntries;
            totalPage = 1;
            Page = 0;
        } else {
            Page = 0;
        }
        GetData(ShowEntries, Page, SeachText, MachineName);
    });

    $("#nextPage").click(function (event) {

        if (Page >= totalPage - 1) {
            swal({
                title: "已到最後一頁！",
                type: 'warning'
            });
        } else {
            Page += 1;
            GetData(ShowEntries, Page, SeachText, MachineName);
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
            GetData(ShowEntries, Page, SeachText, MachineName);
        }
    });

    /*
        對資料進行的操作
    */

    var CreateForm = document.getElementById("MachineProbabilityForm");
    CreateForm.novalidate = false;

    $("#MachineProbabilitySubmit").click(function () {
        $.ajax({
            url: AjaxUrl,
            method: "POST",
            data: $("#MachineProbabilityForm").serialize(),
            success: function (result) {
                $("#MachineProbabilitySubmit").prop('disabled', false);
                swal({
                    title: "操作成功！",
                    text: "列表將自動更新。",
                    type: "success",
                    animation: true
                });
                GetData(ShowEntries, Page, SeachText, MachineName);
            },
            statusCode: {
                500: function () {
                    swal("操作失敗", "請確認欄位是否填寫正確！", "error");
                }
            }
        });
        ChangeFormFlag = 0;
        $('#MachineProbabilityModal').modal('toggle');
    });

    $('#MachineProbabilityModal').on('hide.bs.modal', function (e) {
        if (ChangeFormFlag == 1) {
            e.preventDefault();
            swal({
                title: '哈囉！',
                text: '我們發現有些資料已經被編輯過了，你確定要離開這個視窗嗎？',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: '放棄編輯',
                cancelButtonText: '留在此視窗'
            }).then(function () {
                ChangeFormFlag = 0;
                $('#MachineProbabilityModal').modal('toggle');
            });
        }
    });

    $('.range').on('input change', function () {
        $('#' + $(this).attr('name') + 'RangeText').text($(this).val());
        ChangeFormFlag = 1;
        CountWater();
    });

    function CountWater() {
        var weight = [];
        weight[GameResult.RoyalFlushOdd] = Number($("input[name='RoyalFlush']").val());
        weight[GameResult.FiveOfAKindOdd] = Number($("input[name='FiveOfAKind']").val());
        weight[GameResult.STRFlushOdd] = Number($("input[name='STRFlush']").val());
        weight[GameResult.FourOfAKindOdd] = Number($("input[name='FourOfAKind']").val());
        weight[GameResult.FullHouseOdd] = Number($("input[name='FullHouse']").val());
        weight[GameResult.FlushOdd] = Number($("input[name='Flush']").val());
        weight[GameResult.StrightOdd] = Number($("input[name='Straight']").val());
        weight[GameResult.ThreeOfAKindOdd] = Number($("input[name='ThreeOfAKind']").val());
        weight[GameResult.TwoPairsOdd] = Number($("input[name='TwoPairs']").val());
        weight[GameResult.Nothing] = 5;
        var water = 0;
        var adj = [];
        for (var i = 0; i <= 8; ++i) {
            adj[i] = (BaseProbabilities[i] - (weight[i] - 1) * ProbabiltyAdj[i]) * 100 * Paytable[i];
            /*
            console.log(i);
            console.log(BaseProbabilities[i]);
            console.log(weight[i]);
            console.log(ProbabiltyAdj[i]);
            console.log(Paytable[i]);
            console.log(adj[i]);
            console.log("-----------------");
            */
            water += adj[i];
        }
        $('input[name="Water"]').val(water.toFixed(3));
        return water.toFixed(3);
    }
});

/*
    This function is used for getting data from API.
 
    @params
        - ShowEntries : Indicated the number of records we query from the API.
        - Page : We needs this to calculate the data offset.
        - SearchText : The keyword we're searching for.
 
*/
function GetData(ShowEntries, Page, SearchText, MachineName) {
    var SendingData = { "ShowEntries": ShowEntries, "Page": Page, "SearchText": SearchText, "MachineName": MachineName };

    swal({
        html: '<strong id="progressText">loading...</strong>',
        imageUrl: '../img/waiting.gif',
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
    })
    $.ajax({
        url: 'Probability/GetTableData',
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
                    data[i].TurtleTime,
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
        success: function (data) {

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
            $('input[name="Water"]').val(data.Water.toFixed(3));
            $('#WaterRangeText').text(data.Water);

            $('input[name="TurtleTime"]').val(data.TurtleTime);
            $('#TurtleTimeRangeText').text(data.TurtleTime);

            $("#MachineProbabilityForm").removeClass("was-validated");
            $("#MachineProbabilityModal").modal('show');
            AjaxUrl = 'Probability/Edit';
        }
    });

    $.ajax({
        url: 'Probability/GetProbabilityAdj',
        method: "GET",
        success: function (data) {
            ProbabiltyAdj[GameResult.RoyalFlushOdd] = data[0].RoyalFlush;
            ProbabiltyAdj[GameResult.FiveOfAKindOdd] = data[0].FiveOfAKind;
            ProbabiltyAdj[GameResult.STRFlushOdd] = data[0].STRFlush;
            ProbabiltyAdj[GameResult.FourOfAKindOdd] = data[0].FourOfAKind;
            ProbabiltyAdj[GameResult.FullHouseOdd] = data[0].FullHouse;
            ProbabiltyAdj[GameResult.FlushOdd] = data[0].Flush;
            ProbabiltyAdj[GameResult.StrightOdd] = data[0].Straight;
            ProbabiltyAdj[GameResult.ThreeOfAKindOdd] = data[0].ThreeOfAKind;
            ProbabiltyAdj[GameResult.TwoPairsOdd] = data[0].TwoPairs;
            //console.log(Number(ProbabiltyAdj[0]));
        }
    });

    $.ajax({
        url: 'Probability/GetBaseProbability',
        method: "GET",
        success: function (data) {
            console.log(data);
            for (var i = 0; i <= 9; ++i) {
                if (data[i].GameResult == 'RoyalFlushOdd')
                    BaseProbabilities[GameResult.RoyalFlushOdd] = data[i].BaseProbability;
                else if (data[i].GameResult == 'FiveOfAKindOdd')
                    BaseProbabilities[GameResult.FiveOfAKindOdd] = data[i].BaseProbability;
                else if (data[i].GameResult == 'STRFlushOdd')
                    BaseProbabilities[GameResult.STRFlushOdd] = data[i].BaseProbability;
                else if (data[i].GameResult == 'FourOfAKindOdd')
                    BaseProbabilities[GameResult.FourOfAKindOdd] = data[i].BaseProbability;
                else if (data[i].GameResult == 'FullHouseOdd')
                    BaseProbabilities[GameResult.FullHouseOdd] = data[i].BaseProbability;
                else if (data[i].GameResult == 'FlushOdd')
                    BaseProbabilities[GameResult.FlushOdd] = data[i].BaseProbability;
                else if (data[i].GameResult == 'StraightOdd')
                    BaseProbabilities[GameResult.StrightOdd] = data[i].BaseProbability;
                else if (data[i].GameResult == 'ThreeOfAKindOdd')
                    BaseProbabilities[GameResult.ThreeOfAKindOdd] = data[i].BaseProbability;
                else if (data[i].GameResult == 'TwoPairsOdd')
                    BaseProbabilities[GameResult.TwoPairsOdd] = data[i].BaseProbability;
            }
            /*
            BaseProbabilities[GameResult.RoyalFlushOdd] = data[0].RoyalFlushOdd;
            BaseProbabilities[GameResult.FiveOfAKindOdd] = data.FiveOfAKindOdd;
            BaseProbabilities[GameResult.STRFlushOdd] = data.STRFlushOdd;
            BaseProbabilities[GameResult.FourOfAKindOdd] = data.FourOfAKindOdd;
            BaseProbabilities[GameResult.FullHouseOdd] = data.FullHouseOdd;
            BaseProbabilities[GameResult.FlushOdd] = data.FlushOdd;
            BaseProbabilities[GameResult.StrightOdd] = data.StrightOdd;
            BaseProbabilities[GameResult.ThreeOfAKindOdd] = data.ThreeOfAKindOdd;
            BaseProbabilities[GameResult.TwoPairsOdd] = data.TwoPairsOdd;
            */
            //console.log(BaseProbabilities.FiveOfAKind);
        }
    });

    $.ajax({
        url: 'Probability/GetPaytable',
        data: { "id": id },
        method: "POST",
        success: function (data) {
            //console.log(data);
            Paytable[GameResult.RoyalFlushOdd] = data.RoyalFlushOdd;
            Paytable[GameResult.FiveOfAKindOdd] = data.FiveOfAKindOdd;
            Paytable[GameResult.STRFlushOdd] = data.STRFlushOdd;
            Paytable[GameResult.FourOfAKindOdd] = data.FourOfAKindOdd;
            Paytable[GameResult.FullHouseOdd] = data.FullHouseOdd;
            Paytable[GameResult.FlushOdd] = data.FlushOdd;
            Paytable[GameResult.StrightOdd] = data.StraightOdd;
            Paytable[GameResult.ThreeOfAKindOdd] = data.ThreeOfAKindOdd;
            Paytable[GameResult.TwoPairsOdd] = data.TwoPairsOdd;
            Paytable[GameResult.Nothing] = 0;
            //console.log(Paytable[0]);
        }
    });
}

function GetAgent(appenTo) {
    $.ajax({
        url: 'GetAgent',
        method: "GET",
        success: function (data) {
            for (var field in data) {
                $('<option value="' + data[field].ID + '">' + data[field].Name + '</option>').appendTo('#' + appenTo);
            }
        }
    });
}