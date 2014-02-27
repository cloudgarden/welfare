<?php
//var_dump($s_info);
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

$hidden_data = array('ano'=> $s_info['ano'], 'rank'=> '', 'duty'=> '', 'home_tel'=> '', 'direct_tel'=> '', 'extension_num'=> '', 'writer_id'=> $this->session->userdata('uid'), 'writer_name'=> $this->session->userdata('uname'), 'status'=> 'N');
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
				<th width=100>지원기준</th>
				<td align="right"><?php echo $all_list['support_standard']; ?></td>
			</tr>
			<tr>
				<th>대상검색</th>
				<td>
					<?php foreach ($this->config->item('target') as $key => $value) {
						if (in_array($value, array('거래처', '기금내부전출입'))) continue;
						echo '<input type="radio" name="target" value="'.$key.'" />'.$value.' ';
					} ?>
					<input type="text" name='target_name' style="width:250px" placeholder="이름이나 거래처명을 2글자 이상 입력하세요.">					
				</td>
			</tr>
			</tbody>
		</table>

		<table class="table">
			<tbody>
				<tr>
					<th>사번</th>
					<td><input type="text" name='enumber' style="width:150px" readonly></td>
					<th>소속</th>
					<td><input type="text" name='company' style="width:150px" readonly></td>
					<th>입사일</th>
					<td><input type="text" name='join_date' style="width:150px" readonly></td>
				</tr>

				<tr>
					<th>이름</th>
					<td><input type="text" name='ename' style="width:150px" readonly></td>
					<th>부서</th>
					<td><input type="text" name='department' style="width:150px" readonly></td>
					<th>사원구분</th>
					<td><input type="text" name='etype' style="width:150px" readonly></td>
				</tr>
				<tr>
					<th>주민번호</th>
					<td><input type="text" name='sn' style="width:150px" readonly></td>
					<th>직위</th>
					<td><input type="text" name='position' style="width:150px" readonly></td>
					<th>연락처</th>
					<td><input type="text" name='hand_tel' style="width:150px"></td>
				</tr>

			</tbody>
		</table>

	</div>
	<div>
