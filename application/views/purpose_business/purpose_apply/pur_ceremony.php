<?php $this->load->view('/include/purpose_business_head.php'); ?>

		<div class="table_top">
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
						<th>관계</th>
						<td>
							<select name='relation' style="width:150px">
								<?php foreach ($all_list['relation'] as $relation) { ?>
									<option value='<?php echo $relation; ?>'><?php echo $relation; ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<th>신청일</th>
						<td>
						<input type="text" name='request_date' id='request_date' style="width:120px">
						</td>
						<th>사유발생일</th>
						<td>
						<input type="text" name='occur_date' id='occur_date' style="width:120px">
						</td>
					</tr>
					<tr>
						<th>신청금액</th>
						<td colspan="3">
						<input type="text" name='request_money' style="width:120px">
						</td>
					</tr>
					<tr>
						<th>계좌번호</th>
						<td colspan="3">
						<input type="text" name='bank_name' style="width:80px">은행, 
						계좌번호 : <input type="text" name='bank_account' style="width:150px">
						예금주 : <input type="text" name='bank_owner' style="width:100px">
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
	

<?php $this->load->view('/include/purpose_business_tail.php'); ?>
