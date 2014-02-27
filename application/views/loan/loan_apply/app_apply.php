<?php

$lon_schedule = $s_info['lon_schedule'];
$s_info = $s_info['basic'];
//var_dump($lon_schedule);
//var_dump($all_list);
//var_dump($_POST);
?>

<style>
.ui-autocomplete-loading {
	background: white url('/wwf/images/ui-anim_basic_16x16.gif') right center no-repeat;
}
</style>


<?php 
if(validation_errors()) {
		$validation_errors = str_replace("'코드'는(은) 필수항목입니다.", "<b>선택된 계정이 없습니다.</b>", validation_errors());
		echo '<br><br>'.$validation_errors;
		$this->load->view('validation_modal');		
}
?>

<?php
$attributes = array('name'=>'form','class' => 'form-horizontal');
echo form_open($this->uri->uri_string(), $attributes);

$hidden_data = array('rank'=> $s_info['rank'], 'duty'=> $s_info['duty'], 'home_tel'=> $s_info['home_tel'], 'direct_tel'=> $s_info['direct_tel'], 'extension_num'=> $s_info['extension_num'], 'writer_id'=> $this->session->userdata('uid'), 'writer_name'=> $this->session->userdata('uname'), 'status'=> 'N');
//$hidden_data = array('mode'=> 'update', 'ano' => $account_basic['ano'], 'pano' => $account_basic['pano'], 'has_children' => $account_basic['has_children'], 'depth' => $account_basic['depth'], 'weight' => $account_basic['weight']);
echo form_hidden($hidden_data);
?>


