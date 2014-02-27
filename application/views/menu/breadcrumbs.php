<?php
	//메뉴 경로
	$breadcrumbs='';
	$breadcrumbs_path='';
	if (!in_array($this->uri->segment(1), $this->config->item('no_submenu'))) {
		//1차 메뉴
		if ($this->uri->segment(1) != '') {
			//메인메뉴(1차 메뉴)
			$breadcrumbs_path = '/'.$menus[0][$this->uri->segment(1)]['mid'];
			
			if ($this->uri->segment(2) != '') $breadcrumbs .= $menus[0][$this->uri->segment(1)]['title'];
			else $breadcrumbs = '<a href="'.$breadcrumbs_path.'">'.$menus[0][$this->uri->segment(1)]['title'].'</a>';
		}
		
		//2차 메뉴
		if ($this->uri->segment(2) != '' and array_key_exists($this->uri->segment(1), $menus)) {
			$breadcrumbs_path .= '/'.$this->uri->segment(2);
			
			if ($this->uri->segment(3) != '') $breadcrumbs .= ' > '.$menus[$this->uri->segment(1)][$this->uri->segment(2)]['title'];
			else $breadcrumbs .= ' ><a href="'.$breadcrumbs_path.'">'.$menus[$this->uri->segment(1)][$this->uri->segment(2)]['title'].'</a>';
		}
				
		//3차 메뉴
		if ($this->uri->segment(3) != '' and array_key_exists($this->uri->segment(2), $menus)) {
			$breadcrumbs_path .= '/'.$this->uri->segment(3);

			if ($this->uri->segment(4) != '') $breadcrumbs .= ' > '.$menus[$this->uri->segment(2)][$this->uri->segment(3)]['title'];
			else $breadcrumbs .= '><a href="'.$breadcrumbs_path.'">'.$menus[$this->uri->segment(2)][$this->uri->segment(3)]['title'].'</a>';
		}
	}

?>
        <div class="topBar">
          <div class="topBarInner">
            <ul class="breadcrumbs">
              <li>
              	<a href="/"><img src="/wwf/img/icons/14x14/home5.png" alt=""></a> > <?php echo $breadcrumbs; ?>
              </li>           
            </ul>
			 <ul class="breadcrumbs">
			  <li><a href="#">+ 자주쓰는메뉴 </a></li>	
              <li><a href="#">- 자주쓰는메뉴 </a></li>
			 </ul>
          </div>
        </div>