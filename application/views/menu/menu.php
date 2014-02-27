<?php 
//var_dump($menus);
?>
<!--1th level menu-->
<nav>
	<ul class="barnav">
   	 	<?php
   	 	
			foreach ($menus['0'] as $mid => $entry) {
   	 	?>
			<li <?php if ($this->uri->segment(1) === $mid) {echo 'class="active"';} ?>><a href="/<?php echo $mid; ?>"><?php echo $entry['title']; ?></a></li>
		<?php } ?>
	</ul>
</nav>

<!--2th more level menu-->
<?php if ($this->uri->segment(1)!='') {?>
<div class="widgetBar">
	<div class="barInner">
		<?php
		$sub_menu_link = array($this->uri->segment(1));
		 //get_left_sub_menu(2, $this->uri->segment_array(), $sub_menu_link, $this->uri->segment(1), $menus, $sub_menu_css);	//하위 메뉴이므로 depth=2부터 시작.
		 get_left_sub_menu($menus, $this->uri->segment(1), $this->uri->segment_array(), 2, $sub_menu_link, $sub_menu_css);	//하위 메뉴이므로 depth=2부터 시작.
		?>
	</div>
</div>
<?php } ?>
<?php /* ?>
<?php  */ ?>

