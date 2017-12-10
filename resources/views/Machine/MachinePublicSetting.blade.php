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
		<div class="col-md-3" id="JokerWinText">
			@if($JokerWin == 1) 是 @else 否 @endif
		</div>
		<div calss="col-md-3">
			<button type="button" class="btn btn-info" onclick="OpenUpdateJokerWinModal('JokerWin', '遇見鬼牌是否一定中獎')">更改</button>
		</div>
	</div>
</li>
<br>
<li class="list-group-item list-group-item-warning">
	<div class="row">
		<div class="col-md-9">基礎機率設定</div>
		<div calss="col-md-3">
			<button type="button" class="btn btn-info" onclick="OpenUpdateProbabilityModal('Probility', '基礎機率設定')">檢視</button>
		</div>
	</div>
</li>

<!-- Machine Public Setting JokerWin Modal -->
<div class="modal" id="MachinePublicSettingJokerWinModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 id="MachinePublicSettingModalTitle" class="modal-title d-block mx-auto"></h4>
			</div>
			<div class="modal-body">

				<form id="MachinePublicSettingForm">
					<div style="text-align: center; font-size: 30px;">
						<label class="radio-inline">
							<input type="radio" name="JokerWin" value=1 class="isChange">是</label>
						<label class="radio-inline">
							<input type="radio" name="JokerWin" value=0 class="isChange">否</label>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="MachinePublicSettingJokerWinSubmit" class="btn btn-primary btn-lg mx-auto">送出</button>
			</div>
			</form>
		</div>
	</div>
</div>

<!-- Machine Public Setting Probility Modal -->
<div class="modal" id="MachinePublicSettingProbilityModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 id="MachinePublicSettingProbabilityModalTitle" class="modal-title d-block mx-auto"></h4>
				<button type="button" class="btn" id="Unlock">解除鎖定</button>
			</div>
			<div class="modal-body">

				<form id="MachineProbabilityForm">
					<input type="hidden" name="id" class="form-control">

					<div class="row">

						<div class="col-md-6 form-group">
							<label class="FormLabel">兩對水位值</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="TwoPairs" class="form-control range needChange checkWater" min="0" max="25" value="20" step="0.001">
								</div>
								<label id="TwoPairsRangeText" class="text-center" />
							</div>
						</div>

						<div class="col-md-6 form-group">
							<label class="FormLabel">三條水位值</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="ThreeOfAKind" class="form-control range needChange checkWater" min="0" max="20" value="20" step="0.001">
								</div>
								<label id="ThreeOfAKindRangeText" class="text-center" />
							</div>
						</div>

					</div>

					<div class="row">

						<div class="col-md-6 form-group">
							<label class="FormLabel">順子水位值</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="Straight" class="form-control range needChange checkWater" min="0" max="20" value="20" step="0.001">
								</div>
								<label id="StraightRangeText" class="text-center" />
							</div>
						</div>

						<div class="col-md-6 form-group">
							<label class="FormLabel">同花水位值</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="Flush" class="form-control range needChange checkWater" min="0" max="20" value="20" step="0.001">
								</div>
								<label id="FlushRangeText" class="text-center" />
							</div>
						</div>

					</div>

					<div class="row">

						<div class="col-md-6 form-group">
							<label class="FormLabel">葫蘆水位值</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="FullHouse" class="form-control range needChange checkWater" min="0" max="20" value="20" step="0.001">
								</div>
								<label id="FullHouseRangeText" class="text-center" />
							</div>
						</div>

						<div class="col-md-6 form-group">
							<label class="FormLabel">四枚水位值</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="FourOfAKind" class="form-control range needChange checkWater" min="0" max="20" value="20" step="0.001">
								</div>
								<label id="FourOfAKindRangeText" class="text-center" />
							</div>
						</div>

					</div>

					<div class="row">

						<div class="col-md-6 form-group">
							<label class="FormLabel">同花順水位值</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="STRFlush" class="form-control range needChange checkWater" min="0" max="20" value="20" step="0.001">
								</div>
								<label id="STRFlushRangeText" class="text-center" />
							</div>
						</div>

						<div class="col-md-6 form-group">
							<label class="FormLabel">五枚水位值</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="FiveOfAKind" class="form-control range needChange checkWater" min="0" max="20" value="20" step="0.001">
								</div>
								<label id="FiveOfAKindRangeText" class="text-center" />
							</div>
						</div>

					</div>

					<div class="row">

						<div class="col-md-6 form-group">
							<label class="FormLabel">同花大順水位值</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="RoyalFlush" class="form-control range needChange checkWater" min="0" max="20" value="20" step="0.001"
									 readonly>
								</div>
								<label id="RoyalFlushRangeText" class="text-center" />
							</div>
						</div>

						<div class="col-md-6 form-group">
							<label class="FormLabel">雙星出現水位值</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="DoubleStar" class="form-control range needChange checkWater" min="0" max="100" value="0" step="0.001">
								</div>
								<label id="DoubleStarRangeText" class="text-center" />
							</div>
						</div>


					</div>

					<div class="col-md-6 form-group">
						<label class="FormLabel">水位數值</label>
						<input id="Water" type="text" name="Water" class="form-control" readonly>
					</div>

			</div>
			<div class="modal-footer">
				<button type="button" id="MachineProbabilitySubmit" class="btn btn-primary btn-lg mx-auto" value=0>關閉</button>
			</div>
			</form>
		</div>
	</div>
</div>

<br> @endsection