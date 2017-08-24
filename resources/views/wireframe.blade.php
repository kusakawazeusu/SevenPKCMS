<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{--  Initialize Bootstrap  --}}
        <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/font-awesome.min.css')}}">



        <script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
        <script src="{{asset('js/popper.min.js')}}"></script>
        <script src="{{asset('js/bootstrap.min.js')}}"></script>
        <script src="{{asset('js/sweetalert2.min.js')}}"></script>
        <script src="{{asset('js/bootstrapValidator.js')}}"></script>
           

        {{--  Load my own css  --}}
        <link rel="stylesheet" type="text/css" href="{{asset('css/wireframe.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert2.min.css')}}">

        <title>SevenPK CMS</title>

    </head>
    <body>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">7PK 管理系統</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                </ul>
                <span class="navbar-text mr-3">
                    @if(Auth::guest())
                    請登入！
                    @else
                    你好，{{ Auth::user()->Name }}！
                    @endif
                </span>
                @if(Auth::guest())
                <a href="{{ url('/login') }}" class="btn btn-primary">登入</a>
                @else
                <a href="{{ url('/logout') }}" class="btn btn-primary">登出</a>
                @endif
                
            </div>
        </nav>

        <div class="container-fluid">
        
            <div class="row mt-4">
                <div class="col-md-2">

                    <div class="jumbotron SideMenu">
                    
                        <h5 class="display-5">櫃控系統</h5>
                        <hr>
                        <p class="SideBarTitle">帳號</p>
                        <a href="">員工管理</a>
                        <br>
                        <a href="">會員儲值</a><br><br>

                        <p class="SideBarTitle">遊戲機台</p>
                        <a href="">機台機率管理</a><br>
                        <a href="">機台分區管理</a><br>
                        <a href="">機台數值表</a><br><br>

                        <p class="SideBarTitle">通路</p>
                        <a href="">介紹人</a><br>
                        <a href="">經銷商</a><br><br>

                        <p class="SideBarTitle">報表</p>
                        <a href="">報表管理</a><br>
                        <a href="">會員遊玩紀錄</a><br><br>

                        <p class="SideBarTitle">業務</p>
                        <a href="">交班管理</a><br><br>

                        <p class="SideBarTitle">會員</p>
                        <a href="">會員管理</a><br><br>

                        <p class="SideBarTitle">時間控制系統</p>
                        <a href="">時間牌型選擇</a><br>
                        <a href="">控制牌型調整</a><br><br>
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
