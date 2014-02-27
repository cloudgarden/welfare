<div class="contentInner">
	<div class="row-fluid">

		<div class="title">

			<div class="text">
				<blockquote>
					<h3>설정</h3>
				</blockquote>
			</div>

			<div class="appbtn">
				<li>
					<div class="iconbox"><img src="../wwf/img/icons/14x14/printer1.png" alt="프린트">
					</div>
				</li>
				<li>
					<div class="iconbox"><img src="../wwf/img/icons/14x14/upload1.png" alt="엑셀다운">
					</div>
				</li>
			</div>
			<div class="clear"></div>

		</div>

		<table class="table">
			<colgroup>
				<col width="100px">
				</col>
				<col>
				</col>
			</colgroup>
			<tbody>
				<tr>
					<th>결재단 설정</th>
					<td><label>
						<input type="radio" />
						전표만 결재</label><label>
						<input type="radio" />
						전표신청서 2중결재</label></td>
				</tr>
				<tr>
					<th>결재라인</th>
					<td>
					<table class="table">
						<thead>
							<tr>
								<th>결제순서</th>
								<th>이름</th>
								<th>본직책</th>
								<th>복지기금직책</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>류현진</td>
								<td>부장</td>
								<td></td>
								<td><a href="#myModal" role="button" data-toggle="modal" class="btn btn-mini btn-success">입력</a></td>

							</tr>
							<tr>
								<td>2</td>
								<td></td>
								<td></td>
								<td></td>
								<td><a href="#myModal" role="button" data-toggle="modal" class="btn btn-mini btn-success">입력</a></td>

							</tr>
							<tr>
								<td>3</td>
								<td></td>
								<td></td>
								<td></td>
								<td><a href="#myModal" role="button" data-toggle="modal" class="btn btn-mini btn-success">입력</a></td>

							</tr>

						</tbody>
					</table></td>
				</tr>

				<tr>
					<th>전결규정</th>
					<td>
					<table class="table">
						<thead>
							<tr>
								<th>결제순서</th>
								<th>결제금액</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>
								<input type="text" >
								이하</td>

							</tr>

							<tr>
								<td>2</td>
								<td>
								<input type="text" >
								이하</td>

							</tr>
							<tr>
								<td>3</td>
								<td>
								<input type="text" >
								이하</td>

							</tr>

						</tbody>
					</table></td>
				</tr>

			</tbody>

		</table>

		<div class="appbox">
			<a href="#" class="btn btn-large btn-success">저장</a>
		</div>
	</div>

</div>

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 500px; margin-left: -250px; display: none;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			×
		</button>
		<h3 id="myModalLabel">위임자 선택</h3>
	</div>
	<div class="modal-body">

		<table class="table">
			<tr>
				<td>
				<input type="">
				</td>
				<td>
				<input type="">
				</td>
			</tr>
			<tr>
				<th>복지기금 직책</th>
				<td>
				<input type="">
				</td>
			</tr>
		</table>

	</div>
	<div class="modal-footer">
		<button class="btn btn-primary">
			저장
		</button>
	</div>
</div>