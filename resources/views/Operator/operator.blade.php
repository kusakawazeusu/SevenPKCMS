@extends('wireframe')

@section('content')

<script>

$(document).ready(function() {
    
    var t = $('#OperatorTable').DataTable({
                "paging":   false,
                "info":     false,
                "searching": false,
                "bAutoWidth": false,
            });

    var Page = 0;
    var ShowEntries = 5;
    var SeachText = "%";

    // Initialize the table
    GetData(ShowEntries,Page,SeachText);



    /*
        This function is used for getting data from API.

        @params
            - ShowEntries : Indicated the number of records we query from the API.
            - Page : We needs this to calculate the data offset.
            - SearchText : The keyword we're searching for.

    */
    function GetData(ShowEntries, Page, SearchText)
    {
        t.clear().draw();
        var SendingData = { "ShowEntries":ShowEntries, "Page":Page, "SearchText":SearchText };

        $.get("{{route('GetOperators')}}",SendingData,function(data){
                
                var NumOfData = data.length;

                for( var i=0; i<NumOfData; i++ )
                {
                    var AccountTypeName;

                    switch (data[i].Type)
                    {
                        case 0:
                            AccountTypeName = "工作人員";
                            break;
                        case 1:
                            AccountTypeName = "管理人員";
                            break;
                        case 2:
                            AccountTypeName = "最高管理員";
                            break;
                    }

                    t.row.add([
                        data[i].Name,
                        data[i].Account,
                        AccountTypeName,
                        data[i].IDCardNumber,
                        data[i].Phone
                    ]).draw(false);
                }
        });
    }

    $("#Name").keyup(function(){
        SeachText = $(this).val();
        GetData(ShowEntries,Page,SeachText);
    });

});


</script>

<h1>員工管理</h1>
<hr>
<br>

<div class="row justify-content-between">

    <div class="col-md-2">
        <button class="btn btn-primary">新增員工</button>
    </div>

    <div class="col-md-4 mr-3">
        <div class="row">
            <div class="col-md-6">
                <div class="input-group mb-2">
                    <div class="input-group-addon">顯示筆數</div>
                    <select class="form-control">
                        <option>5</option>
                        <option>10</option>
                        <option>全部</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group mb-2">
                    <div class="input-group-addon">姓名</div>
                    <input type="text" class="form-control" id="Name" placeholder="要搜尋的姓名 ...">
                </div>
            </div>
        </div>
    </div>

</div>

<div class="col-md-12">

    <table id="OperatorTable"  class="table table-bordered table-hover" cellspacing="0">
            <thead>
                <tr>
                    <th>員工姓名</th>
                    <th>帳號名稱</th>
                    <th>帳號類別</th>
                    <th>身分證字號</th>
                    <th>行動電話</th>
                </tr>
            </thead>
            
            <tbody>
            </tbody>
    </table>


</div>

    



@endsection