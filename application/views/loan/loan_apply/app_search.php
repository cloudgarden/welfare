<?php
//var_dump($s_info);
//var_dump($all_list);
//var_dump($_POST);


$status = $this->config->item('status');
//$account_group = $this->config->item('account_group');
?>

<div class="contentInner">
	<div class="row-fluid">

		<div class="title">

		<?php $this->load->view('/include/main_head.php', array('uri_depth'=>3)); ?>

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
		echo form_open($this->uri->uri_string(), $attributes);

		//$hidden_data = array('delete_ano'=> '');	//전표삭제시 사용
		//echo form_hidden($hidden_data);
		?>


		<table class="table">
			<tbody>
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
							<input type="radio" name="search_term" value='all' <?php if (in_array($s_info['search_term'], array('', 'all'))) echo 'checked'; ?>>전체
							<input type="radio" name='search_term' value='today' start_date='<?php echo date("Y-m-d")?>' <?php if ($s_info['search_term'] == 'today') echo 'checked'; ?> /> 오늘
							<input type="radio" name='search_term' value='month' start_date='<?php echo date("Y-m-d", strtotime('first day of this month'))?>' <?php if ($s_info['search_term'] == 'month') echo 'checked'; ?> /> 이번달
							<input type="radio" name='search_term' value='week' start_date='<?php echo date("Y-m-d",strtotime("-1 week"))?>' <?php if ($s_info['search_term'] == 'week') echo 'checked'; ?> /> 일주일간
							<input type="radio" name='search_term' value='beforemonth' start_date='<?php echo date("Y-m-d",strtotime("-1 month"))?>' <?php if ($s_info['search_term'] == 'beforemonth') echo 'checked'; ?> /> 한달간
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
					<th>소속</th>
					<td colspan="5">
						회사 : 
						<select name='company' style="width:120px">
							<option value="all" <?php if (in_array($s_info['company'], array('', 'all'))) echo'selected';?>>전체</option>
							<?php
							foreach ($s_info['company_list'] as $row) {
								if ($s_info['company'] == $row['title']) $selected = 'selected';
								else $selected = '';
								echo '<option value="'.$row['title'].'" '.$selected.'>'.$row['title'].'</option>';
							}
							?>
						</select>
						부서 : 
						<select name='department' style="width:120px">
							<option value="all" <?php if (in_array($s_info['department'], array('', 'all'))) echo'selected';?>>전체</option>
							<?php
							foreach ($s_info['department_list'] as $row) {
								if ($s_info['department'] == $row['title']) $selected = 'selected';
								else $selected = '';
								echo '<option value="'.$row['title'].'" '.$selected.'>'.$row['title'].'</option>';
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th>대상</th>
					<td colspan="5">
					<?php foreach ($this->config->item('target') as $key => $value) {
						if (in_array($value, array('거래처', '기금내부전출입'))) continue;
						echo '<input type="radio" name="target" value="'.$key.'" />'.$value.' ';
					} ?>
					<input type="text" name='target_name' style="width:250px" value="<?php echo $s_info['target_name']; ?>" placeholder="이름이나 거래처명을 2글자 이상 입력하세요.">
					<input type="text" name='target_id' style="width:120px" value="<?php echo $s_info['target_id']; ?>" readonly placeholder=""> 
					</td>
				</tr>
				<tr>
					<th>구분</th>
					<td colspan="5">
						<input type="radio" name="ano" value='all' <?php if (in_array($s_info['ano'], array('', 'all'))) echo'checked';?>>전체
						<?php foreach ($s_info['kind'] as $row) { ?>
							<input type="radio" name="ano" value='<?php echo $row['ano']; ?>' <?php if ($s_info['ano'] ==$row['ano']) echo'checked';?>><?php echo $row['title_owner']; ?>
						<?php } ?>
					
					</td>
				</tr>
				<tr>
					<th>결제상태</th>
					<td colspan="5">
						<input type="radio" name="status" value='all' <?php if (in_array($s_info['status'], array('', 'all'))) echo'checked';?>>전체
						<input type="radio" name="status" value='N' <?php if ($s_info['status'] =='N') echo'checked';?>>미결
						<input type="radio" name="status" value='F' <?php if ($s_info['status'] =='F') echo'checked';?>>완결
						<input type="radio" name="status" value='R' <?php if ($s_info['status'] =='R') echo'checked';?>>부결
					</td>
				</tr>

			</tbody>
		</table>
		<p style="text-align:center">
			<input type="submit" class="button lButton bSky" value="검색" />
		</p>
		<?php echo form_close(); ?>

			<div>
				<div class="table_top">
					<div class="total">
						대부사업 <span>총 125건</span>
					</div>
				</div>
				<table class="table">
					<colgroup>
						<col></col>
						<col></col>
						<col></col>
						<col></col>
						<col></col>
						<col></col>
						<col></col>
						<col></col>
						<col width="100px"></col>
					<colgroup>
					<thead>
						<tr>
							<th>사번</th>
							<th>성명</th>
							<th>사업장</th>
							<th>부서</th>
							<th>직급</th>
							<th>대부종류</th>
							<th>대부금액</th>
							<th>신청일</th>
							<th>상태</th>
						</tr>
					</thead>
					<tbody>
					<?php
						foreach ($all_list as $row) {
							//$debit_money = 0;
							//if (in_array($row['dc'], array('debit_main', 'debit_sub'))) $debit_money += $row['money'];
					?>
						<tr>
							<td><?php echo $row['enumber'] ; ?></td>
							<td><?php echo $row['ename'] ; ?></td>
							<td><?php echo $row['company'] ; ?></td>
							<td><?php echo $row['department'] ; ?></td>
							<td><?php echo $row['position']; ?></td> 
							<td><?php echo $row['ano'] ; ?></td>
							<td><?php echo $row['loan_money'] ; ?></td>
							<td><?php echo $row['request_date'] ; ?></td>
							<td><?php echo $status[$row['status']] ; ?></td>
						</tr>
					<?php } ?>
					</tbody>

				</table>
				<div class="table_top">
					<div class="total">
						<?php echo $this->pagination->create_links(); ?>
						<br><br><br><br><br><br>
					</div>
					<div class="outputlist">
						<a href="#" class="btn btn-small">출력</a><a href="#" class="btn btn-small btn-success">엑셀출력</a>
					</div>
				</div>
			</div>
	</div>

</div>

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:1000px; margin-left:-500px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			×
		</button>
		<h3 id="myModalLabel">전표생성</h3>
	</div>
	<div class="modal-body">
		<div class="table_top">
			<table class="table">
				<colgroup>
					<col width="120px">
					</col>
					<col>
					</col>
					<col width="120px">
					</col>
					<col>
					</col>
				</colgroup>
				<tr>
					<th>전표번호</th>
					<td>20130612-A-A-0001</td>
					<th>소속</th>
					<td>목적사업회계</td>
				</tr>
				<tr>
					<th>분류</th>
					<td>수입전표</td>
					<th>작성자</th>
					<td>홍길동</td>
				</tr>
			</table>
		</div>
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
							<th>계정과목</th>
							<th>금액</th>
							<th>거래처</th>
							<th>적요</th>
						</tr>
						<tr>
							<td style="padding:9px 4px 9px 15px;">의료비</td>
							<td>
							<input type="text" style="width:100px" />
							</td>
							<td>99</td>
							<td></td>
						</tr>
						<tr>
							<td style="padding:9px 4px 9px 15px;">건강진단</td>
							<td>
							<input type="text" style="width:100px" />
							</td>
							<td>20</td>
							<td></td>
						</tr>
					</tbody>
					<thead>
						<tr>
							<th colspan="4">
							<div style="margin:0 auto; width:180px;" >
								<label><b>차변합계 : </b></label><label name='debit_total'>1,000,000</label>
							</div></th>
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
							<th>계정과목</th>
							<th>금액</th>
							<th>거래처</th>
							<th>적요</th>
						</tr>
						<tr>
							<td>
							<select style="width:120px">
								<option>계정과목</option>
							</select></td>
							<td>
							<input type="text" style="width:100px" />
							</td>
							<td>99</td>
							<td></td>
						</tr>
						<tr>
							<td>
							<select style="width:120px">
								<option>계정과목</option>
							</select></td>
							<td>
							<input type="text" style="width:100px" />
							</td>
							<td>20</td>
							<td></td>
						</tr>
					</tbody>
					<thead>
						<tr>
							<th colspan="4">
							<div style="margin:0 auto; width:180px;" >
								<label><b>대변합계 : </b></label><label name='debit_total'>\1,000,000</label>
							</div></th>
						</tr>
					</thead>

				</table>

			</div>

		</div>

		<div id="payment">
			<table class="table" id="payment_inner">
				<tr>
					<th>결제방법</th>
					<td>
					<select name="payment_method[]" style='width:180px'>
						<option>계좌이체</option>
						<option>현금</option>
						<option>직불카드</option>
						<option>법인카드</option>
					</select></td>
					<th>금액</th>
					<td>
					<input type="text" name="payment_money[]" style="width:100px" />
					</td>
				</tr>
				<tr>
					<th>증빙</th>
					<td>
					<select name="payment_kind[]" style='width:180px'>
						<option value="1" />송금</option> <option value="2" />세금계산서</option> <option value="3" />카드영수증</option> <option value="4" />세금(면세)</option> <option value="5" />지출증빙현금영수증</option> <option value="6" />간이영수증</option> <option value="7" />소득공제영수증</option> <option value="8" />해당없음</option>
					</select></td>
					<th>증빙일자</th>
					<td>
					<input type="text" name="payment_date[]" id="payment_date1" style="width:120px" />
					</a></td>
				</tr>
				<tr>
					<th>증빙파일</th>
					<td  colspan="3">
					<input type="file" name="file[]" value='111.txt' style="width:180px">
					</td>
				</tr>
			</table>
		</div>

	</div>
	<div class="modal-footer">
		<button class="btn btn-primary">
			전표생성
		</button>
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
	});
	
	
</script>