<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * 
 */

class Accounting extends WWF_Controller {
	function __construct() {
		parent::__construct();
	}
	
	function index(){
		redirect('accounting/acc_billing/basic');
	}
	
	/*
	 *전표입력
	 */
	function acc_billing(){
		$this->{$this->uri->segment(3)}();
	} // end of bunge()

	
	/*
	 * 목적사업의 단순전표입력
	 */
	function make_purpose_business_basic(){
	}
		
	
	
	/*
	 * 단순전표입력
	 */
	function basic(){
		//////////////////////////////////////////////////////
		//common variable
		//$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('form_validation');
		
		////////////////////////////////////////////////////////////////////////////////////
		//선택된 정보 셋팅
		
		if ($this->input->query_string('sno') != '') {
			//전표번호가 있을 때  값 셋팅
			//$where_arr = array(array('', 'sno', $this->input->query_string('sno')));
			//$s_info['basic'] = $this->common_model->get(STATEMENT, $where_arr);
			
		} else {
			//form 값 셋팅
			$s_info['basic'] = $this->get_form_values(STATEMENT);
			$s_info['payment'] = $this->get_form_values(STATEMENT_PAYMENT);

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
			//echo '실패';
			$this->load->view('/accounting/acc_billing/basic', array('s_info'=>$s_info));
		} else {
			//echo '성공';
			//전표공통정보 입력입력
			$sno = $this->common_model->add(STATEMENT, $s_info['basic']);
			
			//차변/대변의 각 전표입력
			$option = array();
			foreach ($s_info['statement'] as $dc => $arr) {
				if (!$arr['account_no']) continue;
				array_push($option, array('sno'=>$sno, 'dc'=>$dc, 'account_no'=>$arr['account_no'], 'money'=>$arr['money']));
			}
			$this->common_model->insert_batch(STATEMENT_LIST, array(), $option);
			
			//전표 증빙 입력
			$option = array();
			if ($s_info['payment']['payment_method']) {
				for ($i=0; $i<count($s_info['payment']['payment_method']); $i++) {
					if ($s_info['payment']['payment_money'][$i] == '') continue;	//일단 입력안해도 넘어가도록함-추후
					if ($s_info['payment']['payment_date'][$i] == '') continue;	//일단 입력안해도 넘어가도록함-추후
					if ($s_info['payment']['user_file'][$i] == '') continue;	//일단 입력안해도 넘어가도록함-추후
															
					$option[$i] = array('sno'=>$sno
						, 'payment_method'=>$s_info['payment']['payment_method'][$i]
						, 'payment_money'=>$s_info['payment']['payment_money'][$i]
						, 'payment_date'=>$s_info['payment']['payment_date'][$i]
						, 'user_file'=>$s_info['payment']['user_file'][$i]
						, 'payment_kind'=>$s_info['payment']['payment_kind'][$i]
					);
				}
			}
			if (count($option)>0)
				$sno = $this->common_model->insert_batch(STATEMENT_PAYMENT, array(), $option);	

			//$this->load->view($this->uri->uri_string(), array('s_info'=>$s_info));
			if ($s_info['basic']['pno'] == '')
				redirect('/accounting/acc_billing/basic');
			else 
				//목적사업신청의 전표생성일 경우 
				redirect('/purpose_business/porpose_search');
				
		}
	} // end of basic()	
	
	/*
	 * 복합전표입력
	 */
	function compound(){
		$this->load->view($this->uri->uri_string());
	} // end of compound()	

