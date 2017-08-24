@extends('wireframe')

@section('content')
<script src ="{{asset('js/Machine/Monitor.js')}}"></script>
    <div class="row">
        @for ($i = 0; $i <= $counters; $i++)
            <div class="col-lg-1">
                <legend class="text-center">{{$i}}</legend>
                <div class="text-center machine">
                    <a id="a{{$i}}">
                    <img id="{{$i}}"src="{{asset('img/machine/online.png')}}" width="100%" data-toggle="tooltip" data-placement="bottom" title="WTF">
                    </a>
                </div>
            </div>
        @endfor
    </div>
@endsection