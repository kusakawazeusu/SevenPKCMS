@extends('wireframe')

@section('title','時間牌型選擇系統')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}" /><!-- 切記這兩行伊定要放在body最下面---->
<script>
	
	var showNum = 5;
	
	var entries = {{ $numOfEntries }};  // 紀錄總共有幾筆data
</script>
<script src="{{asset('js/CardType.js')}}"></script>

<div>
	<h1>控制牌型</h1>
	<hr>
	<br>
	
</div>
@endsection