	/*
	 * 전표조회
	 */
	function billing_search(){
		
		//////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('pagination');
		$this->load->library('form_validation');
		$this->load->model('statement_model');
		
		//전표삭제
		if ($this->input->post('delete_ano')) {
			log_message('error', 'delete_ano');
			$this->statement_model->delete_statement($this->input->post('delete_ano'));
			$_POST['delete_ano'] = '';
		}

		
		//////////////////////////////////////////////////////
		//pagenation
		$config['base_url'] = '/accounting/billing_search/';
		$config['total_rows'] = $this->common_model->load_list_total(STATEMENT);
		//$config['per_page'] = $this->config->item('per_page');
		$config['per_page'] = 100;
		$this->pagination->initialize($config); 
				
		$page_num =  $this->uri->segment(3);
		if (!$page_num) $page_num = 0;
		
		////////////////////////////////////////////////////////////////////////////////////
		//all list
		$this->config->load('pagination');
		////////////////////////////////////////////////////////////////////////////////////
		//검색조건 셋팅
		$s_info = $this -> set_search_condition();

		$where_arr = array();
		if (!in_array($s_info['search_term'], array('', 'all'))) {
			if ($s_info['start_date'] != '')
				$where_arr[] = array('', 'input_date >= ', $s_info['start_date']);
			if ($s_info['end_date'] != '')
				$where_arr[] = array('', 'input_date <= ', $s_info['end_date']);
		}
		if (!in_array($s_info['account_group'], array('', 'all')))
			$where_arr[] = array('', 'account_group', $s_info['account_group']);
		if (!in_array($s_info['account_kind'], array('', 'all')))
			$where_arr[] = array('', 'account_kind = ', $s_info['account_kind']);
		if (!in_array($s_info['target'], array('', 'all')))
			$where_arr[] = array('', 'target = ', $s_info['target']);
		if (!in_array($s_info['target_name'], array('', 'all')))
			$where_arr[] = array('like', 'target_name', $s_info['target_name']);
		if (!in_array($s_info['account_no'], array('', 'all')))
			$where_arr[] = array('', 'account_no', $s_info['account_no']);
		if (!in_array($s_info['status'], array('', 'all')))
			$where_arr[] = array('', 'status', $s_info['status']);
		


		if ($s_info['search_term'] == 'all' || count($where_arr)>0) {
			$orderby = 'input_date DESC';
			$all_list = $this->common_model->gets(STATEMENT, $where_arr, '', $orderby, $page_num, $config['per_page']);
			//각 전표마다 대변/차변 값 가져오기
			$in_str = array();
			foreach ($all_list as $row) {
				$in_str[] = $row['sno'];
			}
			
			if (count($in_str)>0) {
				$select=STATEMENT_LIST.'.*, A.title_owner';
				$where_arr = array(array('in', STATEMENT_LIST.'.sno', $in_str));
				$join = array(
					array(ACCOUNT.' as A', STATEMENT_LIST.'.account_no = '.'A.ano', 'left')
				
				);
				$dc_list = $this->common_model->gets(STATEMENT_LIST, $where_arr, $select, '', -1, -1, '', $join, array('sno', 'dc'));
				//var_dump($dc_list);
				
				//차변/대변 정보를 전포목록에 합치기
				foreach ($all_list as $idx => $row) {
					$all_list[$idx]['dc'] = $dc_list[$row['sno']];
					if (!array_key_exists('debit_main', $all_list[$idx]['dc'])) $all_list[$idx]['dc']['debit_main'] = array('account_no' => '', 'money'=>0, 'title_owner' =>'');
					if (!array_key_exists('credit_main', $all_list[$idx]['dc'])) $all_list[$idx]['dc']['credit_main'] = array('account_no' => '', 'money'=>0, 'title_owner' =>'');
					if (!array_key_exists('debit_sub', $all_list[$idx]['dc'])) $all_list[$idx]['dc']['debit_sub'] = array('account_no' => '', 'money'=>0, 'title_owner' =>'');
					if (!array_key_exists('credit_sub', $all_list[$idx]['dc'])) $all_list[$idx]['dc']['credit_sub'] = array('account_no' => '', 'money'=>0, 'title_owner' =>'');
				}
				
				//log_message('error', $this->db->last_query());
			}
			
		}
		//log_message('error', $in_str);


		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view($this->uri->uri_string(), array('total_rows'=>$config['total_rows'], 'all_list'=>$all_list, 's_info'=>$s_info));
		} else {
			$this->load->view($this->uri->uri_string(), array('total_rows'=>$config['total_rows'], 'all_list'=>$all_list, 's_info'=>$s_info));
		} 
		
	} // end of billing_search()	

	/*
	 *전표결산
	 */
	function chit_manage(){
		$this->{$this->uri->segment(3)}();
	} // end of bunge()
		
	/*
	 * 
	 */
	function acc_grouping(){
		$this->load->view($this->uri->uri_string());
	} // end of acc_grouping()	

	
	function acc_closing(){
		$this->load->view($this->uri->uri_string());
	} // end of acc_grouping()	

	
	
	/*
	 *장부관리
	 */
	function jangbu(){
		$this->{$this->uri->segment(3)}();
	} // end of jangbu()

	/*
	 * 일(월)계표
	 */
	function acc_ilge(){
		
		//////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->model('statement_model');
		$this->load->library('form_validation');
		
		////////////////////////////////////////////////////////////////////////////////////
		//all list
		//모든 계정과목
		
		////////////////////////////////////////////////////////////////////////////////////
		//선택된 정보 셋팅
		//검색조건 셋팅
		$s_info = $this -> set_search_condition();
		
		$where_arr = array();
		if (!in_array($s_info['search_term'], array('all'))) {
			if ($s_info['start_date'] != '')
				$where_arr[] = array('', 'A.input_date >= ', $s_info['start_date']);
			if ($s_info['end_date'] != '')
				$where_arr[] = array('', 'A.input_date <= ', $s_info['end_date']);
		}
		if (!in_array($s_info['account_group'], array('', 'all')))
			$where_arr[] = array('', 'A.account_group', $s_info['account_group']);
		
		
		if (count($where_arr) > 0 || in_array('all', array($s_info['search_term']))) {
			$s_info['account_sum_list'] = $this->statement_model->get_statement_dc_sum($where_arr, array('code'));
		}

		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
		} else {
			$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
		} 
	} // end of acc_ilge()	

	/*
	 * 계정보조부
	 */
	function acc_bojo(){
		
		//////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->model('statement_model');
		$this->load->library('form_validation');
		$this->load->helper('statement');
		$balance_standard = $this->config->item('balance');//잔액 계산을 위한 기준
		
		
		////////////////////////////////////////////////////////////////////////////////////
		//all list
		
		////////////////////////////////////////////////////////////////////////////////////
		//검색조건 셋팅
		$s_info = $this -> set_search_condition();

		$where_arr = array();
		if (!in_array($s_info['search_term'], array('all'))) {
			if ($s_info['start_date'] != '')
				$where_arr[] = array('', 'A.input_date >= ', $s_info['start_date']);
			if ($s_info['end_date'] != '')
				$where_arr[] = array('', 'A.input_date <= ', $s_info['end_date']);
		}
		
		if ($s_info['account_no']) {
			$where_arr[] = array('', STATEMENT_LIST.'.account_no', $s_info['account_no']);
			
			//차변 합계금액
			$select='A.input_date, A.sno, A.account_summary, '.STATEMENT_LIST.'.account_no, '.STATEMENT_LIST.'.dc, '.STATEMENT_LIST.'.money, B.title_owner, left(B.code, 2) as code';
			$join = array(
				array(STATEMENT.' as A', STATEMENT_LIST.'.sno= '.'A.sno', 'left')
				,array(ACCOUNT.' as B', STATEMENT_LIST.'.account_no= '.'B.ano', 'left')
			);
			$orderby='B.code ASC';
			//array_push($where_arr, array('like', STATEMENT_LIST.'.dc', 'debit', 'after'));
			$result = $this->common_model->gets(STATEMENT_LIST, $where_arr, $select, $orderby, -1, -1, '', $join, array(), FALSE);
			//log_message('error', $this->db->last_query());
			
			foreach ($result as $row) {
				$debit_money = $credit_money = $balance = 0;
				if (in_array($row['dc'], array('debit_main', 'debit_sub'))) {
					$debit_money = $row['money'];
					$credit_money = 0;
				} else if (in_array($row['dc'], array('credit_main', 'credit_sub'))) {
					$debit_money = 0;
					$credit_money = $row['money'];
				}
				list($debit_balance, $credit_balance) = cal_balance($balance_standard, $row['code'], $debit_money, $credit_money);
				 
				$s_info['statement_list'][] = array('input_date'=>$row['input_date'], 'debit_money'=>$debit_money, 'credit_money'=>$credit_money, 'account_summary'=>$row['account_summary'], 'balance'=>$debit_balance+$credit_balance);
			}
									
			
		}

		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view($this->uri->uri_string(), array('s_info'=>$s_info));
		} else {
			$this->load->view($this->uri->uri_string(), array('s_info'=>$s_info));
		} 
	} // end of acc_bojo()	


	/*
	 *고유목적사업준비금
	 */
	function acc_junbi(){
		$this->{$this->uri->segment(3)}();
	} // end of acc_junbi()

	/*
	 * 설정
	 */
	function junbi_setting() {
		//////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->model('statement_model');
		$this->load->library('form_validation');
		
		////////////////////////////////////////////////////////////////////////////////////
		//all list
				
		////////////////////////////////////////////////////////////////////////////////////
		//검색조건 셋팅
		$s_info = $this -> set_search_condition();

		
		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view($this->uri->uri_string(), array('s_info'=>$s_info));
		} else {
			$this->load->view($this->uri->uri_string(), array('s_info'=>$s_info));
		} 
	} // end of junbi_setting()	
	
	/*
	 * 사용
	 */
	function junbi_use() {
		//////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->model('statement_model');
		$this->load->library('form_validation');
		
		////////////////////////////////////////////////////////////////////////////////////
		//all list
				
		////////////////////////////////////////////////////////////////////////////////////
		//검색조건 셋팅
		$s_info = $this -> set_search_condition();

		
		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view($this->uri->uri_string(), array('s_info'=>$s_info));
		} else {
			$this->load->view($this->uri->uri_string(), array('s_info'=>$s_info));
		} 
	} // end of junbi_use()	
	
		/*
	 *제무제표
	 */
	function acc_jaemu(){
		$this->{$this->uri->segment(3)}();
	} // end of acc_jaemu()

	/*
	 * 합계잔액시산표
	 */
	function jae_sisan() {
		//////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->model('statement_model');
		$this->load->library('form_validation');
		$this->load->helper('account');	//view 에서 사용
		$this->load->helper('wwf_array');
				
		////////////////////////////////////////////////////////////////////////////////////
		//all list
		$where_arr = array(array('', 'use', 'Y'));
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
				
		////////////////////////////////////////////////////////////////////////////////////
		//검색조건 셋팅
		$s_info = $this -> set_search_condition();

		$where_arr = array();
		if (!in_array($s_info['search_term'], array('all'))) {
			if ($s_info['start_date'] != '')
				$where_arr[] = array('', 'A.input_date >= ', $s_info['start_date']);
			if ($s_info['end_date'] != '')
				$where_arr[] = array('', 'A.input_date <= ', $s_info['end_date']);
		}
		if (!in_array($s_info['account_group'], array('', 'all')))
			$where_arr[] = array('', 'A.account_group', $s_info['account_group']);
		
		if (count($where_arr) > 0 || in_array('all', array($s_info['search_term']))) {
			$s_info['account_sum_list'] = $this->statement_model->get_statement_dc_sum($where_arr, array('account_no'), 'all');
			
			/////////////////////////////////////////////////////////////////////////////////////
			//추가한 계정의 합계 구하기 - 17:사업수익, 18:사업외수익, 19:고유목적사업비용, 20:일반관리비, 21:사업외비용, 22:법인세비용, 
			//total_business_profit:사업충이익, business_profit:사업이익, tax_profit:법인세차감전순이익, this_profit:당기순이익
			//사업총이익 : 사업수익-사업비용
			//사업이익 : 사업총이익-일반관리비
			//법인세차감전순이익 : 사업이익+사업외수익-사업외비용
			//당기순이익 : 법인세차감전순이익-법인세비용
			$s_info['account_sum_list'] = make_dc_sum_of_sisan($s_info['account_sum_list']);
		}
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view($this->uri->uri_string(), array('all_accounts'=>$all_accounts , 's_info'=>$s_info));
		} else {
			$this->load->view($this->uri->uri_string(), array('all_accounts'=>$all_accounts , 's_info'=>$s_info));
		} 
	} // end of acc_bojo()	

	
	/*
	 * 재무상태표
	 */
	function jae_jaemusang() {
		//////////////////////////////////////////////////////
		//common variable
		$all_accounts = array();	//계정 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->model('statement_model');
		$this->load->library('form_validation');
		$this->load->helper('account');	//view 에서 사용
		$this->load->helper('statement');
		$this->load->helper('wwf_array');
		
		////////////////////////////////////////////////////////////////////////////////////
		//검색조건 셋팅
		$s_info = $this -> set_search_condition();
		//법인설립등기일과 결산기
		list($s_info['registration_date'], $s_info['settlement_term']) = $this->common_model->get_settlement_info();
		
		//결산 기수/검색일 설정
		list($s_info['start_date_list'], $s_info['end_date_list']) = settlement_term_info($s_info['registration_date'], $s_info['settlement_term']);

		//전기 실적
		$s_info['prev_account_sum_list'] = array();
		$where_arr = array();
		if ($s_info['prev_start_date'] != '')
			$where_arr[] = array('', 'A.start_date = ', $s_info['prev_start_date']);
		if ($s_info['prev_end_date'] != '')
			$where_arr[] = array('', 'A.end_date = ', $s_info['prev_end_date']);
		
		if (count($where_arr) > 0) {
			$where_arr[] = array('', 'data_name', 'jaemusang');

			//대차대조표의 하위 계정만 가져온다.
			//$s_info['prev_account_sum_list'] = $this->statement_model->get_statement_dc_sum($where_arr, array('account_no'), 'all', $top_code='A');
			$select=BASIC_DATA_LIST.'.*';
			$join = array(
				array(BASIC_DATA.' as A', BASIC_DATA_LIST.'.bno = '.'A.bno', 'left')
			);
			
			$s_info['prev_account_sum_list'] = $this->common_model->gets(BASIC_DATA_LIST, $where_arr, $select, '', -1, -1, '', $join, array('account_no'));
		}
		
		//당기 실적
		$where_arr = array();
		if ($s_info['start_date'] != '')
			$where_arr[] = array('', 'A.input_date >= ', $s_info['start_date']);
		if ($s_info['end_date'] != '')
			$where_arr[] = array('', 'A.input_date <= ', $s_info['end_date']);
		
		if (count($where_arr) > 0) {
			////////////////////////////////////////////////////////////////////////////////////
			//all list
			//대차대조표의 모든 하위 계정
			$all_where_arr = array(array('', 'use', 'Y'), array('', 'left(code, 1)=', 'A'));
			$all_accounts = $this->common_model->hierarchical_gets(ACCOUNT, array('pano', 'ano'), $all_where_arr);
			 				
			/*
			 * 대차대조표의 계정에 보고서용 계정 추가 및 계정옮기기
			 * '자산총계'을 '부채' 앞으로 '추가
			 * '부채총계'을 '자본' 앞으로 '추가
			 * '자본총계'을 맨 마지막에 추가
			 * '부채와 자본 총계'을 맨 마지막에 추가
			 */
			$all_accounts = make_jaemusang_account($all_accounts);


			//대차대조표의 하위 계정만 가져온다.
			$s_info['account_sum_list'] = $this->statement_model->get_statement_dc_sum($where_arr, array('account_no'), 'all', $top_code='A', TRUE);
			
			/////////////////////////////////////////////////////////////////////////////////////
			//asset_total:자산 총계, liabilities_total:부채 총계, capital_total:자본 총계, liabilities_capital_total:부채와 자본 총계
			//자산:6, 부채:7, 자본:8
			//list($s_info['account_sum_list'], $s_info['prev_account_sum_list']) = make_dc_sum_of_jaemusang($s_info['account_sum_list'], $s_info['prev_account_sum_list'], $this->config->item('account_group'));			
			$s_info['account_sum_list'] = make_dc_sum_of_jaemusang($s_info['account_sum_list'], $this->config->item('account_group'));
		}

		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view($this->uri->uri_string(), array('all_accounts'=>$all_accounts, 's_info'=>$s_info));
		} else {
			$this->load->view($this->uri->uri_string(), array('all_accounts'=>$all_accounts, 's_info'=>$s_info));
		} 
	} // end of jae_jaemusang()	

	/*
	 * 손익계산서
	 */
	function jae_sonik() {
		//////////////////////////////////////////////////////
		//common variable
		$all_accounts = array();	//계정 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->model('statement_model');
		$this->load->library('form_validation');
		$this->load->helper('account');	//view 에서 사용
		$this->load->helper('statement');
		$this->load->helper('wwf_array');
 		
		////////////////////////////////////////////////////////////////////////////////////
		//검색조건 셋팅
		$s_info = $this -> set_search_condition();
		//법인설립등기일과 결산기
		list($s_info['registration_date'], $s_info['settlement_term']) = $this->common_model->get_settlement_info();
		
		//결산 기수/검색일 설정
		//list($s_info['start_date_list'], $s_info['end_date_list']) = settlement_term_info($s_info['registration_date'], $s_info['settlement_term']);

		//전기 실적
		$s_info['prev_account_sum_list'] = array();
		$where_arr = array();
		if ($s_info['prev_start_date'] != '')
			$where_arr[] = array('', 'A.start_date = ', $s_info['prev_start_date']);
		if ($s_info['prev_end_date'] != '')
			$where_arr[] = array('', 'A.end_date = ', $s_info['prev_end_date']);
		
		if (count($where_arr) > 0) {
			$where_arr[] = array('', 'data_name', 'sonik');

			//대차대조표의 하위 계정만 가져온다.
			//$s_info['prev_account_sum_list'] = $this->statement_model->get_statement_dc_sum($where_arr, array('account_no'), 'all', $top_code='A');
			$select=BASIC_DATA_LIST.'.*';
			$join = array(
				array(BASIC_DATA.' as A', BASIC_DATA_LIST.'.bno = '.'A.bno', 'left')
			);
			
			$s_info['prev_account_sum_list'] = $this->common_model->gets(BASIC_DATA_LIST, $where_arr, $select, '', -1, -1, '', $join, array('account_no'));
		}


		
		//당기 실적
		$where_arr = array();
		if ($s_info['start_date'] != '')
			$where_arr[] = array('', 'A.input_date >= ', $s_info['start_date']);
		if ($s_info['end_date'] != '')
			$where_arr[] = array('', 'A.input_date <= ', $s_info['end_date']);
		
		if (count($where_arr) > 0) {
			////////////////////////////////////////////////////////////////////////////////////
			//all list
			//손익계산서의 모든 하위 계정
			$all_where_arr = array(array('', 'use', 'Y'), array('', 'left(code, 1)=', 'B'));
			$all_accounts = $this->common_model->hierarchical_gets(ACCOUNT, array('pano', 'ano'), $all_where_arr);
			
			/*
			 * 손익계산서의 계정에 보고서용 계정 추가 및 계정옮기기
			 * '사업외수익'을 '사업외비용' 앞으로 옮기기
			 * '사업총이익'을 '일반관리비' 앞으로 '추가
			 * '사업이익'을 '사업외수익' 앞으로 추가 - '사업외수익'이 비용(ano=10)으로 옮겨갔으므로 pano=10이다
			 * '법인세차감전순이익'을 '법인세비용'앞으로 추가
			 * '당기순이익'을 맨 마지막(법인세비용 뒤)에 추가
			 */
			$all_accounts = make_sonik_account($all_accounts);
			
			//대차대조표의 하위 계정만 가져온다.
			$s_info['account_sum_list'] = $this->statement_model->get_statement_dc_sum($where_arr, array('account_no'), 'all', $top_code='B', TRUE);
			
			/////////////////////////////////////////////////////////////////////////////////////
			//추가한 계정의 합계 구하기 - 17:사업수익, 18:사업외수익, 19:고유목적사업비용, 20:일반관리비, 21:사업외비용, 22:법인세비용, 
			//total_business_profit:사업충이익, business_profit:사업이익, tax_profit:법인세차감전순이익, this_profit:당기순이익
			//사업총이익 : 사업수익-사업비용
			//사업이익 : 사업총이익-일반관리비
			//법인세차감전순이익 : 사업이익+사업외수익-사업외비용
			//당기순이익 : 법인세차감전순이익-법인세비용
			//list($s_info['account_sum_list'], $s_info['prev_account_sum_list']) = make_dc_sum_of_sonik($s_info['account_sum_list'], $s_info['prev_account_sum_list'], $this->config->item('account_group'));
			$s_info['account_sum_list'] = make_dc_sum_of_sonik($s_info['account_sum_list'], $this->config->item('account_group'));
		}

		
		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view($this->uri->uri_string(), array('all_accounts'=>$all_accounts , 's_info'=>$s_info));
		} else {
			$this->load->view($this->uri->uri_string(), array('all_accounts'=>$all_accounts , 's_info'=>$s_info));
		} 
	} // end of jae_sonik()	

	/*
	 * 이익잉여금처분
	 */
	function jae_gesan() {
		//////////////////////////////////////////////////////
		//common variable
		$all_accounts = array();	//목록
		$s_info = array();	//선택한 or 입력한 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->model('statement_model');
		$this->load->library('form_validation');
		$this->load->helper('account');	//view 에서 사용
		$this->load->helper('statement');
		
		////////////////////////////////////////////////////////////////////////////////////
		//all list
		//이익잉여금 처분계산서의 계정
		$earned_surplus_acc = $this->config->item('earned_surplus');	//이익잉여금의 계정과목들
		 				
		////////////////////////////////////////////////////////////////////////////////////
		//검색조건 셋팅
		$s_info = $this -> set_search_condition();
		//법인설립등기일과 결산기
		list($s_info['registration_date'], $s_info['settlement_term']) = $this->common_model->get_settlement_info();
		
		//결산 기수/검색일 설정
		list($s_info['start_date_list'], $s_info['end_date_list']) = settlement_term_info($s_info['registration_date'], $s_info['settlement_term']);

		
		//당기 실적
		$where_arr = array();
		if ($s_info['start_date'] != '')
			$where_arr[] = array('', 'A.input_date >= ', $s_info['start_date']);
		if ($s_info['end_date'] != '')
			$where_arr[] = array('', 'A.input_date <= ', $s_info['end_date']);
		
		if (count($where_arr) > 0) {
		}
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view($this->uri->uri_string(), array('all_accounts'=>$all_accounts, 'earned_surplus_acc'=>$earned_surplus_acc, 's_info'=>$s_info));
		} else {
			$this->load->view($this->uri->uri_string(), array('all_accounts'=>$all_accounts, 'earned_surplus_acc'=>$earned_surplus_acc, 's_info'=>$s_info));
		} 
	} // end of jae_gesan()	

	/*
	 * 재무재표이월
	 */
	function acc_succession() {
		//log_message('error', 'jae_sisan');
		//////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->model('statement_model');
		$this->load->library('form_validation');
		
		////////////////////////////////////////////////////////////////////////////////////
		//all list
				
		////////////////////////////////////////////////////////////////////////////////////
		//검색조건 셋팅
		$s_info = $this -> set_search_condition();

		
		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view($this->uri->uri_string(), array('s_info'=>$s_info));
		} else {
			$this->load->view($this->uri->uri_string(), array('s_info'=>$s_info));
		} 
	} // end of jae_gesan()	

	
		/*
	 *복합분개
	 */
	function bunge(){
		$this->{$this->uri->segment(3)}();
	} // end of bunge()

	/*
	 * 복합분개 설정
	 * 새로운 화면 - http://html.com/accounting_management/bunge_setting.html
	 */
	function bunge_setting(){
		//////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//복합분개명칭 목록
		$s_info = array();	//선택한 or 입력한 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('form_validation');
		
		////////////////////////////////////////////////////////////////////////////////////
		//all list
		//$all_list = $this->common_model->gets(COMPOUND_ENTRY, array(), 'journal_name', 'journal_name', -1, -1, 'journal_name');
		$where_arr = array();
		$all_list = $this->common_model->gets(COMPOUND_ENTRY, $where_arr, '', 'journal_order');
		//echo $this->db->last_query();

		////////////////////////////////////////////////////////////////////////////////////
		//선택된 정보 셋팅
		if ($this->input->query_string('journal_name')) {
			//form 값 셋팅
			$where_arr = array(array('', 'journal_name', $this->input->query_string('journal_name')));
			$s_info = $this->common_model->gets(COMPOUND_ENTRY, $where_arr);
		} else {
			//db 값 셋팅
			$s_info = $this->get_form_values(COMPOUND_ENTRY);
				
		}
				
		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			//echo '실패';
			$this->load->view('/accounting/bunge', array('all_list'=>$all_list, 's_info'=>$s_info));
		} else {
			//echo '성공';
			
			//db에서 컬럼 리스트르르 배열의 키값으로 만든 배열
			$list_field = $this->get_list_fields(COMPOUND_ENTRY);
			
			//DB에 반영
			$option=array();
			for ($i=1; $i<=count($s_info['account_kind']); $i++) {
				foreach ($list_field as $key => $value) {
				//echo $key;
				//var_dump($s_info[$key]);

					if ($key == 'journal_name')
						$list_field[$key] = $s_info['journal_name'];
					else if ($key == 'journal_order')
						$list_field[$key] = $i;
					else {
						if (array_key_exists($i, $s_info[$key]))
							$list_field[$key] = $s_info[$key][$i];
						else 
							$list_field[$key] = '';
					}
				}
				array_push($option, $list_field);
			}

			$where_arr = array(array('', 'journal_name', $s_info['journal_name']));
			$this->common_model->delete_insert_batch(COMPOUND_ENTRY, $where_arr, $option);
			//$this->load->view('/accounting/bunge', array('all_list'=>$all_list, 's_info'=>$s_info));
			redirect($this->uri->uri_string());
		}
	} // end of bunge_setting()	

	function bunge_magic() {
		$this->load->view($this->uri->uri_string());
	}

	
}