<?php
//var_dump($s_info);
//var_dump($all_accounts);
//var_dump($s_info['prev_account_sum_list']);
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

		$hidden_data = array('prev_start_date'=> '', 'prev_end_date'=> '');	//전기의 검색기간
		echo form_hidden($hidden_data);
		?>


		<table class="table">
			<tr>
				<th>법인설립등기일</th>
				<td><?php echo $s_info['registration_date']; ?></td>
				<th>결산기</th>
				<td><?php echo $s_info['settlement_term']; ?> 월</td>
			</tr>
			<tr>
				<th>기간</th>
				<td	colspan="3">
				<div style="float:left;">
					<select name='start_date' style="width:120px">
						<?php
							foreach ($s_info['start_date_list'] as $value) {
								if ($s_info['start_date'] == $value) $selected = 'selected';
								else $selected = '';
								echo '<option value="'.$value.'" '.$selected.'>'.$value.'</option>';
							}
						?>
					</select>
					~
					<input type="text" name='end_date' id='end_date' style="width:80px" />
				</div>
				<div style="float:left; width:10px; ">
					&nbsp;&nbsp;
				</div>
				</td>
			</tr>
		</table>
		
		<p style="text-align:center">
			<input type="button" class="button lButton bSky" value="검색" onclick='return check_form(this.form);' />
		</p>
		<?php echo form_close(); ?>

		<table class="table nbr" >
		<colgroup>
			<col />
		<colgroup>
		<tr>
		<td style="border:none:">
			<ul class="tabs">
				<li id="tab-1"><label for="tab1"><input type="radio" name="tab" id="one" value="tab1" checked>분류회계</label></li>
				<li id="tab-2"><label for="tab2"><input type="radio" name="tab" id="two" value="tab2">목적사업회계</label></li>
				<li id="tab-3"><label for="tab3"><input type="radio" name="tab" id="three" value="tab3">수익사업회계 분류회계</label></li>
				<li id="tab-4"><label for="tab4"><input type="radio" name="tab" id="four" value="tab4">통합회계</label></li>
			</ul>	
		</td>
		</tr>
		</table>


		<div class="tab_container">
			<div ID="tab1" class="tab_content">
				<div class="text center">
					<h3>제<span name='stage'></span>기 재무상태표 <span>(2013년 4월 30일 현재)</span></h3>
				</div>
				<div class="table_top">
					<div class="table_left">
						0000 사내근로복지기금
					</div>
					<div class="table_right">(단위:원,%) </div>
				</div>

				<table class="table">
						<thead>
							<tr>
								<th width="210px;">계정과목</th>
								<th>전기</th>
								<th nowrap="nowrap" colspan="3" >당기(2013. 3. 31)실적</th>
								<th nowrap="nowrap" colspan="2" >전기대비 증감현황</th>
							</tr>
						</thead>
						<tr>
							<th> 　 </th>
							<th>금 액 </th>
							<th>목적사업회계 </th>
							<th>수익사업회계 </th>
							<th>금 액 </th>
							<th>증 감 액 </th>
							<th>증감율 </th>
						</tr>
						
						
					<?php
					if (count($all_accounts)>0)
						show_account_in_jaemusang(1, $all_accounts, $s_info['prev_account_sum_list'], $s_info['account_sum_list'], $ol);
					?>
					
					<?php /* ?>


					<?php */ ?>
		
				</table>

			</div>

			<div ID="tab2" class="tab_content">
				<div class="text center">
					<h3>제1기 재무상태표 <span>(2013년 4월 30일 현재)</span></h3>
				</div>
				<div class="table_top">
					<div class="table_left">
						0000 사내근로복지기금
					</div>
					<div class="table_right">
						(단위:원,%)
					</div>
				</div>

				<table class="table">
					<thead>
						<tr>
							<th  width="140px;">과 목 </th>
							<th>전기 </th>
							<th>당기(2013. 3. 31)실적 </th>
							<th colspan="2"> 전기대비 증감현황 </th>
						</tr>
					</thead>
					<tr>
						<th></th>
						<th>금 액 </th>
						<th>목적사업회계 </th>
						<th>증 감 액 </th>
						<th>증감율 </th>
					</tr>
					<tr>
						<td><b>[ 자 산 ]</b></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅰ. 유 동 자 산 </th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td> 1. 당    좌 자 산 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (1)현금및현금성자산 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (2)단    기 예 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (3)단기 매매 증권 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (4)미    수 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (5)선 급 법 인 세 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (6)미    수 수 익 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (7)선    급 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (8)가    지 급 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<th>Ⅱ. 비 유 동 자 산 </th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td> 1. 투    자 자 산 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (1)생활안정자금대부금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (2)주택구입자금대부금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (3)장기대여금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (4)출    자 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> 2. 유    형 자 산 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (1)공 구 와 기 구 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (감가상각 누계액)</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<td><b>[ 자 산 총 계 ]</b></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<td><b> [ 부 채 ]</b></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅰ. 유 동 부 채 </th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td> 1. 유    동 부 채 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (1)미    지 급 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (2)예    수 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (3)미지급 법인세 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (4)가    수 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (5)선    수 이 자 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<th>Ⅱ. 비 유 동 부 채 </th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td> 1. 비 유 동 부 채 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (1)퇴직급여충당금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (2)고유목적사업준비금1</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (3)고유목적사업준비금2</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<td><b>[ 부    채 총 계    ]</b></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> 　 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td><b>[  자 본 ]</b></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅰ. 자 본 금 </th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td> 1. 기본재산(기금원금)</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> 2. 자본금(수익사업)</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅱ. 결 손 금 </th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td> 1. 기타적립금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> 2. 처분전결손금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<th> [ 자    본 총 계    ]</th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<th> [ 부 채 와 자 본 총 계 ] </th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</table>

			</div>

			<div ID="tab3" class="tab_content">

				<div class="text center">
					<h3>제1기 재무상태표 <span>(2013년 4월 30일 현재)</span></h3>
				</div>
				<div class="table_top">
					<div class="table_left">
						0000 사내근로복지기금
					</div>
					<div class="table_right">
						(단위:원,%)
					</div>
				</div>

				<table class="table">
					<thead>
						<tr>
							<th width="140px">계 정 과    목 </th>
							<th>전 기 </th>
							<th>당기(2013. 3. 31)실적 </th>
							<th colspan="2"> 전기대비 증감현황 </th>
						</tr>
					</thead>
					<tr>
						<th> 　 </th>
						<th>금 액 </th>
						<th>수익사업회계 </th>
						<th>증 감 액 </th>
						<th>증감율 </th>
					</tr>
					<tr>
						<td><b>[ 자 산 ]</b></td>
						<td> 　 </td>
						<td> 　 </td>
						<td> 　 </td>
						<td> 　 </td>
					</tr>
					<tr>
						<th>Ⅰ. 유 동 자 산 </th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td> 1. 당    좌 자 산 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (1)현금및현금성자산 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (2)단    기 예 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (3)단기 매매 증권 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (4)미    수 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (5)선 급 법 인 세 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (6)미    수 수 익 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (7)선    급 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (8)가    지 급 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<th>Ⅱ. 비 유 동 자 산 </th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td> 1. 투    자 자 산 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (1)생활안정자금대부금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (2)주택구입자금대부금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (3)장기대여금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (4)출    자 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> 2. 유    형 자 산 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (1)공 구 와 기 구 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (감가상각 누계액)</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<td><b> [ 자    산 총 계    ] </b></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<td><b> [    부 채 ] </b></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>Ⅰ. 유 동 부 채 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th> 1. 유    동 부 채 </th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td> (1)미    지 급 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (2)예    수 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (3)미지급 법인세 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (4)가    수 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (5)선    수 이 자 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<th>Ⅱ. 비 유 동 부 채 </th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td> 1. 비 유 동 부 채 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (1)퇴직급여충당금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (2)고유목적사업준비금1</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (3)고유목적사업준비금2</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<td><b>[ 부    채 총 계    ]</b></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<td><b> [    자 본 ]</b></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅰ. 자 본 금 </th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td> 1. 기본재산(기금원금)</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> 2. 자본금(수익사업)</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅱ. 결 손 금 </th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td> 1. 기타적립금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> 2. 처분전결손금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<th><b> [ 자    본 총 계    ] </b></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<th><b>[ 부 채 와 자 본 총 계 ]</b></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</table>
			</div>
			<div ID="tab4" class="tab_content">

				<div class="text center">
					<h3>제1기 재무상태표 <span>(2013년 4월 30일 현재)</span></h3>
				</div>
				<div class="table_top">
					<div class="table_left">
						0000 사내근로복지기금
					</div>
					<div class="table_right">
						(단위:원,%)
					</div>
				</div>

				<table class="table">
					<thead>
						<tr>
							<th width="140">계 정 과    목 </th>
							<th>전 기 </th>
							<th>당기(2013. 3. 31)실적 </th>
							<th colspan="2"> 전기대비 증감현황 </th>
						</tr>
					</thead>
					<tr>
						<th> 　 </th>
						<th>금 액 </th>
						<th>통합회계 </th>
						<th>증 감 액 </th>
						<th>증감율 </th>
					</tr>
					<tr>
						<td><b>[ 자 산 ]</b></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅰ. 유 동 자 산 </th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td> 1. 당    좌 자 산 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (1)현금및현금성자산 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (2)단    기 예 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (3)단기 매매 증권 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (4)미    수 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (5)선 급 법 인 세 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (6)미    수 수 익 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (7)선    급 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (8)가    지 급 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<th>Ⅱ. 비 유 동 자 산 </th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td> 1. 투    자 자 산 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (1)생활안정자금대부금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (2)주택구입자금대부금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (3)장기대여금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (4)출    자 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> 2. 유    형 자 산 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (1)공 구 와 기 구 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (감가상각 누계액)</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<td><b>[ 자    산 총 계    ]</b></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<td><b> [    부 채 ]</b></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅰ. 유 동 부 채 </th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td> 1. 유    동 부 채 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (1)미    지 급 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (2)예    수 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (3)미지급 법인세 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (4)가    수 금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (5)선    수 이 자 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<th>Ⅱ. 비 유 동 부 채 </th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td> 1. 비 유 동 부 채 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (1)퇴직급여충당금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (2)고유목적사업준비금1</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> (3)고유목적사업준비금2</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<td><b> [ 부    채 총 계    ]</b></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<td><b>[    자 본 ]</b></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅰ. 자 본 금 </th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td> 1. 기본재산(기금원금)</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> 2. 자본금(수익사업)</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅱ. 결 손 금 </th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<th> 1. 기타적립금 </th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td> 2. 처분전결손금 </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr>
						<th><b> [ 자    본 총 계    ] </b></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<th> [ 부 채 와 자 본 총 계 ] </th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</table>

			</div>

		</div>

	</div>
