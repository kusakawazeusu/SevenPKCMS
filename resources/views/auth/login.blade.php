@extends('wireframe')

@section('content')

<div class="row justify-content-center">

    <div class="col-md-4">
    
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center"><strong>登入</strong></h4>
                <hr>
                <form method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                    <label for="UserName">帳號：</label>
                    <input type="text" name="UserName" id="UserName" class="form-control" placeholder="請輸入帳號名稱 ...">
                    </div>

                    <div class="form-group">
                    <label for="Password">密碼：</label>
                    <input type="password" name="Password" id="Password" class="form-control" placeholder="請輸入密碼 ...">
                    </div>

                    <button class="d-block mx-auto btn btn-primary" type="submit">送出</button>

                </form>
            </div>


        </div>
    
    </div>

</div>

@endsection