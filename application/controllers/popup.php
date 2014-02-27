<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * top, sub 메뉴가 없는 컨토롤러
 */

class Popup extends WWF_Controller {
	function __construct() {
		parent::__construct();
	}
	
	/*
	 * 모든 계정과목 가져오기
	 */
	function zip_search() {
		$this->load->view('popup/zip_search');
	}
	
	/*
	 * 기초자료입력 - 재무상태표
	 */
	function inputbalance() { 
 		//////////////////////////////////////////////////////
		//common variable
		$all_accounts = array();	//대차대조표 계정 목록
		$s_info = array();	//선택한 or 입력한 정보
 
 		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->helper('account');	//view 에서 사용
		$this->load->helper('wwf_array');
		$this->load->library('form_validation');
		
		////////////////////////////////////////////////////////////////////////////////////
		//검색조건 셋팅(기수, 기수의 기간, 기초자료 종류(data_name))
		$s_info['basic'] = $this->get_form_values(BASIC_DATA);
		$s_info['basic']['kind'] = $this->input->post('kind');
		
		
		////////////////////////////////////////////////////////////////////////////////////
		//all list
		//입력된 자료가 없으면  "대차대조표의 모든 하위 계정"을 가져오고, 있으면 입력된 목록을 가져온다.
		$earned_surplus_acc = $this->config->item('earned_surplus');	//이익잉여금의 계정과목들
		
		$where_arr = array(array('', 'bno', $s_info['basic']['bno']));
		if ($this->common_model->exist(BASIC_DATA_LIST, $where_arr)) {
			//입력된 기초자료가 있는 경우
			$s_info['basic']['mode'] = 'udpate';
			$all_accounts = $this->common_model->gets(BASIC_DATA_LIST, $where_arr, $select='', 'order', -1, -1, '', array(), array('account_no'));
			
			//log_message('error', $this->db->last_query());
		} else {
			//입력된 기초자료가 없는 경우
			$s_info['basic']['mode'] = 'insert';
			if ($s_info['basic']['kind'] == 'jaemusang') {
				$where_arr = array(array('', 'use', 'Y'), array('', 'left(code, 1)=', 'A'));
				$all_accounts = $this->common_model->hierarchical_gets(ACCOUNT, array('pano', 'ano'), $where_arr);
				
				/*
				 * 대차대조표의 계정에 보고서용 계정 추가 및 계정옮기기
				 * '자산총계'을 '부채' 앞으로 '추가
				 * '부채총계'을 '자본' 앞으로 '추가
				 * '자본총계'을 맨 마지막에 추가
				 * '부채와 자본 총계'을 맨 마지막에 추가
				 */
				$all_accounts = make_jaemusang_account($all_accounts);
			} else if ($s_info['basic']['kind'] == 'sonik') {
				//손익계산서의 모든 하위 계정
				$where_arr = array(array('', 'use', 'Y'), array('', 'left(code, 1)=', 'B'));
				$all_accounts = $this->common_model->hierarchical_gets(ACCOUNT, array('pano', 'ano'), $where_arr);
				
				/*
				 * 손익계산서의 계정에 보고서용 계정 추가 및 계정옮기기
				 * '사업외수익'을 '사업외비용' 앞으로 옮기기
				 * '사업총이익'을 '일반관리비' 앞으로 '추가
				 * '사업이익'을 '사업외수익' 앞으로 추가 - '사업외수익'이 비용(ano=10)으로 옮겨갔으므로 pano=10이다
				 * '법인세차감전순이익'을 '법인세비용'앞으로 추가
				 * '당기순이익'을 맨 마지막(법인세비용 뒤)에 추가
				 */
				$all_accounts = make_sonik_account($all_accounts);
			} else if ($s_info['basic']['kind'] == 'earned_surplus') {
				//이익잉여금처분계산서 모든 하위 계정
				$all_accounts = array();
			}
		}
		//log_message('error', $this->db->last_query());


		$s_info['prev_account_sum_list'] = array();
		$s_info['account_sum_list'] = array();

		//////////////////////////////////////////////////////////////////////////////////////////
		if ($this -> form_validation -> run() === false) {
			$this->load->view('popup/inputbalance', array('all_accounts'=>$all_accounts, 'earned_surplus_acc'=>$earned_surplus_acc, 's_info'=>$s_info));
		} else {
			//기초자료 공통정보 update
			//$option = array();
			$where_arr = array(array('', 'bno', $s_info['basic']['bno']));
			$option = array('writer'=>$s_info['basic']['writer'], 'input_method'=>$s_info['basic']['input_method']);
			$set_arr = array(array('name'=>'input_date', 'value'=>'now()', 'flag'=>false));
			$this->common_model->update(BASIC_DATA, $where_arr, $option, $set_arr);
			//log_message('error', $this->db->last_query());
			//이미 입력된 자료인지 check
			//$this->common_model->delete(BASIC_DATA, array(array('', 'bno >', '0')));
			//$this->common_model->delete(BASIC_DATA_LIST, array(array('', 'bno >', '0'))); 
			
			//기초자료의 계정등과 금액 입력
			$account_no_arr = $this->input->post('account_no');
			$title_owner_arr = $this->input->post('title_owner');
			$depth_arr = $this->input->post('depth');
			$weight_arr = $this->input->post('weight');
			$prev_money_arr = $this->input->post('prev_money');
			$purpose_money_arr = $this->input->post('purpose_money');
			$fund_money_arr = $this->input->post('fund_money');
			$total_money_arr = $this->input->post('total_money');
			$increase_money_arr = $this->input->post('increase_money');
			$increase_rate_arr = $this->input->post('increase_rate');

			$option = array();
			$order = 0;

			foreach ($account_no_arr as $ano) {
				//echo 'depth : '.$this->input->post('depth['.$ano.']');
				$order++;
				$option[] = array('bno'=>$s_info['basic']['bno'], 'account_no'=>$ano, 'title_owner'=>strip_tags($title_owner_arr[$ano]), 'depth'=>$depth_arr[$ano], 'weight'=>$weight_arr[$ano], 'prev_money'=>str_replace(',','',$prev_money_arr[$ano]), 'purpose_money'=>str_replace(',','',$purpose_money_arr[$ano]), 'fund_money'=>str_replace(',','',$fund_money_arr[$ano]), 'total_money'=>str_replace(',','',$total_money_arr[$ano]), 'increase_money'=>str_replace(',','',$increase_money_arr[$ano]), 'increase_rate'=>$increase_rate_arr[$ano], 'order'=>$order);
				
				if ($s_info['basic']['mode'] == 'udpate') {
					$where_arr = array(array('', 'bno', $s_info['basic']['bno']), array('', 'account_no', $ano));
					$this->common_model->update(BASIC_DATA_LIST, $where_arr, end($option));
				}
			}
			
			//var_dump($option);
			if ($s_info['basic']['mode'] == 'insert') {
				$this->common_model->insert_batch(BASIC_DATA_LIST, $where_arr, $option);
			}
			//$this->common_model->insert_batch(BASIC_DATA_LIST, array(), $option);

			//log_message('error', $this->db->last_query());
			//$this->load->view('popup/inputbalance', array('all_accounts'=>$all_accounts, 'earned_surplus_acc'=>$earned_surplus_acc, 's_info'=>$s_info));
			redirect('/basis_info/base_info/inputbalance?kind='.$s_info['basic']['kind']);
		} 
	} 

	
	/*
	 * 모든 계정과목 가져오기
	 */
	function account_list() {
		$this -> load -> model('basis_info/account_model');
		
		//계정목록
		$this->accounts = $this->account_model->hierarchical_gets('account', array('pano', 'ano'));

		$this->load->helper('account');
		$this->load->helper('wwf_array');
		
		$this->load->view('popup/account_list', array('accounts'=>$this->accounts, 'max_depth'=>$this->input->query_string('max_depth'), 'frm_name'=>$this->input->query_string('frm_name')));
	}

