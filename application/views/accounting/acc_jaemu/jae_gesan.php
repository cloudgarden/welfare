<?php
//var_dump($s_info);
//var_dump($all_accounts);
//var_dump($s_info['account_sum_list']);
//var_dump($_POST);

$ol = $this->config->item('ol');

//echo $s_info['stage'];
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
				<th width=150>법인설립등기일</th>
				<td width=200><?php echo $s_info['registration_date']; ?></td>
				<th width=100>결산기</th>
				<td><?php echo $s_info['settlement_term']; ?> 월</td>
			</tr>
			<tr>
				<th>기간</th>
				<td	colspan="3">
				<div style="float:left;">
					<select name='start_date' style="width:120px">
						<?php
							$i=0;
							foreach ($s_info['start_date_list'] as $value) {
								$i++;
								if ($i==1) continue;	//당기는 볼 수 없음
								
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
				<?php /* ?>
				<input type="text" name='start_date' value='<?php echo $s_info['start_date_list'][0]; ?>' style="width:80px" readonly />
				~ <input type="text" name='end_date' value='<?php echo $s_info['end_date_list'][0]; ?>' style="width:80px" readonly />
				<?php */ ?>
				</td>
			</tr>
		</table>
		
		<p style="text-align:center">
			<input type="button" class="button lButton bSky" value="검색" onclick='return check_form(this.form);' />
		</p>
		<?php echo form_close(); ?>
		
		
		
		<div class="text center">
			<h3>이익잉여금 처분계산서 <span>(2013년 4월 30일 현재)</span></h3>
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
					<th colspan="2">1. 이익잉여금처분계산서 </th>
					<th colspan="2">2. 결손금처리계산서 </th>
				</tr>
			</thead>
			<?php
				//이익잉여금처분계산서
				$first_weight = 0;	//보여줄 계정목록중 첫번째 depth인 것, 부모와 상관없이 순서를 붙이기 위해 필요
				show_account_in_earned_surplus($earned_surplus_acc, $all_accounts, $ol, $first_weight);
			?>
		</table>

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

