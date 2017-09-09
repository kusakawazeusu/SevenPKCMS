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

    $("#AgentID").keyup(function(event) {
        SeachText = $(this).val();
        GetData(ShowEntries, Page, SeachText);
    });

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
        if (CreateForm.checkValidity() == false) {
            $("#MachineProbabilityForm").addClass("was-validated");
        } else {
            $("#MachineProbabilitySubmit").prop('disabled', true);
            $.ajax({
                url: AjaxUrl,
                method: "POST",
                data: $("#MachineProbabilityForm").serialize(),
                success: function(result) {
                    $("#MachineProbabilitySubmit").prop('disabled', false);
                    $("#MachineProbabilityModal").modal('hide');
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

    $.ajax({
        url: 'Probability/GetTableData',
        method: "GET",
        data: SendingData,
        success: function(data) {
            console.log(data);
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
                    data[i].AgentID,
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
            $("input[name='ThreeOfAKind']").val(data.ThreeOfAKind);
            $("input[name='Straight']").val(data.Straight);
            $("input[name='Flush']").val(data.Flush);
            $("input[name='FullHouse']").val(data.FullHouse);
            $('input[name="FourOfAKind"]').val(data.FourOfAKind);

            $('input[name="STRFlush"]').val(data.STRFlush);
            $('input[name="FiveOfAKind"]').val(data.FiveOfAKind);
            $('input[name="RoyalFlush"]').val(data.RoyalFlush);
            $('input[name="RealFourOfAKind"]').val(data.RealFourOfAKind);
            $('input[name="RealSTRFlush"]').val(data.RealSTRFlush);
            $('input[name="RealFiveOfAKind"]').val(data.RealFiveOfAKind);

            $('input[name="RealRoyalFlush"]').val(data.RealRoyalFlush);
            $('input[name="Turtle"]').val(data.Turtle);
            $('input[name="DoubleStar"]').val(data.DoubleStar);
            $('input[name="BonusDifficulty"]').val(data.BonusDifficulty);
            $('input[name="WildCard"]').val(data.WildCard);
            $('input[name="Water"]').val(data.Water);

            $("#MachineProbabilityForm").removeClass("was-validated");
            $("#MachineProbabilityModal").modal('show');
            AjaxUrl = 'Probability/Edit';
        }
    });
}