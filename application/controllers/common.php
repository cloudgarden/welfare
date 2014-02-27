<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * top, sub 메뉴가 없는 컨토롤러
 */

class Common extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	
	function index(){
	}

	//table의 컬럼 name과 value로 배열을 만들어서 return
	function auto_form_values($table) {
		$option = array();
		
		$fields = $this->db->list_fields($table);		
		foreach ($fields as $field){
			$option[$field] = '';
		}
		
		return $option;
	}
	
	function test() {
		$this->load->view('head_nomenu');
		$this->load->view('test');
		$this->load->view('footer_nomenu');
	}
	
	function set_categorization() {
		echo 'success';
		$gid = $this->input->post('gid',true);
		echo $gid;
		/*
		$userinfo = json_decode($_GET['data']);
		$userinfo->address = 'seoul';
		$userinfo->phonenumber = '01023456789';
		echo json_encode($userinfo);
		//return $this->add('categorization', $option, array());
		 * 
		 */
		//$this->load->view('footer_nomenu');
		 
	}
	
	function json_test () {
		//$this->load->view('/test', array('total_rows'=>$config['total_rows'], 'all_list'=>$all_list, 's_info'=>$s_info));
		//$userinfo = json_decode($_GET['data']);
		$data  =$this->input->get('data'); 
		$userinfo = json_decode($data);
		$userinfo->address = 'seoul';
		$userinfo->phonenumber = '01023456789';
		echo json_encode($userinfo);
	}
}
