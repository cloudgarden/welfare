
		<div>
			<div class="span4"></div>
			<div class="span4">
				<?php echo validation_errors(); ?>

				<?php
				$attributes = array('class' => 'form-horizontal', 'id' => 'myform');
				echo form_open($this->uri->uri_string(), $attributes);
				?>
				<div class="control-group">
					<label class="control-label" for="ID">ID</label>
					<div class="controls">
						<input type="text" id="uid" name="uid" value="<?php echo set_value('uid'); ?>" placeholder="ID">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="이름">이름</label>
					<div class="controls">
						<input type="text" id="uname" name="uname" value="<?php echo set_value('uname'); ?>" placeholder="이름">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="password">비밀번호</label>
					<div class="controls">
						<input type="text" id="password" name="password" value="<?php echo set_value('password'); ?>"  placeholder="비밀번호">
					</div>
				</div>

				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<input type="submit" class="btn btn-primary" value="사용자등록" />
					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
			<div class="span4"></div>
		</div>
