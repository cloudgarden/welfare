<?php
//var_dump($s_info);
//var_dump($all_accounts);
//var_dump($s_info['account_sum_list']);
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
				<th>검색조건</th>
				<td	>
				<div style="float:left;">
					<select name='search_year' style="width:80px">
						<?php
						for ($i=date('Y'); $i>=date("Y",strtotime($s_info['registration_date'])); $i--) {
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
				<th>검색기간</th>
				<td	>
				<div style="float:left;">
					<input type="text" name='start_date' value="<?php echo $s_info['start_date']; ?>" style="width:80px" readonly />
					~
					<input type="text" name='end_date' value="<?php echo $s_info['end_date']; ?>" style="width:80px" readonly />
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
						<li id="tab-1">
							<label for="tab1">
								<input type="radio" name="tab" id="one" value="tab1" checked>
								분류회계</label>
						</li>
						<li id="tab-2">
							<label for="tab2">
								<input type="radio" name="tab" id="two" value="tab2">
								목적사업회계</label>
						</li>
						<li id="tab-3">
							<label for="tab3">
								<input type="radio" name="tab" id="three" value="tab3">
								수익사업회계 분류회계</label>
						</li>
						<li id="tab-4">
							<label for="tab4">
								<input type="radio" name="tab" id="four" value="tab4">
								통합회계</label>
						</li>
					</ul></td>
				</tr>
		</table>

		<div class="tab_container">
			<div ID="tab1" class="tab_content">
				<div class="text center">
					<h3>손익계산서 <span>(2013년 4월 30일 현재)</span></h3>
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
					<thead >
						<tr>
							<th  width="210px">계정과목 </th>
							<th>전    기 </th>
							<th colspan="3">당 기 </th>
							<th nowrap="nowrap" colspan="2" >전기대비 증감현황</th>
						</tr>
					</thead>
					<tr>
						<th></th>
						<th> 금    액 </th>
						<th >목적사업회계 </th>
						<th >기금관리회계 </th>
						<th >합 계 </th>
						<th>증 감 액 </th>
						<th>증감율 </th>
					</tr>

						<?php
							//손익계산서 합계, 법인세차감전순이익 , 당기순이익  : array('ano' => array(전기, 목적사업회계, 기금관리회계, 분류없음회계, 당기합계))
							//ano - 17:사업수익, 18:사업외수익, 21:사업외비용, 22:법인세비용
							$first_weight = 1;	//보여줄 계정목록중 첫번째 depth인 것, 부모와 상관없이 순서를 붙이기 위해 필요
							if (count($s_info['account_sum_list'])>0) {
								show_account_in_sonik(2, $all_accounts, $s_info['prev_account_sum_list'], $s_info['account_sum_list'], $ol, $first_weight);
							}
						 ?>


				</table>

			</div>

			<div ID="tab2" class="tab_content">
				<div class="text center">
					<h3>손익계산서 <span>(2013년 4월 30일 현재)</span></h3>
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
							<th  width="140px">계정과목 </th>
							<th>전 기 </th>
							<th colspan="2" >당 기 </th>
						</tr>
					</thead>
					<tr>
						<th></th>
						<th> 금    액 </th>
						<th>목적사업회계 </th>
						<th>증감(%)</th>
					</tr>
					<tr>
						<th>Ⅰ.사업 수익 </th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td>1)이    자 수 익 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>2)배 당 금 수 익 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>3)대부이자수익(생활)</td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>4)대부이자수익(주택)</td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>5)단기매매증권처분이익 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>6)단기매매증권평가이익 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅱ.사업 비용 </th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td> 가.고유목적사업비용 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>1)재난.재해지원 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>2)체육.문화활동지원 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>3)경 조 비 지 원 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>4)장기근속자지원 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>5)장 학 금 지 원 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>6)육아휴직 지 원 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> 나.증식사업비용 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>1)단기매매증권처분손실 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>2)단기매매증권평가손실 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th> Ⅲ.사업총이익 </th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<th>Ⅳ.일반관리비 </th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td> 가.인 건 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>1)급    여 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>2)퇴직급여 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td> 나.일반관리비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>1)복 리 후 생 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>2)여 비 교 통 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>3)통    신 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>4)세 금 과 공 과 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>5)소    모 품 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>6)도 서 인 쇄 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>7)수 선 유 지 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>8)지 급 수 수 료 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>9)교 육 훈 련 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>10)협 력 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>11)회 의 진 행 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>12)등 기 소 송 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅴ.사 업    이익</th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<th>Ⅵ.사업외수익</th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td>1)잡    이 익 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>2)고유목적사업준비금1전입수입 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>3)고유목적사업준비금2전입수입 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅶ.사업외비용</th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td>1)잡    손 실 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>2)고유목적사업준비금전입액 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅷ.법인세차감전순이익</th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<th>Ⅸ.법인세비용</th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<th>Ⅹ.당기순이익(손실)</th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</table>

			</div>

			<div ID="tab3" class="tab_content">

				<div class="text center">
					<h3>손익계산서 <span>(2013년 4월 30일 현재)</span></h3>
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
							<th  width="140px">계정과목 </th>
							<th>전 기 </th>
							<th colspan="2" >당 기 </th>
						</tr>
					</thead>
					<tr>
						<th></th>
						<th> 금    액 </th>
						<th>수익사업회계 </th>
						<th>증감(%)</th>
					</tr>
					<tr>
						<th>Ⅰ.사업 수익 </th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td>1)이    자 수 익 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>2)배 당 금 수 익 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>3)대부이자수익(생활)</td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>4)대부이자수익(주택)</td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>5)단기매매증권처분이익 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>6)단기매매증권평가이익 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅱ.사업 비용 </th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td>가.고유목적사업비용 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>1)재난.재해지원 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>2)체육.문화활동지원 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>3)경 조 비 지 원 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>4)장기근속자지원 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>5)장 학 금 지 원 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>6)육아휴직 지 원 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>나.증식사업비용 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>1)단기매매증권처분손실 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>2)단기매매증권평가손실 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅲ.사업총이익 </th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<th>Ⅳ.일반관리비 </th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td>가.인 건 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>1)급    여 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>2)퇴직급여 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>나.일반관리비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>1)복 리 후 생 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>2)여 비 교 통 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>3)통    신 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>4)세 금 과 공 과 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>5)소    모 품 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>6)도 서 인 쇄 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>7)수 선 유 지 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>8)지 급 수 수 료 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>9)교 육 훈 련 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>10)협 력 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>11)회 의 진 행 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>12)등 기 소 송 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅴ.사 업    이익 </th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<th>Ⅵ.사업외수익 </th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td>1)잡    이 익 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>2)고유목적사업준비금1전입수입 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>3)고유목적사업준비금2전입수입 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅶ.사업외비용 </th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td>1)잡    손 실 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>2)고유목적사업준비금전입액 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅷ.법인세차감전순이익 </th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<th>Ⅸ.법인세비용 </th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<th>Ⅹ.당기순이익(손실) </th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</table>
			</div>
			<div ID="tab4" class="tab_content">

				<div class="text center">
					<h3>손익계산서 <span>(2013년 4월 30일 현재)</span></h3>
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
							<th width="140px">계정과목 </th>
							<th>전 기 </th>
							<th colspan="2" >당 기 </th>
						</tr>
					</thead>
					<tr>
						<th></th>
						<th> 금    액 </th>
						<th>통합회계 </th>
						<th>증감(%)</th>
					</tr>
					<tr>
						<th>Ⅰ.사업 수익 </th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td>1)이    자 수 익 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>2)배 당 금 수 익 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>3)대부이자수익(생활)</td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>4)대부이자수익(주택)</td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>5)단기매매증권처분이익 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>6)단기매매증권평가이익 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅱ.사업 비용 </th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td>가.고유목적사업비용 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>1)재난.재해지원 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>2)체육.문화활동지원 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>3)경 조 비 지 원 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>4)장기근속자지원 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>5)장 학 금 지 원 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>6)육아휴직 지 원 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>나.증식사업비용 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>1)단기매매증권처분손실 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>2)단기매매증권평가손실 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅲ.사업총이익</th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<th>Ⅳ.일반관리비</th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td>가.인 건 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>1)급    여 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>2)퇴직급여 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>나.일반관리비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>1)복 리 후 생 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>2)여 비 교 통 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>3)통    신 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>4)세 금 과 공 과 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>5)소    모 품 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>6)도 서 인 쇄 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>7)수 선 유 지 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>8)지 급 수 수 료 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>9)교 육 훈 련 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>10)협 력 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>11)회 의 진 행 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>12)등 기 소 송 비 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅴ.사 업    이익 </th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<th>Ⅵ.사업외수익 </th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td>1)잡    이 익 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>2)고유목적사업준비금1전입수입 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>3)고유목적사업준비금2전입수입 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅶ.사업외비용</th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<td>1)잡    손 실 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>2)고유목적사업준비금전입액 </td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th>Ⅷ.법인세차감전순이익 </th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<th>Ⅸ.법인세비용</th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<th>Ⅹ.당기순이익(손실)</th>
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
		var search_year = $("select[name='search_year'] option:selected").val();
		
		//특정기간을 선택했을 때 검색기간 셋팅
		if ($this.attr('name') =='search_year') {
			//console.log(year);
			$("input[name='start_date']").val(search_year+'-01-01');
			$("input[name='end_date']").val(search_year+'-12-31');
			
			$("input[name='prev_start_date']").val((search_year-1)+'-01-01');
			$("input[name='prev_end_date']").val((search_year-1)+'-12-31');
		//특정기간을 선택했을 때 검색기간 셋팅
		} else if ($this.attr('name') =='search_month') {
			//$("select[name='search_month'] option:eq(0)").attr("selected", "selected");
			$("select[name='search_month4'] option:eq(0)").attr("selected", "selected");
			$("select[name='search_month2'] option:eq(0)").attr("selected", "selected");

			var search_month = parseInt($this.val());
			var prev_search_month = search_month-1;

			if (search_month<10) search_month = '0'+search_month;
			if (prev_search_month<10) prev_search_month = '0'+prev_search_month;
			
			$("input[name='start_date']").val(search_year+'-'+search_month+'-'+'01');
			$("input[name='end_date']").val(search_year+'-'+search_month+'-'+'31');
			
			if ($this.val()==1) {	//1월인 경우 전년도 12월
				$("input[name='prev_start_date']").val((search_year-1)+'-12-01');
				$("input[name='prev_end_date']").val((search_year-1)+'-12-31');
			} else {
				$("input[name='prev_start_date']").val(search_year+'-'+prev_search_month+'-'+'01');
				$("input[name='prev_end_date']").val(search_year+'-'+prev_search_month+'-'+'31');
			}
		} else if ($this.attr('name') =='search_month4') {
			$("select[name='search_month'] option:eq(0)").attr("selected", "selected");
			//$("select[name='search_month4'] option:eq(0)").attr("selected", "selected");
			$("select[name='search_month2'] option:eq(0)").attr("selected", "selected");

			var end_month = parseInt($this.val())+2;
			if (end_month<10) end_month = '0'+end_month;
			
			$("input[name='start_date']").val(search_year+'-'+$this.val()+'-'+'01');
			$("input[name='end_date']").val(search_year+'-'+end_month+'-'+'31');
		} else if ($this.attr('name') =='search_month2') {
			$("select[name='search_month'] option:eq(0)").attr("selected", "selected");
			$("select[name='search_month4'] option:eq(0)").attr("selected", "selected");
			//$("select[name='search_month2'] option:eq(0)").attr("selected", "selected");

			var end_month = parseInt($this.val())+5;
			if (end_month<10) end_month = '0'+end_month;
			
			$("input[name='start_date']").val(search_year+'-'+$this.val()+'-'+'01');
			$("input[name='end_date']").val(search_year+'-'+end_month+'-'+'31');
		}
	});
	
	//검색기간 초기값 셋팅
	$("select[name='search_year']").trigger('change');
	
</script>

