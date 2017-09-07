@extends('wireframe')

@section('title','下班')

@section('content')

<div class="row justify-content-center">

    <div class="col-md-4">
    
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center"><strong>下班</strong></h4>
                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                <hr>
                <form method="POST" action="{{ url('knockoff') }}">
                    {{ csrf_field() }}

                    <div class="form-group">
                            <label for="KnockOffTime">下班時間</label>
                            <input readonly type="text" name="KnockOffTime" class="form-control" value="{{ date('Y-m-d H:i:s') }}">
                    </div>

                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label for="UserName">帳號</label>
                            <input readonly type="text" name="Account" id="Account" class="form-control" value="{{ Auth::user()->Account }}">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="SessionID">班別代號</label>
                            <input readonly type="text" name="SessionID" class="form-control" value="{{ Auth::user()->SessionID }}">
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="CreditIn">總鍵入金額</label>
                            <input readonly type="text" name="CreditIn" class="form-control" value="{{ $CreditIn }}">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="CreditOut">總鍵出金額</label>
                            <input readonly type="text" name="CreditOut" class="form-control" value="{{ $CreditOut }}">
                        </div>
        
                        <div class="col-md-4 form-group">
                            <label for="KnockOffTime">吞吐差額</label>
                            <input readonly type="text" name="Throughput" class="form-control" value="{{ $CreditIn - $CreditOut }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="CoinIn">押分金額</label>
                            <input readonly type="text" name="CoinIn" class="form-control" value="{{ $CoinIn }}">
                        </div>
            
                        <div class="col-md-4 form-group">
                            <label for="CoinOut">得分金額</label>
                            <input readonly type="text" name="CoinOut" class="form-control" value="{{ $CoinOut }}">
                        </div>
            
                        <div class="col-md-4 form-group">
                            <label for="CoinDiff">押得差額</label>
                            <input readonly type="text" name="CoinDiff" class="form-control" value="{{ $CoinIn - $CoinOut }}">
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="Password">密碼</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="請輸入密碼 ...">
                    </div>

                    <input class="d-block mx-auto btn btn-primary" type="submit">

                </form>
            </div>


        </div>
    
    </div>

</div>

@endsection