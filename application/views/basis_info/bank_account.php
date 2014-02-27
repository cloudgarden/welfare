
<?php
if(validation_errors() || $this->input->query_string('bno')) {
?>
<script>
    $(document).ready(function() {
        $('#myModal').modal('show');
    }); 
</script>
<?php
}
?>

<div class="contentInner">
	<div class="row-fluid">

		<div class="title">

			<div class="text">
				<blockquote>
					<h3>계좌관리</h3>
				</blockquote>
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
		<div class="inbox">

			<div>
				<table class="table">
					<thead>
						<tr>
							<th>계좌용도</th>
							<th>해당계정</th>
							<th>은행</th>
							<th>통장명칭</th>
							<th>계좌번호</th>
							<th>잔액</th>
							<th>관리자</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($all_list as $row) {
							$href_str = '<a href="/basis_info/bank_account/?bno='.$row['bno'].'">';
						?>
						<tr>
							<td><?php echo $row['use']; ?></td>
							<td><?php echo $row['account']; ?></td>
							<td><?php echo $row['bank_name']; ?></td>
							<td><?php echo $href_str; ?><?php echo $row['alias']; ?></a></td>
							<td><?php echo $href_str; ?><?php echo $row['bank_account']; ?></a></td>
							<td><?php echo $row['money']; ?></td>
							<td><?php echo $row['manager']; ?></td>
						</tr>
						<?php } ?>


					</tbody>
				</table>
			</div>

		</div>

		<div class="buttonline">
			<a href="#myModal" role="button" class="btn btn-small btn-warning" data-toggle="modal">계좌추가</a>
		</div>

		<div class="clear"></div>

		<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:1000px; margin-left: -500px; min-hight:680px;">
			<?php
			$attributes = array('class' => 'form-horizontal');
			echo form_open('/basis_info/bank_account/', $attributes);

			$hidden_data = array('bno' => $s_info['bno']);
			echo form_hidden($hidden_data);
			?>

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					×
				</button>
				<h3 id="myModalLabel">계좌관리 추가</h3>
			</div>
			<div class="modal-body">
			<?php 
			if(validation_errors()) {
					echo validation_errors();
			}
			?>
				<table class="table">
					<colgroup>
						<col width="120px">
						</col>
						<col />
						<col width="120px">
						</col>
						<col />
					</colgroup>
					<tr>
						<th>해당계정</th>
						<td colspan="3">
						<ul>
							<li>
								<input type="radio" name="account" value='유동자산' <?php if ($s_info['account'] == '유동자산') echo 'checked'; ?>>유동자산
							</li>
							<li>
								<input type="radio" name="account" value='비유동자산' <?php if ($s_info['account'] == '비유동자산') echo 'checked'; ?>>비유동자산
							</li>
						</ul></td>
					</tr>
					<tr>
						<th>계좌용도</th>
						<td>
						<input type="text" name='use' value="<?php echo $s_info['use']; ?>" style="width:180px;">
						</td>
						<th>은행</th>
						<td>
						<input type="text" name='bank_name' value="<?php echo $s_info['bank_name']; ?>" style="width:180px;">
						</td>
					</tr>
					<tr>
						<th>통장명칭</th>
						<td>
						<input type="text" name='alias' value="<?php echo $s_info['alias']; ?>" style="width:180px;">
						</td>
						<th>계좌번호</th>
						<td>
						<input type="text" name='bank_account' value="<?php echo $s_info['bank_account']; ?>" style="width:180px;">
						</td>
					</tr>

					<tr>
						<th>잔액</th>
						<td>
						<input type="text" name='money' value="<?php echo $s_info['money']; ?>" style="width:180px;">
						</td>
						<th>관리자</th>
						<td>
						<input type="text" name='manager' value="<?php echo $s_info['manager']; ?>" style="width:180px;">
						</td>
					</tr>

					<tr>
						<th>연동계정</th>
						<td colspan="3">
						<ul>
							<li>
								<input type="radio" name="linked_ano" value='당좌자산' <?php if ($s_info['linked_ano'] == '당좌자산') echo 'checked'; ?>>당좌자산
							</li>
							<li>
								<input type="radio" name="linked_ano" value='투자자산' <?php if ($s_info['linked_ano'] == '투자자산') echo 'checked'; ?>>투자자산
							</li>
							<li>
								<input type="radio" name="linked_ano" value='유동부채' <?php if ($s_info['linked_ano'] == '유동부채') echo 'checked'; ?>>유동부채
							</li>
							<li>
								<input type="radio" name="linked_ano" value='비유동부채' <?php if ($s_info['linked_ano'] == '비유동부채') echo 'checked'; ?>>비유동부채
							</li>
							<li>
								<input type="radio" name="linked_ano" value='자본금' <?php if ($s_info['linked_ano'] == '자본금') echo 'checked'; ?>>자본금
							</li>
							<li>
								<input type="radio" name="linked_ano" value='이익잉여금' <?php if ($s_info['linked_ano'] == '이익잉여금') echo 'checked'; ?>>이익잉여금
							</li>
							<li>
								<input type="radio" name="linked_ano" value='사업수익' <?php if ($s_info['linked_ano'] == '사업수익') echo 'checked'; ?>>사업수익
							</li>
							<li>
								<input type="radio" name="linked_ano" value='사업외수익' <?php if ($s_info['linked_ano'] == '사업외수익') echo 'checked'; ?>>사업외수익
							</li>
							<li>
								<input type="radio" name="linked_ano" value='고유목적사업비용' <?php if ($s_info['linked_ano'] == '고유목적사업비용') echo 'checked'; ?>>고유목적사업비용
							</li>
							<li>
								<input type="radio" name="linked_ano" value='일반관리비' <?php if ($s_info['linked_ano'] == '일반관리비') echo 'checked'; ?>>일반관리비
							</li>
							<li>
								<input type="radio" name="linked_ano" value='사업외비용' <?php if ($s_info['linked_ano'] == '사업외비용') echo 'checked'; ?>>사업외비용
							</li>
							<li>
								<input type="radio" name="linked_ano" value='법인세비용' <?php if ($s_info['linked_ano'] == '법인세비용') echo 'checked'; ?>>법인세비용
							</li>
						</ul></td>
					</tr>
				</table>

			</div>
			<div class="modal-footer">
				<input type="submit" class="btn btn-primary" value="저장" />
			</div>
			<?php echo form_close(); ?>
		</div>

	</div>
</div>