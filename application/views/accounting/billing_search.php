<?php
//var_dump($s_info);
//var_dump($all_list[0]);
//var_dump($_POST);


$account_kind = $this->config->item('account_kind');
$account_group = $this->config->item('account_group');
?>

<div class="contentInner">
	<div class="row-fluid">

		<div class="title">

		<?php $this->load->view('/include/main_head.php', array('uri_depth'=>2)); ?>

		</div>

		<?php 
		if(validation_errors()) {
				$validation_errors = str_replace("'코드'는(은) 필수항목입니다.", "<b>선택된 계정이 없습니다.</b>", validation_errors());
				echo '<br><br>'.$validation_errors;
				$this->load->view('validation_modal');		
		}
		?>

		<?php
		$attributes = array('name'=>'statement_form','class' => 'form-horizontal');
		echo form_open('/accounting/billing_search', $attributes);

		$hidden_data = array('delete_ano'=> '');	//전표삭제시 사용
		echo form_hidden($hidden_data);
		?>


		<table class="table">
			<tr>
				<th>기간</th>
				<td	>
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
						<input type="radio" name="search_term" value='all' <?php if (in_array($s_info['search_term'], array('', 'all'))) echo 'checked'; ?>>전체
					<br>
					<input type="text" name='search_year' value='<?php echo date("Y")?>' style="width:50px" />년 
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
				</div>
				</td>
			</tr>
			<tr>
				<th>분류 </th>
				<td	>
					<input type="radio" name="account_kind" value='all' <?php if (in_array($s_info['account_kind'], array('', 'all'))) echo'checked';?>>전체
					<?php foreach ($account_kind as $key => $value) {
						echo '<input type="radio" name="account_kind" value="'.$key.'" />'.$value.' ';
					} ?>
				</td>
			</tr>
			<tr>
				<th>대상</th>
				<td	>
					<input type="radio" name="target" value='all' <?php if (in_array($s_info['target'], array('', 'all'))) echo'checked';?>>전체
					<?php foreach ($this->config->item('target') as $key => $value) {
						echo '<input type="radio" name="target" value="'.$key.'" />'.$value.' ';
					} ?>

				<input type="text" name='target_name' value='<?php echo $s_info['target_name'];?>' style="width:250px">
				</td>
			</tr>
			<tr>
				<th>계정명</th>
				<td	><select name='account_no' style="width:200px"></select></td>
			</tr>
			<tr>
				<th>회계분류</th>
				<td	>
					<input type="radio" name="account_group" value='all' <?php if (in_array($s_info['account_group'], array('', 'all'))) echo'checked';?>>전체
					<?php foreach ($this->config->item('account_group') as $key => $value) {
						echo '<input type="radio" name="account_group" value="'.$key.'" />'.$value.' ';
					} ?>
				</td>
			</tr>
			<tr>
				<th>진행상태</th>
				<td	><select name="status" style="width:80px"></select></td>
			</tr>
		</table>
		
		<p style="text-align:center">
			<input type="submit" class="button lButton bSky" value="검색" />
		</p>
		<?php echo form_close(); ?>

		<table class="table nbr" >
			<colgroup>
				<col />
				<col width="150px">
				</col>
				<col width="150px">
				</col>
			<colgroup>
			<tr>
				<td style="border:none:">
				<ul class="tabs">
					<li id="tab-1">
						<label for="tab1"><input type="radio" name="tab" id="one" value="list" checked />목록형</label>
					</li>
					<li id="tab-2">
						<label for="tab2"><input type="radio" name="tab" id="two" value="journalize" />분개형</label>
					</li>
				</ul>
				</td>
				<td>
				<select style="width:120px;">
					<option>20개씩 보기</option>
				</select>
				</td>
			</tr>
		</table>

		<div class="tab_container">
			<div ID="list" class="tab_content">
				<table class="table">
					<thead>
						<tr>
							<th>전표번호</th>
							<th>작성일</th>
							<th>분류</th>
							<th>대상명</th>
							<th>금액</th>
							<th>적요</th>
							<th>회계분류</th>
							<th>작성자</th>
							<th>상태</th>
							<th>삭제</th>
						</tr>
					</thead>
					<tbody>
					<?php //for ($i=1; $i<count($all_list); $i++) { ?>
					<?php
						foreach ($all_list as $row) {
							//$debit_money = 0;
							//if (in_array($row['dc'], array('debit_main', 'debit_sub'))) $debit_money += $row['money'];
					?>
						<tr>
							<td><?php echo $row['sno'] ; ?></td>
							<td><?php echo $row['input_date'] ; ?></td>
							<td><?php echo $account_kind[$row['account_kind']] ; ?></td>
							<td><?php echo $row['target_name'] ; ?></td>
							<td align="right"><?php echo number_format($row['sum_money']); ?></td> 
							<td><?php echo $row['account_summary'] ; ?></td>
							<td><?php echo $account_group[$row['account_group']] ; ?></td>
							<td><?php echo $row['writer_name'] ; ?></td>
							<td>보류</td>
							<td><label name='delete_ano' value='<?php echo $row['sno'] ; ?>'>삭제</label></td>
						</tr>
					<?php } ?>
					</tbody>

				</table>
				<?php echo $this->pagination->create_links(); ?>
			</div>

			<div ID="journalize" class="tab_content">
				<table class="table">
					<thead>
						<tr>
							<th>전표번호</th>
							<th>작성일</th>
							<th>분류</th>
							<th>대상명</th>
							<th>차변</th>
							<th>차변금액</th>
							<th>대변</th>
							<th>대변금액</th>
							<th>적요</th>
							<th>작성자</th>
							<th>상태</th>
							<th>삭제</th>
						</tr>
					</thead>
					<tbody>
					<?php
						foreach ($all_list as $row) {
							//대변, 차변 합계가 다르면 줄 색깔을 바꾸기
							$debit_total_money = $row['dc']['debit_main']['money'] + $row['dc']['debit_sub']['money'];
							$credit_total_money = $row['dc']['credit_main']['money'] + $row['dc']['credit_sub']['money'];
							if ($debit_total_money == $credit_total_money) $bgcolor = '#cc0033';
							else $bgcolor='#cc0033';
					?>
						<tr bgcolor="<?php echo $bgcolor; ?>">
							<td rowspan="2"><?php echo $row['sno'] ; ?></td>
							<td rowspan="2"><?php echo $row['input_date'] ; ?></td>
							<td rowspan="2"><?php echo $account_kind[$row['account_kind']] ; ?></td>
							<td rowspan="2"><?php echo $row['target_name'] ; ?></td>
							<td><?php echo $row['dc']['debit_main']['title_owner'] ; ?></td>
							<td><?php if ($row['dc']['debit_main']['money'] != '') echo number_format($row['dc']['debit_main']['money']); else echo '-'; ?></td>
							<td><?php echo $row['dc']['credit_main']['title_owner'] ; ?></td>
							<td><?php if ($row['dc']['credit_main']['money'] != '') echo number_format($row['dc']['credit_main']['money']); else echo '-';  ?></td>
							<td rowspan="2"><?php echo $row['account_summary'] ; ?></td>
							<td rowspan="2"><?php echo $row['writer_name'] ; ?></td>
							<td rowspan="2">보류</td>
							<td rowspan="2"><label name='delete_ano' value='<?php echo $row['sno'] ; ?>'>삭제</label></td>
						</tr>
						<tr>
							<td><?php echo $row['dc']['debit_sub']['title_owner'] ; ?></td>
							<td><?php if ($row['dc']['debit_sub']['money'] != '') echo number_format($row['dc']['debit_sub']['money']); else echo '-';  ?></td>
							<td><?php echo $row['dc']['credit_sub']['title_owner'] ; ?></td>
							<td><?php if ($row['dc']['credit_sub']['money'] != '') echo number_format($row['dc']['credit_sub']['money']); else echo '-';  ?></td>
						</tr>
					<?php } ?>

					</tbody>

				</table>

				<?php echo $this->pagination->create_links(); ?>
				<br><br><br><br><br><br>
			</div>

		</div>

	</div>

