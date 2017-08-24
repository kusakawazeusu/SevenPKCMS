@extends('wireframe')

@section('content')

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

@endsection