</div>


<script>
	//var speed=0;	//click 이벤트 발생이 안하는 에러를 없애기 위해 선언함 값, 어디선가 쓰이고 있음.
	//form 값 체크, form_validation 과는 별개
	function check_form(form){
		if (form.start_date.value == '') {
			alert('검색기간을 입력해주세요.');
			$("input[name='start_date']").focus();
			return false;
		}
		form.submit();
	}

	//검색기간 셋팅
	$('select').live('change', function() {
		$this = $(this);
		
		//특정기간을 선택했을 때 검색기간 셋팅
		if ($this.attr('name') =='start_date') {
			var total_stage = $("select[name='start_date'] option").size();	//전체기수
			
			//php의 검색종료일을 javascript의 배열로 변변환
			var start_date_list = new Array();
			var end_date_list = new Array();
			<?php
				for ($i=0; $i<count($s_info['start_date_list']); $i++) {
					echo 'start_date_list['.$i.']="'.$s_info['start_date_list'][$i].'";';
					echo 'end_date_list['.$i.']="'.$s_info['end_date_list'][$i].'";';
				}
				//for ($i=0; $i<count($s_info['end_date_list']); $i++) {
				//	echo 'end_date_list['.$i.']="'.$s_info['end_date_list'][$i].'";';
				//}
			?>
			
			var idx = $("select[name='start_date'] option:selected").index();
			$("#end_date").val(end_date_list[idx]);

			//2기 이상일 경우, 전기의 검색기간 셋팅
			if (total_stage - idx > 1) {
				$("input[name='prev_start_date']").val(start_date_list[idx+1]);
				$("input[name='prev_end_date']").val(end_date_list[idx+1]);
			} else {
				$("input[name='prev_start_date']").val('');
				$("input[name='prev_end_date']").val('');
			}
			
			$("span[name='stage']").text(total_stage-idx);	//재무상태표 제목에 기수 표기
		}
	});
	
	$("select[name='start_date']").trigger('change');
	
</script>

