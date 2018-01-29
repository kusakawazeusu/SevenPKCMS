@extends('wireframe') @Section('title','機台機率調整系統') @section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<!-- 切記這兩行伊定要放在body最下面---->
<script src="{{asset('js/Machine/MachineProbability.js')}}"></script>

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

<h1>機台機率管理</h1>
<hr>
<br>

<div class="row justify-content-between">

	<div class="col-md-2">
	</div>

    <div class="col-md-7 mr-7">
        <div class="row">
            <div class="col-md-3">
                <div class="input-group mb-2">
                    <div class="input-group-addon">顯示筆數</div>
                    <select class="form-control ShowEntries">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="ALL">全部</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-5">
                <div class="input-group mb-2">
                    <div class="input-group-addon">廠商編號</div>
                    <select id="AgentID" class="form-control">
                        <option value="-1"> -- 選擇經銷商 -- </option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="input-group mb-2">
                    <div class="input-group-addon">機台名稱</div>
                    <input type="text" class="form-control" id="MachineName" placeholder="要搜尋的名稱 ...">
                </div>
            </div>            
        </div>
	</div>

</div>

<div class="row">
	<table id="MachineProbabilityTable" class="table table-striped text-center" cellspacing="0">
		<thead>
			<tr>
				<th>功能</th>
				<th>編號</th>
				<th>經銷商</th>
				<th>機台名稱</th>
				<th>分區編號</th>
				<th>兩對機率</th>
				<th>三條機率</th>
				<th>順子機率</th>
				<th>同花機率</th>
				<th>葫蘆機率</th>
				<th>四枚機率</th>
				<th>同花順機率</th>
				<th>五枚機率</th>
				<th>同花大順機率</th>
				<th>比倍難易度(%)</th>
				<th>鬼牌出現機率(%)</th>
				<th>正四枚機率</th>
				<th>正同花大順機率</th>
				<th>正五枚機率</th>
				<th>正同花大順機率</th>
				<th>小烏龜出現機率</th>
				<th>小烏龜出現時間</th>
				<th>雙星出現機率</th>
				<th>水位數值</th>
			</tr>
		</thead>
	</table>
</div>

<div class="row justify-content-between mt-4">
	<div class="col-4">
		<div class="text-left">
			<a id="previousPage" class="btn btn-light" role="button">返回上一頁</a>
		</div>
	</div>
	<div class="col-4">
		<p class="text-center">資料共
			<font id="NumberOfEntries"></font>筆，總共
			<font id="totalPage"></font>頁，目前在第
			<font id="page"></font>頁。</p>
	</div>
	<div class="col-4">
		<div class="text-right">
			<a id="nextPage" class="btn btn-light" role="button">前往下一頁</a>
		</div>
	</div>
</div>

