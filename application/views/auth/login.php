<?php
	if ($this->session->flashdata('message')) {
?>
	<script>
	alert('<?=$this -> session -> flashdata('message') ?>');
	</script>
<?php
}
?>


    <div class="mainContainer">
      <div class="loginWrap">
          <div class="loginContainer">
            <div class="loginHeader">
              <h5><img src="/wwf/images/14x14/lock3.png" alt=""> 로그인</h5>
            </div>
	<?php
	$attributes = array('name' => 'myform', 'id' => 'myform', 'class' => 'form-horizontal');
	echo form_open('/auth/login?returnURL=' . rawurlencode($returnURL), $attributes);
	?>
                <label for="username">아이디</label>
                <div class="inputField">
                  <input type="text" id="uid" name="uid" placeholder="ID">
                  <img src="/wwf/images/14x14/member2.png" alt="">
                </div>
                <label for="password">비밀번호</label>
                <div class="inputField">
                  <input type="password" id="password" name="password"  placeholder="비밀번호">
                  <img src="/wwf/images/14x14/lock3.png" alt="">
                </div>
                <!--div class="checkX" style="margin-bottom:10px;">
                	<br>
                  	<a href='/'>새로운 사용자등록</a>
                </div-->
                <button type="submit" class="button noMargin sButton blockButton bOlive" >로그인</button>
	<?php echo form_close(); ?>
          </div>
        </div>
    </div>



