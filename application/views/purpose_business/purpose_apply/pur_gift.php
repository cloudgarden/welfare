<?php
//var_dump($s_info);
//var_dump($all_list);
//var_dump($_POST);
?>

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

<div class="contentInner">
	<div class="row-fluid">

		<div class="title">

			<div class="text">
			<blockquote><h3><?php echo $menus[$this->uri->segment(2)][$this->uri->segment(3)]['title']; ?></h3></blockquote>
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
		<table class="table">
			<tbody>
				<tr>
					<th>구분</th>
					<td>
						<select name='kind' style="width:200px">
							<?php foreach ($all_list['kind'] as $kind) { ?>
								<option value='<?php echo $kind; ?>'><?php echo $kind; ?></option>
							<?php } ?>
						</select>
					</td>
					<th>지급일</th>
					<td>
					<input type="text" name='occur_date' id='occur_date' style="width:120px">
					</td>
				</tr>
				<tr>
					<th>대상인원</th>
					<td><input type="text" name='recipient_number' style="width:120px">명</td>
					<th>수량</th>
					<td><input type="text" name='order_number' style="width:120px">개</td>
				</tr>
				<tr>
					<th>품목</th>
					<td><input type="text" name='order_product' style="width:120px"></td>
					<th>단가</th>
					<td><input type="text" name='order_price' style="width:120px">원</td>
				</tr>
				<tr>
					<th>신청일</th>
					<td>
					<input type="text" name='request_date' id='request_date' style="width:120px">
					</td>
					<th>신청금액</th>
					<td>
					<input type="text" name='request_money' style="width:120px">
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

	</div>

	</div>
		<div class="appbox">
			<input type="button" class="btn btn-large btn-success" value="<?php echo $menus[$this->uri->segment(2)][$this->uri->segment(3)]['title']; ?> 신청" onclick='return check_form(this.form);' />
		</div>
	</div>

</div>

<?php echo form_close(); ?>

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

