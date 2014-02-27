<?php
//var_dump($s_info);
//var_dump($all_list[0]);
//var_dump($_POST);

?>

<div class="contentInner">
	<div class="row-fluid">

		<div class="title">
			
		<?php $this->load->view('/include/main_head.php', array('uri_depth'=>2)); ?>

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

		$hidden_data = array('start_date'=> '', 'end_date'=> '');	//전표삭제시 사용
		echo form_hidden($hidden_data);
		?>


		<table class="table">
			<tr>
				<th>회계기간 </th>
				<td>
				<div style="float:left;">
					<!--input type="text" name='start_date' id='start_date' value="<?php echo $s_info['start_date']; ?>" style="width:80px" />
					~
					<input type="text" name='end_date' id='end_date' value="<?php echo $s_info['end_date']; ?>" style="width:80px" /-->
				</div>
				<div style="float:left; width:10px; ">
					&nbsp;&nbsp;
				</div>
				<div style="float:left;">
					<select name='search_year' style="width:80px">
						<?php
						for ($i=date('Y'); $i>=$s_info['registration_date']; $i--) {
							if ($s_info['search_year'] == $i) $selected = 'selected';
							else $selected = '';
							echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
						}
						?>
					</select> 년
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
					</select> 월
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
				<th>구분 </th>
				<td>
				<select style="width:80px;">
					<option>신청</option><option>집행</option>
				</select></td>
			</tr>
		</table>

		<p style="text-align:center">
			<input type="submit" class="button lButton bSky" value="검색" />
		</p>
		<?php echo form_close(); ?>

		<table class="table nbr">
			<colgroup>
				<col width="120px">
				<col width="180px">
				<col />
			</colgroup>
			<tbody>
				<tr>
					<td style="border:none:">
					<ul class="tabs">
						<li id="tab-1">
							<label for="tab1">
								<input type="radio" name="tab" id="one" value="staff" checked />
								금액</label>
						</li>
						<li id="tab-2">
							<label for="tab2">
								<input type="radio" name="tab" id="two" value="customers" />
								인원</label>
						</li>
					</ul></td>
					<td></td>
				</tr>
			</tbody>
		</table>

		<div class="tab_container">
			<div ID="staff" class="tab_content">
				<table style="width:100%">
					<colgroup>
						<col width="150px">
						</col>
						<col />
						<col width="80px">
						</col>
					<colgroup>

						<tr>
							<td>사내근로복지기금</td>
							<td  style="text-align:center"><h3>목적사업신청현황
							<br>
							<span>2013년 8월 </span></h3></td>
							<td text="right">(단위: 원)</td>
						</tr>

				</table>

				<table class="table">
					<thead>
						<tr>
							<th>목적사업</th>
							<th>경조비</th>
							<th>장학금</th>
							<th>의료비</th>
							<th>주택자금이자</th>
							<th>육아휴직</th>
							<th>장기근속자지원</th>
							<th>체육문화활동</th>
							<th>기념품지급</th>
							<th>계</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1월</td>
							<td>12,000원</td>
							<td>10원</td>
							<td>20원</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>

						</tr>
						<tr>
							<td>2월</td>
							<td>12,000원</td>
							<td>10원</td>
							<td>20원</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>

						</tr>
						<tr>
							<td>2월</td>
							<td>12,000원</td>
							<td>10원</td>
							<td>20원</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>
						</tr>
						<tr>
							<td>3월</td>
							<td>12,000원</td>
							<td>10원</td>
							<td>20원</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>

						</tr>
						<tr>
							<td>4월</td>
							<td>12,000원</td>
							<td>10원</td>
							<td>20원</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>

						</tr>
						<tr>
							<td>5월</td>
							<td>12,000원</td>
							<td>10원</td>
							<td>20원</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>

						</tr>
						<tr>
							<td>6월</td>
							<td>12,000원</td>
							<td>10원</td>
							<td>20원</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>

						</tr>
						<tr>
							<td>7월</td>
							<td>12,000원</td>
							<td>10원</td>
							<td>20원</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>

						</tr>
						<tr>
							<td>8월</td>
							<td>12,000원</td>
							<td>10원</td>
							<td>20원</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>

						</tr>
						<tr>
							<td>9월</td>
							<td>12,000원</td>
							<td>10원</td>
							<td>20원</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>

						</tr>
						<tr>
							<td>10월</td>
							<td>12,000원</td>
							<td>10원</td>
							<td>20원</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>

						</tr>
						<tr>
							<td>11월</td>
							<td>12,000원</td>
							<td>10원</td>
							<td>20원</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>

						</tr>
						<tr>
							<td>12월</td>
							<td>12,000원</td>
							<td>10원</td>
							<td>20원</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>
							<td>1원</td>
							<td>100원</td>
							<td>100</td>

						</tr>

						<tr>
							<th>계</th>
							<th>12,000원</th>
							<th>10원</th>
							<th>20원</th>
							<th>1원</th>
							<th>100원</th>
							<th>100</th>
							<th>10원</th>
							<th>20원</th>
							<th>1원</th>

						</tr>

					</tbody>
				</table>
				<div class="graph_ar">
					그래프 영역

				</div>
			</div>

			<div ID="customers" class="tab_content">
				<table style="width:100%">
					<colgroup>
						<col width="150px">
						</col>
						<col />
						<col width="80px">
						</col>
					<colgroup>

						<tr>
							<td>사내근로복지기금</td>
							<td  style="text-align:center"><h3>목적사업신청현황
							<br>
							<span>2013년 8월 </span></h3></td>
							<td text="right">(단위: 명)</td>
						</tr>

				</table>

				<table class="table">
					<thead>
						<tr>
							<th>목적사업</th>
							<th>경조비</th>
							<th>장학금</th>
							<th>의료비</th>
							<th>주택자금이자</th>
							<th>육아휴직</th>
							<th>장기근속자지원</th>
							<th>체육문화활동</th>
							<th>기념품지급</th>
							<th>계</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1월</td>
							<td>12,000명</td>
							<td>10명</td>
							<td>20명</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>

						</tr>
						<tr>
							<td>2월</td>
							<td>12,000명</td>
							<td>10명</td>
							<td>20명</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>

						</tr>
						<tr>
							<td>2월</td>
							<td>12,000명</td>
							<td>10명</td>
							<td>20명</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>
						</tr>
						<tr>
							<td>3월</td>
							<td>12,000명</td>
							<td>10명</td>
							<td>20명</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>

						</tr>
						<tr>
							<td>4월</td>
							<td>12,000명</td>
							<td>10명</td>
							<td>20명</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>

						</tr>
						<tr>
							<td>5월</td>
							<td>12,000명</td>
							<td>10명</td>
							<td>20명</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>

						</tr>
						<tr>
							<td>6월</td>
							<td>12,000명</td>
							<td>10명</td>
							<td>20명</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>

						</tr>
						<tr>
							<td>7월</td>
							<td>12,000명</td>
							<td>10명</td>
							<td>20명</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>

						</tr>
						<tr>
							<td>8월</td>
							<td>12,000명</td>
							<td>10명</td>
							<td>20명</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>

						</tr>
						<tr>
							<td>9월</td>
							<td>12,000명</td>
							<td>10명</td>
							<td>20명</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>

						</tr>
						<tr>
							<td>10월</td>
							<td>12,000명</td>
							<td>10명</td>
							<td>20명</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>

						</tr>
						<tr>
							<td>11월</td>
							<td>12,000명</td>
							<td>10명</td>
							<td>20명</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>

						</tr>
						<tr>
							<td>12월</td>
							<td>12,000명</td>
							<td>10명</td>
							<td>20명</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>
							<td>1명</td>
							<td>100명</td>
							<td>100</td>

						</tr>

						<tr>
							<th>계</th>
							<th>12,000명</th>
							<th>10명</th>
							<th>20명</th>
							<th>1명</th>
							<th>100명</th>
							<th>100</th>
							<th>10명</th>
							<th>20명</th>
							<th>1명</th>

						</tr>

					</tbody>
				</table>
				<div class="graph_ar">
					그래프 영역

				</div>
			</div>

		</div>

	</div>

