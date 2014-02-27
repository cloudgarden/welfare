<?php
//var_dump($s_info);
//var_dump($all_list);
//var_dump($_POST);


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

		<?php 
		if(validation_errors()) {
				echo '<br><br>'.validation_errors();
				$this->load->view('validation_modal');		
		}
		?>

		<?php
		$attributes = array('name'=>'form','id'=>'form','class' => 'form-horizontal');
		echo form_open($this->uri->uri_string(), $attributes);

		//$hidden_data = array('use'=> '', 'dc'=> '');
		//echo form_hidden($hidden_data);
		?>


		<table class="table">
			<tr>
				<th>기간</th>
				<td	colspan=3>
				<div style="float:left;">
					<input type="text" name='start_date' id='start_date' value="<?php echo $s_info['start_date']; ?>" style="width:80px" />
					~
					<input type="text" name='end_date' id='end_date' value="<?php echo $s_info['end_date']; ?>" style="width:80px" />
				</div>
				<div style="float:left; width:10px; ">
					&nbsp;&nbsp;
				</div>
				<div style="float:left;">
						<input type="radio" name='search_term' value='today' start_date='<?php echo date("Y-m-d")?>' <?php if ($s_info['search_term'] == 'today') echo 'checked'; ?> /> 오늘
						<input type="radio" name='search_term' value='month' start_date='<?php echo date("Y-m-d", strtotime('first day of this month'))?>' <?php if ($s_info['search_term'] == 'month') echo 'checked'; ?> /> 이번달
						<input type="radio" name='search_term' value='week' start_date='<?php echo date("Y-m-d",strtotime("-1 week"))?>' <?php if ($s_info['search_term'] == 'week') echo 'checked'; ?> /> 일주일간
						<input type="radio" name='search_term' value='beforemonth' start_date='<?php echo date("Y-m-d",strtotime("-1 month"))?>' <?php if ($s_info['search_term'] == 'beforemonth') echo 'checked'; ?> /> 한달간
						<input type="radio" name="search_term" value='all' <?php if (in_array($s_info['search_term'], array('all'))) echo 'checked'; ?>>전체
					<br>
					<input type="text" name='search_year' value='<?php if ($s_info['search_year']) echo $s_info['search_year'];  else echo date("Y")?>' style="width:50px" />년 
					<select name='search_month' style="width:80px">
						<option value=''></option>
						<?php
						for ($i=1; $i<=12; $i++) {
							$value = str_pad($i, 2, "0", STR_PAD_LEFT);
							if ($s_info['search_month'] == $value) $selected = 'selected';
							else $selected = '';
							echo '<option value="'.$value.'" '.$selected.'>'.$i.'월</option>';
						}
						?>
					</select>
					월
					<select name='search_month4' style="width:80px">
						<option value=''></option>
						<option value='01' <?php if ($s_info['search_month4'] == '01') echo 'selected'; ?>>1</option>
						<option value='04' <?php if ($s_info['search_month4'] == '04') echo 'selected'; ?>>2</option>
						<option value='07' <?php if ($s_info['search_month4'] == '07') echo 'selected'; ?>>3</option>
						<option value='10' <?php if ($s_info['search_month4'] == '10') echo 'selected'; ?>>4</option>
					</select> 사분기
					<select name='search_month2' style="width:80px">
						<option value=''></option>
						<option value='01' <?php if ($s_info['search_month2'] == '01') echo 'selected'; ?>>상</option>
						<option value='07' <?php if ($s_info['search_month2'] == '07') echo 'selected'; ?>>하</option>
					</select> 반기
				</div></td>
			</tr>
			<tr>
				<th>회계분류</th>
				<td	>
					<input type="radio" name="account_group" value='all' <?php if (in_array($s_info['account_group'], array('all'))) echo 'checked'; ?>>전체
					<?php foreach ($this->config->item('account_group') as $key => $value) {
						$ckecked = '';
						if ($s_info['account_group'] == $key) $ckecked = 'checked';
						echo '<input type="radio" name="account_group" value="'.$key.'" '.$ckecked.' />'.$value.' ';
					} ?>
				</td>
				<th>계정 </th>
				<td><select name='account_no' style="width:150px"></select></td>
			</tr>

		</table>
		
		<p style="text-align:center">
			<input type="button" class="button lButton bSky" value="검색" onclick='return check_form(this.form);' />
		</p>
		<?php echo form_close(); ?>
		
		<?php if (count($s_info['statement_list']) == 0) { ?>
			검색결과가 없습니다.
		<?php } else { ?>

		<table class="table" id='statement_list'>
			<thead>
				<tr>
					<th colspan="5"><div style="margin:0 auto; width:250px; font-weight:bold;" ><label name='account_name'></label></div></th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<th>날짜</th>
					<th>적요</th>
					<th>차변</th>
					<th>대변</th>
					<th>잔액</th>
				</tr>
				<?php
				$total_debit = $total_credit = $total_balance = 0;
				foreach ($s_info['statement_list'] as $account_no => $arr) {
					$total_debit += $arr['debit_money'];
					$total_credit += $arr['credit_money'];
					$total_balance += $arr['balance'];
				?>
				<tr>
					<td><?php echo $arr['input_date']; ?></td>
					<td><?php echo $arr['account_summary']; ?></td>
					<td><?php if ($arr['debit_money'] == 0) echo '-'; else echo number_format($arr['debit_money']); ?></td>
					<td><?php if ($arr['credit_money'] == 0) echo '-'; else echo number_format($arr['credit_money']); ?></td>
					<td><?php if ($arr['balance'] == 0) echo '-'; else echo number_format($arr['balance']); ?></td>
				</tr>
				<?php } ?>
				<tr>
					<th>합계</th>
					<td></td>
					<td><?php if ($total_debit == 0) echo '-'; else echo number_format($total_debit); ?></td>
					<td><?php if ($total_credit == 0) echo '-'; else echo number_format($total_credit); ?></td>
					<td><?php if ($total_balance == 0) echo '-'; else echo number_format($total_balance); ?></td>
				</tr>

			</tbody>
		</table>
		<?php } ?>

	</div>
