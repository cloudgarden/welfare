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

			<?php 
			if(validation_errors()) {
					$validation_errors = str_replace("'코드'는(은) 필수항목입니다.", "<b>선택된 계정이 없습니다.</b>", validation_errors());
					echo '<br><br>'.$validation_errors;
					$this->load->view('validation_modal');		
			}
			?>

		<?php
		$attributes = array('name'=>'account_form','class' => 'form-horizontal');
		echo form_open('/accounting/acc_billing/basic', $attributes);

		$hidden_data = array('use'=> '', 'dc'=> '', 'sum_money'=> 0, 'writer_id'=> $this->session->userdata('uid'), 'writer_name'=> $this->session->userdata('uname'), 'status'=> 'N');
		//$hidden_data = array('mode'=> 'update', 'ano' => $account_basic['ano'], 'pano' => $account_basic['pano'], 'has_children' => $account_basic['has_children'], 'depth' => $account_basic['depth'], 'weight' => $account_basic['weight']);
		echo form_hidden($hidden_data);
		?>

		<div class="table_top" >		
			<div class="outputlist" style="margin-bottom:20px;">
			<a href="#" class="btn btn-large btn-success">복합분개마법사</a>
			<a href="#" class="btn btn-large btn-primary">고유목적사업비 설정마법사</a>
			<a href="#" class="btn btn-large btn-warning">기금출연</a>
			</div>
		</div>

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
					<td>
						
						<ul>
						<?php foreach ($this->config->item('account_kind') as $key => $value) {
							echo '<li><input type="radio" name="account_kind" value="'.$key.'" />'.$value.'</li>  ';
						} ?>
						</ul>
					</td>
					<th>날짜</th>
					<td>
					<input type="text" name='input_date' id='input_date' style="width:120px" value="<?php echo $s_info['basic']['input_date']; ?>">
					</td>
				</tr>
				<tr>
					<th>대상</th>
					<td colspan="3">
					<div style="float:left;" >
					<ul class="tabs">
						<?php foreach ($this->config->item('target') as $key => $value) {
							echo '<input type="radio" name="target" value="'.$key.'" />'.$value.' ';
						} ?>
					</ul>
					</div>
					<div style="float:left;" >&nbsp;&nbsp;</div>
					<div style="float:left;">
						<input type="text" name='target_name' style="width:250px" value="<?php echo $s_info['basic']['target_name']; ?>" placeholder="이름이나 거래처명을 2글자 이상 입력하세요.">
						<input type="text" name='target_id' style="width:120px" value="<?php echo $s_info['basic']['target_id']; ?>" readonly placeholder=""> 
					</div>
					</td>
				</tr>
				<tr>
					<th>계정</th>
					<td colspan="3">
						<ul>
							<li><select name='account_no' style="width:220px"></select></li>
							<li>차대변 구분 : <input type="text" name='dc_name' style="width:50px" readonly  /></li>
						</ul>
					</td>
				</tr>
				<tr>
					<th>적요</th>
					<td colspan="3">
						<ul>
							<li>
							<select name='account_summary' style="width:150px"></select>
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
							<input type="radio" name="tax" value='Y' <?php if ($s_info['basic']['tax'] == 'Y') echo 'checked'; ?>>적용
							<input type="radio" name="tax" value='N' <?php if ($s_info['basic']['tax'] == 'N') echo 'checked'; ?>>무시
							<input type="radio" name="tax" value='F' <?php if ($s_info['basic']['tax'] == 'F') echo 'checked'; ?>>면세
					</td>
				</tr>

				<tr>
					<th>회계분류</th>
					<td colspan="3">
						<?php foreach ($this->config->item('account_group') as $key => $value) {
							echo '<input type="radio" name="account_group" value="'.$key.'" />'.$value.' ';
						} ?>
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
							<td><select name='debit_main' style="width:180px"></select></td>
							<td><input type="text" name='debit_main_money' style="width:80px"  value="<?php echo $s_info['statement']['debit_main']['money']; ?>"/></td>
							<td><label name='customer_title'><?php echo $s_info['basic']['target_name']; ?></label></td>
							<td><label name='summary_title'><?php echo $s_info['basic']['account_summary']; ?></label></td>
						</tr>
						<tr>
							<td><select name='debit_sub' style="width:180px"></select></td>
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
							<td><select name='credit_main' style="width:180px"></select></td>
							<td><input type="text" name='credit_main_money' style="width:80px" value="<?php echo $s_info['statement']['credit_main']['money']; ?>" /></td>
							<td><label name='customer_title'><?php echo $s_info['basic']['target_name']; ?></label></td>
							<td><label name='summary_title'><?php echo $s_info['basic']['account_summary']; ?></td>
						</tr>
						<tr>
							<td><select name='credit_sub' style="width:180px"></select></td>
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
		
		<div id="payment">
			<?php
				$payment_cnt = 1;
				if ($s_info['payment']['payment_method']) $payment_cnt = count($s_info['payment']['payment_method']);
				
				$payment_method = $payment_money = $payment_kind = $payment_date = $user_file = '';
				for ($i=0; $i<$payment_cnt; $i++) {
					if ($s_info['payment']['payment_method']) {
						$payment_method = $s_info['payment']['payment_method'][$i];
						$payment_money = $s_info['payment']['payment_money'][$i];
						$payment_kind = $s_info['payment']['payment_kind'][$i];
						$payment_date = $s_info['payment']['payment_date'][$i];
						//$user_file = $s_info['payment']['user_file'][$i];
					}
			?>
			<table class="table" id="payment_inner">
				<tr>
				<th>결제방법</th>
				<td>
					<select name="payment_method[]" style="width:180px">
						<?php foreach ($this->config->item('payment_method') as $key => $value) { ?>
							<option value="<?php echo $key; ?>" <?php if ($key==$payment_method) echo 'selected'; ?>><?php echo $value; ?></option>
						<?php } ?>
					</select>
					<input name="plus_table" type="button" class="btn btn-small btn-inverse" value="+">
				</td>
				<th>금액</th>
				<td><input type="text" name="payment_money[]" value='<?php echo $payment_money; ?>' style="width:100px" /></td>
				</tr>
				<tr>
				<th>증빙</th>
				<td>
					<select name="payment_kind[]" style="width:180px">
					<?php foreach ($this->config->item('payment_kind') as $key => $value) { ?>
						<option value="<?php echo $key; ?>" <?php if ($key==$payment_kind) echo 'selected'; ?>><?php echo $value; ?></option>
					<?php } ?>
					</select>
				</td>
				<th>증빙일자</th>
				<td><input type="text" name="payment_date[]" id="payment_date1" value='<?php echo $payment_date; ?>' style="width:120px" /> </a></td>
				</tr>
				<tr>
				<th>증빙파일</th>
				<td  colspan="3">
					<?php if ($user_file) { ?>
						<div>
						<input name="file_remove" type="button" value="X"><input name='user_file_org[]' value='<?php echo $user_file; ?>' style="width:180px">
						</div>
					<?php } ?>
					<input type="file" name="user_file[]" style="width:180px">
					</td>
				</tr>
			</table>
			<?php
				}
			?>
		</div>

		<div class="appbox">
			<input type="button" class="btn btn-large btn-success" value="저장" onclick='return check_form(this.form);' />
			<br><br><br><br>
			<!--input type="submit" class="btn btn-large btn-success" value="저장" /><a href="#" class="btn btn-large btn-primary">동일거래처 계속</a><a href="#" class="btn btn-large btn-warning">동일거래 계속</a><a href="#" class="btn btn-large btn-inverse">자주쓰는거래 등록</a-->
		</div>

		<?php echo form_close(); ?>
	</div>
