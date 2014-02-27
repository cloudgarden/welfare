<?php
//var_dump($s_info);
//var_dump($all_list);
//var_dump($_POST);

//상환방법
$repayment_method = $this->config->item('repayment');
//$account_group = $this->config->item('account_group');

?>

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			×
		</button>
		<h3 id="myModalLabel">월상환액보기</h3>
	</div>
	<div class="modal-body">
		<table class="table">
			<thead>
				<tr>
					<th>대출금</th>
					<td><?php echo number_format($s_info['loan_money']); ?>원</td>
					<th>이율</th>
					<td>년 <?php echo $s_info['year_rate']; ?>%</td>
					<th>거치기간</th>
					<td><?php echo $s_info['unredeemed_month']; ?>개월</td>
				</tr>
				<tr>
					<th>이자총액</th>
					<td><?php echo number_format($s_info['total_rate']); ?>원</td>
					<th>상환기간</th>
					<td><?php echo $s_info['repayment_month']; ?>개월</td>
					<th>상환방법</th>
					<td><?php echo $repayment_method[$s_info['repayment_method']]; ?></td>
				</tr>
			</thead>
		</table>

		<table class="table">
			<thead>
				<tr>
					<th>회차</th>
					<th>상환일</th>
					<th>이자</th>
					<th>원금상환</th>
					<th>합계</th>
					<th>납입원금 합계</th>
					<th>남은원금</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$total_interest = $interest_by_day = 0;
				for ($i=0; $i<count($s_info['schedule']); $i++) {
					$total_interest += $s_info['schedule'][$i]['interest'];
				?>
				<tr>
					<th style="text-align:center"><?php echo $i+1; ?></th>
					<td style="text-align:center"><?php echo $s_info['schedule'][$i]['deposit_date']; ?></td>
					<th style="text-align:right"><?php echo number_format($s_info['schedule'][$i]['interest']); ?> 원</th>
					<td style="text-align:right"><?php echo number_format($s_info['schedule'][$i]['principal']); ?> 원</td>
					<th style="text-align:right"><?php echo number_format($s_info['schedule'][$i]['repayment_money']); ?> 원</th>
					<td style="text-align:right"><?php echo number_format($s_info['schedule'][$i]['payed_principal']); ?> 원</td>
					<td style="text-align:right"><?php echo number_format($s_info['schedule'][$i]['balance']); ?> 원</td>
				</tr>
				<?php } ?>
				<tr>
					<th></th>
					<th></th>
					<th><?php echo number_format($total_interest); ?> 원</th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</tbody>
		</table>

	</div>
	<!--div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">
			출력
		</button>
		<button class="btn btn-primary">
			엑셀추출
		</button>
	</div-->
