@extends('wireframe')

@section('title','報表系統')

@section('navbar-item')

<li class="nav-item">
    <a href="{{ url('/report') }}" class="nav-link">當班營業報表</a>
</li>
<li class="nav-item">
    <a href="{{ url('/dayreport') }}" class="nav-link">單日營業報表</a>
</li>
<li class="nav-item">
    <a href="{{ url('/monthreport') }}" class="nav-link">月營業報表</a>
</li>
<li class="nav-item">
    <a href="{{ url('/playerbetreport') }}" class="nav-link">會員消費明細報表</a>
</li>
<li class="nav-item">
    <a href="{{ url('/report') }}" class="nav-link">獎金明細報表</a>
</li>
@endsection

@section('content')

<h1>報表系統 - @yield('report-title')</h1>
<hr>
<br>
@section('report')
@show

@endsection

