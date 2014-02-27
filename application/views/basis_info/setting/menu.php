<?php
	//echo 'mode : '.$s_info['mode'].'<br>';
	//echo $this->db->last_query();
	//var_dump($s_info);
	//var_dump($menus);


		$s_info = array('input_title'=>'"메인메뉴', 'mid' => 0, 'title' => 0, 'type' => 0, 'pmid' => 0, 'has_children' => 0, 'depth' => 0, 'weight' => 1, 'child_depth'=>0, 'child_weight'=>count($menus[0]));
		
		$curl = $this->uri->segment_array();
		
		if (count($curl) >3) {
			$s_info['mid'] = end($curl);	//현재 선택된 메뉴의 ID
		}
		
		if (count($curl) >4) $s_info['pmid'] = prev($curl);	//현재 선택된 메뉴의 부모 ID
				

		if (count($curl)>3){
			$s_info = $menus[$s_info['pmid']][$s_info['mid']];
			
			$s_info['child_depth'] = $s_info['depth']+1;
			
			if (array_key_exists($s_info['mid'], $menus)) 
				$s_info['child_weight'] = count($menus[$s_info['mid']])+1;
			else 
				$s_info['child_weight'] = 1;
		}

		$s_info['mode'] = $this->input->post('mode');
		$s_info['org_mid'] = $this->input->post('org_mid');
		$s_info['org_title'] = $this->input->post('org_title');
		$s_info['input_title'] = '"메인메뉴';
		
		$s_info['return_url'] = '';
		for ($i=1; $i<count($curl); $i++) {
			$s_info['return_url'] .= '/'.$curl[$i];
		}
		
		
		//추가, 수정, 삭제  창 선택
		$s_info['data_target'] = $this->input->post('data_target');
		if (!$s_info['data_target']) $s_info['data_target']='inbox1';
		
		if (count($curl) >3) {
			//메인 메뉴중 하나가 선택되었을 때 보여줄 계층적 메뉴 title
			$s_info['input_title'] = $s_info['input_title'].'>'.$menus['0'][$curl[4]]['title'];
			for ($i=4; $i<count($curl)-1; $i++) {
				//echo 'title : '.$curl[$i].'<br>';
				$s_info['input_title'] .= '>'.$menus[$curl[$i]][$curl[$i+1]]['title'];
			}
		}
		$s_info['input_title'] .= '"';
		
		//var_dump($s_info);
		
