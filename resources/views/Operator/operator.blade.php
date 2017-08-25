@extends('wireframe')

@section('content')

<script>

$(document).ready(function() {
    $('#OperatorTable').DataTable({
        "paging":   false,
        "info":     false,
        "searching": false,
        "bAutoWidth": false
    });

} );


</script>

<h1>員工管理</h1>
<hr>
<br>

<div class="row justify-content-between">

    <div class="col-md-2">
        <button class="btn btn-primary">新增員工</button>
    </div>

    <div class="col-md-3 mr-3">
        <div class="input-group mb-2">
            <div class="input-group-addon">姓名</div>
            <input type="text" class="form-control" id="Name" placeholder="要搜尋的姓名 ...">
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