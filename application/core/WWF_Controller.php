<?php

class WWF_Controller extends CI_Controller {
	
	var $menus = array();	//메인메뉴, 서부메뉴...

    function __construct() {
        parent::__construct();
		
		//하면 post 전송된 모든 변수값에 대해 xss clean 처리를 해줍니다.
		//$this->param = $this->input->post(NULL, true);
		
		// Form Validation 은 xss_clean를 알아서 막아준다.
		
		/*
		 *  그냥 post로 받아도 되고, param으로 받아도 된다. 
		echo $this->input->post('uid').'<br>';
		echo $this->param['uid'].'<br>';
		 */
		
		//메인메뉴, 레프트 메뉴, 탭메뉴
        $this->load->model('common_model');
		
		$this->menus = $this->common_model->hierarchical_left_menu($this->menus, array('pmid', 'mid'), MENU, array(array('', 'pmid', '0')), '', 'weight');	//메인메뉴
		
		if ($this->uri->segment(1)!='') {
			$this->menus = $this->common_model->hierarchical_left_menu($this->menus, array('pmid', 'mid'), MENU, array(array('', 'pmid', $this->uri->segment(1))), '', 'weight');
			
			//var_dump($this->menus);
		}
	}
	
	/*
	 * layout 설정
	 * 메뉴에 따라 head 파일과 footer 파일을 지정한다.
	 */
	function _remap($method) {
		if (in_array($this->uri->segment(1), array('popup', 'auth'))) {
			$this->load->view('head_nomenu');
			if( method_exists($this, $method)) {
				$this->{"{$method}"}();
			}
			$this->load->view('footer_nomenu');
		} else {
			$this->_head();
			if( method_exists($this, $method)) {
				$this->{"{$method}"}();
			}
			$this->_footer();
		}
	}	
	
    function _head(){
    	//로그인이 안된 경우 경우
		if (!$this->session->userdata('is_login')) {
			//var_dump ($this->session->all_userdata());
			redirect('/auth/login?returnURL='.$this->uri->uri_string());
		}
		
    	//$this->config->load('/wwf/submenu');
    	$sub_menu_css = $this->config->item('submenu_class_css');

		if ($this->uri->segment(1) == '') {//main
			$view_url = 'head_main';
		} else {
			$view_url = 'head';
		}


        $this->load->view($view_url, array('menus'=>$this->menus, 'sub_menu_css'=>$sub_menu_css));        
    }

    function _sidebar(){
    }
	
    function _footer(){
 		if ($this->uri->segment(1) == '') {//main
			$view_url = 'footer_main';
		} else {
			$view_url = 'footer';
		}
        $this->load->view($view_url);
    }
	
	//table의 컬럼과 일치하는 form의 값들을 자동 셋팅 해서 배열로 return
	/*
	 * @param string Table Name
	 * @param string post:배열의 값을 post 값으로, blank:배열의 값을 default 값으로
	 * @param array(string[, string,....]) form name이 배열로 이루어진 경우 키, form name이 다차원 배열인 경우  
	 */
	function get_form_values($table, $select='', $add_form_name=array()) {
		$option = array();
		
		if ($select != '') $this->db->select($select);
		
		//$fields = $this->db->list_fields($table);	
		$fields = $this->db->get($table)->list_fields();
		//echo $this->db->last_query().'<br>';
		
		//DB에 있는 컬럼을 key와 일치하는 form value 셋팅
		foreach ($fields as $field){
			$value = $this->input->post($field);
			if (!is_array($value)) $value = trim($value);
			$option[$field] = $value;
		}
		
		//1. DB 컬럼중 쪼갤 컬럼 셋팅
		//2. form에서 쪼개진 컬럼 값을 합쳐서 db 컬럼에 입력
		foreach ($add_form_name as $name => $num_arr){
			$start_pos = 0;
			for ($i=1; $i<=$num_arr[0]; $i++) {
				if ($i>1) $start_pos += $num_arr[$i-1];
				
				$option[$name.$i] = trim($this->input->post($name.$i));
				$option[$name] .= trim($this->input->post($name.$i));
			}
		}
		
		return $option;
	}

	//table의 컬럼과 일치하는 배열로 return
	/*
	 * @param string Table Name
	 * 	 */
	function get_list_fields($table) {
		$option = array();
		
		$fields = $this->db->get($table)->list_fields();
		//echo $this->db->last_query().'<br>';
		
		foreach ($fields as $field){
			$option[$field] = '';
		}
		
		return $option;
	}

