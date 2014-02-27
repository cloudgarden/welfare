<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * 사용자 역할 - 생성, 수정, 삭제
 * 사용자 - 로그인, 로그아웃, 회원가입, 역할 부여
 * 권한 - 역할에 따른 메뉴 접근 권한 부여, 삭제
 */

class Auth extends WWF_Controller {
	function __construct() {
		parent::__construct();
	}
	
	function index(){
		$this -> load -> library('form_validation');
		//$this -> load -> view('login', array('returnURL' => $this -> input -> get('returnURL')));
	}

	/*
	 * 역할 생성
	 */
	function make_role() {
		$this -> load -> library('form_validation');
		$this -> load -> view('/auth/role');
	}

	/*
	 * 역할 삭제
	 */
	function del_role() {

	}

	/*
	 * 역할 수정
	 */
	function update_role() {

	}
	
	function test() {
		$this -> load -> view('cal');
	}

	/*
	 * 로그인
	 */
	function login() {
		log_message('error', 'login');
		//로그인이 되어 있는 경우
		if ($this->session->userdata('is_login')) {
			//var_dump ($this->session->all_userdata());
			redirect('/'); 
		}

		//////////////////////////////////////////////////////
		//common variable
		$s_info = array();	//선택한 or 입력한 거래처 정보

		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('form_validation');

		//로그인이 되어 있지 않으면 로그인 화면 보이기		
		$s_info = $this->get_form_values(USER);
		
		$s_info['returnURL'] = $this -> input -> post('returnURL');
		if ($s_info['returnURL'] == '') $s_info['returnURL'] = $this -> input -> get('returnURL');
		if ($s_info['returnURL'] == '') $s_info['returnURL'] = $this -> input -> query_string('returnURL');
		if (in_array($s_info['returnURL'], array('', 'auth/login', '/auth/login'))) {
			$s_info['returnURL'] = '/';
		}

		if ($this -> form_validation -> run() === false) {
			$this -> load -> view($this->uri->uri_string(), array('returnURL' => $s_info['returnURL']));
		} else {
			//$user = $this -> user_model -> getByUid(array('email' => $this -> input -> post('uid')));
			$where_arr = array(array('', 'uid', $s_info['uid']));
			$user= $this->common_model->get(USER, $where_arr);
			
			if (!function_exists('password_hash')) {
				$this -> load -> helper('password');
			}
			
			if ($s_info['uid'] == $user['uid'] && password_verify($s_info['password'],  $user['password'])) {
				//세션값 등록: 사용자 ID, 이름
				$session_info = array('is_login'=> true, 'uid'=>$s_info['uid'], 'uname'=>$user['uname']);
				$this -> session -> set_userdata($session_info);
				
				//세션 값 가져오기
				//echo $this->session->userdata('email');
				
				//세션에 등록된 모든 정보
				//var_dump ($this->session->all_userdata());
				
				redirect($s_info['returnURL']);
			} else {
				$this -> session -> set_flashdata('message', '로그인에 실패 했습니다.');
				$this -> load -> helper('url');
				redirect($this->uri->uri_string());
			}
		}
	}

	/*
	 * 로그아웃
	 */
	function logout() {
		$this -> session -> sess_destroy();
		redirect('/auth/login');
	}
	
	/*
	 * 관리자  DATA 입력
	 * 주요 data : ID와 비밀번호
	 * 관리자 입력후 또는 등록시 반드시 기금대상자 또는 내부직원과 연결해야 한다. - 최초 등록시 사용자가 한명도 없을 수 있으므로, 연결되지 않아도 사용할 수 있어야 한다.
	 * - 어느 시점에서 기금대상자와 연결할 것인지 고민할 것
	 */
	function add_user() {
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('form_validation');

		//////////////////////////////////////////////////////
		//common variable
		$s_info = array();	//선택한 or 입력한 거래처 정보

		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('form_validation');

		//로그인이 되어 있지 않으면 로그인 화면 보이기		
		$s_info = $this->get_form_values(USER);

		if ($this -> form_validation -> run() === false) {
			$this -> load -> view('/auth/add_user');
		} else {
			if (!function_exists('password_hash')) {
				$this -> load -> helper('password');
			}
			$hash = password_hash($s_info['password'], PASSWORD_BCRYPT);
			
			//echo $this -> input -> post('password')."<br>";
			//echo $hash."<br>";
			
			//$where_arr = array(array('', 'bno', $s_info['basic']['bno']));
			$option = array('uid'=>$s_info['uid'], 'uname'=>$s_info['uname'], 'password' => $hash);
			$set_arr = array(array('name'=>'input_date', 'value'=>'now()', 'flag'=>false));
			//$this->common_model->update(ROLE_USER, $where_arr, $option, $set_arr);
			$this->common_model->add(USER, $option, $set_arr);

			$this -> session -> set_flashdata('message', '사용자를 등록했습니다.');
			//$this -> load -> view($this->uri->uri_string());
			redirect('/auth/login');
		}
	}


}
