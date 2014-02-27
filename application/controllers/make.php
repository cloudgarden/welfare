<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * 메인메뉴, 서브메뉴
 */

class Make extends WWF_Controller {
	function __construct() {
		parent::__construct();
		
	}
	
	function index(){
		echo '<!DOCTYPE html>
<html lang="ko">
	<head>
		<meta charset="utf-8">';
		
		//파일을 생성할 경로
		$controllers_path = 'application/controllers';
		$models_path = 'application/models';
		$views_path = 'application/views';
		
		//이미 다 만들어져서 체크안할 메뉴들
		$nocheck = array('basis_info');
		
		//모든 메뉴 가져오기
		$menus = $this -> common_model -> hierarchical_gets();
		
		foreach ($menus[0] as $idx => $top_menu) {
			if (in_array($top_menu['mid'], $nocheck)) continue;
			
			//1차 메뉴
			echo $top_menu['mid'].' : ';
			//1. controller와 view에 디렉토리가 없으면 생성
			//$this->make_path('controllers', $controllers_path.'/'.$top_menu['mid'], '');
			//$this->make_path('views', $views_path.'/'.$top_menu['mid'], '');
			echo '<br>';
			
			//2차 메뉴가 있는 경우만 실행
			if (array_key_exists($top_menu['mid'], $menus)) {
				foreach ($menus[$top_menu['mid']] as $sub_mid => $sub_menus) {
					echo '-->'.$sub_mid.'<br>';
					
					$contents = file_get_contents('application/controllers/make_sample.php');
					//class 명 변경
					$contents = str_replace('class Make_sample', 'class '.strtoupper(substr($sub_mid, 0, 1)).substr($sub_mid, 1), $contents);
					
					
					//3차 메뉴가 있는 경우만 실행
					if (array_key_exists($sub_mid, $menus)) {
						foreach ($menus[$sub_mid] as $sub_sub_mid => $sub_sub_menus) {
							echo '---->'.$sub_sub_mid.'<br>';
							$contents = rtrim($contents, '}');
							$contents .= '	function '.$sub_sub_mid.'(){
		$this->load->view($this->view_path);
	}

}';
							$this->make_path('controllers', $controllers_path.'/'.$top_menu['mid'], $sub_mid.'.php', $contents);
							$this->make_path('models', $models_path.'/'.$top_menu['mid'], $sub_mid.'.php', $contents);
							$this->make_path('views', $views_path.'/'.$top_menu['mid'].'/'.$sub_mid, $sub_sub_mid.'.php');
						}
					} else {
						//2차 메뉴가지만 있으면 파일 생성
						//controller 파일 생성
						//$this->make_path('controllers', $controllers_path.'/'.$top_menu['mid'], $sub_mid.'.php', $contents);
						//$this->make_path('views', $views_path.'/'.$top_menu['mid'], $sub_mid.'.php');
										
					}
				}
			}
			
		}
	}
	
	function make_path($kind, $path, $file, $contents='') {
		
		//if ($path != 'application/views/basis_info') return;
		//if ($file != 'basebase.php') return;
		
		if (!is_dir($path)) {
			echo $path.':디렉토리 아님<br>';	
			mkdir ($path, 0775);
			exec ('chmod 775 '.$path);
		}
		else
			echo '**'.$path.':디렉토리 있음<br>';	


		if ($file != '') {
			if (!is_file($path.'/'.$file)) {
				echo '**'.$path.'/'.$file.':파일 없음<br>';	
				if ($kind == 'views') {
					exec ('touch '.$path.'/'.$file);
					exec ('chmod 664 '.$path.'/'.$file);
				} else if ($kind == 'controllers' || $kind == 'models'){
					//콘트롤러 파일 생성
					echo "";
					exec ('touch '.$path.'/'.$file);
					exec ('chmod 664 '.$path.'/'.$file);
					file_put_contents($path.'/'.$file, $contents);
					
				}
			}
			else
				echo '**'.$path.'/'.$file.':파일 있음<br>';	
		}
		
	}

}



