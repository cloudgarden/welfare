<?php
class Account_model extends WWF_model {

	function __construct() {
		parent::__construct();
	}

	/**
	 * 계정과목의 묶음계정, 상대계정, 상대묶음계정, 적요 가져오기
	 *
	 * 결과값이 없을때도 빈값으로 채워진 배열이 필요할 때 사용.
	 *
	 * @param	string	계정ID
	 * @param	array	결과를 담을 배열
	 *
	 */
	function get_account_relation($ano, $account_relation) {
		
		$select = 'kind, relation_name, relation_ano';
		$where_arr = array(array('', 'ano', $ano));
		$result = $this->gets(ACCOUNT_RELATION, $where_arr, $select, $orderby='relation_ano');
		
		//echo $this->db->last_query();
		
		foreach ($result as $row) {
			$account_relation[$row['kind']][$row['relation_name']][] = $row['relation_ano'];
		}
		
		$select = 'kind, tag';
		$result = $this->gets(ACCOUNT_SUMMARY, $where_arr, $select, $orderby='tag');
		foreach ($result as $row) {
			$account_relation[$row['kind']]['summary'][] = $row['tag'];
		}

		return $account_relation;
	}


	/*
	 * 선택된 계정의 코드구조 가져오기(top level부터 부모 계정까지)
	 */
	function get_ano_title($table, $where_arr, $select='') {
		$this->set_query ($where_arr, $select, $set_arr=array(), $limit=-1, $offset=-1, '','',FALSE);

		$result = $this -> db -> get($table) -> result_array();
		//echo $this->db->last_query();
		
		$return_arr = array();
		foreach ($result as $row) {
			$return_arr[] = $row['ano'];
		}
		
		return $return_arr;
	}
	
	/*
	 * 선택된 계정의 계정명 구조 가져오기(top level부터 부모 계정까지)
	 */
	function get_breadcrumbs($table, $pano, $select='', $bread=array()) {
		if (!$pano) return array('', '');
		
		$where_arr = array(array('', 'ano', $pano));
		$result = $this->get($table, $where_arr, 'title, code, pano');
		
		$bread = array($result['title'].' > '.$bread[0], $result['code'].' > '.$bread[1]);
		
		if ($result['pano'] != 0)
			$bread=$this->get_breadcrumbs($table, $result['pano'], $select, $bread);
		
		return $bread;
	}
	
	/*
	 * 선택된 계정의 계정명 구조 가져오기(top level부터 부모 계정까지)
	function get_child_accont_num($table, $pano, $select='', $bread=array()) {
		if (!$pano) return array('', '');
		
		$where_arr = array(array('', 'ano', $pano));
		$result = $this->get($table, $where_arr, 'title, code, pano');
		
		$bread = array($result['title'].' > '.$bread[0], $result['code'].' > '.$bread[1]);
		
		if ($result['pano'] != 0)
			$bread=$this->get_breadcrumbs($table, $result['pano'], $select, $bread);
		
		return $bread;
	}
	*/
	
	//////////////////////////////////////////////////////////////////////////////////////
	//JSON에서 사용
	/*
	 * 전표입력에서 선택된 옵션에 따라 계정과목 가져오기(has_children=0 인것만 가져오기)
	 */
	function account_list_by_kind($account_kind, $target) {
		if ($account_kind == 'all' && $target == 'all') {
			$select='ano, title_owner';
			$orderby='title_owner';
			$where_arr = array(array('', 'use', 'Y'), array('', 'has_children', '0'));
			
			$result = $this->gets(ACCOUNT, $where_arr, $select, $orderby);
		} else if ($account_kind != ''){
			$this->db->distinct();
			$select=ACCOUNT_OPTION.'.ano, A.title_owner';
			$orderby='A.title_owner';
			$where_arr = array(array('', ACCOUNT_OPTION.'.use', 'Y'));
			$join = array();
			
			if ($account_kind != 'all') {
				$join[] = array(ACCOUNT.' as A', ACCOUNT_OPTION.'.ano = '.'A.ano', 'left');
				$where_arr[] = array('', ACCOUNT_OPTION.'.kind', $account_kind);
			}
			if (!in_array($target, array('', 'all'))) {
				$join[] = array(ACCOUNT_TARGET.' as B', ACCOUNT_OPTION.'.ano = '.'B.ano', 'left');
				$where_arr[] = array('', 'B.kind', $account_kind);
				$where_arr[] = array('', 'B.target', $target);
			}
	
			$result = $this->gets(ACCOUNT_OPTION, $where_arr, $select, $orderby, -1, -1, '', $join);
			
			//log_message('error', $this->db->last_query());
		}

		return $result;
	}

