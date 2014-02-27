<?php
//var_dump($s_info);
//var_dump($all_accounts);
//var_dump($s_info['account_sum_list']);
//var_dump($_POST);

$ol = $this->config->item('ol');

//echo $s_info['stage'];
?>


<div>
	<div class="modal-header">
		<h3 id="myModalLabel"><?php echo $s_info['basic']['stage']; ?>기 <?php echo $s_info['basic']['data_name']; ?>  입력
		(<?php echo $s_info['basic']['start_date']; ?> ~ <?php echo $s_info['basic']['end_date']; ?>)</h3>
		<br>
	</div>
	
	<?php
	$attributes = array('name'=>'form','class' => 'form-horizontal');
	echo form_open($this->uri->uri_string(), $attributes);

	//$hidden_data = array('prev_start_date'=> '', 'prev_end_date'=> '', 'start_date'=> $s_info['start_date'], 'end_date'=> $s_info['end_date'], );	//전기의 검색기간
	$hidden_data = array('kind'=> $s_info['basic']['kind'], 'mode'=> $s_info['basic']['mode'], 'bno'=> $s_info['basic']['bno'], 'stage'=> $s_info['basic']['stage'], 'data_name'=> $s_info['basic']['data_name'], 'start_date'=> $s_info['basic']['start_date'], 'end_date'=> $s_info['basic']['end_date'], 'writer'=>'작성자', 'input_method'=>'수기입력');	//전기의 검색기간
		echo form_hidden($hidden_data);
	?>
	
	<div class="modal-body">
		 
		<table class="table">
			<?php
				if (in_array($s_info['basic']['kind'], array('jaemusang', 'sonik'))) {
			?>
				<thead>
					<tr>
						<th width="210px;">계정과목</th>
						<th>전기</th>
						<th nowrap="nowrap" colspan="3" >당기(2013. 3. 31)실적</th>
						<th nowrap="nowrap" colspan="2" >전기대비 증감현황</th>
					</tr>
				</thead>
				<tr>
					<th> 　 </th>
					<th>금 액 </th>
					<th>목적사업회계 </th>
					<th>수익사업회계 </th>
					<th>금 액 </th>
					<th>증 감 액 </th>
					<th>증감율 </th>
				</tr>
		
			<?php
				} else if ($s_info['basic']['kind'] == 'earned_surplus') {
			?>
				<thead>
					<tr>
						<th colspan="2">1. 이익잉여금처분계산서 </th>
						<th colspan="2">2. 결손금처리계산서 </th>
					</tr>
				</thead>

				<tr>
					<th width="250px">과 목 </th>
					<th>금 액 </th>
					<th width="250px">과 목 </th>
					<th>금 액 </th>
				</tr>
			
			<?php
				}
			?>
				<?php
				if ($s_info['basic']['kind'] == 'jaemusang') {
					//재무상태표
					if ($s_info['basic']['mode'] == 'insert') {
						show_account_in_jaemusang(1, $all_accounts, $s_info['prev_account_sum_list'], $s_info['account_sum_list'], $ol, $s_info['basic']['kind']);
					} else if ($s_info['basic']['mode'] == 'udpate') {
						//echo 'update';
						foreach ($all_accounts as $entry) {
							//var_dump($entry);
							$first_weight = '';
							show_account_in_inputbalance($entry['account_no'], $entry, $ol, $first_weight, $s_info['basic']['kind']);
						}
					}
				} else if ($s_info['basic']['kind'] == 'sonik') {
					//손익계산서
					$first_weight = 1;	//보여줄 계정목록중 첫번째 depth인 것, 부모와 상관없이 순서를 붙이기 위해 필요
					if ($s_info['basic']['mode'] == 'insert') {
						
						show_account_in_sonik(2, $all_accounts, $s_info['prev_account_sum_list'], $s_info['account_sum_list'], $ol, $first_weight, $s_info['basic']['kind']);
					} else if ($s_info['basic']['mode'] == 'udpate') {
						//echo 'update';
						foreach ($all_accounts as $entry) {
							//var_dump($entry);
							show_account_in_inputbalance($entry['account_no'], $entry, $ol, $first_weight, $s_info['basic']['kind']);
							
						}
					}
				} else if ($s_info['basic']['kind'] == 'earned_surplus') {
					//이익잉여금처분계산서
					$first_weight = 0;	//보여줄 계정목록중 첫번째 depth인 것, 부모와 상관없이 순서를 붙이기 위해 필요
					show_account_in_earned_surplus($earned_surplus_acc, $all_accounts, $ol, $first_weight);
				}
				
				?>
	
		</table>		


	</div>
	<div class="modal-footer">
		<input type="button" class="btn btn-primary" value="저장" onclick='return check_form(this.form);' />
	</div>
	<?php echo form_close(); ?>
	
	
</div>


<script>
	//var speed=0;	//click 이벤트 발생이 안하는 에러를 없애기 위해 선언함 값, 어디선가 쓰이고 있음.
	//form 값 체크, form_validation 과는 별개
	function check_form(form){
		if (form.start_date.value == '') {
			alert('검색기간을 입력해주세요.');
			return false;
		}
		form.submit();
	}
</script>