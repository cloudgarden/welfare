<?php
/*
 * 공통으로 사용하는 model
 */
class WWF_Model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	/*
	 * $table의 모든 값 가져오기
	 * $where_arr = array(array('', $field, $value), array('and', $field, $value))
	 * @where_param : and|or|in|or_in|not_in|or_not_in|like|or_like|not_like|or_not_like
	 */
	function get($table, $where_arr=array(), $select='', $orderby='', $groupby='', $join=array(), $key=array(), $select_flag=true) {
		$this->set_query ($where_arr, $select, $set_arr=array(), $limit=-1, $offset=-1, $orderby, $groupby, $join, $select_flag);

		$result = $this -> db -> get($table) -> row_array();
		return $result;
	}
	
	/*
	 * $mid의 메뉴정보가져오기 
 	 * @param	$key key가 있을 경우 배열의 key를 index에서 key값($row[key])로 대체한다.
	 *
	 */
	function gets($table, $where_arr=array(), $select='', $orderby='', $limit=-1, $offset=-1, $groupby='', $join=array(), $key=array(), $select_flag=true) {
		$this->set_query ($where_arr, $select, $set_arr=array(), $limit, $offset, $orderby, $groupby, $join, $select_flag);

		$result = $this -> db -> get($table) -> result_array();
		//var_dump($result);
		if (count($key) > 0) {
			$temp_arr = array();
			foreach ($result as $idx => $row) {
				foreach (array_keys($row) as $row_key) {
					//if ($row_key == $key) continue;
					if (array_key_exists($row_key, $key)) continue;
					//log_message('error', 'row_key:'.$row_key.', '. $row[$row_key]);
					
					if (count($key) == 1)
						$temp_arr[$row[$key[0]]][$row_key] = $row[$row_key];
					else if (count($key) == 2)
						$temp_arr[$row[$key[0]]][$row[$key[1]]][$row_key] = $row[$row_key];
				}
			}
			$result = $temp_arr;
		}
		//log_message('error', $this->db->last_query());
		return $result;
	}
	
	/*
	 * 선택된 계정의 계정명 구조 가져오기(top level부터 부모 계정까지)
	 */
	function get_max_depth($table, $select='') {
		$this->db->select_max($select);
		$result = $this->db->get($table) -> row_array();
		//log_message('error', $this->db->last_query());
		
		return $result['depth'];
	}
	
	/*
	 * 계층구조로 가져오기
	 * @param	string	table Name
	 * @param	array	3차원 배열의 키값 array(parentkey, key)
	 * 
	 */
	function hierarchical_gets($table, $key, $where_arr=array()) {
		//컬럼 list
		$fields = $this->db->get($table)->list_fields();

		$this->set_query ($where_arr, $select='', $set_arr=array(), $limit=-1, $offset=-1, $orderby='', $groupby='', $join=array());
		
		$this -> db -> order_by('depth asc');
		$this -> db -> order_by('weight asc');
		$all_datas = $this -> db -> get($table) -> result_array();

		$return_val = array();
		foreach ($all_datas as $entry) {
			foreach ($fields as $field){
				//echo 'pano :'.$entry[$field].'<br>';
				if (count($key)==1)
					$return_val[$entry[$key[0]]][$field] = $entry[$field];
				else if (count($key)==2)
					$return_val[$entry[$key[0]]][$entry[$key[1]]][$field] = $entry[$field];
			}
		}

		return $return_val;
	}

	function hierarchical_gets_imsi($table, $key) {
		//컬럼 list
		$fields = $this->db->get($table)->list_fields();

		$this -> db -> order_by('depth asc');
		$this -> db -> order_by('weight asc');
		$all_datas = $this -> db -> get($table) -> result_array();

		$return_val = array();
		foreach ($all_datas as $entry) {
			foreach ($fields as $field){
				//echo 'pano :'.$entry[$field].'<br>';
				$return_val[$entry[$key[0]]][$entry[$key[1]]][$field] = $entry[$field];
			}
		}

		return $return_val;
	}

	/*
	 * 계층구조로 가져오기
	 * @param	array	메뉴를 담은 array
	 * @param	string	table Name
	 * @param	array	조건 $where_arr = array(array('', $field, $value), array('and', $field, $value))
	 * @param	string	가져오고자 하는 컬럼, 컬럼은 3개 이상이어야 한다.
	 * @param	array	결과를 담을 배열
	 * 
	 */
	function hierarchical_left_menu($menu, $key, $table, $where_arr, $select='', $orderby='') {
		//컬럼 list
		$fields = $this->db->get($table)->list_fields();
		//log_message('error', $this->db->last_query());

		$this->set_query ($where_arr, $select, $set_arr=array(), $limit=-1, $offset=-1, $orderby, $groupby='', $join=array());
		$result = $this -> db -> get($table) -> result_array();
		//log_message('error', $this->db->last_query());
		
		//var_dump($result);
		
		foreach ($result as $row) {
			/*
			foreach ($key as $field){
				//echo 'pano :'.$entry[$field].'<br>';
				$menu[$row[$key[0]]][$row[$key[1]]][$field] = $row[$field];
			}
			 */
			foreach ($fields as $field){
				$menu[$row[$key[0]]][$row[$key[1]]][$field] = $row[$field];
			}
			
			if ($row['pmid'] != '0' && $row['has_children'] == '1') {
				//echo 'pmid:'.$row['pmid'].'';
				$where_arr = array(array('', 'pmid', $row['mid']));
				$menu = $this->hierarchical_left_menu($menu, $key, $table, $where_arr, $select, $orderby);
			}
		}
		//var_dump($menu);

		return $menu;
	}



	/**
	 * $table의 모든 값 가져오기
	 *
	 * 조건에 해당하는 result가 없으면 column을 key로, value는 ''로 채워서 return한다.
	 *
	 * @param	string	table Name
	 * @param	array	조건 $where_arr = array(array('', $field, $value), array('and', $field, $value))
	 * @param	string	가져오고자 하는 컬럼
	 * 
	 */
	function get_with_blank($table, $where_arr=array(), $select='') {

		$this->set_query ($where_arr, $select, $set_arr=array(), $limit=-1, $offset=-1, $orderby='');
		
		//컬럼 list
		$fields = $this->db->get($table)->list_fields();
		
		//컬럼 value
		$this->set_query ($where_arr, $select, $set_arr=array(), $limit=-1, $offset=-1, $orderby='');
		$result = $this -> db -> get($table) -> row_array();
		
		//return 값 셋팅
		$option = array();
		
		foreach ($fields as $field){
			if (empty($result[$field]))
				$option[$field] = '';
			else
				$option[$field] = $result[$field];
		}

		return $option;
	}
	
	
	function exist($table, $where_arr=array()){
		$this->set_query ($where_arr, $select='', $set_arr=array(), $limit=-1, $offset=-1, $orderby='');

		$query = $this->db->limit(1)->get($table);
		//log_message('error', $query->num_rows());
		//log_message('error', $query->num_rows() === 0);
		//log_message('error', 1 === 0);
		//return $query->num_rows() === 0;
		return $query->num_rows();
    }

	/*
	 * Data 입력
	 * $set_arr = array(array('name'=> '', 'value'=>'', 'flag'=> [true|false]))
	 */
	function add($table, $option, $set_arr=array()) {
		//echo 'add()';
		//log_message('error', 'add');
		$this->set_query ($where_arr=array(), $select='', $set_arr, $limit=-1, $offset=-1, $orderby='');
		/*
		if (count($set_arr) > 0) {
			//var_dump($set_arr);
			foreach ($set_arr as $set_val) {
				if (!array_key_exists('flag', $set_val) || $set_val['flag'] == '') $set_val['flag'] = false;
				$this->db->set($set_val['name'], $set_val['value'], $set_val['flag']);
			}
		}
		 */
		$this -> db -> insert($table, $option);	//바로 실행
		//log_message('error', $this->db->last_query());
		return $this->db->insert_id();
	}


	/*
	 * Data 수정
	 */
	function update($table, $where_arr, $option, $set_arr=array()) {
		$this->set_query ($where_arr, $select='', $set_arr, $limit=-1, $offset=-1, $orderby='');
		
		/*
		if (count($set_arr)>0) {
			foreach ($set_arr as $key => $value) {
				$this->db->set($key, $value, false);
			}
			//$this -> db -> update($table);
		} else {
			$this -> db -> update($table, $option);
		}
		 */
		$this -> db -> update($table, $option);
		//log_message('error', $this->db->last_query());
	}

	/*
	 * Data 수정
	 */
	function update_batch($table, $option, $where_key) {
		$this->db->update_batch($table, $option, $where_key); 
		//log_message('error', $this->db->last_query());
	}

	/*
	 * 메뉴의 위치 이동, 삭제시 weight 수정
	 */
	function update_weights($table, $where_arr, $weight) {
		$this->db->set('weight', 'weight-1', false);
		$this->db->where('weight >', $weight, false);
		$this->set_query ($where_arr, $select='', $set_arr=array(), $limit=-1, $offset=-1, $orderby='');

		$this -> db -> update($table);
		//log_message('error', $this->db->last_query());
	}
	
	/*
	 * Data가 있으면 수정, 없으면 입력
	 */
	function update_insert($table, $where_arr, $option, $set_arr=array()) {
		$this->set_query ($where_arr, $select='', $set_arr, $limit=-1, $offset=-1, $orderby='');

		$query = $this->db->limit(1)->get($table);
		
		if ($query->num_rows() === 0) {
			//add
			$this -> db -> insert($table, $option);	//바로 실행
		} else {
			//update
			$this->set_query ($where_arr, $select='', $set_arr, $limit=-1, $offset=-1, $orderby='');
			$this -> db -> update($table, $option);
		}
		//log_message('error', $this->db->last_query());
	}


	/*
	 * Data 삭제
	 */
	function delete($table, $where_arr=array()) {
		$this->set_query ($where_arr, $select='', $set_arr=array(), $limit=-1, $offset=-1, $orderby='');
		$this -> db -> delete($table);
	}
	
	/*
	 * 분류 가져오기
	 */
	function get_categorization($gid) {
		$where_arr = array(array('', 'gid', $gid));
		//return $this->gets('categorization', $where_arr, '', 0, 0);
		return $this->gets('categorization', $where_arr, 'title', 0, 0);
	}
	
	/*
	 * Data 입력 
	 */
	function insert_batch($table, $where_arr, $option, $set_arr=array()) {
		$this->set_query ($where_arr, $select='', $set_arr, $limit=-1, $offset=-1, $orderby='');
		$this -> db -> insert_batch($table, $option);	//바로 실행
		//log_message('error', $this->db->last_query());
	}

	/*
	 * Data 가 있으면 수정, 없으면 입력 
	 */
	function update_insert_batch($table, $where_arr, $option, $set_arr=array(), $where_key) {
		$this->set_query ($where_arr, $select='', $set_arr, $limit=-1, $offset=-1, $orderby='');
		
		if ($this->common_model->exist($table, $where_arr)) {
			$this->update_batch($table, $option, $where_key);
		} else {
			$this->insert_batch($table, $where_arr, $option);	//바로 실행
		}
		//log_message('error', $this->db->last_query());
	}

	/*
	 * Data (있으면 삭제후) 입력 
	 */
	function delete_insert_batch($table, $where_arr, $option, $set_arr=array()) {
		$this->delete($table, $where_arr);

		$this->set_query ($where_arr, $select='', $set_arr, $limit=-1, $offset=-1, $orderby='');
		$this -> db -> insert_batch($table, $option);	//바로 실행
		//log_message('error', $this->db->last_query());
	}

	/*
	 * 분류 추가
	 */
	function set_categorization($option) {
		return $this->add('categorization', $option, array());
	}
	

	function load_list_total($table, $method='') {
		/*
		$this->db->select('count(no) as cnt');
		$where = "";
		if ($method) {
			if($method == 'all') {
				//$this->db->like('subject', $post['s_word']);
				//$this->db->or_like('contents', $post['s_word']);
				//$where = "(subject like '%".$post['s_word']."%' or contents like '%".$post['s_word']."%') and ";
			} else {
				//$this->db->like($post['method'], $post['s_word']);
				$where = "";
			}
  		}
		//$where .= "(is_delete='N' and original_no='0')";
  		//$this->db->where($where);

		$query = $this->db->get($this->table);
		$tot_cnt = $query->row();

        //return $query->num_rows();
		//var_dump($query->row());
		return $tot_cnt->cnt;
		 */
		return $this->db->count_all($table);
	}

	/*
	 * 해당 data 의 개수 가져오기
	 */
	function get_count($table, $where_arr=array()) {
		$this->set_query ($where_arr, $select='', $set_arr=array(), $limit=-1, $offset=-1, $orderby='', $groupby='', $join=array());

		$result = $this -> db -> count_all_results($table);
		//log_message('error', $this->db->last_query());
		//return $result['numrows'];
		return $result;
	}
	
	////////////////////////////////////////////////////////////////////////////////////////
	//where 절 setting
	/*
	 * $where_arr = array(array('where_param', $field, $value, $like_param=''), array('and', $field, $value))
	 * @where_param : and|or|in|or_in|not_in|or_not_in|like|or_like|not_like|or_not_like
	 * $like_param : before, after, match
	 * $join : array(array(join_Table, join_on), array(join_Table, join_on), ...)
	 * 	 */
	//protected function set_query ($where_arr, $select='', $set_arr=array(), $limit=-1, $offset=-1, $orderby='', $groupby='', $select_flag=true) {
			
	protected function set_query ($where_arr, $select='', $set_arr=array(), $limit=-1, $offset=-1, $orderby='', $groupby='', $join=array(), $select_flag=true) {
		//echo '$limit:'.$limit.'<br>';
		//echo '$offset:'.$offset.'<br>';
		//if ($select != '') $this->db->select($select, $select_flag);
		if ($select != '') $this->db->select($select, $select_flag);
		if ($orderby != '') $this->db->order_by($orderby);
		if ($groupby != '') $this->db->group_by($groupby);
		
		foreach ($where_arr as $where_str) {
			if ($where_str[0] == '') {
				$this -> db -> where($where_str[1], $where_str[2]);
			}
			else if ($where_str[0] == 'or') {
				//echo '--------------------';
				//var_dump($where_str[2]); 
				$this -> db -> or_where($where_str[1], $where_str[2]);
			}
			else if ($where_str[0] == 'in') {
				//echo '--------------------';
				//var_dump($where_str[2]); 
				$this -> db -> where_in($where_str[1], $where_str[2]);
			}
			else if ($where_str[0] == 'like') {
				//echo '--------------------';
				//var_dump($where_str[2]); 
				if (count($where_str) == 3)
					$this -> db -> like($where_str[1], $where_str[2]);
				else if (count($where_str) == 4)
					$this -> db -> like($where_str[1], $where_str[2], $where_str[3]);
			}
		}
		
		if (count($set_arr) > 0) {
			//var_dump($set_arr);
			foreach ($set_arr as $set_val) {
				if (!array_key_exists('flag', $set_val) || $set_val['flag'] == '') $set_val['flag'] = false;
				$this->db->set($set_val['name'], $set_val['value'], $set_val['flag']);
			}
		}
		
		if ($limit != -1) {
			if ($offset != -1) $this->db->limit($offset, $limit);
			else $this->db->limit($limit);
		} 
		
		if ($join != '' && count($join) > 0) {
			foreach ($join as $join_arr) {
				//log_message('error', $join_arr[0].$join_arr[1].$join_arr[2]);
				$this->db->join($join_arr[0], $join_arr[1], $join_arr[2]);
			}
		}
				
	} 
}