</div>


<script>
	//검색기간 셋팅
	$('select').live('change', function() {
		$this = $(this);
		//var search_year = $("input[name='search_year']").val();
		var search_year = $("select[name='search_year'] option:selected").val();
		//console.log(search_year);
		
		//특정기간을 선택했을 때 검색기간 셋팅
		if ($this.attr('name') =='search_year') {
			//$("select[name='search_year'] option:eq(0)").attr("selected", "selected");
			$("select[name='search_month'] option:eq(0)").attr("selected", "selected");
			$("select[name='search_month4'] option:eq(0)").attr("selected", "selected");
			$("select[name='search_month2'] option:eq(0)").attr("selected", "selected");

			$("input[name='start_date']").val(search_year+'-01-01');
			$("input[name='end_date']").val(search_year+'-12-31');
		} else if ($this.attr('name') =='search_month') {
			$("select[name='search_year'] option:eq(0)").attr("selected", "selected");
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
			$("select[name='search_year'] option:eq(0)").attr("selected", "selected");
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
			$("select[name='search_year'] option:eq(0)").attr("selected", "selected");
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
	//form_validation 시 해당 값들 셋팅
	if ("<?php echo $s_info['search_year']; ?>" != '') {
		//$("select[name='search_year']").val("<?php echo $s_info['search_year']; ?>").attr("selected", "selected");
		$("select[name='search_year']").trigger('change');
	}
	
</script>

