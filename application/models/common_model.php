<?php
class Common_model extends WWF_model {

	function __construct() {
		parent::__construct();
	}

	/*
	 * 계정이름에 해당하는 ano 가져오기
	 */
	function get_ano_by_title_owner($title_owner) {
		$where_arr = array(array('', 'title_owner', $title_owner), array('', 'use', 'Y'));
		$row = $this->common_model->get(ACCOUNT, $where_arr, 'ano');
		return $row['ano'];
	}

	/*
	 * 계정이름에 해당하는 ano 가져오기
	 */
	function get_title_owner_by_ano($ano) {
		$where_arr = array(array('', 'ano', $ano), array('', 'use', 'Y'));
		$row = $this->common_model->get(ACCOUNT, $where_arr, 'title_owner');
		return $row['title_owner'];
	}

	/*
	 * 결산에 필요한 정보 가져오기
	 */
	function get_settlement_info() {
		
		$select='registration_date, settlement_term';
		
		$row = $this->get(BASIC_FUND_INFO, array(), $select);
		
		//log_message('error', $this->db->last_query());
		//log_message('error', var_dump($result));

		return array($row['registration_date'], $row['settlement_term']);
	}
	
	/*
	 * 당기순이익 가져오기(기초자료입력에서 가져옴)
	 * $stage : 기수
	 * $end_date : 검색기간
	 * $where_arr : 검색기간 where 절
	 */
	function get_this_profit($stage) {
		//log_message('error', '$this_profit : '.$this_profit);
		//손익계산서의 당기순이익 - 수익의 합계
		$where_arr = array(array('', BASIC_DATA.'.stage', $stage), array('', BASIC_DATA.'.data_name', 'sonik'));
		$where_arr[] = array('', 'A.account_no', 'this_profit');	//당기순이익 계정
		
		//수익 차변 합계금액
		$select='A.total_money';
		$join = array(
			array(BASIC_DATA_LIST.' as A', BASIC_DATA.'.bno= '.'A.bno', 'left')
		);
		
		//$orderby='B.code ASC';
		$row = $this->get(BASIC_DATA, $where_arr, $select, $orderby='', '', $join, array(), false);
		
		if ($row)
			$this_profit = $row['total_money'];
		else
			$this_profit = '';
		//var_dump($debit);
		//log_message('error', $this->db->last_query());
		//log_message('error', '$this_profit : '.$this_profit);
		
		return $this_profit;
	}

}
