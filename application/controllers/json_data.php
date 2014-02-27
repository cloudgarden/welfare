<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * top, sub 메뉴가 없는 컨토롤러
 */

class Json_data extends CI_Controller {
	
	var $data = '';	//ajax를 통해 넘어온 data
	var $userinfo;	//data를 json_decode() 한 값.
	
	function __construct() {
		parent::__construct();
		
		$this->load->model('common_model');
	}
	
	function index(){
	}

	/*
	 * 분류에 따른 계정과목 리스트 가져오기
	 */
	function account_list_by_kind () {
		$this->load->model('/basis_info/account_model');

		$result = array();
		if ($this->input->get('account_kind') != '' || $this->input->get('target') != '') {
			$account_list = $this->account_model->account_list_by_kind($this->input->get('account_kind'), $this->input->get('target'));
			foreach ($account_list as $key => $value) {
				$result['title_owner'][] = $value['title_owner'];
				$result['ano'][] = $value['ano'];
			}
		}
		echo json_encode($result);
	}
	
	/*
	 * 분류에 따른 계정과목 리스트 가져오기
	 */
	function account_list_in_statement () {
		$this->load->model('statement_model');

		$result = array();
		if ($this->input->get('account_group') != '') {
			$result = $this->statement_model->account_list_in_statement($this->input->get('account_group'));
		}

		echo json_encode($result);
	}
	
	/*
	 * 계정과목 의 정보 가져오기 
	 */
	function account_info () {
		//log_message('error', 'account_info()');
		$this->load->model('/basis_info/account_model');

		$result = array();
		$ano = trim($this->input->get('ano'));

		if ($ano != '') {
			//log_message('error', $ano);
			$result = $this->account_model->account_info($ano);
		}

		echo json_encode($result);
	}
	
	/*
	 * 자식계정 추가시 자식계정의 기본정보 만들기 
	 */
	function child_account_info () {
		$this->load->model('/basis_info/account_model');

		$ano = $this->input->get('ano');
		$pano = $this->input->get('pano');
		$code = $this->input->get('code');
		$depth = $this->input->get('depth');
		$has_children = $this->input->get('has_children');
		$result = array();
		
		//자식계정 정보 셋팅
		$result['ano'] = '';
		$result['pano'] = $ano;
		$result['depth'] = $depth+1;
		$result['weight']=1;
		$result['code'] = 65;	//'A'
		
		//부모계정에 자식계정이 있는 경우
		if ($has_children=='1') {
			$where_arr = array(array('', 'pano', $ano));
			$result['weight'] = $this->common_model->get_count(ACCOUNT, $where_arr)+1;
			$result['code'] = 65+$result['weight']-1;
			if ($result['weight']>26) $result['code'] = 97+$result['weight']-26-1;
		}
		$result['code'] = $code.chr($result['code']);
		
		$breadcrumbs = $this->account_model->get_breadcrumbs(ACCOUNT, $ano, 'title_owner, code, pano', array('', ''));
		$result['title_breadcrumbs'] = $breadcrumbs[0];
		$result['code_breadcrumbs'] = $breadcrumbs[1];
		//log_message('error', 'weight : '.$result['weight']);

		echo json_encode($result);
	}
	
	/*
	 * 계정삭제
	 */
	function delete_account () {
		$ano = $this->input->get('ano');
		$pano = $this->input->get('pano');
		$depth = $this->input->get('depth');
		$weight = $this->input->get('weight');
		$has_children = $this->input->get('has_children');
		//부모계정에 자식계정이 있는 경우
		if ($has_children=='1') {
			return false;
		} else {
			$where_arr = array(array('', 'ano', $ano));
			$this->common_model->delete(ACCOUNT, $where_arr);
			
			$where_arr = array(array('', 'pano', $pano));
			$this->common_model->update_weights(ACCOUNT, $where_arr, $weight);
			
			$where_arr = array(array('', 'pano', $pano));
			$chind_nums = $this->common_model->get_count(ACCOUNT, $where_arr) ;
			if ($chind_nums == 0) {
				$where_arr = array(array('', 'ano', $pano));
				$option = array('has_children' => 0);
				$this->common_model->update(ACCOUNT, $where_arr, $option);
			}
		}
	}
	
	/*
	 * 계정과목 의 정보 가져오기 - 묶음계정, 상대계정, 상대묶음계정, 적요
	 */
	function account_info_by_kind () {
		//log_message('error', 'account_info()');
		$this->load->model('/basis_info/account_model');

		$result = array();
		//if ($this->input->get('ano') != '') {
			$account_list = $this->account_model->account_sub_info($this->input->get('ano'), $this->input->get('account_kind'));
			foreach ($account_list as $key => $val_arr) {
				if (is_array($val_arr)) {
					foreach ($val_arr as $row) {
						$result[$key][] = $row;
					}
				} else {
					$result[$key] = $val_arr;
				}
			}
		//}
		echo json_encode($result);
	}
	