</div>
</div>

<script>
//var speed=0;	//click 이벤트 발생이 안하는 에러를 없애기 위해 선언함 값, 어디선가 쓰이고 있음.

	$('input').live('click', function() {
		$this = $(this);
		//alert($this.attr('name') + ':'+ $this.attr('value'));
		
		//특정기간을 선택했을 때 검색기간 셋팅
		if ($this.attr('name') =='search_term') {
			$("select[name='search_month'] option:eq(0)").attr("selected", "selected");
			$("select[name='search_month4'] option:eq(0)").attr("selected", "selected");
			$("select[name='search_month2'] option:eq(0)").attr("selected", "selected");

			if ($this.val() == 'all') {
				$("input[name='start_date']").val('');
				$("input[name='end_date']").val('');
			} else {
				//오늘날짜
				var today = '<?php echo date("Y-m-d")?>';
				
				$("input[name='start_date']").val($this.attr('start_date'));
				$("input[name='end_date']").val(today);
				
				var date1 = new Date($("input[name='start_date']"));
		  		var date2 = new Date($("input[name='end_date']"));
				if (date2 - date1 < 0){
					alert("마지막날은 시작날짜보다 이후여야 합니다.");
				}
			}
		}
		//분류를 선택했을 때 해당 값들 셋팅
		else if ($this.attr('name') =='account_kind') {
			//alert('account_kind clicked');
			$("select[name='account_no']").empty();
			
			//if ($this.attr('value') == 'all') return false;
			
			$.ajax({
				url:'/json_data/account_list_by_kind', 
				data:{
					'account_kind':$this.attr('value'),
					'target':$("input[name='target']:checked").val()	//대상의 선택된 값
					},
				dataType:'json',
				success:function(result){
					$("select[name='account_no']").append('<option value=""></option>');
					for (var i=0; i<result['ano'].length; i++) {
						$("select[name='account_no']").append('<option value="'+result['ano'][i]+'">'+result['title_owner'][i]+'</option>');
					}
				},
				error:function(msg)
				{
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
		//특정기간을 선택했을 때 검색기간 셋팅
		if ($this.attr('name') =='search_month') {
			$("input[name='search_term']").parents().removeClass("checked");
			//$("select[name='search_month'] option:eq(0)").attr("selected", "selected");
			$("select[name='search_month4'] option:eq(0)").attr("selected", "selected");
			$("select[name='search_month2'] option:eq(0)").attr("selected", "selected");

			if (search_year == '') {
				alert('검색년도를 입력해주세요.');
				$("input[name='search_year']").focus();
			}
			else if ($this.val() != '') {
				$("input[name='start_date']").val(search_year+'-'+$this.val()+'-'+'01');
				$("input[name='end_date']").val(search_year+'-'+$this.val()+'-'+'31');
			}
		} else if ($this.attr('name') =='search_month4') {
			$("input[name='search_term']").parents().removeClass("checked");
			$("select[name='search_month'] option:eq(0)").attr("selected", "selected");
			//$("select[name='search_month4'] option:eq(0)").attr("selected", "selected");
			$("select[name='search_month2'] option:eq(0)").attr("selected", "selected");

			if (search_year == '') {
				alert('검색년도를 입력해주세요.');
				$("input[name='search_year']").focus();
			}
			else if ($this.val() != '') {
				var end_month = parseInt($this.val())+2;
				if (end_month<10) end_month = '0'+end_month;
				
				$("input[name='start_date']").val(search_year+'-'+$this.val()+'-'+'01');
				$("input[name='end_date']").val(search_year+'-'+end_month+'-'+'31');
			}
		} else if ($this.attr('name') =='search_month2') {
			$("input[name='search_term']").parents().removeClass("checked");
			$("select[name='search_month'] option:eq(0)").attr("selected", "selected");
			$("select[name='search_month4'] option:eq(0)").attr("selected", "selected");
			//$("select[name='search_month2'] option:eq(0)").attr("selected", "selected");

			if (search_year == '') {
				alert('검색년도를 입력해주세요.');
				$("input[name='search_year']").focus();
			}
			else if ($this.val() != '') {
				var end_month = parseInt($this.val())+5;
				if (end_month<10) end_month = '0'+end_month;
				
				$("input[name='start_date']").val(search_year+'-'+$this.val()+'-'+'01');
				$("input[name='end_date']").val(search_year+'-'+end_month+'-'+'31');
			}
		}
	});

	$('label').live('click', function() {
		$this = $(this);
		//alert($this.attr('name') + ':'+ $this.attr('value'));
		var delete_ano = $this.attr('value');
		$("input[name='delete_ano']").val(delete_ano);
		console.log(delete_ano);
		$("form[name='statement_form']").submit();
		
		
		
		/*
		//특정기간을 선택했을 때 검색기간 셋팅
		if ($this.attr('name') =='search_term') {
			if ($this.val() == 'all') {
				$("input[name='start_date']").val('');
				$("input[name='end_date']").val('');
			} else {
				//오늘날짜
				var today = '<?php echo date("Y-m-d")?>';
				
				$("input[name='start_date']").val($this.attr('start_date'));
				$("input[name='end_date']").val(today);
				
				var date1 = new Date($("input[name='start_date']"));
		  		var date2 = new Date($("input[name='end_date']"));
				if (date2 - date1 < 0){
					alert("마지막날은 시작날짜보다 이후여야 합니다.");
				}
			}
		}
		*/
	});




	////////////////////////////////////////////////////////////////////////////////////////////////
	//form_validation 시 해당 값들 셋팅
	if ("<?php echo $s_info['account_kind']; ?>" != '') {
		$("input[name='account_kind'][value='<?php echo $s_info['account_kind']; ?>']").trigger('click');
	}
	if ("<?php echo $s_info['target']; ?>" != '') {
		$("input[name='target'][value='<?php echo $s_info['target']; ?>']").trigger('click');
	}
	if ("<?php echo $s_info['target_name']; ?>" != '') {
		$("input[name='target_name']").value($s_info['target_name']);
	}
	if ("<?php echo $s_info['account_no']; ?>" != '') {
		$("select[name='account_no']").val("<?php echo $s_info['account_no']; ?>").attr("selected", "selected");
	}
	if ("<?php echo $s_info['account_group']; ?>" != '') {
		$("input[name='account_group'][value='<?php echo $s_info['account_group']; ?>']").trigger('click');
	}
	if ("<?php echo $s_info['status']; ?>" != '') {
		$("select[name='status']").val("<?php echo $s_info['status']; ?>").attr("selected", "selected");
	}
	
</script>
