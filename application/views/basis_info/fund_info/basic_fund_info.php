<?php
if (!empty($form_values))
//var_dump($form_values);

?>
        <div class="contentInner">
		<div class="row-fluid">

		<div class="title">
		
			<div class="text">
			<blockquote><h3><?php echo $menus[$this->uri->segment(2)][$this->uri->segment(3)]['title']; ?></h3></blockquote>
			</div>
			<?php 
				if(validation_errors()) {
					echo '<br><br>'.validation_errors();
				}			
			?>
		</div>

		 <?php
		 $attributes = array('class' => 'form-horizontal');
		 echo form_open('/basis_info/fund_info/basic_fund_info', $attributes);
		 
		 $hidden_data = array('fno'=>$form_values['fno']);
		 echo form_hidden($hidden_data);
		 ?>

		<div class="inbox">

			<div class="tab-content">
				  <div>

				  	
						<table class="table">
						<colgroup>
							<col width="180px"></col>
							<col></col>
							<col width="180px"></col>
							<col></col>
						</colgroup>
						<thead>
							<tr>
							<th></th>
							<th>계정과목</th>
							<th>거래처</th>
							<th>사업자번호</th>
							</tr>
						</thead>

						<tbody>
							<tr>
							<th>사업자등록번호</th>
							<td>
								<input type="text" name='fund_business_number1' value="<?php echo $form_values['fund_business_number1']; ?>" style="width:50px;">
								<input type="text" name='fund_business_number2' value="<?php echo $form_values['fund_business_number2']; ?>" style="width:50px;">
								<input type="text" name='fund_business_number3' value="<?php echo $form_values['fund_business_number3']; ?>" style="width:80px;"></td>
							<th>기금명칭</th>
							<td><input type="text" name='fund_name' value="<?php echo $form_values['fund_name']; ?>" style="width:80px;"></td>
							</tr>
							<tr>
							<th>인가번호</th>
							<td><input type="text" name='permission_number' value="<?php echo $form_values['permission_number']; ?>" style="width:130px;"></td>
							<th>법인등록번호</th>
							<td><input type="text" name='corporation_number1' value="<?php echo $form_values['corporation_number1']; ?>" style="width:100px;"> <input type="text" name='corporation_number2' value="<?php echo $form_values['corporation_number2']; ?>" style="width:100px;"></td>
							</tr>
							<th>기금설립인가일</th>
							<td><input type="text" name='permission_date' id='permission_date' value="<?php echo $form_values['permission_date']; ?>" style="width:130px;"></td>
							<th>법인설립등기일</th>
							<td><input type="text" name='registration_date' id='registration_date' value="<?php echo $form_values['registration_date']; ?>" style="width:80px;"></td>
							</tr>

							<tr>
							<th>업태</th>
							<td><input type="text" name='business_status' value="<?php echo $form_values['business_status']; ?>"></td>
							<th>종목</th>
							<td><input type="text" name='business_category' value="<?php echo $form_values['business_category']; ?>"></td>
							</tr>
							<tr>
							<th>주업종코드</th>
							<td colspan="3"><input type="text" name='business_type' value="<?php echo $form_values['business_type']; ?>"></td>
							<tr>
							<th>대표자성명</th>
							<td><input type="text" name='representative_name' value="<?php echo $form_values['representative_name']; ?>" style="width:120px;"></td>
							<th>대표자 주민번호</th>
							<td><input type="text" name='representative_sn'1 value="<?php echo $form_values['representative_sn']; ?>"  style="width:100px;"> <input type="text" name='representative_sn2' value="<?php echo $form_values['representative_sn2']; ?>" style="width:100px;"></td>
							</tr>
							<tr>
							<th>기금 소재지</th>
							<td colspan="3">
								<input type="text" name='fund_zipcode' value="<?php echo $form_values['fund_zipcode']; ?>"  style="width:50px;">
								<input type="text" name='fund_zipcode' value="<?php echo $form_values['fund_zipcode']; ?>"  style="width:50px;">
								<a href="/popup/zip_search" onclick="window.open(this.href, 'pop', 'width=350, height=500, status=yes'); return false;" class="btn btn-small btn-info">우편번호</a> <br/><br/>
								<input type="text" name='fund_address1' value="<?php echo $form_values['fund_address2']; ?>"  style="width:100%;"><br/><br/>
								<input type="text" name='fund_address2' value="<?php echo $form_values['fund_address2']; ?>"  style="width:100%;">
							</td>
							</tr>
							<tr>
							<th>대표 전화번호</th>
							<td><input type="text" name='fund_tel' value="<?php echo $form_values['fund_tel']; ?>"></td>
							<th>담당자 이메일</th>
							<td><input type="text" name='fund_email' value="<?php echo $form_values['fund_email']; ?>"></td>
							</tr>
							<tr>
							<th>기본 재산</th>
							<td colspan=3><input type="text" name='paid_capital' value="<?php echo $form_values['paid_capital']; ?>"  style="width:150px;">  원</td>
							</tr>
							<tr>
							<th>관할세무서</th>
							<td><input type="text" name='tax_office' value="<?php echo $form_values['tax_office']; ?>"  style="width:150px;"></td>
							<th>결산기</th>
							<td>
								<select name='settlement_term' style="width:80px;">
								<option value='12' <?php if ($form_values['settlement_term'] == '12') echo 'selected'; ?>>12</option>
								<option value='01' <?php if ($form_values['settlement_term'] == '01') echo 'selected'; ?>>01</option>
								<option value='02' <?php if ($form_values['settlement_term'] == '02') echo 'selected'; ?>>02</option>
								<option value='06' <?php if ($form_values['settlement_term'] == '06') echo 'selected'; ?>>06</option>
								<option value='10' <?php if ($form_values['settlement_term'] == '10') echo 'selected'; ?>>10</option>
								</select>월
							</td>
							</tr>
							<tr>
							<th>국세환급금계좌신고</th>
							<td colspan="3"> 예입처 <input type="text" name='refund_bank' value="<?php echo $form_values['refund_kind']; ?>" style="width:150px;">  예금종류 <input type="text" name='refund_kind' value="<?php echo $form_values['refund_kind']; ?>" style="width:150px;">  계좌번호 <input type="text" name='refund_account' value="<?php echo $form_values['refund_account']; ?>" style="width:150px;"> </td>
							</tr>
							

						</tbody>
						</table>
				  </div>
			 
			</div>




			</div>



        </div>


		<div class="title">
		
			<div class="text">
			<blockquote><h3>사업체 정보</h3></blockquote>
			</div>
	
		</div>

		<div class="inbox">

			<div class="tab-content">
				  <div>
				  	
						<table class="table">
						<colgroup>
							<col width="180px"></col>
							<col></col>
							<col width="180px"></col>
							<col></col>
						</colgroup>
						<thead>
							<tr>
							<th></th>
							<th>계정과목</th>
							<th>거래처</th>
							<th>사업자번호</th>
							</tr>
						</thead>
						
						<tbody>
							<tr>
								<th>사업체 전화번호</th>
								<td><input type="text" name='com_tel' value="<?php echo $form_values['com_tel']; ?>"></td>
								<th>사업자등록번호</th>
								<td>
									<input type="text" name='business_number1' value="<?php echo $form_values['business_number1']; ?>" style="width:50px;">
									<input type="text" name='business_number2' value="<?php echo $form_values['business_number2']; ?>" style="width:50px;">
									<input type="text" name='business_number3' value="<?php echo $form_values['business_number3']; ?>" style="width:80px;"></td>
							</tr>
							<tr>
								<th>사업체 대표자</th>
								<td><input type="text" name='com_representative_name' value="<?php echo $form_values['com_representative_name']; ?>" style="width:150px;"> </td>
								<th>사업체 업종</th>
								<td><input type="text" name='com_business_category' value="<?php echo $form_values['com_business_category']; ?>" style="width:150px;"> </td>
							</tr>
							<tr>
							<th>자본금</th>
							<td colspan=3><input type="text" name='company_capital' value="<?php echo $form_values['company_capital']; ?>"  style="width:150px;">  원</td>


							</tr>
							<tr>
								<th>근로자수</th>
								<td><input type="text" name='workers_number' value="<?php echo $form_values['workers_number']; ?>"  style="width:150px;">  명</td>
								<th>노동조합원수</th>
								<td><input type="text" name='union_number' value="<?php echo $form_values['union_number']; ?>"  style="width:150px;">  명</td>
							</tr>

						</tbody>
						</table>
					<div class="buttonline"><input type="submit" class="btn btn-primary" value="저장" onclick='return check_form(this.form);' /></div>
				  </div>
			 
			</div>




			</div>



        </div>
        <?php echo form_close(); ?>	



<script>
//var speed=0;	//click 이벤트 발생이 안하는 에러를 없애기 위해 선언함 값, 어디선가 쓰이고 있음.
//form 값 체크, form_validation 과는 별개
function check_form(form){
	business_number1
	if (form.business_number1.value == '' || form.business_number2.value == '' || form.business_number3.value == '') {
		alert('사업자등록번호를 입력해주세요.');
		form.business_number1.focus();
		return false;
	} else if (form.fund_name.value == '') {
		alert('기금명칭을 입력해주세요.');
		form.fund_name.focus();
		return false;
	} else if (form.permission_number.value == '') {
		alert('인가번호를 입력해주세요.');
		form.permission_number.focus();
		return false;
	} else if (form.corporation_number1.value == '' || form.corporation_number2.value == '') {
		alert('법인등록번호를 입력해주세요.');
		form.corporation_number1.focus();
		return false;
	}
	form.submit();
}
</script>
