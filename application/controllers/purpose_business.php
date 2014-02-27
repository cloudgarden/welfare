<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Purpose_business extends WWF_Controller {
	function __construct() {
		parent::__construct();
		
	}
	
	/*
	 * 목적사업
	 */
	function index(){
		redirect('/purpose_business/dashboard');
	}
	
	/*
	 * 목적사업 신청현황
	 */
	function dashboard(){
		//////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//계정 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		//$this->load->model('statement_model');
		$this->load->library('form_validation');
		
		////////////////////////////////////////////////////////////////////////////////////
		//검색조건 셋팅
		$s_info = $this -> set_search_condition();
		if ($s_info['search_year'] == '') $s_info['search_year'] = date('Y');
		
		//법인설립등기일과 결산기
		list($s_info['registration_date'], $s_info['settlement_term']) = $this->common_model->get_settlement_info();
		
		//당기 실적
		$where_arr = array();
		if ($s_info['start_date'] != '')
			$where_arr[] = array('', 'request_date >= ', $s_info['start_date']);
		if ($s_info['end_date'] != '')
			$where_arr[] = array('', 'request_date <= ', $s_info['end_date']);
		
		if (count($where_arr) > 0) {
			////////////////////////////////////////////////////////////////////////////////////
			//all list
			//$select=PURPOSE_BUSINESS.'.*, A.title_owner';
			$select=PURPOSE_BUSINESS.'.ano, sum('.PURPOSE_BUSINESS.'.request_money), A.title_owner';
			$join = array(
				array(ACCOUNT.' as A', PURPOSE_BUSINESS.'.ano = '.'A.ano', 'left')
			);
			$orderby = 'A.title_owner';
			$group_by = PURPOSE_BUSINESS.'.ano';
			$all_list = $this->common_model->gets(PURPOSE_BUSINESS, $where_arr, $select, '', -1, -1, $group_by, $join, array('ano'), false);
		}

		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
		} else {
			$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
		} 
	}
	
	/*
	 * 목적사업 신청
	 * 경조비, 장학금, 체육문화활동지원, 건강증진지원, 장기근속자지원, 출산육아지원, 주택자금지원, 기념품지원, 재난재해지원, 의료비보조
	 */
	function purpose_apply(){
		//$this->{$this->uri->segment(3)}();
		
		//////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		//DB에 있는 컬럼중 쪼개서 사용하는 컬럼들
		$divide_form_name = array('recipient_sn'=>array(2,6,7)
						);
		
		$this->load->model('statement_model');
		$this->load->library('form_validation');

		////////////////////////////////////////////////////////////////////////////////////
		//검색조건 셋팅
		$s_info = $this->get_form_values(PURPOSE_BUSINESS, '', $divide_form_name);
		
		////////////////////////////////////////////////////////////////////////////////////
		//all list
		//log_message('error', $this->db->last_query());
		
		//구분, 관계등 카테고리 가져오기
		if ($s_info['ano'] == '') {
			//해당 목적사업의 계정ID(ano) 가져오기(사용중으로 설정되었을 때만)
			$s_info['ano'] = $this->common_model->get_ano_by_title_owner($this->menus[$this->uri->segment(2)][$this->uri->segment(3)]['title']);
		}

		$where_arr = array(array('', 'ano', $s_info['ano']));
		$all_list = $this->common_model->get(PURPOSE_BUSINESS_META, $where_arr, 'kind, relation, long_term_kind, support_standard');
		foreach ($all_list as $key => $value) {
			if ($key == 'support_standard') continue;
			$value = trim($value);
			$value = str_replace(', ', ',', $value);
			$all_list[$key] = explode(',', $value);
		}
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
		} else {

			//$where_arr = array(array('', 'ano', $s_info['ano']));
			//$this->common_model->update_insert(PURPOSE_BUSINESS, $where_arr, $s_info);
			//DB에 있는 컬럼중 쪼개서 사용하는 컬럼들을 form value에서 삭제
			array_splice($s_info, $this->divide_column_number($divide_form_name));
			$this->common_model->add(PURPOSE_BUSINESS, $s_info);
			
//log_message('error', $this->db->last_query());
			
			
			redirect($this->uri->uri_string());
			//$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
		} 
	} // end of bunge()

	/*
	 * 목적사업 조회
	 */
	function porpose_search(){
		//////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('form_validation');

		////////////////////////////////////////////////////////////////////////////////////
		//all list
		//log_message('error', $this->db->last_query());
		//고유목적사업비용(BBA)의 하위 계정만 가져오기
		$select = 'ano, title_owner';
		$where_arr = array(array('', 'pano', 19), array('', 'use', 'Y'));
		$orderby = 'title_owner';
		$all_list = $this->common_model->gets(ACCOUNT, $where_arr, $select, $orderby, -1, -1, '', array(), array('ano'));
		//$where_arr = array();
		//$all_list = $this->common_model->gets(PURPOSE_BUSINESS, $where_arr);

		////////////////////////////////////////////////////////////////////////////////////
		//검색조건 셋팅
		////////////////////////////////////////////////////////////////////////////////////
		//검색조건 셋팅
		$s_info = $this -> set_search_condition();
		//$s_info['ano'] = $this->input->post('ano');
		if ($s_info['ano'] == '') $s_info['ano'] = array();
		//$s_info = $this->get_form_values(PURPOSE_BUSINESS);
		

		//검색조건에 해당하는 data 가져오기
		
		$in_str = array();
		if (is_array($s_info['ano']) && count($s_info['ano'])>0) {
			if ($s_info['ano'][0] == 'all')
				$where_arr = array();
			else {
				foreach ($s_info['ano'] as $ano) {
					$in_str[] = $ano;
				}
				$where_arr = array(array('in', PURPOSE_BUSINESS.'.ano', $in_str));
			}
			
			$select=PURPOSE_BUSINESS.'.*, A.title_owner, B.status as statement_status';
			$join = array(
				array(ACCOUNT.' as A', PURPOSE_BUSINESS.'.ano = '.'A.ano', 'left')
				, array(STATEMENT.' as B', PURPOSE_BUSINESS.'.pno = '.'B.pno', 'left')
			);
			
			$s_info['list'] = $this->common_model->gets(PURPOSE_BUSINESS, $where_arr, $select, '', -1, -1, '', $join, array('ano', 'pno'));
			//log_message('error', $this->db->last_query());
		}

		
		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
		} else {
			//redirect($this->uri->uri_string());
			$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
		} 
	}

	/*
	 * 목적사업 설정
	 */
	function purpose_biz_setting(){
		
		//////////////////////////////////////////////////////
		//common variable
		$all_list = array();	//거래처 목록
		$s_info = array();	//선택한 or 입력한 거래처 정보
		
		//////////////////////////////////////////////////////
		//library, helper, model load
		$this->load->library('form_validation');
		
		////////////////////////////////////////////////////////////////////////////////////
		//all list
		//고유목적사업비용(BBA)의 하위 계정만 가져오기
		$select = 'ano, title_owner';
		$where_arr = array(array('', 'pano', 19), array('', 'use', 'Y'));
		$orderby = 'title_owner';
		$all_list = $this->common_model->gets(ACCOUNT, $where_arr, $select, $orderby);
		
		//메뉴명에서 가져오기
		//$select = 'mid, title';
		//$where_arr = array(array('', 'pmid', 'purpose_apply'));
		//$orderby = 'weight';
		//$all_list = $this->common_model->gets(MENU, $where_arr, $select, $orderby);		
		
		////////////////////////////////////////////////////////////////////////////////////
		//검색조건 셋팅
		$s_info = $this->get_form_values(PURPOSE_BUSINESS_META);
		//var_dump($s_info);
		if ($this->input->query_string('ano') != '') $s_info['ano'] = $this->input->query_string('ano');
		if ($s_info['ano'] == '') $s_info['ano'] = $all_list[0]['ano'];
		
		
		$where_arr = array(array('', 'ano', $s_info['ano']));
		if ($this->input->post('ano') == '') {
			//db 값 셋팅
			if ($this->common_model->exist(PURPOSE_BUSINESS_META, $where_arr)) {
				$s_info = $this->common_model->get(PURPOSE_BUSINESS_META, $where_arr);
			}
		}

		//////////////////////////////////////////////////////////////////////////////////////////
		//form_validation 실행
		if ($this -> form_validation -> run() === false) {
			$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
		} else {

			$where_arr = array(array('', 'ano', $s_info['ano']));
			$this->common_model->update_insert(PURPOSE_BUSINESS_META, $where_arr, $s_info);

			
			
			redirect($this->uri->uri_string().'?ano='.$s_info['ano']);
			//$this->load->view($this->uri->uri_string(), array('all_list'=>$all_list, 's_info'=>$s_info));
		} 
	}

}
