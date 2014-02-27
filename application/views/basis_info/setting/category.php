<?php
	//echo 'mode : '.$s_info['mode'].'<br>';
	//echo $this->db->last_query();
	//var_dump($s_info);
	//var_dump($_POST);

		
?>
	<style>
	#sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
	#sortable li { margin: 0 5px 5px 5px; padding: 5px; font-size: 1em; width: 15em; ; height: 1.5em; }
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
					<h3>카테고리 추가</h3>
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
			 echo form_open('/basis_info/setting/category', $attributes);
			 
			 $data = array('mode'=>'add', 'pgid'=>0, 'type'=>'text', 'has_children'=>0, 'depth'=>0, 'weight'=>count($all_list)+1);
			 echo form_hidden($data);
			 ?>
			 카테고리명 : <input type="text" id="gid" name="gid" value="<?php if ($s_info['pgid'] == '0') echo set_value('gid'); ?>">
			 <input type="submit" class="btn btn-primary" value="입력" />
			 <?php echo form_close(); ?>
		</div>

<br>
		<div>
			<!--top level 카테고리 목록-->
			<div class="text">
					<h3>카테고리 목록</h3>
			</div>
	
			 <?php
			 foreach ($all_list as $row) {
			 	$style="";
				if ($s_info['pgid'] == $row['gid'])
					$style='style="color:#ff0000; font-weight:bold;"';
			 ?>
			 <span style="display:inline-block"><a href="/basis_info/setting/category/?pgid=<?php echo $row['gid']; ?>" <?php echo $style; ?>><?php echo $row['gid']; ?></a></span>
			 <?php } ?>
		</div>

	 <?php
	 	if ($s_info['pgid']) {
	 ?>
 <br>
		<div>
			<!--선택된 분류의 목록-->
			<div class="text">
					<h3>"<?php echo $s_info['pgid']; ?>"의 목록</h3>
			</div>
			 <?php
			 $attributes = array('class'=>'form-horizontal');
			 echo form_open('/basis_info/setting/category', $attributes);
			 
			 $data = array('mode'=>'add', 'pgid'=>$s_info['pgid'], 'type'=>'text', 'has_children'=>0, 'depth'=>1, 'weight'=>count($s_list)+1);
			 echo form_hidden($data);
			 ?>
			 <input type="text" id="gid" name="gid" value="<?php if ($s_info['gid'] != 'sortable') echo set_value('gid'); ?>" style="width:150px;">
			 <input type="submit" class="btn btn-primary" value="추가" />
			 <?php echo form_close(); ?>
 <br>	
 목록의 정렬 순서를 바꾸려면 드래그해서 위치를 바꾼후 '정렬순서 저장'버튼을 누르세요.
			<!--정렬순서 변경-->
			 <?php
			 $attributes = array('class'=>'form-horizontal');
			 echo form_open('/basis_info/setting/category', $attributes);
			 
			 $data = array('mode'=>'sortable', 'pgid'=>$s_info['pgid'], 'gid'=>'sortable');
			 echo form_hidden($data);
			 ?>

			<ul id="sortable" class="nav nav-pills">
				 <?php
				 $idx=0;
				 foreach ($s_list as $row) {
				 	$idx++;
				 ?>
				 <li class="ui-state-default"><?php echo $idx.'. '; ?><?php echo $row['gid']; ?><input type="hidden" name="sortable_gid[]" value='<?php echo $row['gid']; ?>' /></li>
				 <?php } ?>
			</ul>
			 <input type="submit" class="btn btn-primary" value="정렬순서 저장" />
			 <?php
			 echo form_hidden($data);
			 ?>



		</div>
	<?php } ?>


<br><br><br><br><br>


	</div>
</div>
