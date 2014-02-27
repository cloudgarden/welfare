<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Loan extends WWF_Controller {
	function __construct() {
		parent::__construct();
	}

	function index(){
		redirect('loan/loan_dashboard');
	}
	
	
	/*
	 * 대부사업 사업현황판
	 */
	function loan_dashboard(){
		$this->load->view($this->uri->uri_string());
	}
	
	/*
	 * 대부금 신청
	 */
	function loan_apply(){
		$this->{$this->uri->segment(3)}();
	} // end of bunge()

	/*
	 * 대부금 신청
	 */
	function app_apply(){
		//$this->{$this->uri->segment(3)}();
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//library, helper, model load
		//$this->load->model('statement_model');
		$this->load->library('form_validation');
		$this->load->helper('loan');

		//////////////////////////////////////////////////////////////////////////////////////////
		//검색조건 셋팅
		$s_info['basic'] = $this->get_form_values(LOAN);
		if ($s_info['basic']['unredeemed_month'] == '') $s_info['basic']['unredeemed_month'] = 0;	//거치기간
		if ($s_info['basic']['request_date'] == '') $s_info['basic']['request_date'] = date('Y-m-d');	//신청일

		//////////////////////////////////////////////////////////////////////////////////////////
		//all list
		//log_message('error', $this->db->last_query());
		
		//투자자산(AABA)의 하위 계정만 가져오기
		//$where_arr = array();
		//$all_list = $this->common_model->get(LOAN_META, $where_arr);
		$select = 'ano, title_owner';
		$where_arr = array(array('', 'pano', 24), array('', 'use', 'Y'), array('like', 'title_owner', '대부금', 'before'));
		$orderby = 'title_owner';
		$all_list = $this->common_model->gets(ACCOUNT, $where_arr, $select, $orderby);
		
		//log_message('error', $this->db->last_query());
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//상환 스케쥴 리스트
		//급여공제일 가져오기
		$pay_date = $this->common_model->get(LOAN_META, array(), 'pay_day');
		$pay_date = $pay_date['pay_day'];

		//대부게시월에 해당하는 급여공제일
		$s_info['lon_schedule'] = $s_info['basic'];
		$s_info['lon_schedule']['pay_date'] = date("Y-m-".$pay_date, strtotime($s_info['basic']['loan_start']));

		$s_info['lon_schedule'] = lon_schedule($s_info['lon_schedule']);
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			//echo '1';
			$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
		} else {
			//echo '2';
			//$where_arr = array(array('', 'ano', $s_info['ano']));
			//$this->common_model->update_insert(LOAN, $where_arr, $s_info);
			//신청서 정보 입력
			$lno = $this->common_model->add(LOAN, $s_info['basic']);
			
			//상환스케쥴 입력
			$option = array();
			foreach ($s_info['lon_schedule']['schedule'] as $row) {
					$option[] = array('lno'=>$lno
						, 'payment_date'=>$row['deposit_date']
						, 'principal'=>$row['principal']
						, 'interest'=>$row['interest']
						, 'repayment_money'=>$row['repayment_money']
						, 'payed_principal'=>$row['payed_principal']
						, 'balance'=>$row['balance']
						, 'status'=>'N'
					);
			}
			$this->common_model->insert_batch(LOAN_SCHEDULE, array(), $option);
			//log_message('error', $this->db->last_query());
			/*
						 */
						
			//redirect($this->uri->uri_string());
			$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
		}
	}

	/*
	 * 대부금 신청조회
	 */
	function app_search(){
		//////////////////////////////////////////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('pagination');
		$this->load->library('form_validation');
		$this->load->model('statement_model');
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//pagenation
		$config['base_url'] = '/'.$this->uri->uri_string().'/';
		$config['total_rows'] = $this->common_model->load_list_total(LOAN);
		//$config['per_page'] = $this->config->item('per_page');
		$config['per_page'] = 100;
		$this->pagination->initialize($config);
		
		$page_num =  $this->uri->segment(4);
		if (!$page_num) $page_num = 0;
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//all list
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//검색조건 셋팅
		$s_info = $this -> set_search_condition();

		$where_arr = array();
		if (!in_array($s_info['search_term'], array('', 'all'))) {
			if ($s_info['start_date'] != '')
				$where_arr[] = array('', 'request_date >= ', $s_info['start_date']);
			if ($s_info['end_date'] != '')
				$where_arr[] = array('', 'request_date <= ', $s_info['end_date']);
		}

		if (!in_array($s_info['ano'], array('', 'all')))
			$where_arr[] = array('', 'ano', $s_info['ano']);
		if (!in_array($s_info['company'], array('', 'all')))
			$where_arr[] = array('', 'company', $s_info['company']);
		if (!in_array($s_info['department'], array('', 'all')))
			$where_arr[] = array('', 'department', $s_info['department']);
		if (!in_array($s_info['status'], array('', 'all')))
			$where_arr[] = array('', 'status', $s_info['status']);

		if ($s_info['search_term'] == 'all' || count($where_arr)>0) {
			$orderby = 'request_date DESC';
			$all_list = $this->common_model->gets(LOAN, $where_arr, '', $orderby, $page_num, $config['per_page']);
		}
		//log_message('error', $this->db->last_query());

		//////////////////////////////////////////////////////////////////////////////////////////
		//대부금 구분 가져오기
		$select = 'ano, title_owner';
		$where_arr = array(array('', 'pano', 24), array('', 'use', 'Y'), array('like', 'title_owner', '대부금', 'before'));
		$orderby = 'title_owner';
		$s_info['kind'] = $this->common_model->gets(ACCOUNT, $where_arr, $select, $orderby);
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//사업장 정보가져오기
		$where_arr = array(array('', 'pgid', '사업장'));
		$s_info['company_list'] = $this->common_model->gets(CATEGORIZATION, $where_arr, '', 'weight asc');
				
		//부서 정보가져오기
		$where_arr = array(array('', 'pgid', '부서'));
		$s_info['department_list'] = $this->common_model->gets(CATEGORIZATION, $where_arr, '', 'weight asc');
				

		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view($this->uri->uri_string(), array('total_rows'=>$config['total_rows'], 'all_list'=>$all_list, 's_info'=>$s_info));
		} else {
			$this->load->view($this->uri->uri_string(), array('total_rows'=>$config['total_rows'], 'all_list'=>$all_list, 's_info'=>$s_info));
		} 
	}

	/*
	 * 대부금지출통합전표생성
	 */
	function loan_execution(){
		//해당월의 전표생성할 내역을 가져오하야 함.
		//LOAN_SCHEDULE 에서 가져와야 하나 아래는 신청서 목록(LOAN)에서 가져옴
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('pagination');
		$this->load->library('form_validation');
		//$this->load->model('statement_model');
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//대부금 구분 가져오기
		$select = 'ano, title_owner';
		$where_arr = array(array('', 'pano', 24), array('', 'use', 'Y'), array('like', 'title_owner', '대부금', 'before'));
		$orderby = 'title_owner';
		$all_list = $this->common_model->gets(ACCOUNT, $where_arr, $select, $orderby, -1, -1, '', array(), array('ano'));

		//////////////////////////////////////////////////////////////////////////////////////////
		//pagenation
		$config['base_url'] = '/'.$this->uri->uri_string().'/';
		$config['total_rows'] = $this->common_model->load_list_total(LOAN_SCHEDULE);
		//$config['per_page'] = $this->config->item('per_page');
		$config['per_page'] = 100;
		$this->pagination->initialize($config); 

		$page_num =  $this->uri->segment(3);
		if (!$page_num) $page_num = 0;
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//all list

		//////////////////////////////////////////////////////////////////////////////////////////
		//검색조건 셋팅
		$s_info = $this -> set_search_condition();

		$where_arr = array();
		if (!in_array($s_info['search_term'], array('', 'all'))) {
			if ($s_info['start_date'] != '')
				$where_arr[] = array('', 'request_date >= ', $s_info['start_date']);
			if ($s_info['end_date'] != '')
				$where_arr[] = array('', 'request_date <= ', $s_info['end_date']);
		}
		/*

		if (count($where_arr)>0) {
			$orderby = 'request_date DESC';
			//$s_info['list'] = $this->common_model->gets(LOAN, $where_arr, '', $orderby, $page_num, $config['per_page'], '', array(), array('ano', 'lno'));
		}
		//log_message('error', $this->db->last_query());
		 */

		//급여공제일 가져오기
		$s_info['pay_day'] = $this->common_model->get(LOAN_META, array(), 'pay_day');
		$s_info['pay_day'] = $s_info['pay_day']['pay_day'];
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view($this->uri->uri_string(), array('total_rows'=>$config['total_rows'], 'all_list'=>$all_list, 's_info'=>$s_info));
		} else {
			$this->load->view($this->uri->uri_string(), array('total_rows'=>$config['total_rows'], 'all_list'=>$all_list, 's_info'=>$s_info));
		} 
	}

	/*
	 * 대부금 상환관리
	 */
	function loan_manage(){
		$this->{$this->uri->segment(3)}();
	} // end of bunge()

	/*
	 * 상환금액현황
	 */
	function ma_refund(){
		$this->load->view($this->uri->uri_string());
	}

	/*
	 * 개별상환금관리
	 */
	function ma_individual(){
		$this->load->view($this->uri->uri_string());
	}

	/*
	 * 대부금금여공제
	 */
	function ma_deduction(){
		$this->load->view($this->uri->uri_string());
	}

	/*
	 * 연체관리
	 */
	function ma_arrears(){
		$this->load->view($this->uri->uri_string());
	}

	/*
	 * 대부금설정
	 */
	function loan_setting(){
		//////////////////////////////////////////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('form_validation');
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//all list
		//투자자산(AABA)의 하위 계정만 가져오기
		$select = 'ano, title_owner';
		$where_arr = array(array('', 'pano', 24), array('', 'use', 'Y'), array('like', 'title_owner', '대부금', 'before'));
		$orderby = 'title_owner';
		$all_list = $this->common_model->gets(ACCOUNT, $where_arr, $select, $orderby);
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//검색조건 셋팅
		$s_info = $this->get_form_values(LOAN_META);
		//var_dump($s_info);
		if ($this->input->query_string('ano') != '') $s_info['ano'] = $this->input->query_string('ano');
		if ($s_info['ano'] == '') $s_info['ano'] = $all_list[0]['ano'];

		$where_arr = array(array('', 'ano', $s_info['ano']));
		if ($this->input->post('ano') == '') {
			//db 값 셋팅
			if ($this->common_model->exist(LOAN_META, $where_arr)) {
				$s_info = $this->common_model->get(LOAN_META, $where_arr);
			}
		}

		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
		} else {

			$where_arr = array(array('', 'ano', $s_info['ano']));
			$this->common_model->update_insert(LOAN_META, $where_arr, $s_info);
			
			//급여공제 기준일은 입력/수정시 모든 분류에 공통 적용
			$this->common_model->update(LOAN_META, array(), array('pay_day'=>$s_info['pay_day']));

			redirect($this->uri->uri_string().'?ano='.$s_info['ano']);
			//$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
		} 
	}


}



