<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * 사용자 역할 - 생성, 수정, 삭제
 * 사용자 - 로그인, 로그아웃, 회원가입, 역할 부여
 * 권한 - 역할에 따른 메뉴 접근 권한 부여, 삭제
 */

class Test extends CI_Controller {
	function __construct() {
		parent::__construct();
		
	}
	
	function index(){
		echo '11'; 
		$this -> load -> view('test');
	}
	function test1(){
		echo '11'; 
		$this -> load -> view('test1');
	}
	function test2(){
		echo '11'; 
		$this -> load -> view('test2');
	}

}