<div class="contentInner">
	<div class="row-fluid">

		<div class="title">

		<?php $this->load->view('/include/main_head.php', array('uri_front'=>'purpose_apply', 'uri_depth'=>3)); ?>

		</div>

		<table class="table">
			<tbody>
			<tr>
				<th width=100>도움말</th>
				<td align="right"><textarea id='help' rows="4" style="width:98%" readonly></textarea></td>
			</tr>
			<tr>
				<th>대상검색</th>
				<td>
					<?php foreach ($this->config->item('target') as $key => $value) {
						if (in_array($value, array('거래처', '기금내부전출입'))) continue;
						if ($s_info['target'] == $key) $checked = 'checked';
						else $checked = '';
						
						echo '<input type="radio" name="target" value="'.$key.'" '.$checked.'/>'.$value.' ';
					} ?>
					<input type="text" name='target_name' value='<?php echo $s_info['ename']; ?>' style="width:250px" placeholder="이름을 2글자 이상 입력하세요.">					
				</td>
			</tr>
			</tbody>
		</table>

		<table class="table">
			<tbody>
				<tr>
					<th>사번</th>
					<td><input type="text" name='enumber' value='<?php echo $s_info['enumber']; ?>' style="width:150px" readonly></td>
					<th>소속</th>
					<td><input type="text" name='company' value='<?php echo $s_info['company']; ?>' style="width:150px" readonly></td>
					<th>입사일</th>
					<td><input type="text" name='join_date' value='<?php echo $s_info['join_date']; ?>' style="width:150px" readonly></td>
				</tr>

				<tr>
					<th>이름</th>
					<td><input type="text" name='ename' value='<?php echo $s_info['ename']; ?>' style="width:150px" readonly></td>
					<th>부서</th>
					<td><input type="text" name='department' value='<?php echo $s_info['department']; ?>' style="width:150px" readonly></td>
					<th>사원구분</th>
					<td><input type="text" name='etype' value='<?php echo $s_info['etype']; ?>' style="width:150px" readonly></td>
				</tr>
				<tr>
					<th>주민번호</th>
					<td><input type="text" name='sn' value='<?php echo $s_info['sn']; ?>' style="width:150px" readonly></td>
					<th>직위</th>
					<td><input type="text" name='position' value='<?php echo $s_info['position']; ?>' style="width:150px" readonly></td>
					<th>연락처</th>
					<td><input type="text" name='hand_tel' value='<?php echo $s_info['hand_tel']; ?>' style="width:150px"></td>
				</tr>

			</tbody>
		</table>

		<table class="table">
			<tbody>
				<tr>
					<th>구분</th>
					<td>
						<select name='ano' style="width:200px">
							<?php foreach ($all_list as $row) { ?>
								<option value='<?php echo $row['ano']; ?>'><?php echo $row['title_owner']; ?></option>
							<?php } ?>
						</select>
					</td>
					<th>대부금액</th>
					<td><input type="text" name='loan_money' value='<?php echo $s_info['loan_money']; ?>' style="width:120px"></td>
				</tr>
				<tr>
					<th>신청일</th>
					<!--td colspan="3"><input type="text" name='request_date' id='request_date' style="width:120px"></td-->
					<td><input type="text" name='request_date' value='<?php echo $s_info['request_date']; ?>' style="width:120px" readonly></td>
					<th>대부개시일</th>
					<td><input type="text" name='loan_start' id='loan_start' value='<?php echo $s_info['loan_start']; ?>' style="width:120px"></td>
					
				</tr>
				<!--tr>
					<th>대부만료일</th>
					<td colspan="3"><input type="text" name='loan_end' id='loan_end' value='<?php echo $s_info['loan_end']; ?>' style="width:120px"></td>
				</tr-->
				<tr>
					<th>거치</th>
					<td colspan="3"><input type="text" name='unredeemed_month' value='<?php echo $s_info['unredeemed_month']; ?>' style="width:120px"> 개월</td>
				</tr>
				<tr>
					<th>상환방법</th>
					<td colspan="3">
					<select name='repayment_method' style="width:150px;">
						<?php foreach ($this->config->item('repayment') as $key => $value) {
							if ($s_info['repayment_method'] == $key) $selected = 'selected';
							else $selected = '';
							echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
						} ?>
					</select>
					<input type="text" name='repayment_month' value='<?php echo $s_info['repayment_month']; ?>' style="width:80px">
					개월상환
					(거치기간 제외)
					<input type="button" class="btn btn-small btn-success" value="월별상환금액보기" onclick='return loan_cal();' />
					</td>
				</tr>
				<tr>
					<th>기준이율</th>
					<td>연 <input type="text" name='year_rate' value='<?php echo $s_info['year_rate']; ?>' style="width:80px" readonly> %</td>
					<th>연체이율</th>
					<td>연 <input type="text" name='overdue_rate' value='<?php echo $s_info['overdue_rate']; ?>' style="width:80px" readonly> %</td>
				</tr>
				<tr>
					<th>담보제공방법</th>
					<td  colspan="3">
					<?php foreach ($this->config->item('collateral') as $key => $value) {
						echo '<input type="checkbox" name="'.$key.'" value="Y" />'.$value.' ';
					} ?>
					</td>
				</tr>
				<tr>
					<th>계좌번호</th>
					<td colspan="3">
					<input type="text" name='bank_name' value='<?php echo $s_info['bank_name']; ?>' style="width:80px">은행, 
					계좌번호 : <input type="text" name='bank_account' value='<?php echo $s_info['bank_account']; ?>' style="width:150px">
					예금주 : <input type="text" name='bank_owner' value='<?php echo $s_info['bank_owner']; ?>' style="width:100px">
					</td>
				</tr>
				<tr>
					<th>지원기준첨부파일</th>
					<td colspan="3">
					<input type="file" style="width:180px">
					<a href="#" class="btn btn-small btn-success">추가</a><a href="#" class="btn btn-small  btn-danger">제거</a></td>
				</tr>
				<tr>
					<th>참조</th>
					<td colspan="3"><textarea name='note' rows="4" style="width:98%"></textarea></td>
				</tr>

			</tbody>
		</table>

		<table class="table">
			<tr>
				<th>연대보증인 </th>
				<td ><label>
					<input type="radio">
					사원</label><label>
					<input type="radio">
					복지내부직원</label>
				<input type="text" name='joint_name'  style="width:250px" placeholder="이름을 2글자 이상 입력하세요.">
				<input type="text" name='joint_id' style="width:120px" readonly placeholder="">
				<a href="#" class="btn btn-small btn-inverse">추가</a></td>
			</tr>
		</table>

		<table class="table">
			<thead>
				<tr>
					<th>번호</th>
					<th>성명</th>
					<th>주민번호</th>
					<th>소속</th>
					<th>부서</th>
					<th>연락처</th>
				</tr>
			</thead>
			<tbody>
				<?php for ($i=0; $i<5; $i++) { ?>
				<tr>
					<td><?php echo $i+1; ?></td>
					<td>홍길동</td>
					<td>1</td>
					<td>본사</td>
					<td>경영지원</td>
					<td>010-111-1111</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>

		<div class="appbox">
			<input type="button" class="btn btn-large btn-success" value="<?php echo $menus[$this->uri->segment(2)][$this->uri->segment(3)]['title']; ?>" onclick='return check_form(this.form);' />
		</div>
	</div>

</div>

<?php echo form_close(); ?>






<script>
	//form 값 체크, form_validation 과는 별개
	function check_form(form){
		if (form.ename.value == '') {
			alert('대상이 없습니다.');
			return false;
		}
		else if (form.ano.value == '') {
			alert('구분을 선택해주세요.');
			return false;
		}
		form.submit();
	}

	//대출금 이자계산기 팝업
	function loan_cal(){
		var loan_money = $("input[name='loan_money']").val();
		var unredeemed_month = $("input[name='unredeemed_month']").val();
		var repayment_method = $("select[name='repayment_method'] option:selected").val();
		var repayment_month = $("input[name='repayment_month']").val();
		var loan_start = $("input[name='loan_start']").val();
		var year_rate = $("input[name='year_rate']").val();
		
		console.log(unredeemed_month);
		console.log(repayment_month);
		
		if (loan_money == '') {
			alert('대부금액을 입력해주세요.');
			$("input[name='loan_money']").focus();
			return false;
		} else if (repayment_month == '') {
			alert('상환개월을 입력해주세요.');
			$("input[name='repayment_month']").focus();
			return false;
		} else if (loan_start == '') {
			alert('대부개시일을 입력해주세요.');
			$("input[name='loan_start']").focus();
			return false;
		}
		var url = '/popup/loan_cal?loan_money='+loan_money+'&unredeemed_month='+unredeemed_month+'&repayment_method='+repayment_method+'&repayment_month='+repayment_month+'&loan_start='+loan_start+'&year_rate='+year_rate;
		window.open(url , "" , "width=800, height=670,scrollbars=yes,status=no,resizable=yes");
		//form.submit();
	}


</script>


<script>

	//구분을 선택했을 때 이율등 정보 셋팅
	$('select').live('change', function() {
		$this = $(this);
		
		//계정이 선택됐을 때 
		if ($this.attr('name') =='ano') {
			var selected_no = $this.attr('value');	// 선택된 계정 ID
			
			//계정과 관련된 값들 초기화
			$("input[name='year_rate']").val('');
			$("input[name='overdue_rate']").val('');

			//console.log(selected_no + ', ' + $("input[name='account_kind']:checked").val() + ',' + $("input[name='target']:checked").val());
			$.ajax({
				url:'/json_data/get_loan_meta', 
				data:{
					'ano': selected_no
					},
				dataType:'json',
				success:function(result){
					//alert('success');
					$("input[name='year_rate']").val(result['year_rate']);
					$("input[name='overdue_rate']").val(result['overdue_rate']);
					$("#help").text(result['help']);
					//$("#help").closest('div').append(result['help']);
				},
				error:function(msg) {
					alert('정보를 가져오는데 실패했습니다.');
					//alert(msg.responseText);
				}
			});
		}
	});


	////////////////////////////////////////////////////////////////////////////////////////////////
	//autocomplete
	//$(function() {
	$('input').live('keyup', function() {
		$this = $(this);
		//alert($this.attr('name') + ':'+ $this.attr('value'));
		
		if ($this.attr('name') =='target_name') {
			//$("input[name='target_id']").val('');
		
			//console.log('target : '+$("input[name='target']:checked").val()+', '+ $("input[name='target_name']").val());
			var query_string = 'target='+($("input[name='target']:checked").val())+'&type=purpose_business';
			
			$("input[name='target_name']").autocomplete({
				source: '/json_data/target_info?'+query_string,
				minLength: 2,
				select: function( event, ui ) {
					$("input[name='enumber']").val(ui.item.enumber );
					$("input[name='ename']").val(ui.item.ename );
					$("input[name='company']").val(ui.item.company );
					$("input[name='join_date']").val(ui.item.join_date );
					$("input[name='department']").val(ui.item.department );
					$("input[name='etype']").val(ui.item.etype );
					$("input[name='sn']").val(ui.item.sn + '-*******' );
					$("input[name='position']").val(ui.item.position );
					$("input[name='hand_tel']").val(ui.item.hand_tel );
					
					$("input[name='bank_name']").val(ui.item.bank_name );
					$("input[name='bank_account']").val(ui.item.bank_account );
					$("input[name='bank_owner']").val(ui.item.bank_owner );

					$("input[name='rank']").val(ui.item.rank );
					$("input[name='duty']").val(ui.item.duty );
					$("input[name='home_tel']").val(ui.item.home_tel );
					$("input[name='direct_tel']").val(ui.item.direct_tel );
					$("input[name='extension_num']").val(ui.item.extension_num );
					
					
					//$("input[name='join_date']").val(ui.item.join_date );
				}
				//, error: function(){ alert('11');  alert('22'); }
			});
		} else if ($this.attr('name') =='joint_name') {
		}
	});
	
	//기본선택값 셋팅
	$("select[name='ano']").trigger('change');
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	//validation_errors나 수정시 해당 값들 셋팅
	if ("<?php echo $s_info['lno']; ?>" != '') {
		$("input[name='target'][value='<?php echo $s_info['target']; ?>']").attr("checked", "checked").parents().addClass("checked");
		
		/*
		$("input[name='account_kind'][value='<?php //echo $s_info['account_kind']; ?>']").trigger('click');
		
		*/

		//alert($("input[name='debit_main_money']").val());
	}

</script>

