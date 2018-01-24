<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{--  Initialize Some Framework  --}}
        <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/font-awesome.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/dataTables.bootstrap4.css')}}"/>
        <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-datepicker3.min.css')}}"/>
        <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-datetimepicker.min.css')}}"/>
        
        
        <script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
        <script src="{{asset('js/popper.min.js')}}"></script>
        <script src="{{asset('js/bootstrap.min.js')}}"></script>
        <script src="{{asset('js/sweetalert2.min.js')}}"></script>

        <script src="{{asset('js/Moment.mis.js')}}"></script>
        <script src="{{asset('js/monment_zh-tw.js')}}"></script>
        <script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
        <script src="{{asset('js/bootstrap-datepicker.zh-TW.min.js')}}"></script>
        <script src="{{asset('js/bootstrap-datetimepicker.min.js')}}"></script>
        

        <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('js/dataTables.bootstrap4.js')}}"></script>

        {{--  Load my own css  --}}
        <link rel="stylesheet" type="text/css" href="{{asset('css/wireframe.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert2.min.css')}}">

        <title>@yield('title') - 7PK管理系統</title>

    </head>
    <body>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="{{ url('/') }}">7PK 管理系統</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav mr-auto">
                    @section('navbar-item')
                    @show
                </ul>
                @if(!Auth::guest())
                <a href="{{ url('/knockoff') }}" class="btn btn-light mr-3">下班</a>
                <a href="{{ url('/shift') }}" class="btn btn-light mr-3">交班</a>
                @endif
                <span class="navbar-text mr-3">
                    @if(!Auth::guest())
                    你好，{{ Auth::user()->Name }}！
                    @endif
                </span>
                @if(Auth::guest())
                <a href="{{ url('/login') }}" class="btn btn-success">登入</a>
                @else
                <a href="{{ url('/logout') }}" class="btn btn-primary">登出</a>
                @endif
                
            </div>
        </nav>

        <div class="container-fluid">
        
            <div class="row mt-4">
                <div class="col-md-2">
                
                    <div class="jumbotron SideMenu">
                        @if( !Auth::guest() && Auth::user()->Type == 2 )
                        <h5 class="display-5">櫃控系統</h5>
                        <hr>
                        <p class="SideBarTitle">帳號</p>
                        <a href="{{ url('/operator') }}">員工管理</a>
                        <br><br>

                        <p class="SideBarTitle">遊戲機台</p>
                        <a href="{{url('/Machine/Probability')}}">機台機率管理</a><br>
                        <a href="{{ url('/Machine') }}">機台分區管理</a><br>
                        <a href="{{url('/Machine/Meter')}}">機台數值表</a><br>
                        <a href="{{url('/Machine/PublicSetting')}}">機台公共設定</a><br><br>

                        <p class="SideBarTitle">通路</p>
                        <a href="{{ url('/introducer') }}">介紹人</a><br>
                        <a href="{{ url('/agent') }}">經銷商</a><br><br>

                        <p class="SideBarTitle">報表</p>
                        <a href="{{ url('/report') }}">報表管理</a><br>
                        <a href="{{url('/PlayerLog')}}">會員遊玩紀錄</a><br><br>

                        <p class="SideBarTitle">業務</p>
                        <a href="{{ url('/report') }}">交班管理</a><br><br>

                        <p class="SideBarTitle">會員</p>
                        <a href="{{ url('/Player') }}">會員管理</a><br><br>

                        <p class="SideBarTitle">時間控制系統</p>
                        <a href="{{ url('/CardBuff') }}">時間牌型選擇</a><br>
                        <a href="{{ url('/CardType') }}">控制牌型調整</a><br>
                        <a href="{{ url('/BroadcastMsg') }}">活動廣播訊息設定</a><br><br>
                        @endif

                        @if( !Auth::guest() &&  Auth::user()->Type == 1 )
                        <h5 class="display-5">櫃控系統</h5>
                        <hr>
                        <p class="SideBarTitle">通路</p>
                        <a href="{{ url('/introducer') }}">介紹人</a><br>

                        <p class="SideBarTitle">報表</p>
                        <a href="{{ url('/report') }}">報表管理</a><br>
                        <a href="{{url('/PlayerLog')}}">會員遊玩紀錄</a><br><br>

                        <p class="SideBarTitle">業務</p>
                        <a href="">交班管理</a><br><br>

                        <p class="SideBarTitle">會員</p>
                        <a href="{{ url('/Player') }}">會員管理</a><br><br>

                        @endif

                        @if( !Auth::guest() &&  Auth::user()->Type == 0 )
                        <h5 class="display-5">櫃控系統</h5>
                        <hr>
                        <p class="SideBarTitle">通路</p>
                        <a href="{{ url('/introducer') }}">介紹人</a><br>

                        <p class="SideBarTitle">業務</p>
                        <a href="">交班管理</a><br><br>

                        <p class="SideBarTitle">會員</p>
                        <a href="{{ url('/Player') }}">會員管理</a><br><br>
                        @endif
                    </div>

                </div>
                <div class="col-md-10">
                    @section('content')
                        這邊顯示Content
                    @show
                </div>
            </div>
        
        </div>

        

    </body>
</html>
