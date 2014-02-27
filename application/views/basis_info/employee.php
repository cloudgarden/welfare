<?php
//if (!empty($s_info))
	//var_dump($s_info);
	//var_dump($cate);


//var_dump($accounts);
//var_dump($s_info);
//var_dump($kind);
//var_dump($account_relation);

//var_dump($_POST);
?>


<script>
function inputbirth() {
	var sn1 = $("input[name='sn1']").attr('value');
	var sn2 = $("input[name='sn2']").attr('value');
	var year = '19';
	if (sn2.substring(0,1)>2) year = '20';
	

	var birth = year+sn1.substring(0,2) +'-'+sn1.substring(2,4) +'-'+sn1.substring(4,6);
	

	$("input[name='birth']").val(birth);
} 
</script>

<?php 
if(validation_errors() || $this->input->query_string('eno') || $this->input->query_string('mode')=='new') {
?>
	<script>
		$(document).ready(function() {
		    $('#myModal').modal('show');
		});
	</script>
<?php 
}
?>

<div class="contentInner">
	<div class="row-fluid">

		<div class="title">

			<div class="text">
				<blockquote>
					<h3>사원정보</h3>
				</blockquote>
			</div>

			<div class="appbtn">
				<li>
					<div class="iconbox"><img src="/wwf/img/icons/14x14/printer1.png" alt="프린트">
					</div>
				</li>
				<li>
					<div class="iconbox"><img src="/wwf/img/icons/14x14/upload1.png" alt="엑셀다운">
					</div>
				</li>
			</div>
			<div class="clear"></div>

		</div>
		<div class="inparea_s top_box">
			<ul>
				<li>
					<input type="radio">
					사원
				</li>
				<li>
					<input type="radio">
					복지기금내부직원
				</li>
				<li>
					<span>부서명 </span>
					<select style="width:80px;">
						<option></option>
					</select>
					<select style="width:80px;">
						<option></option>
					</select>
				</li>
				<li>
					<input type="text" style="width:80px;">
				</li>
				<li>
					<a href="#" class="btn btn-small btn-primary">검색</a>
				</li>
				<li>
					<!--a href="#myModal" role="button" class="btn btn-small btn-warning" data-toggle="modal">신규입력</a-->
					<a href="/<?php echo $this->uri->uri_string(); ?>/?mode=new" class="btn btn-small btn-warning">신규입력</a>
				</li>
				<li>
					<a href="#myModal2" role="button" class="btn btn-small btn-success" data-toggle="modal">엑셀일괄입력</a>
				</li>
			</ul>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>

		<div class="table_top">
			<div class="total">
				총 <span><?php echo $total_rows; ?></span>개의 게시물이 있습니다
			</div>
			<div class="outputlist">
				<select>
					<option>10개씩 출력</option>
				</select>
			</div>
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
				<?php foreach ($all_list as $row) { ?>
				<tr>
					<td><a href='/basis_info/employee/?eno=<?php echo $row['eno']; ?>'><?php echo $row['enumber']; ?></a></td>
					<td><?php echo $row['ename']; ?></td>
					<td><?php echo $row['department']; ?></td>
					<td><?php echo $row['position']; ?></td>
					<td><?php echo $row['hand_tel']; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>

	</div>
	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:1000px; margin-left: -500px; min-hight:680px;">
		 <?php
		 $attributes = array('class' => 'form-horizontal');
		 echo form_open('/basis_info/employee/', $attributes);
		 
		 $hidden_data = array('eno'=>$s_info['eno']);
		 echo form_hidden($hidden_data);
		 ?>
		
		
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">사원정보 입력</h3>
		</div>
		<div class="modal-body">
			<?php 
			if(validation_errors()) {
					echo validation_errors();
			}
			?>

			<table class="table">
				<colgroup>
					<col width="140px">
					</col>
					<col />
					<col width="140px">
					</col>
					<col />

				</colgroup>
				<tbody>

					<tr>
						<th class="left">사번</th>
						<td colspan="3">
						<input type="text" name='enumber' value="<?php echo $s_info['enumber']; ?>" style="width:180px;">
						<a href="#" class="btn btn-small btn-inverse">비밀번호변경</a>
						</td>
					</tr>
					<tr>
						<th class="left">이름</th>
						<td>
						<input type="text" name='ename' value="<?php echo $s_info['ename']; ?>" style="width:180px;">
						</td>
						<th>재직상태</th>
						<td>
						<select name='is_work' style="width:120px;">
							<?php echo $cate['is_work']; ?>
						</select></td>
					</tr>
					<tr>
						<th>주민번호</th>
						<td>
						<input type="text" name='sn1' id='sn1' value="<?php echo $s_info['sn1']; ?>" style="width:80px;">
						<input type="text" name='sn2' id='sn2' value="<?php echo $s_info['sn2']; ?>" style="width:80px;" onChange="inputbirth()">
						</td>
						<th>생년월일</th>
						<td>
						<input type="text" name='birth' id='birth' value="<?php echo $s_info['birth']; ?>" style="width:150px;">
						</td>
					</tr>
					<tr>
						<th>사업장</th>
						<td>
						<select name='company' style="width:120px;">
							<?php echo $cate['company']; ?>
						</select></td>
						<th>부서</th>
						<td>
						<select name='department' style="width:120px;">
							<?php echo $cate['department']; ?>
						</select></td>
					</tr>
					<tr>
						<th>직위</th>
						<td>
						<select name='position' style="width:120px;">
							<?php echo $cate['position']; ?>
						</select></td>
						<th>직급</th>
						<td>
						<select name='rank' style="width:120px;">
							<?php echo $cate['rank']; ?>
						</select></td>
					</tr>
					<tr>
						<th>직책</th>
						<td>
						<select name='duty' style="width:120px;">
							<?php echo $cate['duty']; ?>
						</select></td>
					</tr>
					<tr>
						<th>입사일자</th>
						<td>
						<input type="text" id='join_date' name='join_date' value="<?php echo $s_info['join_date']; ?>" style="width:120px;">
						</td>
						<th>퇴직일자</th>
						<td>
						<input type="text" id='retire_date' name='retire_date' value="<?php echo $s_info['retire_date']; ?>" style="width:120px;">
						</td>
					</tr>
					<tr>
						<th>주소</th>
						<td colspan="3">
						<input type="text" name='zipcode1' value="<?php echo $s_info['zipcode1']; ?>"  style="width:50px;">
						<input type="text" name='zipcode2' value="<?php echo $s_info['zipcode2']; ?>"  style="width:50px;">
						<a href="#" class="btn btn-small btn-info">우편번호</a>
						
						<br/>
						<input type="text" name='address1' value="<?php echo $s_info['address1']; ?>"  style="width:100%;">
						<br/><input type="text" name='address2' value="<?php echo $s_info['address2']; ?>"  style="width:100%;">
						</td>
					</tr>
					<tr>
					<tr>
						<th>집전화번호</th>
						<td>
						<input type="text" name='home_tel' value="<?php echo $s_info['home_tel']; ?>" style="width:150px;">
						</td>
						<th>휴대폰번호</th>
						<td>
						<input type="text" name='hand_tel' value="<?php echo $s_info['hand_tel']; ?>" style="width:150px;">
						</td>
					</tr>

					<tr>
						<th>직통번호</th>
						<td>
						<input type="text" name='direct_tel' value="<?php echo $s_info['direct_tel']; ?>" style="width:150px;">
						내선 : <input type="text" name='extension_num' value="<?php echo $s_info['extension_num']; ?>" style="width:50px;">
						</td>
						<th>이메일</th>
						<td>
						<input type="text" name='email' value="<?php echo $s_info['email']; ?>"style="width:250px;">
						</td>

					</tr>
					<tr>
						<th>사원구분</th>
						<td>
							<?php
							foreach ($cate['etype'] as $key => $value) {
							//echo $cate['etype'];
							?>
								<input type="radio" name="etype" value='<?php echo $value['title']; ?>' <?php if ($s_info['etype'] == $value['title']) echo 'checked'; ?>><?php echo $value['title']; ?>
							<?php } ?>
						</td>
						<th>노조가입</th>
						<td>
							<input type="radio" name="labor_union" value='Y' <?php if ($s_info['labor_union'] =='Y') echo 'checked'; ?>>가입
							<input type="radio" name="labor_union" value='N' <?php if ($s_info['labor_union'] == 'N') echo 'checked'; ?>>미가입
						</td>
					</tr>

					<tr>
						<th>결혼여부</th>
						<td>
							<input type="radio" name="marriage" value='Y' <?php if ($s_info['marriage']=='Y') echo 'checked'; ?>>기혼
							<input type="radio" name="marriage" value='N' <?php if ($s_info['marriage'] == 'N') echo 'checked'; ?>>미혼
						</td>
						<th>자녀수</th>
						<td>
						<input type="text" name='children' value="<?php echo $s_info['children']; ?>"style="width:80px;">
						명 </td>
					</tr>

					<tr>
						<th>거래계좌</th>
						<td colspan="3">
						<input type="text" name='bank_name' value="<?php echo $s_info['bank_name']; ?>" style="width:120px;">
						<span>은행</span>, <span>계좌번호</span>
						<input type="text" name='bank_account' value="<?php echo $s_info['bank_account']; ?>" style="width:120px;">
						<span>예금주</span>
						<input type="text" name='bank_owner' value="<?php echo $s_info['bank_owner']; ?>" style="width:120px;">
						</td>
					</tr>

				</tbody>
			</table>
		</div>
		<div class="modal-footer">
			<input type="submit" class="btn btn-primary" value="저장" />
		</div>
		<?php echo form_close(); ?>	
	</div>

	<div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:500px; margin-left: -250px; min-hight:200px;">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				×
			</button>
			<h3 id="myModalLabel">사원정보 엑셀일괄입력</h3>
		</div>
		<div class="modal-body">
			<table class="table">
				<colgroup>
					<col width="140px">
					</col>
					<col />

				</colgroup>
				<tbody>
					<tr>
						<th>엑셀파일선택</th>
						<td>
						<input type="file" style="width:140px;">
						</td>
					</tr>

				</tbody>
			</table>
			<div class="alert alert-error noMargin">
				<strong>주의!</strong> 샘플파일 다운 후 양식에 맞추어 입력 후 업로드 해주세요
			</div>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn btn-small">샘플파일다운받기</a>
			<a href="#" class="btn btn-small btn-primary">엑셀일괄입력</a>
		</div>
	</div>

</div>