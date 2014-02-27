<?php
//var_dump($s_info);
//var_dump($all_list);
//var_dump($_POST);


?>

<div class="contentInner">
	<div class="row-fluid">

		<div class="title">

		<?php $this->load->view('/include/main_head.php', array('uri_front'=>'purpose_apply', 'uri_depth'=>2)); ?>

		</div>
		
		<table class="table">
			<colgroup>
				<col width="180px">
				</col>
				<col />
			</colgroup>
			<tr>
				<th>분류</th>
				<td>
					
					<?php
						foreach ($all_list as $row) {
							if ($s_info['ano'] == $row['ano']) $checked = 'checked';
							else $checked = '';
							
							$onclick = "location.href='/".$this->uri->uri_string()."?ano=".$row['ano']."'";
					?>
						<label><input type="radio" name="ano" value="<?php echo $row['ano']; ?>" onclick=<?php echo $onclick; ?> <?php echo $checked; ?>><?php echo $row['title_owner']; ?> </label>
					<?php } ?>

					
				</td>
			</tr>
		</table>

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
		
		$hidden_data = array('ano'=> $s_info['ano']);
		echo form_hidden($hidden_data);
		?>

		<table class="table">
			<colgroup>
				<col width="180px">
				</col>
				<col />
			</colgroup>
			<tr>
				<th>대부금액 한도</th>
				<td colspan="3"><input type="text" name='max_money' value='<?php echo $s_info['max_money']; ?>' style="width:180px" /> 원</td>
			</tr>
			<tr>
				<th>대부대상설정</th>
				<td colspan="3">근속 <input type="text" name='working_year' value='<?php echo $s_info['working_year']; ?>' style="width:50px" /> 년이상</td>
			</tr>
			<tr>
				<th>급여공제기준일</th>
				<td colspan="3"><input type="text" name='pay_day' value='<?php echo $s_info['pay_day']; ?>' style="width:50px" /> 일 
					(*급여공제기준일은 입력/수정시 대부금 설정의 모든 분류에 동시 적용됩니다.)
				</td>
			</tr>
			<tr>
				<th>기준이율</th>
				<td>연 <input type="text" name='year_rate' value='<?php echo $s_info['year_rate']; ?>' style="width:100px" /> %</td>
				<th>연체이율</th>
				<td>연 <input type="text" name='overdue_rate' value='<?php echo $s_info['overdue_rate']; ?>' style="width:100px" /> %</td>

			</tr>
			<tr>
				<th>도움말</th>
				<td colspan="3"><textarea rows="4" name='help' style="width:98%"><?php echo $s_info['help']; ?></textarea></td>
			</tr>
			<tr>
				<th>지원기준첨부파일</th>
				<td  colspan="3"><input type="file" name='file_name' style="width:180px" /></td>
			</tr>
		</table>
		<p style="text-align:center">
			<input type="button" class="btn btn-large btn-primary" value="저장" onclick='return check_form(this.form);' />
		</p>
				
		<?php echo form_close(); ?>

	</div>

</div>


<script>
	//form 값 체크, form_validation 과는 별개
	function check_form(form){
		if (form.ano.value == '') {
			alert('분류를 선택해주세요.');
			return false;
		}
		form.submit();
	}


</script>
