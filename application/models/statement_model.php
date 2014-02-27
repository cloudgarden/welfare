<?php
class Statement_model extends WWF_model {

	function __construct() {
		parent::__construct();
	}

	/*
	 * 입력된 전표 중 회계분류에 따라 계정과목 가져오기. 중복된 계정은 제거. 주로 보고서에서 사용
	 */
	function account_list_in_statement($account_group) {

		//return 할 값
		$return_arr = array();
		
		//공통 쿼리문
		$this->db->distinct();
		$select = STATEMENT_LIST.'.account_no, B.title_owner';
		$orderby='B.title_owner';
		$where_arr = array();
		if (!in_array($account_group, array('', 'all')))
			$where_arr[] = array('', 'A.account_group', $account_group);

		$join = array(
			array(STATEMENT.' as A', STATEMENT_LIST.'.sno= '.'A.sno', 'left')
			,array(ACCOUNT.' as B', STATEMENT_LIST.'.account_no= '.'B.ano', 'left')
		);
		$result = $this->gets(STATEMENT_LIST, $where_arr, $select, $orderby, -1, -1, '', $join, array('account_no'));
		
		//log_message('error', $this->db->last_query());

		//계정명수능로 정렬
		//arsort($result);
		
		return $result;

	}
	

	/*
	 * 전표입력에서 선택된 옵션에 따라 계정과목 가져오기
	 */
	function get_list($account_kind, $select='') {
		
		$select=STATEMENT.'.*, '.ACCOUNT.'.title_owner';
		$orderby=STATEMENT.'.sno';
		$where_arr = array(array('', ACCOUNT_OPTION.'.kind', $account_kind), array('', ACCOUNT_OPTION.'.use', 'Y'));
		
		$this->db->join(ACCOUNT, ACCOUNT_OPTION.'.ano = '.ACCOUNT.'.ano');
		
		$result = $this->gets(ACCOUNT_OPTION, $where_arr, $select, $orderby);
		
		//log_message('error', $this->db->last_query());
		//log_message('error', var_dump($result));

		return $result;
	}

	/*
	 * 각 계정의 차변과 대변의 합을 가져온다.
	 * $account_type : ''-금액이 있는 계정만, 'all':depth>1인 모든 계정
	 * $sort_key : 보여줄 정렬 기준, 가져온 결과의 카값으로 사용해서 결과를 합칠때 key순으로 정렬해서 보여준다.
	 * $account_no=계정ID - 계정보조부, return array('account_no' => array(input_date, debit, title_owner, credit), .......)
	 * $account_type : ''-금액이 있는 계정만, 'all'-금액이 있든 없든 모든 계정(***** 사실 금액이 없는 계정은 보여줄때 index를 체크해서 0으로 보여줘도 된다.)
	 * $top_code : 특정 부모밑에 있는 계정들만 가져올때 사용
	 * $account_group : true 이면 gets()의 파라미터중 $key에 포함시키고, groupby에 추가시킨다. - 재무상태표와 손익계산서에서 사용
	 */
	function get_statement_dc_sum($where_arr, $sort_key=array(), $account_type='', $top_code='', $account_group=FALSE) {
		//////////////////////////////////////////////////////
		//library, helper load
		$this->load->helper('statement');
		$this->load->helper('wwf_array');
 		$balance_standard = $this->config->item('balance');//잔액 계산을 위한 기준
 		$account_group_list = $this->config->item('account_group');//회계분류 목록
 		 		
 		if ($sort_key == '') $sort_key = array('account_no');
		if ($account_group) $sort_key[] = 'account_group';
 		
		if ($top_code != '') $where_arr[] = array('', 'left(B.code, 1)=', $top_code);
		
		//차변 합계금액
		$select=STATEMENT_LIST.'.account_no, sum('.STATEMENT_LIST.'.money) as debit_money, 0 as credit_money, A.account_group, B.code, B.pano, B.title_owner';
		$join = array(
			array(STATEMENT.' as A', STATEMENT_LIST.'.sno= '.'A.sno', 'left')
			,array(ACCOUNT.' as B', STATEMENT_LIST.'.account_no= '.'B.ano', 'left')
		);
		$groupby = STATEMENT_LIST.'.account_no';
		if ($account_group) $groupby .= ', A.account_group';
		
		//$orderby='B.code ASC';
		array_push($where_arr, array('like', STATEMENT_LIST.'.dc', 'debit', 'after'));
		$debit = $this->gets(STATEMENT_LIST, $where_arr, $select, $orderby='', -1, -1, $groupby, $join, $sort_key, false);
		//var_dump($debit);
		//log_message('error', $this->db->last_query());
		
		//대변 합계금액
		$select=STATEMENT_LIST.'.account_no, sum('.STATEMENT_LIST.'.money) as credit_money, A.account_group, B.code, B.pano, B.title_owner';
		array_splice($where_arr, -1);
		array_push($where_arr, array('like', STATEMENT_LIST.'.dc', 'credit', 'after'));
		$credit = $this->gets(STATEMENT_LIST, $where_arr, $select, $orderby='', -1, -1, $groupby, $join, $sort_key);
		
		//log_message('error', $this->db->last_query());

		//각각의 차변/대변 금액을 하나의 배열로 합친다.
		if (!$account_group)
			$sum_list = array_merge_dc($debit, $credit, $balance_standard);
		else 
			$sum_list = array_merge_dc_by_group($debit, $credit, $balance_standard, $account_group_list);
		//var_dump($sum_list);
		
		//마지막 계정의 금액을, 상위계정이 넘겨받아 상위 계정의 합계를 만든다.
		if ($account_type == 'all') {
			//합계잔액시산표에서는 depth=2 부터 사용
			$where_arr = array(array('', 'use', 'Y'));
			//$where_arr = array();	//사용안하는 계정도 모두 가져와야 된다.
			if ($top_code != '') $where_arr[] = array('', 'left(code, 1)=', $top_code);
			
			$all_accounts = $this->gets(ACCOUNT, $where_arr, '', '', -1, -1, '', array(), array('ano'));
			
			if (!$account_group) {
				//log_message('error', '합계잔액시산표');
				//출력에 필요한 변수 추가 셋팅
				foreach ($all_accounts as $ano => $acc_arr) {
					//$all_accounts[$ano]['account_group'] = '';	//회계분류
					$all_accounts[$ano]['debit_money'] = 0;	//차변금액
					$all_accounts[$ano]['credit_money'] = 0;	//대변금액
					$all_accounts[$ano]['debit_balance'] = 0;	//차변잔액
					$all_accounts[$ano]['credit_balance'] = 0;	//대변잔액
				}
				
				//var_dump($all_accounts);
				//$this->make_all_account_dc_sum($sum_list, $all_accounts, 0);
				//차변/대변의 금액을 부모계정에 합산시킨다.
				foreach ($sum_list as $ano => $acc_arr) {
					//log_message('error', $ano.'------------------------------------');
					$all_accounts[$ano]['debit_money'] = $acc_arr['debit_money'];
					$all_accounts[$ano]['credit_money'] = $acc_arr['credit_money'];
					$all_accounts[$ano]['debit_balance'] = $acc_arr['debit_balance'];
					$all_accounts[$ano]['credit_balance'] = $acc_arr['credit_balance'];
					
					$all_accounts = make_all_account_dc_sum($acc_arr['pano'], $all_accounts, $acc_arr);
				}
			} else {
				//log_message('error', '재무상태표');
				//출력에 필요한 변수 추가 셋팅
				foreach ($all_accounts as $ano => $acc_arr) {
					foreach ($account_group_list as $account_group => $temp) {
						//log_message('error', $account_group);
						//$all_accounts[$ano]['account_group'] = '';	//회계분류
						$all_accounts[$ano][$account_group]['debit_money'] = 0;	//차변금액
						$all_accounts[$ano][$account_group]['credit_money'] = 0;	//대변금액
						$all_accounts[$ano][$account_group]['debit_balance'] = 0;	//차변잔액
						$all_accounts[$ano][$account_group]['credit_balance'] = 0;	//대변잔액
					}
				}
				//var_dump($all_accounts);
				
				//차변/대변의 금액을 부모계정에 합산시킨다.
				foreach ($sum_list as $ano => $group_arr) {
					foreach ($group_arr as $account_group => $acc_arr) {
						//log_message('error', $ano.'------------------------------------');
						//log_message('error', 'pano : '.$acc_arr['pano'].'------------------------------------');
						//log_message('error', $account_group);
						$all_accounts[$ano][$account_group]['debit_money'] = $acc_arr['debit_money'];
						$all_accounts[$ano][$account_group]['credit_money'] = $acc_arr['credit_money'];
						$all_accounts[$ano][$account_group]['debit_balance'] = $acc_arr['debit_balance'];
						$all_accounts[$ano][$account_group]['credit_balance'] = $acc_arr['credit_balance'];
						
						$all_accounts = make_all_account_dc_sum_group($acc_arr['pano'], $all_accounts, $acc_arr, $account_group);
					}
				}
				//var_dump($all_accounts);
				
			}
			
			$sum_list = $all_accounts;
		}
		
		return $sum_list;
	}

