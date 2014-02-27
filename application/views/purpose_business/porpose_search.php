<?php
//var_dump($all_list);
//var_dump($s_info['list']);
//var_dump($_POST);


?>

<div class="contentInner">
	<div class="row-fluid">

		<div class="title">

		<?php $this->load->view('/include/main_head.php', array('uri_depth'=>2)); ?>

		</div>

		<?php 
		if(validation_errors()) {
				echo '<br><br>'.validation_errors();
				$this->load->view('validation_modal');		
		}
		?>

		<?php
		$attributes = array('name'=>'form','class' => 'form-horizontal');
		echo form_open($this->uri->uri_string(), $attributes);

		$hidden_data = array('delete_ano'=> '');	//전표삭제시 사용
		echo form_hidden($hidden_data);
		?>

		<table class="table">
			<colgroup>
				<col width="90px">
				<col width="300px">
				<col>
				<col />
			</colgroup>
			<tr>
				<th>분류</th>
				<td colspan="3">
					<input type="checkbox" name="ano[]" value="all">전체
					<?php
						foreach ($all_list as $row) {
							if (is_array($s_info['ano']) && count($s_info['ano'])>0 && in_array($row['ano'], $s_info['ano'])) $checked = 'checked';
							else $checked = '';
							//$checked = '';
					?>
						<input type="checkbox" name="ano[]" value="<?php echo $row['ano']; ?>"  <?php echo $checked; ?>><?php echo $row['title_owner']; ?> 
					<?php } ?>
				</td>
			</tr>
			<tr>
				<th>기간</th>
				<td colspan="3">
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
				<th>대상 </th>
				<td >
					<input type="radio" name="target" value="employee" <?php if ($s_info['target'] == 'employee') echo 'checked'; ?> /> 사원
					<input type="radio" name="target" value="fund_employee" <?php if ($s_info['target'] == 'fund_employee') echo 'checked'; ?> /> 복지내부직원
				</td>
				<th>대상 범위</th>
				<td >
					<input type="radio" name="target_range" value="all" <?php if ($s_info['target_range'] == 'employee') echo 'checked'; ?> /> 전체
					<input type="radio" name="target_range" value="retired_employee" <?php if ($s_info['target_range'] == 'retired_employee') echo 'checked'; ?> /> 퇴직자포함
					<input type="radio" name="target_range" value="temporary_employee" <?php if ($s_info['target_range'] == 'temporary_employee') echo 'checked'; ?> /> 비정규직포함
				</td>
			</tr>

			<tr>
				<th>진행상태 </th>
				<td colspan="3"><select  style="width:120px; margin-top:8px;"></select></td>
			</tr>

			<tr>
				<th>상세조건 </th>
				<td colspan="3"><select  style="width:120px; margin-top:8px;"></select><select  style="width:120px; margin-top:8px;"></select><select  style="width:120px; margin-top:8px;"></select>
				<input type="text" style="width:120px">
				<a href="#" class="btn btn-small btn-inverse">검색</a>
				</td>
			</tr>
			<tr>
				<th>출력갯수 </th>
				<td colspan="3">
					<select>
						<option>10개씩 출력</option>
					</select>
				</td>
			</tr>
		</table>
		<p style="text-align:center">
			<input type="submit" class="button lButton bSky" value="검색" />
		</p>
		<?php echo form_close(); ?>




			<?php
				foreach ($all_list as $ano => $account) {
					if (!in_array($ano, $s_info['ano'])) continue;
					$cnt = 0;
					if (array_key_exists($ano, $s_info['list'])) $cnt = count($s_info['list'][$ano]);
					
			?>
				<h4 style="position:relative; top:20px;"><?php echo $account['title_owner']; ?></h4>
				<div class="table_top">
					<div class="total">
						총 <span><?php echo $cnt; ?></span>개의 게시물이 있습니다
					</div>
				</div>
				<table class="table">
				<?php if ($cnt > 0) { ?>
		
					<thead>
						<tr>
							<th>no</th>
							<th>성명</th>
							<th>직급</th>
							<th>부서</th>
							<th>대상자</th>
							<th>지원사업</th>
							<th>신청일</th>
							<th>신청금액</th>
							<th>상태</th>
							<th>전표</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($s_info['list'][$ano] as $row) {
								//신청서 상태
								$request_process = $process = '';
								if ($row['status'] == 'N') {
									$request_process = '신청서 결재전';	
									$process = '신청서 결재전';	
								} else if ($row['status'] == 'F') {
									$request_process = '결재';	//신청서 상태
									//전표생성 상태
									if ($row['statement_status'] == null) {
										//$onclick = 'onclick=\'window.open("/accounting/acc_billing/basic?pno='.$row['pno'].'&ano='.$row['ano'].'")\'';
										$onclick = 'onclick=\'window.open("/popup/statement?pno='.$row['pno'].'", "", "width=1100, height=600, scrollbars=yes, status=yes"); return false;\'"';
										
										$process = '<input type="button" class="btn btn-small btn-primary" value="전표생성" '.$onclick.'/>';
									} else if ($row['statement_status'] =='N') {
										$process = '미결재';
									} else if ($row['statement_status'] == 'F') {
										$process = '처리';
									}
								}
								
					
						?>
						<tr>
							<td><?php echo $row['pno']; ?></td>
							<td><?php echo $row['ename']; ?></td>
							<td><?php echo $row['position']; ?></td>
							<td><?php echo $row['department']; ?></td>
							<td><?php echo $row['recipient_name']; ?></td>
							<td><?php echo $row['title_owner']; ?></td>
							<td><?php echo $row['request_date']; ?></td>
							<td><?php echo $row['request_money']; ?></td>
							<td><?php echo $request_process; ?></td>
							<td><?php echo $process; ?></td>
		
						</tr>
						<?php } ?>
		
					</tbody>
		
				<?php } ?>
				</table>

			<?php } ?>

		<!--div class="pagination pagination-centered">
			<ul>
				<li>
					<a href="#">←</a>
				</li>
				<li class="active">
					<a href="#">10</a>
				</li>
				<li class="disabled">
					<a href="#">...</a>
				</li>
				<li>
					<a href="#">20</a>
				</li>
				<li>
					<a href="#">→</a>
				</li>
			</ul>
		</div-->

	</div>

</div>






<script>
//var speed=0;	//click 이벤트 발생이 안하는 에러를 없애기 위해 선언함 값, 어디선가 쓰이고 있음.
	$('input').live('click', function() {
		$this = $(this);
		//alert($this.attr('name') + ':'+ $this.attr('value'));
		console.log($this.attr('name') + ':'+ $this.val() + ':'+ $("input[name='ano[]']").length);
		
		//분류에서 전체 선택시 모두 선택되도록 셋팅
		if ($this.attr('name') =='ano[]') {
			if ($this.val() == 'all') {
				if ($this.is(":checked") == true) {
				  	for(var i=0; i < $("input[name='ano[]']").length; i++) {
				  		$("input[name='ano[]']").eq(i).attr("checked", "checked").parents().addClass("checked");
					}
				} else {
				  	for(var i=0; i < $("input[name='ano[]']").length; i++) {
				  		$("input[name='ano[]']").eq(i).attr("checked", false).parents().removeClass("checked");
					}
				}
			} else {
				//전체버튼을 제외한 다른 버튼의 체크가 해제되면 전체버튼 해제
				if ($this.is(":checked") == false) {
					$("input[name='ano[]']").eq(0).attr("checked", false).parents().removeClass("checked");
				}
				
			}
		}
		//특정기간을 선택했을 때 검색기간 셋팅
		else if ($this.attr('name') =='search_term') {
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

