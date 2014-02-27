<?php
//var_dump($s_info);
//var_dump($all_list);
//var_dump($_POST);


?>

<div class="contentInner">
	<div class="row-fluid">

		<div class="title">

		<?php $this->load->view('/include/main_head.php', array('uri_depth'=>2)); ?>

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

		<div>
			<div>
				
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
					<tr>
						<th>구분</th>
						<td>
						<input type="text" name='kind' value='<?php echo $s_info['kind']; ?>' style="width:380px" />
						<span>콤마로 분류</span></td>
					</tr>
					<tr>
						<th>관계</th>
						<td>
						<input type="text" name='relation' value='<?php echo $s_info['relation']; ?>' style="width:380px" />
						<span>콤마로 분류</span></td>
					</tr>
					<tr>
						<th>장기근속자유형</th>
						<td>
						<input type="text" name='long_term_kind' value='<?php echo $s_info['long_term_kind']; ?>' style="width:380px" />
						<span>콤마로 분류</span></td>
					</tr>

					<tr>
						<th>지원대상설정</th>
						<td>근속 <input type="text" name='working_year' value='<?php echo $s_info['working_year']; ?>' style="width:50px" /> 년이상</td>
					</tr>
					<tr>
						<th>증여세 해당여부</th>
						<td><label><input type="radio" name='tax' value='Y' <?php if ($s_info['tax']=='Y') echo 'checked'; ?>>해당 </label>
							<label><input type="radio" name='tax' value='N' <?php if ($s_info['tax']=='N') echo 'checked'; ?>>해당없슴 </label></td>
					</tr>
					<tr>
						<th>지원기준</th>
						<td><textarea rows="4" name='support_standard' style="width:98%"><?php echo $s_info['support_standard']; ?></textarea></td>
					</tr>
					<tr>
						<th>사업목적</th>
						<td><textarea rows="4" name='business_purpose' style="width:98%"><?php echo $s_info['business_purpose']; ?></textarea></td>
					</tr>

					<tr>
						<th>도움말</th>
						<td><textarea rows="4" name='help' style="width:98%"><?php echo $s_info['help']; ?></textarea></td>
					</tr>
					<tr>
						<th>지원기준첨부파일</th>
						<td><input type="file" name='file_name' style="width:180px" /></td>
					</tr>
				</table>
				<input type="button" class="btn btn-large btn-primary" value="저장" onclick='return check_form(this.form);' />
				
				<?php echo form_close(); ?>

			</div>
		</div>

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
