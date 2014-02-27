<?php
	//echo 'mode : '.$s_info['mode'].'<br>';
	//echo $this->db->last_query();
	//var_dump($_POST);
	var_dump($s_info);
		
		
?>
	<style>
	#sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
	#sortable li { margin: 0 5px 5px 5px; padding: 5px; font-size: 1em; width: auto; ; height: 1.5em; }
	html>body #sortable li { height: 1.5em; line-height: 1.2em; }
	.ui-state-highlight { height: 1.5em; line-height: 1.2em; }
	</style>
	<script>
	$(function() {
		$( "#sortable" ).sortable({
			placeholder: "ui-state-highlight"
		});
		$( "#sortable" ).disableSelection();
	});
	</script>



<div class="contentInner">
	<div class="row-fluid">

		<div class="title">

			<div class="text">
			<blockquote><h3><?php echo $menus[$this->uri->segment(2)][$this->uri->segment(3)]['title']; ?></h3></blockquote>
			</div>

		</div>
		
		<br>

		<div>
			<!--카테고리 입력-->
			<div class="text">
					<h3>사용자정의 Table 추가</h3>
			</div>
			<?php 
			if(validation_errors()) {
					//$validation_errors = str_replace("'코드'는(은) 필수항목입니다.", "<b>선택된 계정이 없습니다.</b>", validation_errors());
					//echo '<br><br>'.$validation_errors;
					$this->load->view('validation_modal');		
			?>
			<?php 
			}
			?>
	
			 <?php
			 $attributes = array('class'=>'form-horizontal');
			 echo form_open('/basis_info/customize_table', $attributes);
			 
			 $data = array('mode'=>'add');
			 echo form_hidden($data);
			 ?>
			 Table명 : <input type="text" id="fid" name="fid">
			 Title : <input type="text" id="fname" name="fname">
			 <input type="submit" class="btn btn-primary" value="입력" />
			 <br>주의)Table명은 DB의 실제 Table명과 반드시 일치해야 합니다.
			 <?php echo form_close(); ?>
		</div>

