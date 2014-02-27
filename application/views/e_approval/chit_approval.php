<div class="contentInner">
	<div class="row-fluid">

		<div class="title">

			<div class="text">
				<blockquote>
					<h3>전자결재</h3>
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
			<tr>
				<th>기간</th>
				<td>
				<input type="text" style="width:80px" />
				~
				<input type="text" style="width:80px" />
				</td>
				<td><label>
					<input type="radio" />
					일주일간</label><label>
					<input type="radio" />
					보름간</label><label>
					<input type="radio" />
					한달간</label></td>
				<th>작성자 </th>
				<td><select></select></td>
				<td><a href="#" class="btn btn-small btn-inverse">검색</a></td>
			</tr>
		</table>
		<div class="table_top">
			<div class="total">
				<span style="color:#5411b0; font-size:14px;">■ : 전결 </span><span style="color:#0073ff;  font-size:14px;"> ■ : 결제완료 </span><span style="color:#de151d;  font-size:14px;"> ■ : 위임결재</span><span style="color:#ccc;  font-size:14px;"> ■  : 미결</span>
			</div>
			<div class="outputlist">
				<select style="margin-top:10px;">
					<option>10개씩 출력</option>
				</select>
				<select style="margin-top:10px;">
					<option>단위</option>
				</select>
				<a href="#" class="btn btn-small btn-inverse">엑셀추출</a>
			</div>
		</div>

		<table class="table">
			<colgroup>
				<col width="100px">
				</col>
				<col>
				</col>
				<col>
				</col>
				<col>
				</col>
				<col>
				</col>
				<col>
				</col>
				<col>
				</col>
				<col width="100px">
				</col>
				<col width="100px">
				</col>

			</colgroup>
			<thead>
				<tr>
					<th>계정</th>
					<th>적요</th>
					<th>작성자</th>
					<th>요청일시</th>
					<th>증빙일자</th>
					<th>전표종류</th>
					<th>전표번호</th>
					<th>결재상태</th>
					<th>
					<input type="checkbox" />
					모두선택</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>경조비</th>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><a href="#myModal2" role="button" data-toggle="modal">20130807-A-A-0001</a></td>
					<td style="text-align:center;"><span style="color:#5411b0; font-size:14px;">■</span><span style="color:#0073ff;  font-size:14px;">■</span><span style="color:#de151d;  font-size:14px;">■</span><span style="color:#ccc;  font-size:14px;">■</span></td>
					<td>
					<input type="checkbox" />
					선택</td>
				</tr>
				<tr>
					<th>의료비</th>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="text-align:center;"><span style="color:#5411b0; font-size:14px;">■</span><span style="color:#0073ff;  font-size:14px;">■</span><span style="color:#de151d;  font-size:14px;">■</span><span style="color:#ccc;  font-size:14px;">■</span></td>
					<td>
					<input type="checkbox" />
					선택</td>
				</tr>

				<tr>
					<th>건강진단</th>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="text-align:center;"><span style="color:#5411b0; font-size:14px;">■</span><span style="color:#0073ff;  font-size:14px;">■</span><span style="color:#de151d;  font-size:14px;">■</span><span style="color:#ccc;  font-size:14px;">■</span></td>
					<td>
					<input type="checkbox" />
					선택</td>
				</tr>
				<tr>
					<th>학자금지원</th>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="text-align:center;"><span style="color:#5411b0; font-size:14px;">■</span><span style="color:#0073ff;  font-size:14px;">■</span><span style="color:#de151d;  font-size:14px;">■</span><span style="color:#ccc;  font-size:14px;">■</span></td>
					<td>
					<input type="checkbox" />
					선택</td>
				</tr>
				<tr>
					<th>계</th>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>

			</tbody>

		</table>

		<div class="table_top">
			<div class="total">
				<div class="pagination pagination-centered" style="margin-top:-20px;">
					<ul>
						<li>
							<a href="#">←</a>
						</li>
						<li class="active">
							<a href="#">10</a>
						</li>
						<li class="disabled">
							<a href="#">...</a>
						</li>
						<li>
							<a href="#">20</a>
						</li>
						<li>
							<a href="#">→</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="outputlist">
				<a href="#" class="btn btn-small btn-danger">일괄결재</a>
			</div>
		</div>

	</div>

</div>

<div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 1000px; margin-left: -500px; display: none;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			×
		</button>
		<h3 id="myModalLabel">복합분개설정</h3>
	</div>
	<div class="modal-body">
		<div class="table_top">
			<div class="total table_left">
				<span class="table_left" style="margin-right:30px;">전표번호 :  20130612-A-A-0001</span>
				<span class="table_left" style="margin-right:30px;">소속 : 목적사업계획</span>
				<span class="table_left" style="margin-right:30px;">분류 : 수입전표</span>
				<span class="table_left" style="margin-right:30px;">작성자 : 홍길동</span>
			</div>
			<div class="total table_right">
				<div class="approval_in">
					<table style="width:320px;" class="table">
						<tr>
							<th>과장</th>
							<th>부장</th>
							<th>이사</th>
							<th>대표이사</th>
						</tr>
						<tr>
							<td><a href="#" class="btn btn-large btn-info">결재</a></td>
							<td><a href="#" class="btn btn-large btn-info">결재</a></td>
							<td><a href="#" class="btn btn-large btn-success">미결</a></td>
							<td><a href="#" class="btn btn-large btn-success">미결</a></td>
						</tr>
					</table>
				</div>
				<div class="clear"></div>
			</div>

			<div class="clear"></div>
			<div class="table_left" style="width:49%;">
				<table class="table">
					<thead>
						<tr>
							<th colspan="4">차변</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th>계정과목</th>
							<th>금액</th>
							<th>거래처</th>
							<th>적요</th>
						</tr>
						<tr>
							<td>의료비</td>
							<td>2</td>
							<td>99</td>
							<td></td>
						</tr>
						<tr>
							<td>건강진단</td>
							<td>6</td>
							<td>20</td>
							<td></td>
						</tr>
					</tbody>
					<thead>
						<tr>
							<th colspan="4">차변합계 : \1,000,000</th>
						</tr>
					</thead>

				</table>

			</div>

			<div class="table_right" style="width:49%;">
				<table class="table">
					<thead>
						<tr>
							<th colspan="4">대변</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th>계정과목</th>
							<th>금액</th>
							<th>거래처</th>
							<th>적요</th>
						</tr>
						<tr>
							<td>계정과목</td>
							<td>2</td>
							<td>99</td>
							<td></td>
						</tr>
						<tr>
							<td>계정과목</td>
							<td>6</td>
							<td>20</td>
							<td></td>
						</tr>
					</tbody>
					<thead>
						<tr>
							<th colspan="4">대변합계 : \1,000,000</th>
						</tr>
					</thead>

				</table>

			</div>
			<div class="clear"></div>
			<table class="table">

				<tbody>
					<tr>
						<th width="100px;">참조</th><td>
						<input type="text" style="width:100%">
						</td>
					</tr>
					<tr>
						<th>부장</th><td>
						<input type="text" style="width:100%">
						</td>
					</tr>
					<tr>
						<th>이사</th><td>
						<input type="text" style="width:100%">
						</td>
					</tr>
					<tr>
						<th>대표이사</th><td>
						<input type="text" style="width:100%">
						</td>
					</tr>

				</tbody>
			</table>
		</div>

	</div>
	<div class="modal-footer">
		<button class="btn btn-primary">
			승인
		</button>
		<button class="btn btn-primary">
			전표
		</button>
		<button class="btn btn-primary">
			반려
		</button>
	</div>
</div>