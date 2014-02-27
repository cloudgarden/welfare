<?php
//var_dump($all_list);
var_dump($s_info);
//var_dump($s_info['account_info']);
//var_dump($_POST);

$target = $this->config->item('target');
$account_group = $this->config->item('account_group');
?>

<div>
	<div class="modal-header">
		<h3 id="myModalLabel">전표입력</h3>
	</div>
	
	<?php 
	if(validation_errors()) {
			$validation_errors = str_replace("'코드'는(은) 필수항목입니다.", "<b>선택된 계정이 없습니다.</b>", validation_errors());
			echo '<br><br>'.$validation_errors;
			$this->load->view('validation_modal');		
	}
	?>

	<?php
	$attributes = array('name'=>'form','class' => 'form-horizontal');
	echo form_open('/accounting/acc_billing/basic', $attributes);
	//echo form_open('/popup/statement', $attributes);

	$hidden_data = array('pno'=> $s_info['pno'], 'account_kind'=> $s_info['basic']['account_kind'], 'target'=> $s_info['basic']['target'], 'target_id'=> $s_info['basic']['target_id'], 'target_name'=> $s_info['basic']['target_name'], 'account_no'=> $s_info['basic']['account_no'], 'account_group'=> $s_info['basic']['account_group'], 'debit_main'=> $s_info['purpose_business']['ano'], 'debit_main_money'=> $s_info['purpose_business']['request_money'], 'dc'=> $s_info['account_info']['dc'], 'sum_money'=> 0, 'writer_id'=> $this->session->userdata('uid'), 'writer_name'=> $this->session->userdata('uname'), 'status'=> 'N');
	//$hidden_data = array('use'=> '', 'dc'=> '', 'sum_money'=> 0, 'writer_id'=> $this->session->userdata('uid'), 'writer_name'=> $this->session->userdata('uname'));
	echo form_hidden($hidden_data);
	?>


	<div class="modal-body">

		<table class="table">
			<colgroup>
				<col width="80px">
				</col>
				<col>
				</col>
			</colgroup>
			<tbody>
				<tr>
					<th>분류</th>
					<td>지출</td>
					<th>날짜</th>
					<td>
					<input type="text" name='input_date' id='input_date' style="width:120px" value="<?php echo $s_info['basic']['input_date']; ?>">
					</td>
				</tr>
				<tr>
					<th>대상</th>
					<td colspan="3">
						<?php echo $target[$s_info['basic']['target']]; ?>, 
						이름 : <?php echo $s_info['basic']['target_name']; ?>, 
						사번 : <?php echo $s_info['basic']['target_id']; ?>
					</td>
				</tr>
				<tr>
					<th>계정</th>
					<td colspan="3">
						<?php echo $s_info['account_info']['title_owner']; ?>, 
						차대변 구분 : <?php echo $s_info['account_info']['dc_name']; ?>
					</td>
				</tr>
				<tr>
					<th>적요</th>
					<td colspan="3">
						<ul>
							<li>
							<select name='account_summary' style="width:150px">
								<?php foreach ($s_info['account_info']['summary'] as $row) { ?>
									<option value='<?php echo $row; ?>'><?php echo $row; ?></option>
								<?php } ?> 
									<option value="직접입력">직접입력</option>
							</select>
							</li>
							<li>
							<div id='account_summary_direct'>
								<input type="text" name='account_summary_direct' style="width:200px;"  /> 
							</div>
							</li>
						</ul>
					</td>
				</tr>

				
				<tr>
					<th>부가세</th>
					<td colspan="3">							
							<input type="radio" name="tax" value='N' checked >무시
					</td>
				</tr>

				<tr>
					<th>회계분류</th>
					<td colspan="3">
						<?php echo $account_group[$s_info['basic']['account_group']]; ?>
					</td>
				</tr>
				<tr>
					<th>참고사항</th>
					<td  colspan="3"><textarea name='note' style="width:100%" ><?php echo $s_info['basic']['note']; ?></textarea>
					</td>
				</tr>

			</tbody>
		</table>



		<div class="table_top">
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
							<th>계정과목</th><th>금액</th><th>거래처</th><th>적요</th>
						</tr>
						<tr>
							<td><?php echo $s_info['account_info']['title_owner']; ?></td>
							<td><?php echo $s_info['purpose_business']['request_money']; ?></td>
							<td><label name='customer_title'><?php echo $s_info['basic']['target_name']; ?></label></td>
							<td><label name='summary_title'><?php echo $s_info['basic']['account_summary']; ?></label></td>
						</tr>
						<tr>
							<td>
								<select name='debit_sub' style="width:180px">
									<?php
										$i=0;
										foreach ($s_info['account_info']['bundle'] as $row) {
									?>
										<option value='<?php echo $row; ?>'><?php echo $s_info['account_info']['bundle_name'][$i]; ?></option>
									<?php
										$i++;
										}
									?> 
								</select>
							</td>
							<td><input type="text" name='debit_sub_money' style="width:80px"  value="<?php echo $s_info['statement']['debit_sub']['money']; ?>"/></td>
							<td><label name='customer_title'><?php echo $s_info['basic']['target_name']; ?></label></td>
							<td><label name='summary_title'><?php echo $s_info['basic']['account_summary']; ?></label></td>
						</tr>
					</tbody>
					<thead>
						<tr>
							<th colspan="4"><div style="margin:0 auto; width:180px;" ><label><b>차변합계 : </b></label><label name='debit_total'></label></div></th>
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
							<th>계정과목</th><th>금액</th><th>거래처</th><th>적요</th>
						</tr>
						<tr>
							<td>
								<select name='credit_main' style="width:180px">
									<?php
										$i=0;
										foreach ($s_info['account_info']['contra'] as $row) {
									?>
										<option value='<?php echo $row; ?>'><?php echo $s_info['account_info']['contra_name'][$i]; ?></option>
									<?php
										$i++;
										}
									?> 
									
								</select>
								
							</td>
							<td><input type="text" name='credit_main_money' style="width:80px" value="<?php echo $s_info['statement']['credit_main']['money']; ?>" /></td>
							<td><label name='customer_title'><?php echo $s_info['basic']['target_name']; ?></label></td>
							<td><label name='summary_title'><?php echo $s_info['basic']['account_summary']; ?></td>
						</tr>
						<tr>
							<td>
								<select name='credit_sub' style="width:180px">
									<?php
										$i=0;
										foreach ($s_info['account_info']['contra_bundle'] as $row) {
									?>
										<option value='<?php echo $row; ?>'><?php echo $s_info['account_info']['contra_bundle_name'][$i]; ?></option>
									<?php
										$i++;
										}
									?> 
									
								</select>
							</td>
							<td><input type="text" name='credit_sub_money' style="width:80px" value="<?php echo $s_info['statement']['credit_sub']['money']; ?>" /></td>
							<td><label name='customer_title'><?php echo $s_info['basic']['target_name']; ?></label></td>
							<td><label name='summary_title'><?php echo $s_info['basic']['account_summary']; ?></td>
						</tr>
					</tbody>
					<thead>
						<tr>
							<th colspan="4"><div style="margin:0 auto; width:180px;" ><label><b>대변합계 : </b></label><label name='credit_total'></label></div></th>
						</tr>
					</thead>

				</table>

			</div>

		</div>


	</div>
	<div class="modal-footer">
		<input type="button" class="btn btn-large btn-primary" value="저장" onclick='return check_form(this.form);' />
	</div>

	<?php echo form_close(); ?>
	
