@extends('wireframe')

@section('title','活動訊息廣播')

@section('content')

<script>

    function checkLength(element)
    {
        if( element.value.length > 15 )
        {
            element.value = element.value.substr(0, 15);
        }
    }

</script>


<h1>活動訊息廣播</h1>
<hr>
<br>

<form method="POST" action="{{ route('SetMsg') }}">
    {{ csrf_field() }}

    <div class="row">
        <div class="col-md-8">
            <label class="FormLabel">訊息一</label>
            <input onkeyup="checkLength(this)" type="text" name="Msg1" class="form-control" required>
        </div>
        <div class="col-md-2">
                <label class="FormLabel">開始時間</label>
                <input type="time" name="Msg1StartTime" class="form-control" required>
        </div>
        <div class="col-md-2">
                <label class="FormLabel">結束時間</label>
                <input type="time" name="Msg1EndTime" class="form-control" required>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-8">
            <label class="FormLabel">訊息二</label>
            <input onkeyup="checkLength(this)" type="text" name="Msg2" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="FormLabel">開始時間</label>
            <input type="time" name="Msg2StartTime" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="FormLabel">結束時間</label>
            <input type="time" name="Msg2EndTime" class="form-control" required>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-8">
            <label class="FormLabel">訊息三</label>
            <input onkeyup="checkLength(this)" type="text" name="Msg3" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="FormLabel">開始時間</label>
            <input type="time" name="Msg3StartTime" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="FormLabel">結束時間</label>
            <input type="time" name="Msg3EndTime" class="form-control" required>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-8">
            <label class="FormLabel">訊息四</label>
            <input onkeyup="checkLength(this)" type="text" name="Msg4" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="FormLabel">開始時間</label>
            <input type="time" name="Msg4StartTime" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="FormLabel">結束時間</label>
            <input type="time" name="Msg4EndTime" class="form-control" required>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-8">
            <label class="FormLabel">訊息五</label>
            <input onkeyup="checkLength(this)" type="text" name="Msg5" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="FormLabel">開始時間</label>
            <input type="time" name="Msg5StartTime" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="FormLabel">結束時間</label>
            <input type="time" name="Msg5EndTime" class="form-control" required>
        </div>
    </div>
    <br>
    <input type="submit" class="btn btn-primary btn-lg"></input>
</form>


@endsection