	/*
	 * 전표삭제
	 * $sno : 전표번호
	 */
	 
	//function get_this_profit($start_date, $end_date) {
	function delete_statement($sno) {
		$where_arr = array(array('', 'sno', $sno));
		$this->delete(STATEMENT, $where_arr);
		$this->delete(STATEMENT_LIST, $where_arr);
		$this->delete(STATEMENT_PAYMENT, $where_arr);
	}

	/*
	 * 당기순이익 가져오기(기초자료입력에서 가져옴)
	 * $start_date : 검색기간
	 * $end_date : 검색기간
	 * $where_arr : 검색기간 where 절
	function get_this_profit($where_arr) {
		$this_profit = 0;
		//손익계산서 - 수익의 합계
		$where_arr[] = array('', 'A.account_no', 'this_profit');	//당기순이익 계정
		
		//수익 차변 합계금액
		$select='sum('.STATEMENT_LIST.'.money) as debit_money, 0 as credit_money';
		$join = array(
			array(BASIC_DATA_LIST.' as A', BASIC_DATA.'.bno= '.'A.bno', 'left')
		);
		
		//$orderby='B.code ASC';
		$debit = $this->get(BASIC_DATA, $where_arr, $select, $orderby='', '', $join, array(), false);
		
		$this_profit = $debit['debit_money'];
		//var_dump($debit);
		log_message('error', $this->db->last_query());
		log_message('error', '$this_profit : '.$this_profit);
		
		//수익 대변 합계금액
		$select='sum('.STATEMENT_LIST.'.money) as credit_money, 0 as debit_money';
		array_splice($where_arr, -2);
		
		$where_arr[] = array('', 'left(B.code, 2)=', $top_code);
		$where_arr[] = array('like', STATEMENT_LIST.'.dc', 'credit', 'after');
		$credit = $this->get(STATEMENT_LIST, $where_arr, $select, $orderby='', '', $join, array(), false);
		$this_profit += $credit['credit_money'];
		log_message('error', $this->db->last_query());
		log_message('error', 'credit_money : '.$this_profit);
				
	}
	 */

}