<br>
		<div>
			<!--top level 카테고리 목록-->
			<div class="text">
					<h3>사용자정의 Table 목록</h3>
			</div>
	
			 <?php
			 $fname = '';
			 foreach ($all_list as $row) {
			 	$style="";
				if ($s_info['fid'] == $row['fid']) {
					$style='style="color:#ff0000; font-weight:bold;"';
					$fname = $row['fname'];
				}
			 ?>
			 <span style="display:inline-block"><a href="/basis_info/setting/customize/?fid=<?php echo $row['fid']; ?>" <?php echo $style; ?>><?php echo $row['fname']; ?></a></span>
			 <?php } ?>
		</div>

	 <?php
	 	if ($s_info['fid']) {
	 ?>
 <br>
		<div>
			<!--선택된 분류의 목록-->
			<div class="text">
					<h3>"<?php echo $fname; ?>"의 항목(필드) 추가/수정</h3>
			</div>
			<?php
			$attributes = array('class'=>'form-horizontal');
			echo form_open('/basis_info/setting/customize', $attributes);
			
			$data = array('mode'=>'add', 'fid'=>$s_info['fid'], 'weight'=>count($s_list)+1);
			echo form_hidden($data);
			?>
			<table class="table">
				<tr>
					<th>필드명</th>
					<td><input type="text" id="column_title" name="column_title" value="<?php if ($s_info['fid'] != 'sortable') echo $s_info['column_title']; ?>" style="width:150px;"></td>
						
					<th>값 유형</th>
					<td>
						<ul>
							<li><input type="radio" name="type" value='text' <?php if (in_array($s_info['type'], array('', 'text'))) echo 'checked'; ?>>Text</li>
							<li><input type="radio" name="type" value='list' <?php if ($s_info['type'] == 'list') echo 'checked'; ?>>목록</li>
						</ul>
					</td>
				</tr>
				<tr>
					<th>사용</th>
					<td>
						<ul>
							<li><input type="radio" name="use" value='Y' <?php if (in_array($s_info['use'], array('', 'Y'))) echo 'checked'; ?>> 사용</li>
							<li><input type="radio" name="use" value='N' <?php if ($s_info['use'] == 'N') echo 'checked'; ?>> 사용안함</li>
						</ul>
					</td>
					<th>필드 변경</th>
					<td>
						<ul>
							<li><input type="radio" name="change" value='Y' <?php if (in_array($s_info['change'], array('', 'Y'))) echo 'checked'; ?>> 가능</li>
							<li><input type="radio" name="change" value='N' <?php if ($s_info['change'] == 'N') echo 'checked'; ?>> 불가능</li>
						</ul>
					</td>
				</tr>
				<tr>
					<th>사용 칸수</th>
					<td colspan=3>
						<select name="colspan" style="width:120px;">
							<option value='1'>1칸(기본값)</option>
							<option value='3'>3칸</option>
						</select>
						(한줄은 총 4칸입니다. 주소처럼 긴 문자를 입력해야 할때 3을 선택하세요.)
					</td>
				</tr>
				<tr>
					<th>필드 옵션</th>
					<td colspan=3>
						<ul>
							<li><input type="checkbox" name="option[]" value='required' <?php if (in_array('required', $s_info['option'])) echo 'checked'; ?>><span>필수 입력</span>  </li>
							<li><input type="checkbox" name="option[]" value='numeric' <?php if (in_array('numeric', $s_info['option'])) echo 'checked'; ?>> 숫자만 입력가능</li>
							<li><input type="checkbox" name="option[]" value='numeric_dash' <?php if (in_array('numeric_dash', $s_info['option'])) echo 'checked'; ?>> 숫자와 하이픈(-)만 입력가능</li>
							<li><input type="checkbox" name="option[]" value='valid_email' <?php if (in_array('valid_email', $s_info['option'])) echo 'checked'; ?>> Email</li>
							
						</ul>
					</td>
				</tr>
				<tr>
					<td colspan=4><div class="buttonline"><input type="submit" class="btn btn-primary" value="저장" /></div></td>
				</tr>
			</table>
			
			<?php echo form_close(); ?>
			
	 <br>	
			<div>
				<!--선택된 분류의 목록-->
				<div class="text">
						<h3>"<?php echo $fname; ?>"의 항목(필드) 순서변경</h3>
				</div>
				
	 목록의 정렬 순서를 바꾸려면 드래그해서 위치를 바꾼후 '정렬순서 저장'버튼을 누르세요.
				<!--정렬순서 변경-->
				<?php
				$attributes = array('class'=>'form-horizontal');
				echo form_open('/basis_info/setting/customize', $attributes);
				
				$data = array('mode'=>'sortable', 'fid'=>$s_info['fid']);
				echo form_hidden($data);
				?>
	
				<ul id="sortable">
					 <?php
					 $idx=0;
					 foreach ($s_list as $row) {
					 	$idx++;
					 ?>
					 <li class="ui-state-default">
					 	<?php echo $idx.'. '; ?><b><?php echo $row['column_title']; ?></b><input type="hidden" name="sortable_fid[]" value='<?php echo $row['column_title']; ?>' />
					 	 &emsp; (<?php echo $row['type']; ?>, 사용:<?php echo $row['use']; ?>, 필드 변경:<?php echo $row['change']; ?>, 칸수:<?php echo $row['colspan']; ?>, 필드 옵션:<?php echo $row['option']; ?>)
					 </li>
					 <?php } ?>
				</ul>
				<input type="submit" class="btn btn-primary" value="정렬순서 저장" />
				<?php
				echo form_hidden($data);
				?>
			</div>



		</div>
	<?php } ?>


<br><br><br><br><br>


	</div>
</div>