</div>
</div>

<script>

//form 값 체크, form_validation 과는 별개
function check_form(form){
	//차변/대변 여부
	var dc=$("input[name='dc']").val();
	var reverse_dc=(dc=='debit') ? 'credit' : 'debit'; //선택된 계정의 반대 차변/대변
	
	var dc_name = (dc=='debit') ? '차변' : '대변';
	var reverse_dc_name = (dc=='debit') ? '대변' : '차변';

	/*
	 *결제방법 체크 - 차후 
	var payment_method_obj = document.getElementsByName("payment_method[]"); 
	var payment_date_obj = document.getElementsByName("payment_date[]"); 
	var payment_money_obj = document.getElementsByName("payment_money[]"); 
	var user_file_obj = document.getElementsByName("user_file[]");
	console.log('payment_method.length : '+payment_method_obj.length);
	console.log('payment_date.length : '+payment_date_obj.length);
	console.log('payment_money.length : '+payment_money_obj.length);
	console.log('user_file.length : '+user_file_obj.length);
	for(var i = 0; i < payment_date_obj.length; i++) { 
		//if( payment_date_obj[i].getAttribute("name") == "name[]" ) 
			console.log('payment_method.value:'+payment_method_obj[i].value); 
			console.log('payment_date.value:'+payment_date_obj[i].value); 
			console.log('payment_money.value:'+payment_money_obj[i].value); 
			console.log('user_file.value:'+user_file_obj[i].value); 
	} 
	*/


	
	//console.log('dc sub.size: '+$("select[name='"+dc+"_sub'] option").size());
	if (typeof($("input[name='account_kind']:checked").val()) == 'undefined') {
		alert('분류를 선택해주세요.');
		$("input[name='account_kind']").focus();
		return false;
	} else if ($("input[name='input_date']").val() == '') {
		alert('날짜를 입력해주세요.');
		$("input[name='input_date']").focus();
		return false;
	/*
	} else if (typeof($("input[name='target']:checked").val()) == 'undefined') {
		alert('대상을 선택해주세요.');
		$("input[name='target']").focus();
		return false;
	} else if ($("input[name='target_name']").val() == '') {
		alert('이름 or 거래처명이 없습니다.');
		$("input[name='target_name']").focus();
		return false;
	} else if ($("input[name='target_id']").val() == '') {
		alert('사번 or 사업자 번호가 없습니다.');
		$("input[name='target_name']").focus();
		return false;
	*/
	} else if (typeof($("select[name='account_no'] option:selected")) == 'undefined') {
		alert('계정을 선택해주세요.');
		$("select[name='account_no']").focus();
		return false;
	} else if (dc == '') {
		alert('계정 정보가 올바르지 않습니다. 계정 설정을 확인해 주세요.');
		$("select[name='account_no']").focus();
		return false;
	} else if (typeof($("input[name='tax']:checked").val()) == 'undefined') {
		alert('부가세 여부를 선택해주세요.');
		$("input[name='tax']").focus();
		return false;
	} else if (typeof($("input[name='account_group']:checked").val()) == 'undefined') {
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
</script>

<script>
var speed=0;	//click 이벤트 발생이 안하는 에러를 없애기 위해 선언함 값, 어디선가 쓰이고 있음.

//hidden text box 숨기기
$('#account_summary_direct').hide();
</script>		


<script>
	//계정의 분류 선택시, 해당 계정목록 셋팅
	$('input').live('click', function() {
		$this = $(this);
		//alert($this.attr('name') + ':'+ $this.attr('value'));
		
		//분류를 선택했을 때 해당 값들 셋팅
		if ($this.attr('name') =='account_kind' || $this.attr('name') =='target') {
			//alert('account_kind clicked');
			if (typeof($("input[name='account_kind']:checked").val()) == 'undefined') {
				alert('분류를 선택해주세요.');
				return;
			}
			$("select[name='account_no']").empty();
			
			//대상을 선택했을 때 해당 값들 셋팅
			if ($this.attr('name') =='target') {
				$("input[name='target_id']").val('');
				$("input[name='target_name']").val('');
				$("label[name='customer_title']").text('');
				
				/*
				if ($("input[name='target']:checked").val() == 'fund_inner') {
					//console.log('target : '+$("input[name='target']:checked").val()+', '+ $("input[name='target_name']").val());
					var query_string = 'target='+($("input[name='target']:checked").val());
					
					$("input[name='target_name']").autocomplete({
						source: '/json_data/target_info?'+query_string,
						minLength: 2,
						select: function( event, ui ) {
							$("input[name='target_id']").val(ui.item.id );
							$("input[name='target_name']").val(ui.item.value );
							$("label[name='customer_title']").text($("input[name='target_name']").val());	//차/대변 거래처 표시
						}
						//, error: function(){ alert('11');  alert('22'); }
					});
				}
				*/
			}

			$.ajax({
				url:'/json_data/account_list_by_kind',
				data:{
					'account_kind':$("input[name='account_kind']:checked").val()
					,'target':$("input[name='target']:checked").val()	//대상의 선택된 값
					},
				dataType:'json',
				success:function(result){
					for (var i=0; i<result['ano'].length; i++) {
						$("select[name='account_no']").append('<option value="'+result['ano'][i]+'">'+result['title_owner'][i]+'</option>');
					}
					//validation_errors나 수정시 해당 값들 셋팅후, click 이벤크 발생
					$("select[name='account_no']").val("<?php echo $s_info['basic']['account_no']; ?>").trigger('change');
					//$("select[name='account_no'] option:eq(0)").attr("selected", "selected").trigger('change');
					
				},
			   error:function(msg)
			   {
			   		alert('결과값을 가져오는데 실패했습니다.');
					//alert(msg.responseText);
			   }
			});
		}
		//대상을 선택했을 때 해당 값들 셋팅
		else if ($this.attr('name') =='plus_table') {
			var psize = $("#payment").children().size()+1;
			console.log(psize);
			var payment_table = "<table class='table' id='payment_inner'>"
					+"<tr>"
					+"<th>결제방법</th>"
					+"<td>"
					+"<select name='payment_method[]' style='width:180px'>";
					
			<?php foreach ($this->config->item('payment_method') as $key => $value) { ?>
			payment_table += "<option value='<?php echo $key; ?>'><?php echo $value; ?></option> ";
			<?php } ?>
					
			payment_table +="</select>"
					+"<input name='plus_table' type='button' class='btn btn-small btn-inverse' value='+'>"
					+"<input name='minus_table' type='button' class='btn btn-small btn-danger' value='-'>"
					+"</td>"
					+"<th>금액</th>"
					+"<td><input type='text' name='payment_money[]' style='width:100px' /></td>"
					+"</tr>"
					+"<tr>"
					+"<th>증빙</th>"
					+"<td>"
					+"<select name='payment_kind[]' style='width:180px'>";
			<?php foreach ($this->config->item('payment_kind') as $key => $value) { ?>
			payment_table += "<option value='<?php echo $key; ?>'><?php echo $value; ?></option> ";
			<?php } ?>

			payment_table +="</select>"
					+"</td>"
					+"<th>증빙일자</th>"
					+"<td><input type='text' name='payment_date[]' id='payment_date"+psize+"' style='width:120px' /> </a></td>"
					+"</tr>"
					+"<tr>"
					+"<th>증빙파일</th>"
					+"<td colspan='3'><input type='file' name='user_file[]' style='width:180px'>"
					+"</td>"
					+"</tr>"
					+"</table>";
			
	        $("#payment").closest('div').append(payment_table);
		}
		//대상을 선택했을 때 해당 값들 셋팅
		else if ($this.attr('name') =='minus_table') {
			$this.parent().parent().parent().parent().remove();
		}
		else if ($this.attr('name') =='btn-addpack') {
	        var addtext = '<div><div class="uploader" id="uniform-undefined"><input type="file" style="width: 180px; opacity: 0;" size="23.8"><span class="filename">No file selected</span><span class="action">Choose File</span></div>  <input name="btn-minuspack" type="button" class="btn btn-small btn-danger" value="제거">';

	        $(this).closest('td').append(addtext);
		}
		else if ($this.attr('name') =='btn-minuspack') {
	        $(this).closest('div').remove();
		}
	});
	
	//계정을 선택했을 때 계정의 정보를 가져와서 셋팅
	$('select').live('change', function() {
		$this = $(this);
		
		//계정이 선택됐을 때 
		if ($this.attr('name') =='account_no') {
			var selected_name = $("select[name='"+$this.attr('name')+"'] option:selected").text();	// 선택된 계정명
			var selected_no = $this.attr('value');	// 선택된 계정 ID
			var dc='';	//차변/대변 정보, ajax로 계정정보를 가져온 다음에 셋팅됨
			var reverse_dc=''; //선택된 계정의 반대 차변/대변
			
			//계정과 관련된 값들 초기화
			$("select[name='account_summary']").empty();
			
			$("label[name='summary_title']").text('');
			
			$("select[name='debit_main']").empty();
			$("select[name='debit_sub']").empty();
			//$("input[name='debit_main_money']").val('');
			//$("input[name='debit_sub_money']").val('');
			
			$("select[name='credit_main']").empty();
			$("select[name='credit_sub']").empty();
			//$("input[name='credit_main_money']").val('');
			//$("input[name='credit_sub_money']").val('');

			$("input[name='tax']").parents().removeClass("checked");		//부가세 초기화
			$("input[name='account_group']").parents().removeClass("checked");	//회계분류 초기화

			/*
			//alert('<?php echo $s_info['basic']['account_kind']; ?>');
			//alert($("input[name='account_group']:checked").val());
			*/ 

			//console.log(selected_no + ', ' + $("input[name='account_kind']:checked").val() + ',' + $("input[name='target']:checked").val());
			$.ajax({
				url:'/json_data/account_info_by_kind', 
				data:{
					'ano': selected_no,
					'account_kind': $("input[name='account_kind']:checked").val(),
					'target': $("input[name='target']:checked").val()	//대상의 선택된 값
					},
				dataType:'json',
				success:function(result){
					//alert('success');
					for(name in result){
						//계정의 option정보 셋팅
						if (name == 'use') {	//사용여부
							$("input[name='"+name+"']").val(result[name]);
						}
						//계정의 option정보 셋팅
						else if (name == 'group') {
							$("input[name='account_group'][value='"+result[name]+"']").parents().addClass("checked");
							
							//라디오버튼의 경우, 화면에 클릭된 것으로 보일려면 부모의 span에 check를 해야하고, 실제 값을 click된 것으로 하기 위해 자기 자신에도 check를 해야한다.
							$("input[name='account_group'][value='"+result[name]+"']").attr("checked", "checked").parents().addClass("checked");

						}
						//계정의 적요 셋팅
						else if (name == 'summary') {
							for (var i=0; i<result[name].length; i++) {
								//alert(result[name][i]);
								$("select[name='account_summary']").append('<option value="'+result[name][i]+'">'+result[name][i]+'</option>');
							}
						}
						//계정의 차변/대변 정보 셋팅
						else if (name == 'dc') { //차변/대변 
							dc = result[name];
							reverse_dc = (dc=='debit') ? 'credit' : 'debit';
							var dc_name = (dc=='debit') ? '차변' : '대변';

							//차변/대변에 메인 계정 보이기, 계정의 입력된 정보의 따라 메인 계정이 차변/대변이 된다.
							$("input[name='"+name+"']").val(dc);
							$("select[name='"+dc+"_main']").append('<option value="'+selected_no+'">'+selected_name+'</option>');
							$("input[name='dc_name']").val(dc_name);	//차/대변 구분 표시
						}
						//계정의 묶음계정 셋팅
						else if (name == 'bundle') {
							for (var i=0; i<result[name].length; i++) {
								$("select[name='"+dc+"_sub']").append('<option value="'+result[name][i]+'">'+result[name+'_name'][i]+'</option>');
							}
						}
						//계정의 상대계정 셋팅
						else if (name == 'contra') {
							for (var i=0; i<result[name].length; i++) {
								$("select[name='"+reverse_dc+"_main']").append('<option value="'+result[name][i]+'">'+result[name+'_name'][i]+'</option>');
							}
						}
						//계정의 상대묶음계정 셋팅
						else if (name == 'contra_bundle') {
							for (var i=0; i<result[name].length; i++) {
								$("select[name='"+reverse_dc+"_sub']").append('<option value="'+result[name][i]+'">'+result[name+'_name'][i]+'</option>');
							}
						}
					}

					//적요에 '직접입력' 항목 셋팅
					$("select[name='account_summary']").append('<option value="직접입력">직접입력</option>');
					
					//////////////////////////////////////////////////////////////////////
					//validation_errors나 수정시 해당 값들 셋팅
					//차변/대변 계정 셋팅
					$("select[name='debit_main']").val("<?php echo $s_info['statement']['debit_main']['account_no']; ?>").attr("selected", "selected");
					$("select[name='debit_sub']").val("<?php echo $s_info['statement']['debit_sub']['account_no']; ?>").attr("selected", "selected");
					$("select[name='credit_main']").val("<?php echo $s_info['statement']['credit_main']['account_no']; ?>").attr("selected", "selected");
					$("select[name='credit_sub']").val("<?php echo $s_info['statement']['credit_sub']['account_no']; ?>").attr("selected", "selected");
					//적요 값 셋팅후, change 이벤트 발생
					$("select[name='account_summary']").val("<?php echo $s_info['basic']['account_summary']; ?>").attr("selected", "selected").trigger('change');
				},
				error:function(msg) {
					alert('계정정보를 가져오는데 실패했습니다.');
					//alert(msg.responseText);
				}
			});
		}
		//적요가 선택됐을 때
		else if ($this.attr('name') =='account_summary') {
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

	////////////////////////////////////////////////////////////////////////////////////////////////
	//autocomplete
	//$(function() {
	$('input').live('keyup', function() {
		$this = $(this);
		//alert($this.attr('name') + ':'+ $this.attr('value'));
		
		if ($this.attr('name') =='target_name') {
			$("input[name='target_id']").val('');
		
			//console.log('target : '+$("input[name='target']:checked").val()+', '+ $("input[name='target_name']").val());
			var query_string = 'target='+($("input[name='target']:checked").val());
			
			$("input[name='target_name']").autocomplete({
				source: '/json_data/target_info?'+query_string,
				minLength: 2,
				select: function( event, ui ) {
					$("input[name='target_id']").val(ui.item.id );
					$("input[name='target_name']").val(ui.item.value );
					$("label[name='customer_title']").text($("input[name='target_name']").val());	//차/대변 거래처 표시
				}
				//, error: function(){ alert('11');  alert('22'); }
			});
		}
		//적요의 집접 입력일 때
		else if ($this.attr('name') =='account_summary_direct') {
			$("label[name='summary_title']").text($("[name='account_summary_direct']").val());	//차변/대변 적요 표시
		}
		//차변/대변 합계 보이기
		else if ($this.attr('name') =='debit_main_money' || $this.attr('name') =='debit_sub_money' || $this.attr('name') =='credit_main_money' || $this.attr('name') =='credit_sub_money') {
			//alert($("input[name='debit_main_money']").val());
			var debit_main_money = debit_sub_money = credit_main_money = credit_sub_money = 0;
			
			if ($("input[name='debit_main_money']").val() != '') 
				debit_main_money = parseInt($("input[name='debit_main_money']").number(true).val());
			
			if ($("input[name='debit_sub_money']").val() != '')
				debit_sub_money = parseInt($("input[name='debit_sub_money']").number(true).val());
			
			if ($("input[name='credit_main_money']").val() != '')
				credit_main_money = parseInt($("input[name='credit_main_money']").number(true).val());
			
			if ($("input[name='credit_sub_money']").val() != '')
				credit_sub_money = parseInt($("input[name='credit_sub_money']").number(true).val());
			
			$("label[name='debit_total']").text((debit_main_money+debit_sub_money)).number(true);
			$("label[name='credit_total']").text(credit_main_money+credit_sub_money).number(true);

			//$("label[name='debit_total']").text($("input[name='debit_main_money']").val());
		}
	});
	

	////////////////////////////////////////////////////////////////////////////////////////////////
	//validation_errors나 수정시 해당 값들 셋팅
	if ("<?php echo $s_info['basic']['account_kind']; ?>" != '') {
		$("input[name='account_kind'][value='<?php echo $s_info['basic']['account_kind']; ?>']").trigger('click');
		$("input[name='target'][value='<?php echo $s_info['basic']['target']; ?>']").attr("checked", "checked").parents().addClass("checked");
		
		$("input[name='debit_main_money']").val('<?php echo $s_info['statement']['debit_main']['money']; ?>');
		$("input[name='debit_sub_money']").val('<?php echo $s_info['statement']['debit_sub']['money']; ?>');
		$("input[name='credit_main_money']").val('<?php echo $s_info['statement']['credit_main']['money']; ?>');
		$("input[name='credit_sub_money']").val('<?php echo $s_info['statement']['credit_sub']['money']; ?>');

		//alert($("input[name='debit_main_money']").val());
	}
	/*
	if ("<?php echo $s_info['payment']['payment_method']; ?>" == '') {
		$("input[name='plus_table']").trigger('click');
		//alert($("input[name='debit_main_money']").val());
	}
	*/
	

</script>
