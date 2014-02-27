<?php
//var_dump($s_info);
//var_dump($all_list);
//var_dump($_POST);

//결제 상태
$status = $this->config->item('status');
//상환방법
$repayment_method = $this->config->item('repayment');
//$account_group = $this->config->item('account_group');

//검색기간
$this_pay_day = date("Y-m-".$s_info['pay_day']);	//이번달의 월급일
$search_date_list = array();
$search_date_list[] = array(date("Y-m-d",strtotime($this_pay_day.' -2 month +1 day')), date("Y-m-d",strtotime($this_pay_day.' -1 month')));
$search_date_list[] = array(date("Y-m-d",strtotime($this_pay_day.' -1 month +1 day')), $this_pay_day);

//var_dump($search_date_list);
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
							<input type="radio" name='search_term' value='this_month' start_date='<?php echo $search_date_list[1][0]?>' end_date='<?php echo $search_date_list[1][1]?>' <?php if (in_array($s_info['search_term'], array('', 'this_month'))) echo 'checked'; ?> /> 이번달
							<input type="radio" name='search_term' value='prev_month' start_date='<?php echo $search_date_list[0][0]?>' end_date='<?php echo $search_date_list[0][1]?>' <?php if ($s_info['search_term'] == 'prev_month') echo 'checked'; ?> /> 지난달
						<br>
					</div>
					</td>
				</tr>
				<tr>
					<th>결제상태</th>
					<td colspan="5">
						<input type="radio" name="status" value='F' checked>완결
					</td>
				</tr>

			</tbody>
		</table>
		<p style="text-align:center">
			<input type="submit" class="button lButton bSky" value="검색" />
		</p>
		<?php echo form_close(); ?>


		<table class="table">
			<colgroup>
				<col width="40px">
				</col>
				<col></col>
				<col></col>
				<col></col>
				<col></col>
				<col></col>
				<col></col>
				<col></col>
				<col></col>
				<col></col>
			</colgroup>
			<thead>
				<tr>
					<th><input type="checkbox" name='lsno[]' value='all'></th>
					<th>사번</th>
					<th>성명</th>
					<th>사업장</th>
					<th>부서</th>
					<th>대부종류</th>
					<th>이자</th>
					<th>원금</th>
					<th>상환총액</th>
				</tr>

			</thead>
			<tbody>
				<?php
					foreach ($all_list as $ano => $account) {
						if (array_key_exists($ano, $s_info['list'])) {
				?>
						<?php foreach ($s_info['list'][$ano] as $row) { ?>
						<tr>
							<th><input type="checkbox" name='lsno[]' value=''></th>
							<td><?php echo $row['enumber']; ?></td>
							<td><?php echo $row['ename']; ?></td>
							<td><?php echo $row['company']; ?></td>
							<td><?php echo $row['department']; ?></td>
							<td><?php echo $all_list[$row['ano']]['title_owner']; ?></td>
							<td><?php echo $row['loan_money']; ?></td>
							<td><?php echo $row['loan_money']; ?></td>
							<td><?php echo $row['loan_money']; ?></td>
						</tr>
						<?php } ?>
					<?php } ?>

				<?php } ?>
			</tbody>
		</table>
				
				
				
				<div class="table_top">
					<div class="total">
						<div class="pagination pagination-centered" style="margin-top:-20px;">
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
						</div>
					</div>
					<div class="outputlist">
						<a href="#" class="btn btn-small">출력</a><a href="#" class="btn btn-small btn-success">엑셀출력</a><a href="#myModal" role="button" data-toggle="modal" class="btn btn-small btn-success">통합전표생성</a>
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
			$("input[name='start_date']").val($this.attr('start_date'));
			$("input[name='end_date']").val($this.attr('end_date'));
		}
	});

	//기본선택값 셋팅
	$("input[name='search_term']:checked").trigger('click');
	
	
	
</script>

