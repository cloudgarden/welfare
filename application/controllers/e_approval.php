<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class E_approval extends WWF_Controller {
	function __construct() {
		parent::__construct();
		
	}

	function index(){
		redirect('e_approval/apply_approval');
	}
	
	
	/*
	 * 신청서결재
	 */
	function apply_approval(){
		//////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('form_validation');

		////////////////////////////////////////////////////////////////////////////////////
		//all list
		/*
		//고유목적사업비용(BBA)의 하위 계정만 가져오기
		$select = 'ano, title_owner';
		$where_arr = array(array('', 'pano', 19), array('', 'use', 'Y'));
		$orderby = 'title_owner';
		$all_list = $this->common_model->gets(ACCOUNT, $where_arr, $select, $orderby, -1, -1, '', array(), array('ano'));
		 */

		////////////////////////////////////////////////////////////////////////////////////
		//목적사업 미결재 신청서
		$select=PURPOSE_BUSINESS.'.*, A.title_owner';
		$join = array(
			array(ACCOUNT.' as A', PURPOSE_BUSINESS.'.ano = '.'A.ano', 'left')
		);
		$where_arr = array(array('', PURPOSE_BUSINESS.'.status = ', 'N'));
		
		$s_info['purpose_list'] = $this->common_model->gets(PURPOSE_BUSINESS, $where_arr, $select, '', -1, -1, '', $join, array(), false);
		//log_message('error', $this->db->last_query());
		
		//대부금 미결재 신청서
		$select=LOAN.'.*, A.title_owner';
		$join = array(
			array(ACCOUNT.' as A', LOAN.'.ano = '.'A.ano', 'left')
		);
		$where_arr = array(array('', LOAN.'.status = ', 'N'));
		
		$s_info['loan_list'] = $this->common_model->gets(LOAN, $where_arr, $select, '', -1, -1, '', $join, array(), false);
		//log_message('error', $this->db->last_query());
		
				
		////////////////////////////////////////////////////////////////////////////////////
		//신청서 결재
		$s_info['pno'] = $this->input->post('pno');	//목적사업신청서 no
		$s_info['lno'] = $this->input->post('lno');	//대부금신청서 no
		$s_info['status'] = $this->input->post('status');
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			//echo '1';
			$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
		} else {
			//echo '2';
			//redirect($this->uri->uri_string());
			//목적사업 신청서 결재
			if (!in_array($s_info['pno'], array('', 'undefined'))) {
				$where_arr = array(array('', 'pno', $s_info['pno']));
				$option = array('status'=>$s_info['status']);
				$this->common_model->update(PURPOSE_BUSINESS, $where_arr, $option);
			}
			//대부금 신청서 결재
			else if ($s_info['lno'] != '') {
				$where_arr = array(array('', 'lno', $s_info['lno']));
				$option = array('status'=>$s_info['status']);
				$this->common_model->update(LOAN, $where_arr, $option);
			}
			//log_message('error', $this->db->last_query());
			redirect($this->uri->uri_string());
			//$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
		} 
	}
	
	/*
	 * 전표결재
	 */
	function chit_approval(){
		$this->load->view($this->uri->uri_string());
	}

	/*
	 * 위임
	 */
	function proxy(){
		$this->load->view($this->uri->uri_string());
	}

	/*
	 * 설정
	 */
	function app_setting(){
		$this->load->view($this->uri->uri_string());
	}


}