?>
<div class="contentInner">
	<div class="row-fluid">

		<div class="title">

			<div class="text">
			<blockquote><h3><?php echo $menus[$this->uri->segment(2)][$this->uri->segment(3)]['title']; ?></h3></blockquote>
			</div>

		</div>
		
		<br>
		
		<?php
			//선택된 메뉴를 게층적으로 보이기
			for ($i=3; $i<=count($curl); $i++) {
				if ($i==3) $pmid = '0';
				else $pmid = $curl[$i];
				get_sub_menu($curl, $i, $pmid, '메인', $menus);
			}
		
		?>

 
		<div class="inbox">
			  <ul class="nav nav-tabs" data-tabs="tabs">
			  	<?php
				
			  	?>
				<li <?php if ($s_info['data_target'] == 'inbox1') echo 'class="active"';?>><a data-toggle="tab" data-target="#inbox1" href="#inbox1">하위 메뉴 추가</a></li>
				<?php if (count($curl)>2){ ?>
				<li <?php if ($s_info['data_target'] == 'inbox2') echo 'class="active"';?>><a data-toggle="tab" data-target="#inbox2" href="#inbox2">메뉴 수정</a></li>
				<li <?php if ($s_info['data_target'] == 'inbox3') echo 'class="active"';?>><a data-toggle="tab" data-target="#inbox3" href="#inbox3">메뉴 삭제</a></li>
				<?php } ?>
			  </ul>
			  
			<div class="tab-content">
	<div class="tab-pane <?php if ($s_info['data_target'] == 'inbox1') echo 'active"';?>" id="inbox1">
		<!--하위 메뉴 입력-->
		<div class="text">
				<h3><?php echo $s_info['input_title']; ?>의 하위메뉴 입력</h3>
		</div>
		 <?php  echo validation_errors(); ?>

		 <?php
		 $attributes = array('class' => 'form-horizontal');
		 echo form_open('/basis_info/setting/menu', $attributes);
		 
		 $data = array('mode'=>'add', 'pmid' => $s_info['mid'], 'has_children' => 0, 'depth' => $s_info['child_depth'], 'weight' => $s_info['child_weight'], 'data_target' => 'inbox1', 'return_url'=>$s_info['return_url']);
		 echo form_hidden($data);
		 ?>
		 메인 메뉴명 : <input type="text" id="title" name="title" size="5" value="<?php echo set_value('title'); ?>" placeholder="메뉴명">
		 메뉴 ID : <input type="text" id="mid" name="mid" value="<?php echo set_value('mid'); ?>" placeholder="메뉴 ID" size="10">
		 메뉴 Type : 
			<select name="type">
			<option value="text" <?php echo set_select('type', 'text', TRUE); ?> >text</option>
			<option value="tab" <?php echo set_select('type', 'tab'); ?> >tab</option>
			</select>
		 <br>
		 <div class="span2"><input type="submit" class="btn btn-primary" value="입력" /></div>
		 <?php echo form_close(); ?>
	</div>

	<div class="tab-pane <?php if ($s_info['data_target'] == 'inbox2') echo 'active"';?>" id="inbox2">
		<!--현재 메뉴 변경/삭제-->
		<div class="text">
				<h3><?php echo $s_info['input_title']; ?> 수정</h3>
		</div>
		 <?php  echo validation_errors(); ?>

		 <?php
		 $attributes = array('class' => 'form-horizontal');
		 echo form_open('/basis_info/setting/menu', $attributes);
		 
		 $data = array('mode'=>'update', 'org_mid' => $s_info['mid'], 'org_title' => $s_info['title'], 'pmid' => $s_info['pmid'], 'has_children' => $s_info['has_children'], 'depth' => $s_info['depth'], 'weight' => $s_info['weight'], 'data_target' => 'inbox2', 'return_url'=>$s_info['return_url']);
		 echo form_hidden($data);
		 ?>
		 메인 메뉴명 : <input type="text" id="title" name="title" size="5" value="<?php echo $s_info['title']; ?>" placeholder="메뉴명">
		 메뉴 ID : <input type="text" id="mid" name="mid" value="<?php echo $s_info['mid']; ?>" placeholder="메뉴 ID" size="10">
		 메뉴 Type : 
			<select name="type">
			<option value="text" <?php if ($s_info['type']=='text') echo 'selected'; ?> >text</option>
			<option value="tab"  <?php if ($s_info['type']=='tab') echo 'selected'; ?>>tab</option>
			</select>
		 <br>
		 <div class="span2"><input type="submit" class="btn btn-primary" value="수정" /></div>
		 <?php echo form_close(); ?>
	</div>
	
	<div class="tab-pane" id="inbox3">
		<!--현재 메뉴 변경/삭제-->
		<!--하위 메뉴 입력-->
		<div class="text">
				<h3><?php echo $s_info['input_title']; ?> 삭제</h3>
		</div>
		 <?php
		 	if ($s_info['has_children']==1) echo '<h4>***하위 메뉴가 있으므로 삭제 할 수 없습니다.</h4>';
			else {
		 ?>
			 <?php  echo validation_errors(); ?>
	
			 <?php
			 $attributes = array('class' => 'form-horizontal');
			 echo form_open('/basis_info/setting/menu', $attributes);
			 
			 $data = array('mode'=>'delete', 'org_mid' => $s_info['mid'], 'mid' => $s_info['mid'], 'title' => $s_info['title'], 'type' => $s_info['type'], 'pmid' => $s_info['pmid'], 'has_children' => $s_info['has_children'], 'depth' => $s_info['depth'], 'weight' => $s_info['weight'], 'data_target' => 'inbox3', 'return_url'=>$s_info['return_url']);
			 echo form_hidden($data);
			 ?>
			 <div class="span2"><input type="submit" class="btn btn-primary" value="삭제" /></div>
			 <?php echo form_close(); ?>
		 <?php
			}
		 ?>
	</div>
			</div>

<br><br><br>


		</div>

	</div>
</div>
