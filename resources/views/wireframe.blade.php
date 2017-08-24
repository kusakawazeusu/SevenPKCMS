<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{--  Initialize Bootstrap  --}}
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <script src="js/jquery-3.2.1.slim.min.js"></script>
        <script src="js/popper.js"></script>
        <script src="js/bootstrap.min.js"></script>

        <title>SevenPK CMS</title>

    </head>
    <body>

        @section('Navbar')
        Navbar
        @show

        <div class="container">
        
            <div class="row">
                <div class="col-md-3">
                    @section('SideMenu')
                        這邊顯示SideMenu
                    @show
                </div>
                <div class="col-md-9">
                    @section('Content')
                        這邊顯示Content
                    @show
                </div>
            </div>
        
        </div>

        

    </body>
</html>
