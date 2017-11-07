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

    $(document).ready(function() {

        $("#SubBtn").click(function(event){
            event.preventDefault();
            
            if( $("input[name='Msg1StartTime']").val() > $("input[name='Msg1EndTime']").val() )
            {
                swal({
                    type: 'error',
                    title: '設定錯誤',
                    text: '訊息一時間設定錯誤！'
                });
            }
            else if( $("input[name='Msg5StartTime']").val() > $("input[name='Msg5EndTime']").val() )
            {
                swal({
                    type: 'error',
                    title: '設定錯誤',
                    text: '訊息五時間設定錯誤！'
                });
            }
            else if( $("input[name='Msg4StartTime']").val() > $("input[name='Msg4EndTime']").val() )
            {
                swal({
                    type: 'error',
                    title: '設定錯誤',
                    text: '訊息四時間設定錯誤！'
                });
            }
            else if( $("input[name='Msg3StartTime']").val() > $("input[name='Msg3EndTime']").val() )
            {
                swal({
                    type: 'error',
                    title: '設定錯誤',
                    text: '訊息三時間設定錯誤！'
                });
            }
            else if( $("input[name='Msg2StartTime']").val() > $("input[name='Msg2EndTime']").val() )
            {
                swal({
                    type: 'error',
                    title: '設定錯誤',
                    text: '訊息二時間設定錯誤！'
                });
            }
            else
            {
                swal({
                    type: 'success',
                    title: '設定成功',
                    showConfirmButton: false
                });
                $("#MsgForm").submit();
            }
        });

    });

</script>


<h1>活動訊息廣播</h1>
<hr>
<br>

<form id="MsgForm" method="POST" action="{{ route('SetMsg') }}">
    {{ csrf_field() }}

    <div class="row">
        <div class="col-md-8">
            <label class="FormLabel">訊息一</label>
            <input value="{{ $msg->msg1 }}" onkeyup="checkLength(this)" type="text" name="Msg1" class="form-control" required>
        </div>
        <div class="col-md-2">
                <label class="FormLabel">開始時間</label>
                <input value="{{ $msg->starttime1 }}" type="time" name="Msg1StartTime" class="form-control" required>
        </div>
        <div class="col-md-2">
                <label class="FormLabel">結束時間</label>
                <input value="{{ $msg->endtime1 }}" type="time" name="Msg1EndTime" class="form-control" required>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-8">
            <label class="FormLabel">訊息二</label>
            <input value="{{ $msg->msg2 }}" onkeyup="checkLength(this)" type="text" name="Msg2" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="FormLabel">開始時間</label>
            <input value="{{ $msg->starttime2 }}" type="time" name="Msg2StartTime" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="FormLabel">結束時間</label>
            <input value="{{ $msg->endtime2 }}" type="time" name="Msg2EndTime" class="form-control" required>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-8">
            <label class="FormLabel">訊息三</label>
            <input value="{{ $msg->msg3 }}" onkeyup="checkLength(this)" type="text" name="Msg3" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="FormLabel">開始時間</label>
            <input value="{{ $msg->starttime3 }}" type="time" name="Msg3StartTime" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="FormLabel">結束時間</label>
            <input value="{{ $msg->endtime3 }}" type="time" name="Msg3EndTime" class="form-control" required>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-8">
            <label class="FormLabel">訊息四</label>
            <input value="{{ $msg->msg4 }}" onkeyup="checkLength(this)" type="text" name="Msg4" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="FormLabel">開始時間</label>
            <input value="{{ $msg->starttime4 }}" type="time" name="Msg4StartTime" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="FormLabel">結束時間</label>
            <input value="{{ $msg->endtime4 }}" type="time" name="Msg4EndTime" class="form-control" required>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-8">
            <label class="FormLabel">訊息五</label>
            <input value="{{ $msg->msg5 }}" onkeyup="checkLength(this)" type="text" name="Msg5" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="FormLabel">開始時間</label>
            <input value="{{ $msg->starttime5 }}" type="time" name="Msg5StartTime" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="FormLabel">結束時間</label>
            <input value="{{ $msg->endtime5 }}" type="time" name="Msg5EndTime" class="form-control" required>
        </div>
    </div>
    <br>
    <input type="submit" id="SubBtn" class="btn btn-primary btn-lg"></input>
</form>


@endsection