	/*
	 * 목적사업 수정시 팝업창
	 */
	function purpose_apply() {
		//////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		$pno = $this->input->query_string('pno');
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('form_validation');
		
		$where_arr = array(array('', 'pno', $pno));
		$s_info = $this->common_model->get(PURPOSE_BUSINESS, $where_arr);

		$this->load->view('/purpose_business/purpose_apply/pur_ceremony', array('all_list'=>$all_list, 's_info'=>$s_info));
	}
		
	/*
	 * 목적사업 전표생성
	 */
	function statement() {
		//////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->model('/basis_info/account_model');
		$this->load->library('form_validation');

		////////////////////////////////////////////////////////////////////////////////////
		//검색조건 셋팅
		//$s_info = $this->get_form_values(PURPOSE_BUSINESS, '');
		$s_info['pno'] = $this->input->query_string('pno');
		
		//목적사업신청정보 가져오기
		$where_arr = array(array('', 'pno', $s_info['pno']));
		$s_info['purpose_business'] = $this->common_model->get(PURPOSE_BUSINESS, $where_arr);
		//log_message('error', $this->db->last_query());
		
		//계정에 해당하는 차변/대변 여부를 가져와야 하나 모두 '지출', '목적사업회계', '차변'으로 동일하다.
		/*
		$select=ACCOUNT.'.*, A.dc';
		$join = array(
			array(ACCOUNT_OPTION.' as A', ACCOUNT.'.ano = '.'A.ano', 'left')
		);
		$where_arr = array();
		$where_arr[] = array('', ACCOUNT.'.ano', $s_info['purpose_business']['ano']);
		$where_arr[] = array('', 'A.kind', 'expense');
		$where_arr[] = array('', 'A.group', 'purpose');
		$s_info['account_info'] = $this->common_model->get(ACCOUNT, $where_arr, $select, '', '', $join);
		$s_info['account_info']['dc_name'] = ($s_info['account_info']['dc']=='debit') ? '차변' : '대변';
		 */
		
		//적요, 상대계정, 묶음계정, 묶음상대계정 가져오기
		$s_info['account_info'] = $this->account_model->account_sub_info($s_info['purpose_business']['ano'], 'expense');
		$s_info['account_info']['dc_name'] = ($s_info['account_info']['dc']=='debit') ? '차변' : '대변';
		$s_info['account_info']['title_owner'] = $this->common_model->get_title_owner_by_ano($s_info['purpose_business']['ano']);

		if ($this->input->query_string('sno')) {
			//전표번호가 있을 때  값 셋팅
			//$where_arr = array(array('', 'sno', $this->input->query_string('sno')));
			//$s_info['basic'] = $this->common_model->get(STATEMENT, $where_arr);
			
		} else {
			//form 값 셋팅
			$s_info['basic'] = $this->get_form_values(STATEMENT);
			$s_info['payment'] = $this->get_form_values(STATEMENT_PAYMENT);
			
			//form 고정값 셋팅 - 목적사업이므로 고정할 수 있음.
			$s_info['basic']['account_kind'] = 'expense';	//지출
			$s_info['basic']['account_group'] = 'purpose';	//목적사업회계
			
			$s_info['basic']['target'] = $s_info['purpose_business']['target'];
			$s_info['basic']['target_id'] = $s_info['purpose_business']['enumber'];
			$s_info['basic']['target_name'] = $s_info['purpose_business']['ename'];
			$s_info['basic']['account_no'] = $s_info['purpose_business']['ano'];
			$s_info['basic']['dc'] = $s_info['account_info']['dc'];
			//$s_info['basic']['debit_main_money'] = $s_info['purpose_business']['request_money'];

			//차변/대변으로 이루어진 전표목록
			$temp = $this->config->item('statement');
			foreach ($temp as $dc => $arr) {
				$temp[$dc]['account_no'] = $this->input->post($dc);
				$temp[$dc]['money'] = $this->input->post($dc.'_money');
			}
			$s_info['statement'] = $temp;
			//var_dump($s_info['statement']);

			//이미 입력된 파일이 있을 경우 파일명으로 셋팅
			if ($this->input->post('user_file_org'))
				$s_info['payment']['user_file'] = $this->input->post('user_file_org');
			
			//적요를 집적입력할 경우 입력한 값으로 변경
			if ($s_info['basic']['account_summary']=='직접입력' && $this->input->post('account_summary_direct')!='')
				$s_info['basic']['account_summary'] = $this->input->post('account_summary_direct');
		}

		
		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
		} else {
			//log_message('error', $this->db->last_query());

			redirect($this->uri->uri_string());
			//$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
		} 
	}
	

	/*
	 * 대부금 대출이자 계산표
	 * 입금일은 무조건 급여공제기준일이고, 
	 * 첫번째 입금일 : 대부게시일로부터 2번째 급여공제일이 첫번째 입금일이다.
	 */
	function loan_cal() {
		//////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		////////////////////////////////////////////////////////////////////////////////////
		//library, helper, model load
		//$this->load->model('/basis_info/account_model');
		$this->load->library('form_validation');
		$this->load->helper('loan');

		////////////////////////////////////////////////////////////////////////////////////
		//검색조건 셋팅
		//$s_info = $this->get_form_values(PURPOSE_BUSINESS, '');
		$s_info['loan_money'] = intval($this->input->query_string('loan_money'));	//대부금
		$s_info['unredeemed_month'] = intval($this->input->query_string('unredeemed_month'));	//거치 개월수
		$s_info['repayment_method'] = $this->input->query_string('repayment_method');	//상환방법
		$s_info['repayment_month'] = intval($this->input->query_string('repayment_month'));	//상환개월수
		$s_info['loan_start'] = $this->input->query_string('loan_start');	//대부개시일
		$s_info['year_rate'] = floatval($this->input->query_string('year_rate'));	//연이율
		
		if ($s_info['unredeemed_month'] == '') $s_info['unredeemed_month'] = 0;
		//echo $this->uri->uri_string();
		
		////////////////////////////////////////////////////////////////////////////////////
		//급여공제일 가져오기
		$pay_date = $this->common_model->get(LOAN_META, array(), 'pay_day');
		$pay_date = $pay_date['pay_day'];

		//대부게시월에 해당하는 급여공제일
		$s_info['pay_date'] = date("Y-m-".$pay_date, strtotime($s_info['loan_start']));
		
		$s_info = lon_schedule($s_info);

		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
		} else {
			$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
		}
	}	
}
