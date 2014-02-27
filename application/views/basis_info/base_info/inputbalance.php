<?php
//var_dump($s_info);
//var_dump($all_list);
//var_dump($s_info['account_sum_list']);
//var_dump($_POST);


$ol = $this->config->item('ol');
$basic_data = $this->config->item('basic_data');
//echo 'key : '.key($basic_data);
?>

<div class="contentInner">
	
		<?php
		$attributes = array('name'=>'form','class' => 'form-horizontal');
		echo form_open('/popup/inputbalance', $attributes);

		$hidden_data = array('kind'=> '', 'bno'=> '', 'stage'=> '', 'start_date'=> '', 'end_date'=> '', 'data_name'=> '');
		echo form_hidden($hidden_data);
		?>
		<?php echo form_close(); ?>
		
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

		<div class="clear"></div>
		<table class="table nbr" >
				<tr>
					<td style="border:none:">
					<ul>
						<li id="tab-1">
							<input type="radio" name="tab" id="one" value="tab1" onclick='location.href="/basis_info/base_info/inputbalance?kind=jaemusang"' <?php if ($s_info['kind'] == 'jaemusang') echo 'checked'; ?>>재무상태표
						</li>
						<li id="tab-2">
							<input type="radio" name="tab" id="two" value="tab2" onclick='location.href="/basis_info/base_info/inputbalance?kind=sonik"' <?php if ($s_info['kind'] == 'sonik') echo 'checked'; ?>>손익계산서
						</li>
						<li id="tab-3">
							<input type="radio" name="tab" id="three" value="tab3" onclick='location.href="/basis_info/base_info/inputbalance?kind=earned_surplus"' <?php if ($s_info['kind'] == 'earned_surplus') echo 'checked'; ?>>이익잉여금처분계산서
						</li>
					</ul></td>
				</tr>
		</table>

		<table class="table">
			<thead>
				<tr>
					<th>기수</th>
					<th>년도</th>
					<th>자료명</th>
					<th>작성자</th>
					<th>작성일자</th>
					<th>작성조건</th>
					<th width="110px">수정/입력</th>
				</tr>
			</thead>
			<tbody>
				
				<?php
					$i=0;
					foreach ($all_list as $arr) {
						$i++;
						$start_year = substr($s_info['start_date_list'][$i], 0, 4);
						$input_date = $arr['input_date'];
						$input_method = $arr['input_method'];
						
						if ($input_date == '0000-00-00 00:00:00') {
							$input_date='';
							$input_method = '미작성';
						}
						
						$input_button = '';
						
						//$onclick = 'onclick=\'window.open("/popup/inputbalance?stage='.$arr['stage'].'", "pop", "width=900, height=600, scrollbars=yes, status=yes"); return false;\' readonly placeholder="여기를 클릭하세요."';
						
						$input_button = '<input type="button" class="btn btn-small btn-info" kind="'.$s_info['kind'].'" bno="'.$arr['bno'].'" stage="'.$arr['stage'].'" start_date="'.$s_info['start_date_list'][$i].'" end_date="'.$s_info['end_date_list'][$i].'" data_name="'.$basic_data[$s_info['kind']].'" value="수기입력" />';

				?>
				<tr>
					<td><?php echo $arr['stage']; ?>기</td>
					<td><?php echo $start_year; ?>년</td>
					<td><?php echo $basic_data[$arr['data_name']]; ?></td>
					<td><?php echo $arr['writer']; ?></td>
					<td><?php echo $input_date; ?></td>
					<td><?php echo $input_method; ?></td>
					<td><?php echo $input_button; ?></td>
				</tr>
				<?php
					}
				?>
				
				
			</tbody>
		</table>

	</div>
</div>



<div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:1000px;  margin-left: -500px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			×
		</button>
		<h3 id="myModalLabel">손익계산서  입력</h3>
	</div>
	<div class="modal-body">
		<table class="table">
			<thead>
				<tr>
					<th width="180px">계정과목</th>
					<th>전    기 </th>
					<th colspan="3">당 기 </th>
				</tr>
			</thead>
			<tr>
				<th></th>
				<th> 금    액 </th>
				<th>목적사업회계 </th>
				<th>기금관리회계 </th>
				<th>합 계 </th>
			</tr>
			<tr>
				<th>Ⅰ.사업 수익 </th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
			</tr>
			<tr>
				<td>1)이    자 수 익 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>2)배 당 금 수 익 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>3)대부이자수익(생활)</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>4)대부이자수익(주택)</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>5)단기매매증권처분이익 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>6)단기매매증권평가이익 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<th> Ⅱ.사업 비용 </th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
			</tr>
			<tr>
				<td> 가.고유목적사업비용 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>1)재난.재해지원 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>2)체육.문화활동지원 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>3)경 조 비 지 원 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>4)장기근속자지원 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>5)장 학 금 지 원 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>6)육아휴직 지 원 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td> 나.증식사업비용 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>1)단기매매증권처분손실 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>2)단기매매증권평가손실 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<th> Ⅲ.사업총이익 </th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
			</tr>
			<tr>
				<th> Ⅳ.일반관리비 </th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>

			</tr>
			<tr>
				<td> 가.인 건 비 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>1)급    여 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>2)퇴직급여 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td> 나.일반관리비 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>1)복 리 후 생 비 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>2)여 비 교 통 비 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>3)통    신 비 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>4)세 금 과 공 과 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>5)소    모 품 비 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>6)도 서 인 쇄 비 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>7)수 선 유 지 비 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>8)지 급 수 수 료 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>9)교 육 훈 련 비 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>10)협 력 비 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>11)회 의 진 행 비 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>12)등 기 소 송 비 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<th> Ⅴ.사 업    이익 </th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
			</tr>
			<tr>
				<th>Ⅵ.사업외수익 </th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
			</tr>
			<tr>
				<td>1)잡    이 익 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>2)고유목적사업준비금1전입수입 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>3)고유목적사업준비금2전입수입 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<th> Ⅶ.사업외비용 </th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
			</tr>
			<tr>
				<td>1)잡    손 실 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<td>2)고유목적사업준비금전입액 </td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
				<td>
				<input type="text" style="width:80px">
				</td>
			</tr>
			<tr>
				<th>Ⅷ.법인세차감전순이익 </th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
			</tr>
			<tr>
				<th> Ⅸ.법인세비용 </th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
			</tr>
			<tr>
				<th>Ⅹ.당기순이익(손실) </th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
				<th>
				<input type="text" style="width:80px">
				</th>
			</tr>
		</table>
		<div class="buttonline">
			<a href="#myModal2" class="btn btn-large btn-success"  data-toggle="modal" role="button">자료입력</a>
		</div>

	</div>
	<div class="modal-footer">
		<button class="btn btn-primary">
			저장
		</button>
	</div>
