<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
if ( ! function_exists('get_main_menu_path')){
	//메인메뉴 path 가져오기, 최종 level의 메뉴까지의 링크
    function get_main_menu_path($main_menu_path, $mid, $menus){
    	
 		//서브메뉴가 존재할 경우 메뉴 링크에 연결
		if ($mid!='' && array_key_exists($mid, $menus)) {
			$sub_mids = array_keys($menus[$mid]);
			$main_menu_path .= '/'.$sub_mids[0];
			
			$main_menu_path = get_main_menu_path($main_menu_path, $sub_mids[0], $menus);
		}
		return $main_menu_path;
    }
}

if ( ! function_exists('get_left_sub_menu')){
	//메인메뉴 path 가져오기, 최종 level의 메뉴까지의 링크
    function get_left_sub_menu($menus, $mid, $segment_list, $depth, $sub_menu_link, $sub_menu_css){
    	
		//echo $depth;
		//하위 메뉴가 없으면 종료
		if (!array_key_exists($mid, $menus)) return;
		
		echo '<ul class="'.$sub_menu_css[$depth-2].'">';

		foreach ($menus[$mid] as $sub_mid => $sub_entry) {
			//echo var_dump($sub_menu_link);
			//echo $sub_mid;
			$sub_menu_link[$depth-1] = $sub_mid;

			if (count($segment_list) > $depth-1 &&  $segment_list[$depth] == $sub_mid) $active = 'class ="active"';
			else $active = '';
			 
		?>
			<li <?php echo $active; ?>><a href="/<?php echo implode('/', $sub_menu_link); ?>"><span>+</span> <?php echo $sub_entry['title']; ?><?php if ($sub_entry['type'] == 'tab') {echo '(tab)';} ?></a>
				<?php if (array_key_exists($sub_mid, $menus)) get_left_sub_menu($menus, $sub_mid, $segment_list, $depth+1, $sub_menu_link, $sub_menu_css); ?>
			</li>
			
	<?php  } ?>
<?php
		echo '</ul>';
    }
}

//특정 부모의 자식 메뉴 보이기
//메ㅠ 추가에서 사용
if ( ! function_exists('get_sub_menu')){
	//메인메뉴 path 가져오기, 최종 level의 메뉴까지의 링크
    function get_sub_menu($curl, $depth, $pmid, $ptitle, $menus){
    	
		//하위 메뉴가 없으면 종료
		if (!array_key_exists($pmid, $menus)) return;
		
		//메뉴링크
		$link_path_head = '';
		for ($i=1; $i<=$depth; $i++) {
			$link_path_head .= '/'.$curl[$i];
		}
		//echo $link_path_head.'<br>';
		
		if ($pmid!='0') {
			$ptitle = $menus[0][$curl[4]]['title'];
			if ($depth>4) {
				$ptitle .= ' > '.$menus[$curl[$depth-1]][$pmid]['title'];
				//echo $depth.','.$pmid.' ++++'.$menus[$curl[$depth-1]][$pmid]['mid'];
			}
		}
		
		echo '<div class="text"><h3>'.$ptitle.' 메뉴</h3></div>';
		echo '<ul class="nav nav-pills">';
		
		foreach ($menus[$pmid] as $mid => $entry) {
			
			$active = '';
			if ($depth>=2 && $depth<count($curl)) {
				if ($mid == $curl[$depth+1]) $active = 'class="active"';
			}

			//메뉴의 링크 경로, tab 메뉴를 제외한 가장 하위메뉴의 링크를  메뉴의 링크로 건다. 중간 경로는 화면이 없다.	
			$link_path = $link_path_head. '/'.$mid;
			
			//서브메뉴 유무 확인
			if (array_key_exists($mid, $menus)) {
				$sub_mids = array_keys($menus[$mid]);
			}

		?>
			<li <?php echo $active; ?>><a href="<?php echo $link_path; ?>"><?php if ($entry['has_children'] == 1) {echo '<b>+</b>';} ?><?php if ($entry['type'] == 'tab') {echo '<b>(tab)</b>';} ?><?php echo $entry['title']; ?></a></li>
			
	<?php  } ?>
<?php
		echo '</ul>';
		
		return $ptitle;
    }
}