</div>

<script>
//var speed=0;	//click 이벤트 발생이 안하는 에러를 없애기 위해 선언함 값, 어디선가 쓰이고 있음.

//hidden text box 숨기기
$('#account_summary_direct').hide();

function check_form(form){
	//차변/대변 여부
	var dc=$("input[name='dc']").val();
	var reverse_dc=(dc=='debit') ? 'credit' : 'debit'; //선택된 계정의 반대 차변/대변
	
	var dc_name = (dc=='debit') ? '차변' : '대변';
	var reverse_dc_name = (dc=='debit') ? '대변' : '차변';

	if ($("input[name='account_kind']").val() == '') {
		alert('분류가 선택되지 않았습니다. 신청서에 문제가 있읍니다.');
		return false;
	} else if ($("input[name='input_date']").val() == '') {
		alert('날짜를 입력해주세요.');
		$("input[name='input_date']").focus();
		return false;
	} else if ($("input[name='target']").val() == '') {
		alert('대상이 선택되지 않았습니다. 신청서에 문제가 있읍니다.');
		return false;
	} else if ($("input[name='target_name']").val() == '') {
		alert('이름이 선택되지 않았습니다. 신청서에 문제가 있읍니다.');
		return false;
	} else if ($("input[name='target_id']").val() == '') {
		alert('사번이 선택되지 않았습니다. 신청서에 문제가 있읍니다.');
		return false;
	} else if ($("input[name='account_no']").val() == '') {
		alert('계정이 선택되지 않았습니다. 신청서에 문제가 있읍니다.');
		return false;
	} else if (dc == '') {
		alert('계정 정보가 올바르지 않습니다. 신청서에 문제가 있읍니다.');
		return false;
	} else if (typeof($("input[name='tax']:checked").val()) == 'undefined') {
		alert('부가세 여부를 선택해주세요.');
		$("input[name='tax']").focus();
		return false;
	} else if ($("input[name='account_group']").val() == 'undefined') {
		alert('회계분류를 선택해주세요.');
		$("input[name='account_group']").focus();
		return false;
	} else if (dc != '') {
		//차변/대변 구분이 있으면 각 변에 해당(or 메인) 계정과 상대계정 이 있어야 함.
		if (typeof($("select[name='"+dc+"_main'] option:selected")) == 'undefined') {
			alert(dc_name+'의 계정과목을 선택해주세요.');
			$("select[name='"+dc+"_main']").focus();
			return false;
		} else if ($("input[name='"+dc+"_main_money']").val() == '') {
			alert(dc_name+'의 금액을 을 입력해주세요.');
			$("input[name='"+dc+"_main_money']").focus();
			return false;
		}
		
		//차변/대변의 금액 입력 check
		else if (typeof($("select[name='"+reverse_dc+"_main'] option:selected")) == 'undefined') {
			alert(reverse_dc_name+'의 계정과목을 선택해주세요.');
			$("select[name='"+reverse_dc+"_main']").focus();
			return false;
		} else if ($("input[name='"+reverse_dc+"_main_money']").val() == '') {
			alert(reverse_dc_name+'의 금액을 을 입력해주세요.');
			$("input[name='"+reverse_dc+"_main_money']").focus();
			return false;
		}
		
		//묶음계정, 상대묶음 계정이 있을 시 금액 입력 확인
		else if ($("select[name='"+dc+"_sub'] option").size() > 0 && $("input[name='"+dc+"_sub_money']").val() == '') {
			alert(dc_name+'의 묶음계정 금액을 을 입력해주세요.');
			$("input[name='"+dc+"_sub_money']").focus();
			return false;
		} else if ($("select[name='"+reverse_dc+"_sub'] option").size() > 0 && $("input[name='"+reverse_dc+"_sub_money']").val() == '') {
			alert(reverse_dc_name+'의 상대묶음 계정 금액을 을 입력해주세요.');
			$("input[name='"+reverse_dc+"_sub_money']").focus();
			return false;
		}

		//묶음계정, 상대묶음 계정의 금액이 있을 시 계정이 있는지 확인 입력 확인
		else if (($("input[name='"+dc+"_sub_money']").val() != '' || $("input[name='"+dc+"_sub_money']").val() == '0') && $("select[name='"+dc+"_sub'] option").size() == 0) {
			alert(dc_name+'의 묶음계정이 없습니다. 금액을 삭제해주세요.');
			$("input[name='"+dc+"_sub_money']").focus();
			return false;
		} else if (($("input[name='"+reverse_dc+"_sub_money']").val() != ''|| $("input[name='"+reverse_dc+"_sub_money']").val() == '0') && $("select[name='"+reverse_dc+"_sub'] option").size() == 0) {
			alert(reverse_dc_name+'의 상대묶음이 없습니다. 금액을 삭제해주세요.');
			$("input[name='"+reverse_dc+"_sub_money']").focus();
			return false;
		}
		
		//금액에 콤마 없애기
		form.debit_main_money.value=$("input[name='debit_main_money']").number(true).val();
		form.debit_sub_money.value=$("input[name='debit_sub_money']").number(true).val();
		form.credit_main_money.value=$("input[name='credit_main_money']").number(true).val();
		form.credit_sub_money.value=$("input[name='credit_sub_money']").number(true).val();

		//차변/대변 합계가 같아야 함.
		var debit_sub_money = credit_sub_money = 0;
		if ($("input[name='debit_sub_money']").val() != '') 
			debit_sub_money = parseInt($("input[name='debit_sub_money']").val());
		if ($("input[name='credit_sub_money']").val() != '')
			credit_sub_money = parseInt($("input[name='credit_sub_money']").val());
		
		var debit_total = parseInt($("input[name='debit_main_money']").val())+debit_sub_money;
		var credit_total = parseInt($("input[name='credit_main_money']").val())+credit_sub_money;
		//console.log('debit_total: '+debit_total);
		//console.log('credit_total: '+credit_total);
		
		if (debit_total != credit_total) {
			alert('차변과 대변의 합계는 같아야 합니다.');
			$("input[name='"+dc+"_main_money']").focus();
			return false;
		}
		$("input[name='sum_money']").val(debit_total);
	} else {

		
	}
	
	alert('전표를 생성합니다. 입력한 전표의 내용은 "전표조회"에서 확인할 수 있습니다.');
	form.submit();
}
//계정을 선택했을 때 계정의 정보를 가져와서 셋팅
$('select').live('change', function() {
	$this = $(this);
	
	if ($this.attr('name') =='account_summary') {
		//직접입력 text box 보이기, 숨기기
		if ($this.attr('value')=='직접입력') {
			$('#account_summary_direct').show();
			$("label[name='summary_title']").text($("input[name='account_summary_direct']").val());	//차/대변 적요 표시
		} else {
			$('#account_summary_direct').hide();
			$("input[name='account_summary_direct']").val('');
			$("label[name='summary_title']").text($("select[name='account_summary']").val());	//차변/대변 적요 표시
		}
	}
});


//적요의 기본값 셋팅
$("select[name='account_summary']").trigger('change');



</script>		
