<?php
//var_dump($s_info);
//var_dump($all_list);
//var_dump($s_info['account_sum_list']);
//var_dump($_POST);

//echo $s_info['stage'];
?>

<div class="contentInner">
	<div class="row-fluid">

		<div class="title">

			<div class="text">
				<blockquote>
					<h3>기초자료입력</h3>
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

		<div class="clear"></div>

		<?php
		$attributes = array('name'=>'form','class' => 'form-horizontal');
		echo form_open($this->uri->uri_string(), $attributes);

		$hidden_data = array('mode'=>'update', 'writer'=>'작성자');
		echo form_hidden($hidden_data);
		?>

		<!-- 고유목적 사업비 1-->
		고유목적 사업비 1
		<table class="table">
			<thead>
				<tr>
					<th rowspan="2">사업연도</th>
					<th rowspan="2">손금산입액</th>
					<th rowspan="2">직전 사업연도까지 고유목적사업 지출액</th>
					<th rowspan="2">해당 사업연도 고유목적사업 지출액</th>
					<th colspan="2">잔액</th>
				</tr>
				<tr>
					<th>5년이내분</th>
					<th>5년경과분</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$style1 = 'style="width:120px; text-align:right;"';
					$style2 = 'style="width:60px; text-align:right;"';
					$total = array(0,0,0,0,0);
					$i=0;
					foreach ($all_list[1] as $stage => $arr) {
						$i++;
						$start_year = substr($s_info['start_date_list'][$i], 0, 4);
						
						$total[0] += $arr['money1'];
						$total[1] += $arr['money2'];
						$total[2] += $arr['money3'];
						$total[3] += $arr['balance1'];
						$total[4] += $arr['balance2'];
						
						$money1 = ($arr['money1'] == 0) ? '' : number_format($arr['money1']);
						$money2 = ($arr['money2'] == 0) ? '' : number_format($arr['money2']);
						$money3 = ($arr['money3'] == 0) ? '' : number_format($arr['money3']);
						$balance1 = ($arr['balance1'] == 0) ? '' : number_format($arr['balance1']);
						$balance2 = ($arr['balance2'] == 0) ? '' : number_format($arr['balance2']);
				?>
				<tr>
					<td><?php echo $start_year; ?>년</td>
					<td><input type='text' name='money1[<?php echo $arr['bno']; ?>]' value='<?php echo $money1; ?>' <?php echo $style1; ?>></td>
					<td><input type='text' name='money2[<?php echo $arr['bno']; ?>]' value='<?php echo $money2; ?>' <?php echo $style1; ?>></td>
					<td><input type='text' name='money3[<?php echo $arr['bno']; ?>]' value='<?php echo $money3; ?>' <?php echo $style1; ?>></td>
					<td><input type='text' name='balance1[<?php echo $arr['bno']; ?>]' value='<?php echo $balance1; ?>' <?php echo $style2; ?>></td>
					<td><input type='text' name='balance2[<?php echo $arr['bno']; ?>]' value='<?php echo $balance2; ?>' <?php echo $style2; ?>></td>
					</tr>
				<?php } ?>
				<tr>
					<td>계</td>
					<?php
						foreach ($total as $value) {
							$value = number_format($value);
							echo '<td style="text-align:right;">'.$value.'  </td>';
						}
					?>
				</tr>

			</tbody>
		</table>

		<!-- 고유목적 사업비 2-->
		고유목적 사업비 2
		<table class="table">
			<thead>
				<tr>
					<th>사업연도</th>
					<th>출연금</th>
					<th>고유목적사업비2 설정액</th>
					<th>해당 사업연도 고유목적사업비2 지출액</th>
					<th>잔액</th>
				</tr>
			</thead>
			<tbody>
				
				<?php
					$style1 = 'style="width:120px; text-align:right;"';
					$style2 = 'style="width:60px; text-align:right;"';
					$total = array(0,0,0,0);
					$i=0;
					foreach ($all_list[2] as $stage => $arr) {
						$i++;
						$start_year = substr($s_info['start_date_list'][$i], 0, 4);
						
						$total[0] += $arr['money1'];
						$total[1] += $arr['money2'];
						$total[2] += $arr['money3'];
						$total[3] += $arr['balance1'];
						
						$money1 = ($arr['money1'] == 0) ? '' : number_format($arr['money1']);
						$money2 = ($arr['money2'] == 0) ? '' : number_format($arr['money2']);
						$money3 = ($arr['money3'] == 0) ? '' : number_format($arr['money3']);
						$balance1 = ($arr['balance1'] == 0) ? '' : number_format($arr['balance1']);
				?>
				<tr>
					<td><?php echo $start_year; ?>년</td>
					<td><input type='text' name='money1[<?php echo $arr['bno']; ?>]' value='<?php echo $money1; ?>' <?php echo $style1; ?>></td>
					<td><input type='text' name='money2[<?php echo $arr['bno']; ?>]' value='<?php echo $money2; ?>' <?php echo $style1; ?>></td>
					<td><input type='text' name='money3[<?php echo $arr['bno']; ?>]' value='<?php echo $money3; ?>' <?php echo $style1; ?>></td>
					<td><input type='text' name='balance1[<?php echo $arr['bno']; ?>]' value='<?php echo $balance1; ?>' <?php echo $style2; ?>></td>
					</tr>
				<?php } ?>
				<tr>
					<td>계</td>
					<?php
						foreach ($total as $value) {
							$value = number_format($value);
							echo '<td style="text-align:right;">'.$value.'  </td>';
						}
					?>
				</tr>

			</tbody>
		</table>
		<div class="appbox">
			<input type="button" class="btn btn-large btn-primary" value="저장" onclick='return check_form(this.form);' />
			<br><br><br><br>
		</div>
		<?php echo form_close(); ?>
		
		
	</div>
</div>


<script>
	//form 값 체크, form_validation 과는 별개
	function check_form(form){
		/*
		if (form.start_date.value == '') {
			alert('검색기간을 입력해주세요.');
			$("input[name='start_date']").focus();
			return false;
		}
		*/
		form.submit();
	}
	
</script>