</div>

<div id="myModal3" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:1000px;  margin-left: -500px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			×
		</button>
		<h3 id="myModalLabel">이익잉여금처분계산서  입력</h3>
	</div>
	<div class="modal-body">
		<table class="table">
			<thead>
				<tr>
					<th colspan="2">1. 이익잉여금처분계산서 </th>
					<th colspan="2">2. 결손금처리계산서 </th>
				</tr>
			</thead>
			<tbody>

				<tr>
					<th width="250px">과 목 </th>
					<th>금 액 </th>
					<th width="250px">과 목 </th>
					<th>금 액 </th>
				</tr>
				<tr>
					<th>Ⅰ.미처분이익잉여금 </th>
					<td>
					<input type="text" style="width:120px;">
					</td>
					<th>Ⅰ.미처리결손금 </th>
					<td>
					<input type="text" style="width:120px;">
					</td>
				</tr>
				<tr>
					<td>1.전기이월미처분이익잉여금
					<br />
					(또는 전기이월 미처리결손금) </td>
					<td>
					<input type="text" style="width:120px;">
					</td>
					<td>1.전기이월미처리결손금
					<br />
					(또는 전기이월미처분이익잉여금) </td>
					<td>
					<input type="text" style="width:120px;">
					</td>
				</tr>
				<tr>
					<td>2.당기순이익
					<br />
					(또는 당기순손실) </td>
					<td>
					<input type="text" style="width:120px;">
					</td>
					<td>2.당기순손실
					<br />
					(또는 당기순이익) </td> 
					<td>
					<input type="text" style="width:120px;">
					</td>
				</tr>
				<tr>
					<th>Ⅱ.임의적립금 등의 이입액 </th>
					<td>
					<input type="text" style="width:120px;">
					</td>
					<th>Ⅱ.결손금처리액 </th>
					<td>
					<input type="text" style="width:120px;">
					</td>
				</tr>
				<tr>
					<td>합 계 </td>
					<td>
					<input type="text" style="width:120px;">
					</td>
					<td>1.임의적립금이입액 </td>
					<td>
					<input type="text" style="width:120px;">
					</td>
				</tr>
				<tr>
					<th>Ⅲ.이익잉여금 처분액 </th>
					<td>
					<input type="text" style="width:120px;">
					</td>
					<th>Ⅲ.차기이월미처리결손금 </th>
					<td>
					<input type="text" style="width:120px;">
					</td>
				</tr>
				<tr>
					<td>1.이익준비금 </td>
					<td>
					<input type="text" style="width:120px;">
					</td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td height="24">2.기타법정적립금 </td>
					<td>
					<input type="text" style="width:120px;">
					</td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<th>Ⅳ.차기이월미처분이익잉여금 </th>
					<td>
					<input type="text" style="width:120px;">
					</td>
					<td colspan="2"></td>
				</tr>
			</tbody>
		</table>
		<div class="buttonline">
			<a href="#myModal3" class="btn btn-large btn-success"  data-toggle="modal" role="button">자료입력</a>
		</div>

	</div>
	<div class="modal-footer">
		<button class="btn btn-primary">
			저장
		</button>
	</div>
</div>


<script>
	//검색기간 셋팅
	$('input').live('click', function() {
		$this = $(this);
		
		//특정기간을 선택했을 때 검색기간 셋팅
		if ($this.attr('value') =='수기입력') {
			//console.log($this.attr('value'));
			form.kind.value = $this.attr('kind');
			form.bno.value = $this.attr('bno');
			form.stage.value = $this.attr('stage');
			form.start_date.value = $this.attr('start_date');
			form.end_date.value = $this.attr('end_date');
			form.data_name.value = $this.attr('data_name');
			form.submit();
		}
	});
	
	$("select[name='start_date']").trigger('change');
	
</script>

