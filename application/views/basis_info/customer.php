<?php
//if (!empty($form_values))
	//var_dump($form_values);
	//var_dump($all_list);


//var_dump($accounts);
//var_dump($form_values);
//var_dump($kind);
//var_dump($account_relation);

//var_dump($_POST);
?>

<?php 
if(validation_errors() || $this->input->query_string('cno') || $this->input->query_string('mode')=='new') {
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
			<blockquote><h3><?php echo $menus[$this->uri->segment(1)][$this->uri->segment(2)]['title']; ?></h3></blockquote>
			</div>

			<div class="appbtn">
				<li>
					<div class="iconbox">
						<a href="#" rel="tipsyN"  original-title="문서인쇄"><img src="/wwf/img/icons/14x14/printer1.png" alt="프린트"></a>
					</div>
				</li>
				<li>
					<div class="iconbox">
						<a href="#" rel="tipsyN"  original-title="엑셀다운로드"><img src="/wwf/img/icons/14x14/upload1.png" alt="엑셀다운"></a>
					</div>
				</li>
			</div>
			<div class="clear"></div>

		</div>
		<div class="inparea_s top_box">
			<ul>
				<li>
					<span>거래처구분 </span>
					<select style="width:120px;">
						<option></option>
					</select>
					<select style="width:120px;">
						<option></option>
					</select>
				</li>
				<li>
					<input type="text" style="width:120px;">
				</li>
				<li>
					<a href="#" class="btn btn-small btn-primary">검색</a>
				</li>
				<li>
					<!--button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-small btn-warning" id='new_input'>신규입력</button-->
					<a href="/<?php echo $this->uri->uri_string(); ?>/?mode=new" class="btn btn-small btn-warning">신규입력</a>
				</li>
			</ul>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>

		<div class="table_top">
			<div class="total">
				총 <span><?php echo $total_rows; ?></span>개의 게시물이 있습니다
			</div>
			<div class="outputlist">
				<select>
					<option>10개씩 출력</option>
				</select>
			</div>
		</div>
		<table class="table">
			<thead>
				<tr>
					<th>사업자번호</th>
					<th>거래처명</th>
					<th>담당자</th>
					<th>연락처</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($all_list as $row) { ?>
				<tr>
					<td><a href='/basis_info/customer/?cno=<?php echo $row['cno']; ?>'><?php echo $row['business_number']; ?></a></td>
					<td><?php echo $row['customer_name']; ?></td>
					<td><?php echo $row['charge_name']; ?></td>
					<td><?php echo $row['charge_tel']; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>

		<?php echo $this->pagination->create_links(); ?>
	</div>
	
	<br><br><br><br>
	<!--거래처 추가-->
	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:1000px; margin-left: -500px; min-hight:680px;">
		 <?php
		 $attributes = array('class' => 'form-horizontal');
		 echo form_open('/basis_info/customer/', $attributes);
		 
		 $hidden_data = array('cno'=>$form_values['cno']);
		 echo form_hidden($hidden_data);
		 ?>

		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				×
			</button>
			<h3 id="myModalLabel">거래처 추가</h3>
		</div>
		<div class="modal-body">
			
		<div class="title">
			
			<?php 
			if(validation_errors()) {
					$validation_errors = str_replace("'코드'는(은) 필수항목입니다.", "<b>선택된 계정이 없습니다.</b>", validation_errors());
					echo $validation_errors;
			}
			?>
		</div>

			<table class="table" width="700px;">
				<tbody>
					<tr>
						<th>거래처명</th>
						<td>
						<input type="text" name='customer_name' value="<?php echo $form_values['customer_name']; ?>" style="width:120px;">
						</td>
						<th>거래처구분</th>
						<td>
						<select name="ctype" style="width:120px;">
							<option value='법인' <?php if ($form_values['ctype'] == '법인') echo 'selected'; ?>>법인</option>
							<option value='개인사업자' <?php if ($form_values['ctype'] == '개인사업자') echo 'selected'; ?>>개인사업자</option>
						</select>
						<!--a href="#categorization" role="button" class="btn btn-small btn-warning" data-toggle="modal">추가</a-->
						</td>
					</tr>
					<tr>
						<th>사업자번호</th>
						<td>
							<input type="text" name='business_number1' value="<?php echo $form_values['business_number1']; ?>" style="width:50px;">
							<input type="text" name='business_number2' value="<?php echo $form_values['business_number2']; ?>" style="width:50px;">
							<input type="text" name='business_number3' value="<?php echo $form_values['business_number3']; ?>" style="width:80px;"></td>
						<th>법인등록번호</th>
						<td>
						<input type="text" name='corporation_number1' value="<?php echo $form_values['corporation_number1']; ?>" style="width:100px;">
						<input type="text" name='corporation_number2' value="<?php echo $form_values['corporation_number2']; ?>" style="width:100px;">
						</td>
					</tr>
					<tr>
						<th>대표자명</th>
						<td><input type="text" name='representative_name' value="<?php echo $form_values['representative_name']; ?>"></td>
						<th>회사 Email</th>
						<td><input type="text" name='com_email' value="<?php echo $form_values['com_email']; ?>"></td>
					</tr>

					<tr>
						<th>업태</th>
						<td>
						<input type="text" name='business_status' value="<?php echo $form_values['business_status']; ?>" style="width:120px;">
						</td>
						<th>종목</th>
						<td>
						<input type="text" name='business_category' value="<?php echo $form_values['business_category']; ?>" style="width:120px;">
						</td>
					</tr>
					<tr>
					<th>사업장 소재지</th>
					<td colspan="3">
						<input type="text" name='zipcode1' value="<?php echo $form_values['zipcode1']; ?>"  style="width:50px;">
						<input type="text" name='zipcode2' value="<?php echo $form_values['zipcode2']; ?>"  style="width:50px;">
						<a href="/popup/zip_search" onclick="window.open(this.href, 'pop', 'width=350, height=500, status=yes'); return false;" class="btn btn-small btn-info">우편번호</a> <br/><br/>
						<input type="text" name='address1' value="<?php echo $form_values['address1']; ?>"  style="width:100%;"><br/><br/>
						<input type="text" name='address2' value="<?php echo $form_values['address2']; ?>"  style="width:100%;">
					</td>
					</tr>
					<tr>
						<th>전화번호</th>
						<td>
							<input type="text" name='com_tel' value="<?php echo $form_values['com_tel']; ?>">
						</td>
						<th>Fax</th>
						<td><input type="text" name='com_fax' value="<?php echo $form_values['com_fax']; ?>"></td>
					</tr>

					<tr>
						<th>담당자명</th>
						<td>
						<input type="text" name='charge_name' value="<?php echo $form_values['charge_name']; ?>" style="width:120px;">
						</td>
						<th>담당자 전화번호</th>
						<td>
						<input type="text" name='charge_tel' value="<?php echo $form_values['charge_tel']; ?>">
						</td>
					</tr>

					<tr>
						<th>담당자 휴대폰번호</th>
						<td>
						<input type="text" name='charge_handtel' value="<?php echo $form_values['charge_handtel']; ?>">
						</td>
						<th>담당자 Email</th>
						<td>
						<input type="text" name='charge_email' value="<?php echo $form_values['charge_email']; ?>">
						</td>
					</tr>

					<tr>
						<th>거래계좌</th>
						<td>
						<input type="text" name='bank_name' value="<?php echo $form_values['bank_name']; ?>" style="width:120px;">
						은행 </td>
						<th>계좌번호</th>
						<td>
						<input type="text" name='bank_account' value="<?php echo $form_values['bank_account']; ?>" style="width:120px;">
						</td>
					</tr>

					<tr>
						<th>예금주</th>
						<td colspan="3">
						<input type="text" name='bank_owner' value="<?php echo $form_values['bank_owner']; ?>" style="width:120px;">
						</td>
					</tr>

				</tbody>
			</table>

		</div>
		<div class="modal-footer">
			<div class="buttonline"><input type="submit" class="btn btn-primary" value="저장" /></div>
		</div>
        <?php echo form_close(); ?>	
	</div>



<!-- Modal -->
<div id="categorization" class="modal hide fade" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">거래처 구분 추가</h3>
  </div>
  <div class="modal-body">
    <input type="hidden" name='gid' id='gid' value='customer'>
    <input type="text" name='title' style="width:200px;">
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">닫기</button>
    <button class="btn btn-primary" id='save_categorization'>저장</button>
  </div>
</div>


	<!-- 새창 -->
<script>
	//계정의 분류 선택시, 해당 계정목록 셋팅
	$('#new_input').live('click', function() {
		alert('new');
	}


/*
	$(document).ready(function() {
        $('#categorization').modal('show');
	});

    $('#save_categorization').click( function() {
		var gid = $("input[name='gid']").attr('value');
		var title = $("input[name='title']").attr('value');
		
        //alert(gid + title);
        //alert(value2);

		$.ajax({
			type: "POST",
			//url:'/common/set_categorization',
            url:'/basis_info/customer/aaa',
			
			//cache: false,
			data: "gid="+gid+"&title="+title,
			dataType: "html",
			
            //dataType:'json',
            //data:{'gid':gid, 'title':title},
			
			success: function(msg)
			{
				alert('success');

				//alert(msg);
			},
			error:function(msg)
			{
				alert('fail');
				//alert(msg.responseText);
			}
		});
*/		
</script>


</div>