<?php
//var_dump($s_info);
//var_dump($all_accounts);
//var_dump($s_info['account_sum_list']);
//var_dump($_POST);

$ol = $this->config->item('ol');

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
				$validation_errors = str_replace("'코드'는(은) 필수항목입니다.", "<b>선택된 계정이 없습니다.</b>", validation_errors());
				echo '<br><br>'.$validation_errors;
				$this->load->view('validation_modal');		
		}
		?>

		<?php
		$attributes = array('name'=>'form','class' => 'form-horizontal');
		echo form_open($this->uri->uri_string(), $attributes);

		//$hidden_data = array('use'=> '', 'dc'=> '');
		//echo form_hidden($hidden_data);
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
					<input type="radio" name="account_group" value='all' <?php if (in_array($s_info['account_group'], array('', 'all'))) echo 'checked'; ?>>전체
					<?php foreach ($this->config->item('account_group') as $key => $value) {
						$ckecked = '';
						if ($s_info['account_group'] == $key) $ckecked = 'checked';
						echo '<input type="radio" name="account_group" value="'.$key.'" '.$ckecked.' />'.$value.' ';
					} ?>
				</td>
			</tr>
		</table>
		
		<p style="text-align:center">
			<input type="button" class="button lButton bSky" value="검색" onclick='return check_form(this.form);' />
		</p>
		<?php echo form_close(); ?>

		<table width="100%" class="table">
			<tbody>
				<tr>
					<th colspan="2" style="text-align:center; background:#e6e6e6;">차변</th>
					<th rowspan="2" style="text-align:center; background:#e6e6e6;">계정과목</th>
					<th colspan="2" style="text-align:center; background:#e6e6e6;">대변</th>
				</tr>
				<tr>
					<th style="text-align:center;">합계</th>
					<th style="text-align:center;">잔액</th>

					<th style="text-align:center;">잔액</th>
					<th style="text-align:center;">합계</th>
				</tr>
			
				<?php
					//$total = array($total_debit, $total_debit_balance, $total_credit, $total_credit_balance);
					//재무상태표(대차대조표) 합계, 손익계산서 합계 : array('ano' => array(차변합계, 차변잔액, 대변합계, 대변잔액))
					$total=array('1'=>array(0, 0, 0, 0), '2'=>array(0, 0, 0, 0));
					if (count($s_info['account_sum_list'])>0) {
						$first_weight = 0;	//보여줄 계정목록중 첫번째 depth인 것, 부모와 상관없이 순서를 붙이기 위해 필요
						show_account_in_sisan(1, $all_accounts, $s_info['account_sum_list'], $ol, $first_weight, $total[1]);
					}
	
					foreach ($total[1] as $key => $value) {
						if ($value == 0) $total[1][$key] = '-';
					}
				 ?>
				<tr>
					<th><?php if ($total[1][0] != '-') echo number_format($total[1][0]); ?></th>
					<th><?php if ($total[1][0] != '-') echo number_format($total[1][1]); ?></th>
					<th style="text-align:center;"> [자산, 부채, 자본 합계 ] </th>
					<th><?php if ($total[1][0] != '-') echo number_format($total[1][3]); ?></th>
					<th><?php if ($total[1][0] != '-') echo number_format($total[1][2]); ?></th>

				</tr>
			<?php //} ?>
		</table>
				
		<br />

		<table width="100%" class="table">
			<tbody>
				<tr>
					<th colspan="2" style="text-align:center; background:#e6e6e6;">차변</th>
					<th rowspan="2" style="text-align:center; background:#e6e6e6;">과목</th>
					<th colspan="2" style="text-align:center; background:#e6e6e6;">대변</th>
				</tr>
				<tr>
					<th style="text-align:center;">합계</th>
					<th style="text-align:center;">잔액</th>

					<th style="text-align:center;">잔액</th>
					<th style="text-align:center;">합계</th>
				</tr>
				<?php
					if (count($s_info['account_sum_list'])>0) {
						$first_weight = 0;	//보여줄 계정목록중 첫번째 depth인 것, 부모와 상관없이 순서를 붙이기 위해 필요
						show_account_in_sisan(2, $all_accounts, $s_info['account_sum_list'], $ol, $first_weight, $total[2]);
					}
		
					foreach ($total[2] as $key => $value) {
						if ($value == 0) $total[2][$key] = '-';
					}
				 ?>
				<tr>
					<th><?php if ($total[2][0] != '-') echo number_format($total[2][0]); ?></th>
					<th><?php if ($total[2][0] != '-') echo number_format($total[2][1]); ?></th>
					<th style="text-align:center;"> [수입비용 누계] </th>
					<th><?php if ($total[2][0] != '-') echo number_format($total[2][3]); ?></th>
					<th><?php if ($total[2][0] != '-') echo number_format($total[2][2]); ?></th>
				</tr>

			</tbody>

		</table>

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
	}
	form.submit();
}

	$('input').live('click', function() {
		$this = $(this);
		//alert($this.attr('name') + ':'+ $this.attr('value'));
		
		//특정기간을 선택했을 때 검색기간 셋팅
		if ($this.attr('name') =='search_term') {
			$("select[name='search_month'] option:eq(0)").attr("selected", "selected");
			$("select[name='search_month4'] option:eq(0)").attr("selected", "selected");
			$("select[name='search_month2'] option:eq(0)").attr("selected", "selected");

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
	
</script>