<!-- Machine Probability Modal -->
<div class="modal" id="MachineProbabilityModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 id="MachineProbabilityModalTitle" class="modal-title d-block mx-auto"></h4>
			</div>
			<div class="modal-body">

				<form id="MachineProbabilityForm">
					<input type="hidden" name="id" class="form-control">

					<div class="row">

						<div class="col-md-2 form-group">
							<label class="FormLabel">兩對機率</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="TwoPairs" class="form-control range" min="1" max="10" value="10">
								</div>
								<label id="TwoPairsRangeText" class="text-center" />
							</div>
						</div>

						<div class="col-md-2 form-group">
							<label class="FormLabel">三條機率</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="ThreeOfAKind" class="form-control range" min="1" max="10" value="10">
								</div>
								<label id="ThreeOfAKindRangeText" class="text-center" />
							</div>
						</div>

						<div class="col-md-2 form-group">
							<label class="FormLabel">順子機率</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="Straight" class="form-control range" min="1" max="10" value="10">
								</div>
								<label id="StraightRangeText" class="text-center" />
							</div>
						</div>

						<div class="col-md-2 form-group">
							<label class="FormLabel">同花機率</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="Flush" class="form-control range" min="1" max="10" value="10">
								</div>
								<label id="FlushRangeText" class="text-center" />
							</div>
						</div>

						<div class="col-md-2 form-group">
							<label class="FormLabel">葫蘆機率</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="FullHouse" class="form-control range" min="1" max="10" value="10">
								</div>
								<label id="FullHouseRangeText" class="text-center" />
							</div>
						</div>

						<div class="col-md-2 form-group">
							<label class="FormLabel">四枚機率</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="FourOfAKind" class="form-control range" min="1" max="10" value="10">
								</div>
								<label id="FourOfAKindRangeText" class="text-center" />
							</div>
						</div>

					</div>

					<div class="row">

						<div class="col-md-2 form-group">
							<label class="FormLabel">同花順機率</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="STRFlush" class="form-control range" min="1" max="10" value="10">
								</div>
								<label id="STRFlushRangeText" class="text-center" />
							</div>
						</div>

						<div class="col-md-2 form-group">
							<label class="FormLabel">五枚機率</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="FiveOfAKind" class="form-control range" min="1" max="10" value="10">
								</div>
								<label id="FiveOfAKindRangeText" class="text-center" />
							</div>
						</div>

						<div class="col-md-2 form-group">
							<label class="FormLabel">同花大順機率</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="RoyalFlush" class="form-control range" min="1" max="10" value="10">
								</div>
								<label id="RoyalFlushRangeText" class="text-center" />
							</div>
						</div>

						<div class="col-md-2 form-group">
							<label class="FormLabel">正四枚機率</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="RealFourOfAKind" class="form-control range" min="1" max="10" value="10">
								</div>
								<label id="RealFourOfAKindRangeText" class="text-center" />
							</div>
						</div>

						<div class="col-md-2 form-group">
							<label class="FormLabel">正同花順機率</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="RealSTRFlush" class="form-control range" min="1" max="10" value="10">
								</div>
								<label id="RealSTRFlushRangeText" class="text-center" />
							</div>
						</div>

						<div class="col-md-2 form-group">
							<label class="FormLabel">正五枚機率</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="RealFiveOfAKind" class="form-control range" min="1" max="10" value="10">
								</div>
								<label id="RealFiveOfAKindRangeText" class="text-center" />
							</div>
						</div>

					</div>

					<div class="row">

						<div class="col-md-2 form-group">
							<label class="FormLabel">正同花大順機率</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="RealRoyalFlush" class="form-control range" min="1" max="10" value="10">
								</div>
								<label id="RealRoyalFlushRangeText" class="text-center" />
							</div>
						</div>

						<div class="col-md-2 form-group">
							<label class="FormLabel">小烏龜出現機率</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="Turtle" class="form-control range" min="1" max="10" value="10">
								</div>
								<label id="TurtleRangeText" class="text-center" />
							</div>
						</div>

						<div class="col-md-2 form-group">
							<label class="FormLabel">雙星出現機率</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="DoubleStar" class="form-control range" min="1" max="10" value="10">
								</div>
								<label id="DoubleStarRangeText" class="text-center" />
							</div>
						</div>

						<div class="col-md-2 form-group">
							<label class="FormLabel">比倍難易度(%)</label>
							<div class="row">
								<div class="col-md-9 form-group">
									<input type="range" name="BonusDifficulty" class="form-control range" min="1" max="100" value="100">
								</div>
								<label id="BonusDifficultyRangeText" class="text-center" />
							</div>
						</div>

						<div class="col-md-2 form-group">
							<label class="FormLabel">鬼牌出現率(%)</label>
							<div class="row">
								<div class="col-md-9 form-group">
									<input type="range" name="WildCard" class="form-control range" min="1" max="100" value="100">
								</div>
								<label id="WildCardRangeText" class="text-center" />
							</div>
						</div>

						<div class="col-md-2 form-group">
							<label class="FormLabel">水位數值</label>
							<input id="Water" type="text" name="Water" class="form-control" readonly>
						</div>

					</div>

					<div class="row">

						<div class="col-md-12 form-group">
							<label class="FormLabel">小烏龜持續時間(壓注次數)</label>
							<div class="row">
								<div class="col-md-10 form-group">
									<input type="range" name="TurtleTime" class="form-control range" min="1" max="300" value="100">
								</div>
								<label id="TurtleTimeRangeText" class="text-center" />
							</div>
						</div>

					</div>

			</div>
			<div class="modal-footer">
				<button type="button" id="MachineProbabilitySubmit" class="btn btn-primary btn-lg mx-auto">送出</button>
			</div>
			</form>
		</div>
	</div>
</div>


@endsection