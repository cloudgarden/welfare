<div>
	<div></div>
	
	
	<?php /* if ($ano>0) { ?>
	<div class="span4">
		<?php //최상위 계정과목입력 ?>
		<?php  echo validation_errors(); ?>

		<?php
		$attributes = array('class' => 'form-horizontal');
		echo form_open('/account/add', $attributes);

		$data = array('pano' => '0', 'has_children' => '0', 'depth' => '0', 'weight' => count($accounts[0])+1);
		echo form_hidden($data);
		?>
			<label class="control-label" for="title">최상위 계정과목 추가</label>
			<br>
				<input type="text" id="title" name="title" value="<?php echo set_value('title'); ?>" placeholder="계정과목">
			<label class="control-label" for="code">코드</label>
				<input type="text" id="code" name="code" value="<?php echo chr(65+count($accounts[0])); ?>" placeholder="코드">
				<input type="submit" class="btn btn-primary" value="입력" />
		<?php echo form_close(); ?>
		<?php  ?>
	</div>
	<?php } */ ?>
	
	<br><br><br>
	
	<?php if ($ano>0) { ?>
	<div>
		<?php //최상위 계정과목입력 ?>
		<?php  echo validation_errors(); ?>

		<?php
		$attributes = array('class' => 'form-horizontal');
		echo form_open('/account/update', $attributes);

		$data = array('ano' => $s_account->ano, 'pano' => $s_account->pano, 'has_children' => $s_account->has_children, 'depth' => $s_account->depth, 'weight' => $s_account->weight);
		echo form_hidden($data);
		?>
			<label class="control-label" for="title">계정과목 수정</label>
			<br>
			계정과목 : <input type="text" id="title" name="title" value="<?php echo $s_account->title; ?>" placeholder="계정과목">
			코드 : <input type="text" id="code" name="code" value="<?php echo $s_account->code; ?>" readonly>
				<input type="submit" class="btn btn-primary" value="수정" />
		<?php echo form_close(); ?>
		<?php  ?>
	</div>
	<?php } ?>
	
	
	<div></div>
</div>

<br><br><br>
<div>
	
	<?php
	view_subaccount($accounts, 0);
	?>

</div>