	/*
	 * 계정과목의 정보 가져오기
	 */
	function account_info($ano) {
		//log_message('error', 'account_info()');
		
		//return value
		$return_arr = array('basic'=>array(), 'option'=>array(), 'relation'=>array(), 'summary'=>array());
		
		///////////////////////////////////////////////////////////
		//ACCOUNT
		$where_arr = array(array('', 'ano', $ano));
		$return_arr['basic'] = $this->get(ACCOUNT, $where_arr);
		//$return_arr = $this->get(ACCOUNT, $where_arr);
		//log_message('error', $this->db->last_query());
		
		///////////////////////////////////////////////////////////
		//계정의 소속코드, 소속계정
		//소속 계정과목, 코드
		$breadcrumbs = $this->get_breadcrumbs(ACCOUNT, $return_arr['basic']['pano'], 'title_owner, code, pano', array('', ''));
		
		$return_arr['basic']['title_breadcrumbs'] = $breadcrumbs[0];
		$return_arr['basic']['code_breadcrumbs'] = $breadcrumbs[1];
		//log_message('error', $this->db->last_query());
				

		///////////////////////////////////////////////////////////
		//ACCOUNT_OPTION 에서 "해당여부, 차대구분, 회계분류"
		$fields = $this->db->get(ACCOUNT_OPTION)->list_fields();
		$result = $this->gets(ACCOUNT_OPTION, $where_arr);
		//log_message('error', $this->db->last_query());
		foreach ($result as $row) {
			foreach ($fields as $field){
				if ($field == 'ano') continue;
				$return_arr['option'][$row['kind']][$field] = $row[$field];
			}
			//log_message('error', $row['relation_name'].$row['relation_ano']);
		}

		///////////////////////////////////////////////////////////
		//ACCOUNT_RELATION 에서 "묶음계정, 상대계정, 상대묶음계정"
		$select = ACCOUNT_RELATION.'.*, A.title_owner';
		//$join = array(ACCOUNT, ACCOUNT_RELATION.'.relation_ano = '.ACCOUNT.'.ano', 'left');
		$join = array(array(ACCOUNT.' as A', ACCOUNT_RELATION.'.relation_ano = '.'A.ano', 'left'));
		
		$where_arr = array(array('', ACCOUNT_RELATION.'.ano', $ano));
		$orderby = ACCOUNT_RELATION.'.relation_ano';
		
		$result = $this->gets(ACCOUNT_RELATION, $where_arr, $select, $orderby, -1, -1, '', $join);
		//log_message('error', $this->db->last_query());

		foreach ($result as $row) {
			$return_arr['relation'][$row['kind']][$row['relation_name']][] = $row['relation_ano'];
			$return_arr['relation'][$row['kind']][$row['relation_name'].'_name'][] = $row['title_owner'];
			//log_message('error', $row['relation_name'].$row['relation_ano']);
		}

		///////////////////////////////////////////////////////////
		//ACCOUNT_SUMMARY 에서 적요
		$where_arr = array(array('', 'ano', $ano));
		$result = $this->gets(ACCOUNT_SUMMARY, $where_arr, 'kind, tag', 'tag');
		//log_message('error', $this->db->last_query());
		
		foreach ($result as $row) {
			$return_arr['summary'][$row['kind']][] = $row['tag'];
		}
		
		///////////////////////////////////////////////////////////
		//ACCOUNT_TARGET 에서 대상
		$where_arr = array(array('', 'ano', $ano));
		$result = $this->gets(ACCOUNT_TARGET, $where_arr, 'kind, target', 'target');
		//log_message('error', $this->db->last_query());
		
		foreach ($result as $row) {
			$return_arr['target'][$row['kind']][] = $row['target'];
		}
		
		return $return_arr;
	}
	