	//form의 변수명이 배열일 경우 사용
	function get_form_values_by_key($table, $select='', $key_arr=array()) {
		
		$option = array();
		
		if ($select != '') $this->db->select($select);
		
		//컬럼 list
		$fields = $this->db->get($table)->list_fields();

		//form에 사용된 field 배열
		$form_value = $this->input->post($key_arr[0]);
		//echo $key_arr[0].':';
		//var_dump($form_value);
		
		foreach ($fields as $field){
			if (count($key_arr)==1) {
				//form 값이 있으면 셋팅, 없으면 ''로 셋팅
				if ($form_value && array_key_exists($field, $form_value)) {
					for ($i=0; $i<count($form_value[$field]); $i++)
						$option[$field][$i] = trim($form_value[$field][$i]);
				} else {
					$option[$field] = '';
				}
			}
			else if (count($key_arr)==2) {
				//form 값이 있으면 셋팅, 없으면 ''로 셋팅
				if ($form_value && array_key_exists($key_arr[1], $form_value) && array_key_exists($field, $form_value[$key_arr[1]])) {
					//echo $key_arr[1].', '.$field.'|';
					$option[$field] = trim($form_value[$key_arr[1]][$field]);
					//echo '$key_arr[1]:'.$form_value[$key_arr[1]][$field];
				} else {
					$option[$field] = '';
				}
			}
		}
		//var_dump($option);
		
		return $option;
	}


	//DB에 있는 컬럼중 쪼개기
	function divide_table_values($form_values, $add_form_name) {
		//echo 'divide_table_values';

		foreach ($add_form_name as $name => $num_arr){
			$start_pos = 0;
			for ($i=1; $i<=$num_arr[0]; $i++) {
				if ($form_values[$name] == '') $form_values[$name.$i] = '';
				else {
					if ($i>1) $start_pos += $num_arr[$i-1];
					
					$form_values[$name.$i] = substr($form_values[$name], $start_pos, $num_arr[$i]);
				}
			}
		}
		return $form_values;
	}
	
	function divide_column_number($divide_form_name){
		$sum = 0;
		foreach ($divide_form_name as $name => $num_arr){
			$sum -= $num_arr[0];
		}
		return $sum;
	}



	//option 값 셋팅
	/*
	 * data 추가 입력시 사용된는 배열(option)
	 * $option_one = array('common_key' =>value, 'common_key' =>value,... )
	 *  : data 입력, 수정시 항상 들어가는 값
	 * $optioin_multi = array(multi_key1'=>array(value, value, ...),'multi_key2'=>array(value, value, ...))
	 *  : 여러개의 값이 들어갈 수 있다.
	 */
	function set_option ($option_one, $optioin_multi=array()) {
		if (!is_array($optioin_multi)) return array();
		
		$return_arr = array();
		//echo '-----------------';
		//var_dump($option_one);
		//echo '$optioin_multi';
		//var_dump($optioin_multi);
		
		
		foreach ($optioin_multi as $key => $arr) {
			if (!is_array($arr)) continue;
			
			foreach ($arr as $value) {
				if ($value=='') continue;
				//echo '$value:'.$value;
				array_shift($arr);
				if (in_array($value, $arr)) continue;	//중복되는 값이 있으면 제거
				
				$value = explode("|", $value);
				//서브계정이 main 계정과 같으면 skip - 상대계정은 자기 자신도 들어 갈 수 있어야 함
				//if ($value[0] == $option_one['ano'] || $value[0] == '') continue;
				
				$return_arr[] = $option_one+array($key => $value[0]);
			}
		}
		
		return $return_arr;
		
	} 
	 
	/*
	 * 분류 가져오기
	 */
	function get_categorization($pgid, $type='', $orderby='') {
		$where_arr = array(array('', 'pgid', $pgid));
		//return $this->gets('categorization', $where_arr, '', 0, 0);
		$return_arr = $this->common_model->gets(CATEGORIZATION, $where_arr, 'title', $orderby);
		
		$list = '';
		if ($type == 'select') {
			foreach ($return_arr as $row) {
				$list .= '<option value="'.$row['title'].'">'.$row['title'].'</option>';
			}
			
			$return_arr = $list;
		}
		
		return $return_arr;
	}
	
	/*
	 * 분류 추가
	 */
	public function set_categorization() {
		echo 'success';
		/*
		$userinfo = json_decode($_GET['data']);
		$userinfo->address = 'seoul';
		$userinfo->phonenumber = '01023456789';
		echo json_encode($userinfo);
		//return $this->add('categorization', $option, array());
		 * 
		 */
	}
	
	/*
	 * 검색조건 설정
	 */
	public function set_search_condition () {
		$s_info = array('start_date'=>'', 'end_date'=>'', 'prev_start_date'=>'', 'prev_end_date'=>'', 'search_term'=>''
						, 'search_year'=>'', 'search_month'=>'', 'search_month4'=>'', 'search_month2'=>''
						, 'account_group'=>'', 'account_no'=>''
						, 'status'=>'', 'account_kind'=>'', 'target'=>'', 'target_name'=>'', 'target_id'=>''
						, 'target_range'=>'', 'ano'=>''
						, 'company'=>'', 'department'=>''
						, 'statement_list' => array(), 'account_sum_list' => array(), 'list' => array()
				);
		
		//검색기간일 셋팅
		if (count($_POST) > 0) {
			foreach ($_POST as $key => $value) {
				//log_message('error', $value);
				$s_info[$key] = $value;
			}
		}
		
		return $s_info;
	}
	
	function test() {
		$this->load->view('test');
	}
	
	
}