</div>


<script>
//var speed=0;	//click 이벤트 발생이 안하는 에러를 없애기 위해 선언함 값, 어디선가 쓰이고 있음.
//form 값 체크, form_validation 과는 별개
function check_form(form){
	if (typeof($("input[name='search_term']:checked").val()) == 'undefined' && form.start_date.value == '') {
		alert('검색기간을 입력해주세요.');
		$("input[name='start_date']").focus();
		return false;
	} else if (typeof($("input[name='account_group']:checked").val()) == 'undefined') {
		alert('회계분류를 선택해주세요.');
		$("input[name='account_group']").focus();
		return false;
	} else if (typeof($("select[name='account_no'] option:selected")) == 'undefined') {
		alert('계정을 선택해주세요.');
		$("select[name='account_no']").focus();
		return false;
	}
	form.submit();
}

	$('input').live('click', function() {
		$this = $(this);
		//alert($this.attr('name') + ':'+ $this.attr('value'));
		
		//특정기간을 선택했을 때 검색기간 셋팅
		if ($this.attr('name') =='search_term') {
			if ($this.val() == 'all') {
				$("#start_date").val('');
				$("#end_date").val('');
			} else {
				//오늘날짜
				var today = '<?php echo date("Y-m-d")?>';
				
				$("#start_date").val($this.attr('start_date'));
				$("#end_date").val(today);
				
				var date1 = new Date($("#start_date"));
		  		var date2 = new Date($("#end_date"));
				if (date2 - date1 < 0){
					alert("마지막날은 시작날짜보다 이후여야 합니다.");
				}
			}
		} else if ($this.attr('name') =='start_date') {
			//$("input[name='search_term']").removeClass("checked");
			$("input:radio[name='search_term'][value='all']").attr("checked", false).parents().removeClass("checked");
		}
		//대상을 선택했을 때 해당 값들 셋팅
		else if ($this.attr('name') =='account_group') {
			$("select[name='account_no']").empty();
			//console.log($this.attr('value'));
			$.ajax({
				url:'/json_data/account_list_in_statement', 
				data:{
					'account_group':$this.attr('value')	//회계분류
					},
				dataType:'json',
				success:function(result){
					for(name in result){
						//console.log(name + ', ' + result[name]['title_owner']);
						$("select[name='account_no']").append('<option value="'+name+'">'+result[name]['title_owner']+'</option>');
					}
					//validation_errors나 수정시 해당 값들 셋팅후, click 이벤크 발생
					$("select[name='account_no']").val("<?php echo $s_info['account_no']; ?>").attr("selected", "selected");
					var selected_name = $("select[name='account_no'] option:selected").text();	// 선택된 계정명
					$("label[name='account_name']").text(selected_name);
				},
			   error:function(msg) {
			   		alert('결과값을 가져오는데 실패했습니다.');
					//alert(msg.responseText);
			   }
			});
		}

	});

	//검색기간 셋팅
	$('select').live('change', function() {
		$this = $(this);
		var search_year = $("input[name='search_year']").val();
		
		//계정 선택시 form submit
		if ($this.attr('name') =='account_no') {
			$("#form").submit();
		}
		
		//특정기간을 선택했을 때 검색기간 셋팅
		else if ($this.attr('name') =='search_month') {
			if (search_year == '') {
				alert('검색년도를 입력해주세요.');
				$("input[name='search_year']").focus();
			}
			else if ($this.val() != '') {
				$("#start_date").val(search_year+'-'+$this.val()+'-'+'01');
				$("#end_date").val(search_year+'-'+$this.val()+'-'+'31');
			}
		} else if ($this.attr('name') =='search_month4') {
			if (search_year == '') {
				alert('검색년도를 입력해주세요.');
				$("input[name='search_year']").focus();
			}
			else if ($this.val() != '') {
				var end_month = parseInt($this.val())+2;
				if (end_month<10) end_month = '0'+end_month;
				
				$("#start_date").val(search_year+'-'+$this.val()+'-'+'01');
				$("#end_date").val(search_year+'-'+end_month+'-'+'31');
			}
		} else if ($this.attr('name') =='search_month2') {
			if (search_year == '') {
				alert('검색년도를 입력해주세요.');
				$("input[name='search_year']").focus();
			}
			else if ($this.val() != '') {
				var end_month = parseInt($this.val())+5;
				if (end_month<10) end_month = '0'+end_month;
				
				$("#start_date").val(search_year+'-'+$this.val()+'-'+'01');
				$("#end_date").val(search_year+'-'+end_month+'-'+'31');
			}
		}
	});
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	//form_validation 시 해당 값들 셋팅
	if ("<?php echo $s_info['account_group']; ?>" != '') {
		$("input[name='account_group'][value='<?php echo $s_info['account_group']; ?>']").trigger('click');
	}
	
</script>

