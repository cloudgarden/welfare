<?php
//var_dump($all_list);
//var_dump($s_info);
//var_dump($_POST);


?>

<div class="contentInner">
	
	<?php
	$attributes = array('name'=>'form','class' => 'form-horizontal');
	echo form_open($this->uri->uri_string(), $attributes);

	$hidden_data = array('pno'=> '', 'lno'=> '', 'status'=> '');
	echo form_hidden($hidden_data);
	?>
	<?php echo form_close(); ?>
		
	
	
	<div class="row-fluid">

		<div class="title">

			<div class="text">
				<blockquote><h3>목적사업 신청서결제</h3></blockquote>
			</div>
			<div class="clear"></div>

		</div>

		<table class="table">
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
				</tr>
			</thead>
			<tbody>

			<?php
				$i = count($s_info['purpose_list']);
				foreach ($s_info['purpose_list'] as $row) {
					//신청서 상태
					if ($row['status'] == 'N') {
						$process = '<input type="button" class="btn btn-small btn-primary" pno="'.$row['pno'].'" value="결재" />';

						//$onclick = 'onclick=\'window.open("'.$this->uri->uri_string().'?pno='.$row['pno'].'", "", "width=1100, height=600, scrollbars=yes, status=yes"); return false;\'"';
						
						//$process = '<input type="button" class="btn btn-small btn-primary" value="결재" '.$onclick.'/>';
					} else if ($row['status'] == 'F') {
						$process = '처리';
					}
					
			?>
				
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $row['ename']; ?></td>
					<td><?php echo $row['position']; ?></td>
					<td><?php echo $row['department']; ?></td>
					<td><?php echo $row['recipient_name']; ?></td>
					<td><a href='/popup/purpose_apply?pno=<?php echo $row['pno']; ?>'><?php echo $row['title_owner']; ?></a></td>
					<td><?php echo $row['request_date']; ?></td>
					<td><?php echo $row['request_money']; ?></td>
					<td><?php echo $process; ?></td>
				</tr>

			<?php
					$i--;
				}
			?>
			</tbody>
		</table>

		<div class="title">

			<div class="text">
				<blockquote><h3>대부금 신청서결제</h3></blockquote>
			</div>
			<div class="clear"></div>

		</div>

		<table class="table">
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
				</tr>
			</thead>
			<tbody>

			<?php
				$i = count($s_info['loan_list']);
				foreach ($s_info['loan_list'] as $row) {
					//신청서 상태
					if ($row['status'] == 'N') {
						$process = '<input type="button" class="btn btn-small btn-primary" lno="'.$row['lno'].'" value="결재" />';

						//$onclick = 'onclick=\'window.open("'.$this->uri->uri_string().'?lno='.$row['lno'].'", "", "width=1100, height=600, scrollbars=yes, status=yes"); return false;\'"';
						
						//$process = '<input type="button" class="btn btn-small btn-primary" value="결재" '.$onclick.'/>';
					} else if ($row['status'] == 'F') {
						$process = '처리';
					}
					
			?>
				
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $row['ename']; ?></td>
					<td><?php echo $row['position']; ?></td>
					<td><?php echo $row['department']; ?></td>
					<td><?php echo $row['ename']; ?></td>
					<td><a href='/popup/purpose_apply?lno=<?php echo $row['lno']; ?>'><?php echo $row['title_owner']; ?></a></td>
					<td><?php echo $row['request_date']; ?></td>
					<td><?php echo $row['loan_money']; ?></td>
					<td><?php echo $process; ?></td>
				</tr>

			<?php
					$i--;
				}
			?>
			</tbody>
		</table>



	</div>

</div>

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:1000px; margin-left:-500px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			×
		</button>
		<h3 id="myModalLabel">경조비 신청</h3>
	</div>
	<div class="modal-body">
		<table class="table">
			<tbody>
				<tr>
					<th>사번</th>
					<td>0121</td>
					<th>소속</th>
					<td>본사</td>
					<th>입사일</th>
					<td>2013-02-02</td>
				</tr>
				<tr>
					<th>이름</th>
					<td>홍길동</td>
					<th>부서</th>
					<td>경영지원</td>
					<th>사원구분</th>
					<td>임원(사원,비정규직)</td>
				</tr>

				<tr>
					<th>주민번호</th>
					<td>781211-*******</td>
					<th>직위</th>
					<td>대리</td>
					<th>연락처</th>
					<td>010-111-1111</td>
				</tr>
			</tbody>
		</table>

		<table class="table">
			<tr>
				<th>구분</th><td>
				<select style="width:100px">
					<option>자녀결혼</option>
				</select></td>
				<th>관계</th><td>
				<select style="width:100px">
					<option>아들</option>
				</select></td>
			</tr>

			<tr>
				<th>대상</th><td>
				<input type="text" style="width:80px" >
				</td><th>주민번호</th><td>
				<input type="text" style="width:80px" >
				-
				<input type="text" style="width:80px" >
				</td>
			</tr>
			<tr>
				<th>신청일</th><td>
				<input type="text" style="width:80px" >
				</td><th>사유발생일</th><td>
				<input type="text" style="width:80px" >
				</td>
			</tr>
			<tr>
				<th>신청금액</th><td colspan="3">
				<input type="text" style="width:80px" >
				원</td>
			</tr>
			<tr>
				<th>계좌번호</th><td colspan="3">
				<input type="text" style="width:80px" >
				은행
				<input type="text" style="width:200px" >
				<input type="text" style="width:200px" >
				</td>
			</tr>
			<tr>
				<th>첨부파일</th><td colspan="3">
				<input type="file" style="width:180px">
				<a href="#" class="btn btn-small btn-success">추가</a><a href="#" class="btn btn-small  btn-danger">제거</a></td>
			</tr>
			<tr>
				<th>참조</th><td colspan="3">
				<input type="text" style="width:100%;">
				</td>
			</tr>
		</table>

	</div>
</div>

<script>
	//신청서 결재
	$('input').live('click', function() {
		$this = $(this);

		if ($this.attr('value') =='결재') {
			//console.log($this.attr('value'));
			form.pno.value = $this.attr('pno');
			form.lno.value = $this.attr('lno');
			form.status.value = 'F';
			form.submit();
		}
	});
	
</script>