	/*
	 * 계정과목의 분류를 선택했을 때 선택된  가져오기
	 */
	function account_sub_info($ano, $kind) {
		//log_message('error', 'account_sub_info()');
		
		//return value
		$return_arr = array('use'=>'', 'dc'=>'', 'group'=>'', 'bundle'=>array(), 'contra'=>array(), 'contra_bundle'=>array(), 'summary'=>array());

		///////////////////////////////////////////////////////////
		//ACCOUNT_OPTION 에서 "해당여부, 차대구분, 회계분류"
		$select = 'use, dc, group';
		$where_arr = array(array('', 'ano', $ano), array('', 'kind', $kind));
		$row = $this->get(ACCOUNT_OPTION, $where_arr, $select);
		$return_arr['use'] = $row['use'];
		$return_arr['dc'] = $row['dc'];
		$return_arr['group'] = $row['group'];
		//log_message('error', $row['dc']);
		


		///////////////////////////////////////////////////////////
		//ACCOUNT_RELATION 에서 "묶음계정, 상대계정, 상대묶음계정"

		$select = ACCOUNT_RELATION.'.relation_name, '.ACCOUNT_RELATION.'.relation_ano, '.ACCOUNT.'.title_owner';
		$join = array(array(ACCOUNT, ACCOUNT_RELATION.'.relation_ano = '.ACCOUNT.'.ano', 'left'));
		
		$where_arr = array(array('', ACCOUNT_RELATION.'.ano', $ano), array('', ACCOUNT_RELATION.'.kind', $kind));
		$orderby = ACCOUNT.'.title_owner';
		
		//$result = $this->gets(ACCOUNT_RELATION, $where_arr, $select, $orderby);
		$result = $this->gets(ACCOUNT_RELATION, $where_arr, $select, $orderby, -1, -1, '', $join);
		//log_message('error', $this->db->last_query());
		
		foreach ($result as $row) {
			$return_arr[$row['relation_name']][] = $row['relation_ano'];
			$return_arr[$row['relation_name'].'_name'][] = $row['title_owner'];
			//log_message('error', $row['relation_name'].$row['relation_ano']);
		}

		///////////////////////////////////////////////////////////
		//ACCOUNT_SUMMARY 에서 적요
		$where_arr = array(array('', 'ano', $ano), array('', 'kind', $kind));
		$result = $this->gets(ACCOUNT_SUMMARY, $where_arr, 'tag', 'tag');
		//log_message('error', $this->db->last_query());
		
		foreach ($result as $row) {
			$return_arr['summary'][] = $row['tag'];
		}
		
		return $return_arr;
	}
	

	/*
	 * 모든 계정의 weight 조정
	 * 조정 기준 : 부모가 같을 때 부모인 곙먼저, 그 다음 코드순
	 */
	function update_all_weights($table, $pano) {
		$where_arr = array(array('', 'pano', $pano));
		$orderby = 'has_children desc, code asc';
		
		$result = $this->gets($table, $where_arr,'' , $orderby);
		
		//foreach ($result as $row) {
		for ($i=0; $i<count($result); $i++) {
			$this->db->set('weight', $i+1);
			$this->db->where('ano', $result[$i]['ano']);
			$this -> db -> update($table);
			//log_message('error', $result[$i]['title_owner'].':'.$this->db->last_query());
			
			if ($result[$i]['has_children'] == '1') {
				$this->update_all_weights($table, $result[$i]['ano']);
			}
		}
	}
	
	

}