	/*
	 * 직원 or 거래처 정보 가져오기
	 */
	function target_info () {
		//log_message('error', 'autocomplete 단순전표입력 Table:'.$this->input->get('target'));

		// no term passed - just exit early with no response
		$term = $_GET['term'];
		if (empty($term)) exit ;
		// remove slashes if they were magically added
		if (get_magic_quotes_gpc()) $term = stripslashes($term);
		
		//log_message('error', 'type : '.$this->input->get('type'));
		
		$search_info = array();	//column, select, orderby
		if ($this->input->get('target') == 'customer') {
			$search_info = array('col'=>'customer_name', 'select'=>'business_number, customer_name', 'orderby'=>'customer_name');
		} else if ($this->input->get('target') == 'employee') {
			if ($this->input->get('type') == '') {
				$search_info = array('col'=>'ename', 'select'=>'enumber, ename', 'orderby'=>'ename');
			} else if ($this->input->get('type') == 'purpose_business') {	//목적사업에서 필요한 사원정보
				$search_info = array('col'=>'ename', 'select'=>'enumber, ename, left(sn, 6) as sn, company, department, position, rank, duty, join_date, home_tel, hand_tel, direct_tel, extension_num, etype, bank_name, bank_account, bank_owner', 'orderby'=>'ename');
			}
		
		} else if ($this->input->get('target') == 'fund_employee') {
			//복지기금내부직원
			$search_info = array('col'=>'ename', 'select'=>'enumber, ename', 'orderby'=>'ename');
			/*
		} else if ($this->input->get('target') == 'fund_inner') {
			$search_info = array('col'=>'ename', 'select'=>'enumber, ename', 'orderby'=>'ename');
			 */
		}
		$where_arr = array(array('like', $search_info['col'], $this->input->get('term')));
		
		$items = $this->common_model->gets($this->input->get('target'), $where_arr, $search_info['select'], $search_info['orderby'], 30, -1, '', array(), array(), FALSE);
		
		//log_message('error', $this->db->last_query());
		$select = str_replace('left(sn, 6) as ', '', $search_info['select']);
		$select = str_replace(' ', '', $select);
		$select = explode(',', $select);
		$result = array();
		
		//if (count($items)<3) {
		$temp_array = array();
		$i=0;
		foreach ($items as $row) {
			if (count($select)<3) {
				$result[] = array("label"=>$row[$select[1]], "id"=>$row[$select[0]], "value" => $row[$select[1]]);
			} else {
				foreach ($select as $cols) {
					//log_message('error', $cols);
					$result[$i]['label'] = $row['ename'];
					$result[$i][$cols] = $row[$cols];
				}
				$i++;
			}
		}
		//log_message('error', count($select));
		
		if (count($items)==0)
			array_push($result, array("label"=>'검색 결과가 없습니다.', "id"=>'', "value" =>''));

		
		// json_encode is available in PHP 5.2 and above, or you can install a PECL module in earlier versions
		echo json_encode($result);
		
		/*
		echo json_encode(array('result'=>true, 'msg'=>$this->input->post('msg')));

		echo '{"hanja": "1", "idx": 0}';
		 */
	}

	/*
	 * 전표/복합분개 의 DB 입력값 가져오기. 최초 화면 셋팅시에 사용
	 */
	function get_compound_entry () {
		$this->load->model('/basis_info/account_model');

		//$journal_name = $this->input->get('journal_name');
		$where_arr = array(array('', 'journal_name', $this->input->get('journal_name')));
		$all_list = $this->common_model->gets(COMPOUND_ENTRY, $where_arr);
		//$all_list = $this->common_model->gets(COMPOUND_ENTRY, $where_arr, 'journal_order, account_no, debit_account_main, debit_account_sub, credit_account_main, credit_account_sub', 'journal_order');
		
		//log_message('error', var_dump($all_list));
		
		foreach ($all_list as $idx => $info_arr) {
			//log_message('error', $idx.$info_arr['ano'] );
			
			$all_list[$idx]['account_sub'] = $this->account_model->account_sub_info($info_arr['account_no'], $info_arr['account_kind']);
			
			$all_list[$idx]['account_list'] = $this->account_model->account_list_by_kind($info_arr['account_kind']);
			//foreach ($account_list as $key => $value) {
			//	$result['title_owner'][] = $value['title_owner'];
			//	$result['ano'][] = $value['ano'];
			//}
			
		}
		echo json_encode($all_list);

	}
	
	/*
	 * 복합분개 설정에서 분류만 가져오기
	 */
	function get_compound_kind () {
		//$journal_name = $this->input->get('journal_name');
		$where_arr = array(array('', 'journal_name', $this->input->get('journal_name')));
		$all_list = $this->common_model->gets(COMPOUND_ENTRY, $where_arr, 'journal_order, account_kind, target, tax, account_group', 'journal_order');

		echo json_encode($all_list);

	}
	
	/*
	 * 대부금 설정 가져오기
	 */
	function get_loan_meta () {
		//$journal_name = $this->input->get('journal_name');
		$where_arr = array(array('', 'ano', $this->input->get('ano')));
		$all_list = $this->common_model->get(LOAN_META, $where_arr);
		log_message('error', $this->db->last_query());

		echo json_encode($all_list);

	}
	
	
}
