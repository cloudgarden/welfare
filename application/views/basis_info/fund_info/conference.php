<?php
//var_dump($tab_menus);
?>
        <div class="contentInner">
		<div class="row-fluid">

		<div class="title">
		
		<div class="text">
		<blockquote><h3><?php echo $menus[$this -> uri -> segment(2)][$this -> uri -> segment(3)]['title']; ?></h3></blockquote>
		</div>

		<div class="appbtn">
			<li><div class="iconbox"><img src="/wwf/img/icons/14x14/printer1.png" alt="프린트"></div> </li>
			<li><div class="iconbox"><img src="/wwf/img/icons/14x14/upload1.png" alt="엑셀다운"></div></li>
		</div>
		<div class="clear"></div>
		
		</div>

		<div class="inbox">

		 
			<div class="tab-content">
				<table class="table">
					<caption>현직</caption>

					<thead>
						<tr>
						<th>직책</th>
						<th>사번</th>
						<th>성명</th>
						<th>회사직위로</th>
						<th>연락처</th>
						<th>위원선임일</th>
						</tr>
					</thead>
					<tbody>
						<tr>
						<th>
							<select name='' style="width:100px">
								<option value='근로자위원'>근로자위원</option>
								<option value='근로자위원'>회사측위원</option>
							</select>
						</th>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						</tr>
						<th>
							<select name='' style="width:100px">
								<option value='근로자위원'>근로자위원</option>
								<option value='근로자위원'>회사측위원</option>
							</select>
						</th>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						</tr>

					</tbody>
					</table>
				<div class="buttonline"><a href="#" id="uimodal" class="button lButton bSky">추가</a></div>

					<table class="table">
					<caption>협의회개최현황</caption>
					<thead>
						<tr>
						<th>no</th>
						<th>제목</th>
						<th>작성일</th>
						<th>첨부파일</th>
						</tr>
					</thead>
					<tbody>
						<tr>
						<td>1</td>
						<td></td>
						<td></td>
						<td></td>
						</tr>
					</tbody>
					</table>
				<div class="buttonline"><a href="#" id="uimodal" class="button lButton bSky">입력</a></div>

			</div>




			</div>



        </div>


			<!--   입력폼 -->

			<div class="row-fluid">
			<div class="inparea">
				<ul>
					<li><span>부서명 </span><select><option></option></select></li>
					<li><select><option></option></select></li>
					<li><div class="span12"><input type="text"></li>
					<li><a href="#" class="btn btn-small btn-info">검색</a></li>
				</ul>
			</div>

			<table class="table">
				<thead>
					<tr>
					<th>사번</th>
					<th>성명</th>
					<th>부서</th>
					<th>직위</th>
					<th>연락처</th>
					</tr>
				</thead>
				<tbody>
					<tr>
					<td>11</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					</tr>
					<tr>
					<td>11</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					</tr>

				</tbody>
			</table>

			<div class="inparea">
				<ul>
					<li><span>부임일</span> <select><option></option></select></li>
					<li><span>퇴임일</span>  <select><option></option></select></li>
					<li><a href="#" class="btn btn-small btn-info">수정</a> <a href="#" class="btn btn-small btn-success">삭제</a></li>
				</ul>
			</div>

			</div>



			<!--/   입력폼 -->

