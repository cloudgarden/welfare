<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * 
 */

class Basis_info extends WWF_Controller {
	function __construct() {
		parent::__construct();
		
	}
	
	function index(){
		redirect('basis_info/fund_info/basic_fund_info');
	}
	
	/*
	 *기금정보
	 */
	function fund_info(){
		$this->{$this->uri->segment(3)}();
	}

	/*
	 *기초기금정보
	 */
	function basic_fund_info(){
		//////////////////////////////////////////////////////
		//common variable
		$s_info = array();		//선택한 or 입력한 정보

		//////////////////////////////////////////////////////
		//library, helper, model load
		
		
		//DB에 있는 컬럼중 쪼개서 사용하는 컬럼들
		//$divide_form_name = array('business_number'=>array('분리할 개수','첫번째 분리값 길이','두번째 분리값 길이','세번째 분리값 길이',.....))
		$divide_form_name = array('fund_business_number'=>array(3,3,2,5)
							, 'corporation_number'=>array(2,6,7)
							, 'representative_sn'=>array(2,6,7)
							, 'business_number'=>array(3,3,2,5)
						);
		
		$this->load->library('form_validation');
		
		////////////////////////////////////////////////////////////////////////////////////
		//선택된 정보 셋팅
		
		//기금정보 가져오기. - 저장을 눌렀을 때만 form 값으로 셋팅한다. 기본값은 DB에서 가져온다.
		//if (!$this->input->post('fno') || $this->input->post('fno') == 0) {
		if ($this->input->post('fno')) {
			//form 값 셋팅
			$s_info = $this->get_form_values(BASIC_FUND_INFO, '', $divide_form_name);
		} else {
			//db 값 셋팅
			$s_info = $this->common_model->get(BASIC_FUND_INFO);
			$s_info = $this->divide_table_values($s_info, $divide_form_name);
		}
		//$s_info = $this->get_form_values(BASIC_FUND_INFO, '', $divide_form_name);
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view($this->uri->uri_string(), array('form_values'=>$s_info));
		} else {
		
			//$this->load->view($this->uri->uri_string(), array('form_values'=>$s_info));
			
			//DB에 있는 컬럼중 쪼개서 사용하는 컬럼들을 form value에서 삭제
			array_splice($s_info, $this->divide_column_number($divide_form_name));
			
			if (!$s_info['fno']) {
				$this->common_model->add(BASIC_FUND_INFO, $s_info);
			} else {
			
				$where_arr = array(array('', 'fno', $s_info['fno']));
				$this->common_model->update(BASIC_FUND_INFO, $where_arr, $s_info);
				
			} 
			//echo $this->db->last_query();
			redirect($this->uri->uri_string());
		}
	}
	
	/*
	 *이사회
	 */
	function directorate(){
		$this->load->view($this->uri->uri_string());
	}

	/*
	 *협의회
	 */
	function conference(){
		$this->load->view($this->uri->uri_string());
	}

	/*
	 *계정과목
	 */
	function account_category() {

		//////////////////////////////////////////////////////
		//common variable
		$kind = $this->config->item('account_kind');	//계정과목 분류
		$option_column = array('use'=>'해당여부', 'dc'=>'차대구분', 'group'=>'회계분류');	//계정과목 분류에 따른 값-해당여부, 차대구분, 회계분류
		$relation_name = array('target'=>'대상', 'bundle'=>'묶음계정', 'contra'=>'상대계정', 'contra_bundle'=>'상대묶음계정', 'summary'=>'적요');	//계정과목 분류에 따른 값-묶음계정, 상대계정, 적요
		
		$all_list = array();	//모든 계정과목
		$s_info = array();		//선택한 or 입력한 정보
				
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->helper('account');
		$this->load->helper('wwf_array');
		$this->load->library('form_validation');
		$this->load->model('basis_info/account_model');
		
		$max_depth = $this->account_model->get_max_depth(ACCOUNT, 'depth');

		////////////////////////////////////////////////////////////////////////////////////
		//all list
		$all_list = $this->common_model->hierarchical_gets(ACCOUNT, array('pano', 'ano'));
		
		//var_dump($all_list);
			
		/*
		 * account_option Table.kind : 계정과목 분류 - 수입(income), 지출(expense), 자산이동(movement), 사용/미사용 여부, 차변/대변 구분, 
		 * account_relation Table.relation_name : 분류에 따른 묶음계정(buldle account), 상대계정(contra account), 적요(summary)
		 */

		////////////////////////////////////////////////////////////////////////////////////
		//선택된 정보 셋팅
		/*
		 * $s_info = array('basic'=>array(), 'account_option'=>array('kind[]'=>array('use', 'dc', 'group'))
								, 'account_relation'=>array('kind[]'=>array('buldle'=>array(), 'contra'=>array(), 'summary'=>array()))
								);
		 */
		$s_info = array('basic'=>array(), 'account_option'=>array(), 'account_relation'=>array());	

		//계정과목 분류에 따른 값(묶음계정, 상대계정, 적요) 배열 구조 완성
		foreach ($kind as $key => $value) {
				foreach ($relation_name as $relation_key => $relation_value) {
					$s_info['account_relation'][$key][$relation_key] = array();
				}
		}

		$s_info['basic'] = $this->get_form_values(ACCOUNT, 'ano, depth, code, pano, title, title_owner, use, description');
		

		foreach ($kind as $key => $value) {
			$s_info['account_option'][$key] = $this->get_form_values_by_key(ACCOUNT_OPTION, 'use, dc, group', array('account_option', $key));
			
			$temp_key = $this->input->post($key);
			//echo $key;
			//var_dump($temp_key);

			//묶음계정, 상대계정 타이틀 변경
			foreach ($relation_name as $relation_key => $relation_value) {
				//var_dump($temp_key[$relation_key]);
				
				//if (count($s_info['account_relation'][$key][$relation_key])>0) {
				if (is_array($temp_key) && array_key_exists($relation_key, $temp_key)) { 
					$s_info['account_relation'][$key][$relation_key] = $temp_key[$relation_key];
				}
			}
		}
		//var_dump($s_info['account_relation']);

		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			//echo '실패';
			$this->load->view('basis_info/account_category', array('accounts'=>$all_list , 'kind'=>$kind, 'relation_name'=>$relation_name, 'form_values'=>$s_info, 'max_depth'=>$max_depth));
		} else {
			//계정과목 정보 DB에 반영
			//계정과목의 기본정보 입력
			if ($s_info['basic']['ano']) {
				//echo 'ano update<br>';
				$where_arr = array(array('', 'ano', $s_info['basic']['ano']));
				$this->common_model->update(ACCOUNT, $where_arr, $s_info['basic']);
				//log_message('error', $this->db->last_query());
			}
			
			//하위계정 추가일 경우
			if ($s_info['basic']['ano'] == '' && $s_info['basic']['pano']) {
				//echo 'sub insert<br>';
				$s_info['basic']['title'] = $s_info['basic']['title_owner'];
				$s_info['basic']['ano'] = $this->common_model->add(ACCOUNT, $s_info['basic']);
				
				$where_arr = array(array('', 'ano', $s_info['basic']['pano']));
				$options = array('has_children'=>'1');
				$this->common_model->update(ACCOUNT, $where_arr, $options);
				//계정 순서 재조정
				//$this->update_all_weights($s_info['basic']['pano']);
				$this->update_all_weights(0);
			}
			
			//계정과목 분류에 따른 값(해당여부, 차대구분, 회계분류) 입력
			if ($s_info['account_option']) {
				foreach ($s_info['account_option'] as $key => $options) {
					$where_arr = array(array('', 'ano', $s_info['basic']['ano']), array('', 'kind', $key));
					$options = array('ano'=>$s_info['basic']['ano'], 'kind'=>$key)+$options;
					$this->common_model->update_insert(ACCOUNT_OPTION, $where_arr, $options);
				}
			}
			
			//계정과목 분류에 따른 값(묶음계정, 상대계정, 적요) 입력
			foreach ($s_info['account_relation'] as $key => $options) {
				if (!is_array($options) || count($options)==0) continue;

				foreach ($options as $option_key => $option_value) {
					if ($option_key == 'summary') {
						$table = ACCOUNT_SUMMARY;
						$where_arr = array(array('', 'ano', $s_info['basic']['ano']), array('', 'kind', $key));
						$options = $this->set_option(array('ano'=>$s_info['basic']['ano'], 'kind'=>$key), array('tag'=>$option_value));
						
					} else if ($option_key == 'target') {
						$table = ACCOUNT_TARGET;
						$where_arr = array(array('', 'ano', $s_info['basic']['ano']), array('', 'kind', $key));
						$options = $this->set_option(array('ano'=>$s_info['basic']['ano'], 'kind'=>$key), array('target'=>$option_value));
						
					} else {
						$table = ACCOUNT_RELATION;
						$where_arr = array(array('', 'ano', $s_info['basic']['ano']), array('', 'kind', $key), array('', 'relation_name', $option_key));
						$options = $this->set_option(array('ano'=>$s_info['basic']['ano'], 'kind'=>$key, 'relation_name'=>$option_key), array('relation_ano'=>$option_value));
					}
					
					//기존 data 모두 삭제후 다시 입력.
					$this->common_model->delete($table, $where_arr);
					//log_message('error', $this->db->last_query());
					
					if (is_array($option_value) && count($option_value)>0) {
						$this->common_model->insert_batch($table, $where_arr, $options);
					}
					//log_message('error', $this->db->last_query());
				}
			}
			 
			redirect('basis_info/account_category'.'?ano='.$s_info['basic']['ano']);
			//$this->load->view('basis_info/account_category', array('accounts'=>$all_list , 'kind'=>$kind, 'relation_name'=>$relation_name, 'form_values'=>$s_info, 'max_depth'=>$max_depth));
		}
	} // end of account_category()

	/*
	 *거래처
	 */
	function update_all_weights($pano=0) {
		$this->load->model('basis_info/account_model');
		
		$this->account_model->update_all_weights(ACCOUNT, $pano);
		redirect('basis_info/account_category');
	}
		
	/*
	 *거래처
	 */
	function customer() {
		//////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('pagination');
		$this->load->library('form_validation');
		
		//////////////////////////////////////////////////////
		//pagenation
		$config['base_url'] = '/basis_info/customer/';
		$config['total_rows'] = $this->common_model->load_list_total(CUSTOMER);
		$config['per_page'] = $this->config->item('per_page');
		$this->pagination->initialize($config); 
				
		$page_num =  $this->uri->segment(3);
		if (!$page_num) $page_num = 0;
		
		////////////////////////////////////////////////////////////////////////////////////
		//all list
		$where_arr = array();
		$this->config->load('pagination');
		$all_list = $this->common_model->gets(CUSTOMER, $where_arr, $select='', '', $page_num, $config['per_page']);
		
		////////////////////////////////////////////////////////////////////////////////////
		//선택된 정보 셋팅
		
		//DB에 있는 컬럼중 쪼개서 사용하는 컬럼들
		//$divide_form_name = array('business_number'=>array('분리할 개수','첫번째 분리값 길이','두번째 분리값 길이','세번째 분리값 길이',.....))
		$divide_form_name = array('business_number'=>array(3,3,2,5)
							, 'corporation_number'=>array(2,6,7), 'zipcode'=>array(2,3,3)
						);

		if ($this->input->query_string('cno')) {
			//form 값 셋팅
			$where_arr = array(array('', 'cno', $this->input->query_string('cno')));
			$s_info = $this->common_model->get(CUSTOMER, $where_arr);
			$s_info = $this->divide_table_values($s_info, $divide_form_name);
		} else {
			//db 값 셋팅
			$s_info = $this->get_form_values(CUSTOMER, '', $divide_form_name);
		}

		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view('basis_info/customer', array('total_rows'=>$config['total_rows'], 'all_list'=>$all_list, 'form_values'=>$s_info));
		} else {
			//계정과목 정보 DB에 반영
			array_splice($s_info, $this->divide_column_number($divide_form_name));
			$where_arr = array(array('', 'cno', $s_info['cno']));
			$this->common_model->update_insert(CUSTOMER, $where_arr, $s_info);

			redirect('basis_info/customer');
		} 
	} // end of customer()

	/*
	 *사원
	 */
	function employee() {
		//////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('pagination');
		$this->load->library('form_validation');
		
		//////////////////////////////////////////////////////
		//pagenation
		$config['base_url'] = '/basis_info/employee/';
		$config['total_rows'] = $this->common_model->load_list_total(EMPLOYEE);
		$config['per_page'] = $this->config->item('per_page');
		$this->pagination->initialize($config); 
				
		$page_num =  $this->uri->segment(3);
		if (!$page_num) $page_num = 0;
		
		////////////////////////////////////////////////////////////////////////////////////
		//all list
		$where_arr = array();
		$this->config->load('pagination');
		$all_list = $this->common_model->gets(EMPLOYEE, $where_arr, $select='', '', $page_num, $config['per_page']);
		
		////////////////////////////////////////////////////////////////////////////////////
		//선택된 정보 셋팅
		
		//DB에 있는 컬럼중 쪼개서 사용하는 컬럼들
		//$divide_form_name = array('business_number'=>array('분리할 개수','첫번째 분리값 길이','두번째 분리값 길이','세번째 분리값 길이',.....))
		$divide_form_name = array('sn'=>array(2,6,7), 'zipcode'=>array(2,3,3)
						);

		if ($this->input->query_string('eno')) {
			//form 값 셋팅
			$where_arr = array(array('', 'eno', $this->input->query_string('eno')));
			$s_info = $this->common_model->get(EMPLOYEE, $where_arr);
			$s_info = $this->divide_table_values($s_info, $divide_form_name);
		} else {
			//db 값 셋팅
			$s_info = $this->get_form_values(EMPLOYEE, '', $divide_form_name);
		}
		
		
		$cate['is_work'] = $this->get_categorization('재직상태', 'select', 'weight asc');
		$cate['company'] = $this->get_categorization('사업장', 'select', 'weight asc');
		$cate['department'] = $this->get_categorization('부서', 'select', 'weight asc');
		$cate['position'] = $this->get_categorization('직위', 'select', 'weight asc');
		$cate['rank'] = $this->get_categorization('직급', 'select', 'weight asc');
		$cate['duty'] = $this->get_categorization('직책', 'select', 'weight asc');
		$cate['etype'] = $this->get_categorization('사원구분', 'radio', 'weight asc', 'etype');

		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			//echo '실패';
			$this->load->view('basis_info/employee', array('total_rows'=>$config['total_rows'], 'all_list'=>$all_list, 's_info'=>$s_info, 'cate'=>$cate));
		} else {
			//echo '성공';
			//계정과목 정보 DB에 반영
			array_splice($s_info, $this->divide_column_number($divide_form_name));
			$where_arr = array(array('', 'enumber', $s_info['enumber']));
			$this->common_model->update_insert(EMPLOYEE, $where_arr, $s_info);
			
			//$this->load->view('basis_info/employee', array('form_values'=>$s_info));
			redirect('basis_info/employee');
		}
	} // end of employee()
	
	/*
	 *사원
	 */
	function bank_account() {
		//////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('pagination');
		$this->load->library('form_validation');
		
		//////////////////////////////////////////////////////
		//pagenation
		$config['base_url'] = '/basis_info/bank_account/';
		$config['total_rows'] = $this->common_model->load_list_total(BANK_ACCOUNT);
		$config['per_page'] = $this->config->item('per_page');
		$this->pagination->initialize($config); 
				
		$page_num =  $this->uri->segment(3);
		if (!$page_num) $page_num = 0;
		
		////////////////////////////////////////////////////////////////////////////////////
		//all list
		$where_arr = array();
		$this->config->load('pagination');
		$all_list = $this->common_model->gets(BANK_ACCOUNT, $where_arr, $select='', '', $page_num, $config['per_page']);
		
		////////////////////////////////////////////////////////////////////////////////////
		//선택된 정보 셋팅
		

		if ($this->input->query_string('bno')) {
			//form 값 셋팅
			$where_arr = array(array('', 'bno', $this->input->query_string('bno')));
			$s_info = $this->common_model->get(BANK_ACCOUNT);
		} else {
			//db 값 셋팅
			$s_info = $this->get_form_values(BANK_ACCOUNT);
		}
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			//echo '실패';
			$this->load->view('basis_info/bank_account', array('total_rows'=>$config['total_rows'], 'all_list'=>$all_list, 's_info'=>$s_info));
		} else {
			//echo '성공';
			//계정과목 정보 DB에 반영
			$where_arr = array(array('', 'bno', $s_info['bno']));
			$this->common_model->update_insert(BANK_ACCOUNT, $where_arr, $s_info);
			
			//$this->load->view('basis_info/bank_account', array('form_values'=>$s_info));
			redirect('basis_info/bank_account');
		}
	} // end of bank_account()
		
	
	/*
	 *기초자료 입력
	 */
	function base_info(){
		$this->{$this->uri->segment(3)}();
	}
	
	/*
	 *재무상태표 
	 */
	function inputbalance(){
		//////////////////////////////////////////////////////
		//common variable
		//$all_accounts = array();	// 
		$all_list = array();	// 
		$s_info = array();	//선택한 or 입력한 정보
		$basic_data_list = $this->config->item('basic_data');
		$s_info['kind'] = $this->input->query_string('kind');
		if ($s_info['kind'] == '') $s_info['kind'] = key($basic_data_list);
		//echo $s_info['kind'];
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('pagination');
		$this->load->library('form_validation');
		$this->load->helper('statement');
		
		
		////////////////////////////////////////////////////////////////////////////////////
		//대차대조표의 모든 하위 계정
		//$all_where_arr = array(array('', 'use', 'Y'), array('', 'left(code, 1)=', 'A'));
		//$all_accounts = $this->common_model->hierarchical_gets(ACCOUNT, array('pano', 'ano'), $all_where_arr);
		
		//법인설립등기일과 결산기
		list($s_info['registration_date'], $s_info['settlement_term']) = $this->common_model->get_settlement_info();
		
		//결산 기수/검색일 설정
		list($s_info['start_date_list'], $s_info['end_date_list']) = settlement_term_info($s_info['registration_date'], $s_info['settlement_term']);
		
		////////////////////////////////////////////////////////////////////////////////////
		//all list
		
		////////////////////////////////////////////////////////////////////////////////////
		//기초자료 최초 List 생성 - 기초자료를 최초로 들어갈 때만 실행
		if ($s_info['registration_date'] != '0000-00-00' && !$this->common_model->exist(BASIC_DATA)) {
			//echo "없음";
			//기초자료 공통정보 입력입력
			for ($i=1; $i<count($s_info['start_date_list']); $i++) {
				$option = array();
				foreach ($this->config->item('basic_data') as $key => $val) {
					$option[] = array('stage'=>(count($s_info['start_date_list'])-$i), 'data_name'=>$key, 'start_date'=>$s_info['start_date_list'][$i], 'end_date'=>$s_info['end_date_list'][$i]);
				}
				//var_dump($option);
				$this->common_model->insert_batch(BASIC_DATA, array(), $option);
			}
						
		} else {
			//echo "있음";
		}
		
		//기초자료의 모든 목록
		$where_arr = array(array('', 'data_name', $s_info['kind']));
		$all_list = $this->common_model->gets(BASIC_DATA, $where_arr, '', 'stage DESC');
		
		//log_message('error', $this->db->last_query());
				
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view('/basis_info/base_info/inputbalance', array('all_list'=>$all_list, 's_info'=>$s_info));
		} else {
			//계정과목 정보 DB에 반영
			$this->load->view('/basis_info/base_info/inputbalance', array('all_list'=>$all_list, 's_info'=>$s_info));
			//redirect('/basis_info/base_info/inputbalance');
		}
	} // end of inputbalance()

	/*
	 *손익계산서
	 */
	function inputincome(){
		$this->load->view('/basis_info/base_info/inputincome');
	} // end of inputbalance()

	/*
	 *고유목적사업비
	 */
	function input_goubiz(){
		//////////////////////////////////////////////////////
		//common variable
		//$all_accounts = array();	// 
		$all_list = array();	// 
		$s_info = array();	//선택한 or 입력한 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('form_validation');
		$this->load->helper('statement');
		
		////////////////////////////////////////////////////////////////////////////////////
		//대차대조표의 모든 하위 계정
		//$all_where_arr = array(array('', 'use', 'Y'), array('', 'left(code, 1)=', 'A'));
		//$all_accounts = $this->common_model->hierarchical_gets(ACCOUNT, array('pano', 'ano'), $all_where_arr);
		
		//법인설립등기일과 결산기
		list($s_info['registration_date'], $s_info['settlement_term']) = $this->common_model->get_settlement_info();
		
		//결산 기수/검색일 설정
		list($s_info['start_date_list'], $s_info['end_date_list']) = settlement_term_info($s_info['registration_date'], $s_info['settlement_term']);

		////////////////////////////////////////////////////////////////////////////////////
		//all list
		
		////////////////////////////////////////////////////////////////////////////////////
		//기초자료 최초 List 생성 - 기초자료를 최초로 들어갈 때만 실행
		if ($s_info['registration_date'] != '0000-00-00' && !$this->common_model->exist(BASIC_DATA_PURPOSE_LIST)) {
			//echo "없음";
			//기초자료 공통정보 입력입력
			for ($i=1; $i<count($s_info['start_date_list']); $i++) {
				$option = array();
				$option[] = array('stage'=>(count($s_info['start_date_list'])-$i), 'data_name'=>'1', 'start_date'=>$s_info['start_date_list'][$i], 'end_date'=>$s_info['end_date_list'][$i]);
				$option[] = array('stage'=>(count($s_info['start_date_list'])-$i), 'data_name'=>'2', 'start_date'=>$s_info['start_date_list'][$i], 'end_date'=>$s_info['end_date_list'][$i]);
				//var_dump($option);
				$this->common_model->insert_batch(BASIC_DATA_PURPOSE_LIST, array(), $option);
			}
						
		} else {
			//echo "있음";
		}
		
		//기초자료의 모든 목록
		$where_arr = array();
		$all_list = $this->common_model->gets(BASIC_DATA_PURPOSE_LIST, $where_arr, '', 'data_name ASC, stage DESC', -1, -1, '', array(), array('data_name', 'stage'));
		
		log_message('error', $this->db->last_query());
		//검색조건 셋팅(기수, 기수의 기간, 기초자료 종류(data_name))
		//$s_info['basic'] = $this->get_form_values(BASIC_DATA);
						
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
		} else {
			//기초자료의 계정등과 금액 입력
			$money1_arr = $this->input->post('money1');
			//var_dump($money1_arr);
			$money2_arr = $this->input->post('money2');
			$money3_arr = $this->input->post('money3');
			$balance1_arr = $this->input->post('balance1');
			$balance2_arr = $this->input->post('balance2');

			foreach ($money1_arr as $bno => $money) {
				//echo 'depth : '.$this->input->post('depth['.$ano.']');
				$option[] = array('money1'=>str_replace(',','',$money1_arr[$bno]), 'money2'=>str_replace(',','',$money2_arr[$bno]), 'money3'=>str_replace(',','',$money3_arr[$bno]), 'balance1'=>str_replace(',','',$balance1_arr[$bno]), 'balance2'=>str_replace(',','',$balance2_arr[$bno]));

				$set_arr = array(array('name'=>'input_date', 'value'=>'now()', 'flag'=>false));
				
				$where_arr = array(array('', 'bno', $bno));
				$this->common_model->update(BASIC_DATA_PURPOSE_LIST, $where_arr, end($option), $set_arr);
			}
			
			//$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
			redirect($this->uri->uri_string());
		}
	} // end of inputbalance()

	/*
	 *설정
	 */
	function setting(){
		$this->{$this->uri->segment(3)}();
	}

	/*
	 *메인메뉴 설정
	 */
	function menu(){
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('form_validation');
		
		
		if ($this->uri->segment_array()>3) {
			$this->menus = $this->common_model->hierarchical_left_menu($this->menus, array('pmid', 'mid'), MENU, array(array('', 'pmid', $this->uri->segment(4))), '', 'weight asc');
		}
			
		//var_dump($this->menus);

		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			//echo '실패';
			$this->load->view('/basis_info/setting/menu', array('menus'=>$this->menus));
		} else {
			//echo '성공';
			$s_info = array(
					'mid' => $this->input->post('mid')
					, 'title' => $this->input->post('title')
					, 'type' => $this->input->post('type'), 'pmid' => $this->input->post('pmid')
					, 'has_children' => $this->input->post('has_children')
					, 'depth' => $this->input->post('depth'), 'weight' => $this->input->post('weight')
					);
					
			//var_dump($s_info);

			$return_url = $this->input->post('return_url');
			//하위 메뉴 추가
			if ($this->input->post('mode') == 'add') {
				$this->common_model->add(MENU, $s_info);
				$return_url .= '/'.$s_info['pmid'];
			}
			//메뉴 수정
			else if ($this->input->post('mode') == 'update') {
				$where_arr = array(array('', 'mid', $this->input->post('org_mid')));
				$this->common_model->update(MENU, $where_arr, $s_info);
				$return_url .= '/'.$s_info['mid'];
			}
			//메뉴 삭제
			else if ($this->input->post('mode') == 'delete') {
				//weight 수정
				$where_arr = array(array('', 'pmid', $s_info['pmid']));
				$this->common_model->update_weights(MENU, $where_arr, $s_info['weight']);
				//echo $this->db->last_query();
				
				//data 삭제
				$where_arr = array(array('', 'mid', $s_info['mid']));
				$this->common_model->delete(MENU, $where_arr);
				
			}
			
			//추가나 삭제시 부모의 하위메뉴가 하나도 없을 경우 has_children=0
			if ($this->common_model->exist(MENU, array(array('', 'pmid', $s_info['pmid'])))) {
				$option_temp = array('has_children' => 1);
			} else {
				$option_temp = array('has_children' => 0);
			}
			
			$where_arr = array(array('', 'mid', $s_info['pmid']));
			$this->common_model->update(MENU, $where_arr, $option_temp);

			/*
			//하위메뉴가 하나도 없을 경우 has_children=0
			if ($this->common_model->exist(MENU, array(array('', 'mid', $s_info['mid'])))) {
				$option_temp = array('has_children' => 0);
			} else {
				$option_temp = array('has_children' => 1);
			}
			
			$where_arr = array(array('', 'mid', $s_info['mid']));
			$this->common_model->update(MENU, $where_arr, $option_temp);
			*/

			//$this->load->view('/basis_info/setting/menu', array('menus'=>$this->menus));
			redirect($return_url);
		}
	} // end of menu()

	/*
	 *각종 분류 설정
	 */
	function category(){
		//////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//모든 분류 목록
		$s_list = array();	//선택된 분류의 목록
		$s_info = array();	//선택한 or 입력한 분류 정보
		$return_url = '/basis_info/setting/category';
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('form_validation');

		////////////////////////////////////////////////////////////////////////////////////
		//all list
		$where_arr = array(array('', 'pgid', '0'));
		$all_list = $this->common_model->gets(CATEGORIZATION, $where_arr);
		
		////////////////////////////////////////////////////////////////////////////////////
		//분류입력을 위한 변수
		$s_info = $this->get_form_values(CATEGORIZATION, ''); 
		$s_info['title'] = $this->input->post('gid');
		
		if ($this->input->query_string('pgid')) {
			$s_info['pgid'] = urldecode($this->input->query_string('pgid'));
		}

		if ($s_info['pgid']) {
			$return_url .= '/?pgid='.$s_info['pgid'];
		}
		
		if ($s_info['pgid']=='0' && $s_info['gid']) {
			$return_url .= '/?pgid='.$s_info['gid'];
		}
		
		////////////////////////////////////////////////////////////////////////////////////
		//선태된 분류 list
		if ($s_info['pgid']) {
			$where_arr = array(array('', 'pgid', urldecode($s_info['pgid'])));
			$s_list = $this->common_model->gets(CATEGORIZATION, $where_arr, '', 'weight asc');
		}
		
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			//echo '실패';
			$this->load->view('/basis_info/setting/category', array('all_list'=>$all_list, 's_list'=>$s_list, 's_info'=>$s_info));
		} else {
			//echo '성공';
					
			//var_dump($s_info);

			// 추가
			if ($this->input->post('mode') == 'add') {
				$this->common_model->add(CATEGORIZATION, $s_info);
			}
			// 수정
			else if ($this->input->post('mode') == 'update') {
				$where_arr = array(array('', 'gid', $this->input->post('org_gid')));
				$this->common_model->update(CATEGORIZATION, $where_arr, $s_info);
			}
			// 정렬순서 변경
			else if ($this->input->post('mode') == 'sortable') {
				$sortable_gid = $this->input->post('sortable_gid');
				$datas = array();
				for ($i=0; $i<count($sortable_gid); $i++) {
					$datas[] = array('gid'=>$sortable_gid[$i], 'weight'=>$i+1);
				}
				$this->common_model->update_batch(CATEGORIZATION, $datas, 'gid');
			}
			// 삭제
			else if ($this->input->post('mode') == 'delete') {
				//weight 수정
				$where_arr = array(array('', 'pgid', $s_info['pgid']));
				$this->common_model->update_weights(CATEGORIZATION, $where_arr, $s_info['weight']);
				//echo $this->db->last_query();
				
				//data 삭제
				$where_arr = array(array('', 'gid', $s_info['gid']));
				$this->common_model->delete(CATEGORIZATION, $where_arr);
				
			}
			
			//추가나 삭제시 부모의 하위메뉴가 하나도 없을 경우 has_children=0
			if ($this->common_model->exist(CATEGORIZATION, array(array('', 'pgid', $s_info['pgid'])))) {
				$option_temp = array('has_children' => 0);
			} else {
				$option_temp = array('has_children' => 1);
			}
			
			$where_arr = array(array('', 'gid', $s_info['pgid']));
			$this->common_model->update(CATEGORIZATION, $where_arr, $option_temp);

			/*
			//하위메뉴가 하나도 없을 경우 has_children=0
			if ($this->common_model->exist(CATEGORIZATION, array(array('', 'gid', $s_info['gid'])))) {
				$option_temp = array('has_children' => 0);
			} else {
				$option_temp = array('has_children' => 1);
			}
			
			$where_arr = array(array('', 'gid', $s_info['gid']));
			$this->common_model->update(CATEGORIZATION, $where_arr, $option_temp);
			*/

			//$this->load->view('/basis_info/setting/category', array('all_list'=>$all_list, 's_list'=>$s_list, 's_info'=>$s_info));
			redirect($return_url);
		}
	} // end of category()

	function customize_table(){
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('form_validation');
		
		////////////////////////////////////////////////////////////////////////////////////
		//분류입력을 위한 변수
		$s_info = $this->get_form_values(CUSTOM_TABLE); 
		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			echo '실패';
			//$this->load->view('/basis_info/setting/customize', array('all_list'=>$all_list, 's_list'=>$s_list, 'fid'=>$fid));
		} else {
			// 추가
			if ($this->input->post('mode') == 'add') {
				$this->common_model->add(CUSTOM_TABLE, $s_info);
			}
			
			redirect('/basis_info/setting/customize');
		}
	}
	
	function customize(){
		//////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//모든 분류 목록
		$s_list = array();	//선택된 Table의 항목 목록
		$s_info = array();	//선택한 or 입력한 항목 정보
		$return_url = '/basis_info/setting/customize';
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('form_validation');

		////////////////////////////////////////////////////////////////////////////////////
		//all list
		$all_list = $this->common_model->gets(CUSTOM_TABLE);

		////////////////////////////////////////////////////////////////////////////////////
		//입력 or 수정할 항목
		$s_info = $this->get_form_values(CUSTOM_FIELD);
		if (!is_array($s_info['option'])) $s_info['option'] = array();
				
		////////////////////////////////////////////////////////////////////////////////////
		//분류입력을 위한 변수
		
		if ($this->input->query_string('fid')) {
			$s_info['fid'] = $this->input->query_string('fid');
		}

		if ($s_info['fid']) {
			/*
			$option = $this->input->post('option');
			$option_value = '';
			foreach ($option as $value) {
				if ($option_value) $option_value.='|';
				$option_value .= $value;
			}
			$s_info['option'] = $option_value;
			 */
			
			 
			$return_url .= '/?fid='.$s_info['fid'];
		}
		//var_dump($s_info);
		
		////////////////////////////////////////////////////////////////////////////////////
		//선택된 Table의 항목 목록
		if ($s_info['fid']) {
			$where_arr = array(array('', 'fid', $s_info['fid']));
			$s_list = $this->common_model->gets(CUSTOM_FIELD, $where_arr, '', 'weight asc');
		}
		
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			//echo '실패';
			$this->load->view('/basis_info/setting/customize', array('all_list'=>$all_list, 's_list'=>$s_list, 's_info'=>$s_info));
		} else {
			//echo '성공';
			$s_info['option'] = implode("|", $s_info['option']);


			// 추가
			if ($this->input->post('mode') == 'add') {
				$this->common_model->add(CUSTOM_FIELD, $s_info);
			}
			// 수정
			else if ($this->input->post('mode') == 'update') {
				$where_arr = array(array('', 'gid', $this->input->post('org_gid')));
				$this->common_model->update(CUSTOM_FIELD, $where_arr, $s_info);
			}
			// 정렬순서 변경
			else if ($this->input->post('mode') == 'sortable') {
				$sortable_gid = $this->input->post('sortable_fid');
				$datas = array();
				for ($i=0; $i<count($sortable_fid); $i++) {
					$datas[] = array('fid'=>$sortable_fid[$i], 'weight'=>$i+1);
				}
				$this->common_model->update_batch(CUSTOM_FIELD, $datas, 'fid');
			}
			// 삭제
			else if ($this->input->post('mode') == 'delete') {
				//weight 수정
				$where_arr = array(array('', 'fid', $s_info['fid']));
				$this->common_model->update_weights(CUSTOM_FIELD, $where_arr, $s_info['weight']);
				//echo $this->db->last_query();
				
				//data 삭제
				$where_arr = array(array('', 'fid', $s_info['fid']));
				$this->common_model->delete(CUSTOM_FIELD, $where_arr);
				
			}
			/*
			 * 
			 */
			

			//$this->load->view('/basis_info/setting/customize', array('all_list'=>$all_list, 's_list'=>$s_list, 's_info'=>$s_info));
			redirect($return_url);
		}
	} // end of customize()

	////////////////////////////////////////////////////////////////////////////
	// 임시 함수
	function set_system_setup() {
		$this->load->view($this->uri->uri_string());
	}

	////////////////////////////////////////////////////////////////////////////
	// 임시 함수
	function set_admin_setup() {
		$this->load->view($this->uri->uri_string());
	}

}