@extends('wireframe') @Section('title','機台公共設定') @section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<!-- 切記這兩行伊定要放在body最下面 -->
<script src="{{asset('js/Deposit.js')}}"></script>
<script src="{{asset('js/Machine/MachinePublicSetting.js')}}"></script>

<style>
	.modal-header,
	h4,
	.close {
		background-color: #36648b;
		color: white !important;
		text-align: center;
		font-size: 30px;
	}

	.modal-body {
		background-color: #f9f9f9;
	}

	.modal-footer {
		background-color: #f9f9f9;
	}
	/* Set gray background color and 100% height */

	.sidenav {
		background-color: #f1f1f1;
		height: 100%;
	}
</style>
<script>
	var operatorID = {{ Auth::user()->id }};

</script>

<h1>機台公共設定</h1>
<hr>
<br>

<li class="list-group-item list-group-item-warning">
	<div class="row">
		<div class="col-md-6">遇見鬼牌是否一定中獎</div>
		<div class="col-md-3">
			@if($JokerWin == 1) 是 @else 否 @endif
		</div>
		<div calss="col-md-3">
			<button type="button" class="btn btn-info" onclick="OpenUpdateProbabilityModal('JokerWin', '遇見鬼牌是否一定中獎')">更改</button>
		</div>
	</div>
</li>

<!-- Machine Public Setting Modal -->
<div class="modal" id="MachinePublicSettingModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 id="MachinePublicSettingModalTitle" class="modal-title d-block mx-auto"></h4>
			</div>
			<div class="modal-body">

				<form id="MachinePublicSettingForm">
					<div style="text-align: center; font-size: 30px;">
						<label class="radio-inline">
							<input type="radio" name="JokerWin" value=1>是</label>
						<label class="radio-inline">
							<input type="radio" name="JokerWin" value=0>否</label>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="MachinePublicSettingSubmit" class="btn btn-primary btn-lg mx-auto">送出</button>
			</div>
			</form>
		</div>
	</div>
</div>

<br> @endsection