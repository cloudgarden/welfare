<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * 2차원 스칼라 배열을 2차원 연관배열로 변환
  * 
  * 스칼라 배열의 0번 index 값을 key로 넘기고 나머지 값들을 value로 만든다.
  * key 값의 충돌이 발생할 경우 덮어쓴다.
  * 
  * array(array('A', 'a', 'aa'), array('B', 'b', 'bb'))
  *  => array('A' => array('a', 'aa'), 'B' => array('b', 'bb'))
  * 
  * @access	public
  * @param	array 2차원 스칼라 배열
  * @param	string overwrite:충돌하는 key는 덮어쓴다, new:key밑으로 새로운 스칼라 배열을 만든다.
  *	@return	int	성공적으로 입력된 변수의 수를 반환한다.
  */
if ( ! function_exists('to_associative')){
	function to_associative(&$var_array, $extract_type='overwrite'){
		$return_arr = array();
		foreach ($var_array as $arr) {
			$key = array_shift($arr);
			if ($extract_type==='overwrite')
				$return_arr[$key] = $arr;
			elseif ($extract_type==='new')
				$return_arr[$key][] = $arr;
		}
		
		return $return_arr;
 	}
}


 /**
  * 2차원 스칼라 배열을 2차원 연관배열로 변환(account_relation table의 값을 3차원 배열로 만들기 위한 함수)
  * 
  * 스칼라 배열의 0번 index 값을 key로 넘기고 나머지 값들을 value로 만든다.
  * 
  * array(array('A', 'a', 'aa'), array('B', 'b', 'bb'), array('C', 'c', 'cc'))
  *  => array('A' => array('a', 'aa'), 'B' => array('b', 'bb'))
  * 
  * @access	public
  * @param	2차원 스칼라 배열
  * @return	int	성공적으로 입력된 변수의 수를 반환한다.
  */

if ( ! function_exists('arr2_to_associative2')){
	function to_associative_ar(&$var_array){
		$return_arr = array();
		foreach ($var_array as $arr) {
			$key = array_shift($arr);
			$return_arr[$key] = $arr;
		}
		
		return $return_arr;
 	}
}

 /*
  * 차변과 대변을 하나의 배열로 합친다.
  * 
  * $a = array('1'=>array('debit_money'=>4, 'title'=>'정기예금'), '2'=>array('debit_money'=>10, ''title'=>선급법인세')); 
  * $b = array('1'=array('credit_money'=>3, 'title'=>'정기예금'), '3'=>array('credit_money'=>11, 'title'=>'소모품비')); 
  *  => $c = array('1'=>array('debit_money'=>7, 'credit_money'=>3, 'title'=>'정기예금'), .....); 
  * 
  * @access	public
  * @param	array 
  * @param	array 
  * @param	array 
  * @param	string	$group_key 가 있으면 결과값을 $debit[$ano][$group_key][col_name] 인 3차원 배열로 return 한다. 
  * @return	array
  */

if ( ! function_exists('array_merge_dc')){
	function array_merge_dc($debit, $credit, $balance_standard){
		foreach ($credit as $ano => $val_arr) {
			if (!array_key_exists($ano, $debit)) {
				$debit[$ano]['debit_money'] = 0;
				$debit[$ano]['code'] = $val_arr['code'];
				$debit[$ano]['pano'] = $val_arr['pano'];
				$debit[$ano]['title_owner'] = $val_arr['title_owner'];
			}
			$debit[$ano]['credit_money'] = $val_arr['credit_money'];
		}

		foreach ($debit as $ano => $val_arr) {
			list($debit[$ano]['debit_balance'], $debit[$ano]['credit_balance']) = cal_balance($balance_standard, $val_arr['code'], $val_arr['debit_money'], $val_arr['credit_money']);
		}

		ksort($debit);
		
		return $debit;
 	}
}

 /*
  * 차변과 대변을 하나의 배열로 합친다.
  * 
  * $a = array('1'=>array('debit_money'=>4, 'title'=>'정기예금'), '2'=>array('debit_money'=>10, ''title'=>선급법인세')); 
  * $b = array('1'=array('credit_money'=>3, 'title'=>'정기예금'), '3'=>array('credit_money'=>11, 'title'=>'소모품비')); 
  *  => $c = array('1'=>array('debit_money'=>7, 'credit_money'=>3, 'title'=>'정기예금'), .....); 
  * 
  * @access	public
  * @param	array 
  * @param	array 
  * @param	array 
  * @param	string	$group_key 가 있으면 결과값을 $debit[$ano][$group_key][col_name] 인 3차원 배열로 return 한다. 
  * @return	array
  */

if ( ! function_exists('array_merge_dc_by_group')){
	function array_merge_dc_by_group($debit, $credit, $balance_standard, $account_group_list){
		//var_dump($credit);
		//합쳐질 배열에 credit의 key가 없으면 추가한다.
		foreach ($credit as $ano => $group_arr) {
			if (!array_key_exists($ano, $debit)) {
				foreach ($account_group_list as $account_group => $temp) {
					$debit[$ano][$account_group]['debit_money'] = 0;
					$debit[$ano][$account_group]['credit_money'] = 0;
					$debit[$ano][$account_group]['code'] = '';
					$debit[$ano][$account_group]['pano'] = 0;
				}
			}
		}
		//var_dump($debit);
				
		//합쳐질 배열의 각 계정에 빠진 회계분류 배열을 추가한다.
		foreach ($debit as $ano => $val_arr) {
			foreach ($account_group_list as $account_group => $temp) {
				if (!array_key_exists($account_group, $val_arr)) {
					$debit[$ano][$account_group]['debit_money'] = 0;
					$debit[$ano][$account_group]['credit_money'] = 0;
					$debit[$ano][$account_group]['code'] = '';
					$debit[$ano][$account_group]['pano'] = 0;
				}
			}
		}
		//var_dump($debit);
		
		foreach ($credit as $ano => $group_arr) {
			foreach ($group_arr as $account_group => $val_arr) {
				$debit[$ano][$account_group]['code'] = $val_arr['code'];
				$debit[$ano][$account_group]['pano'] = $val_arr['pano'];
				//$debit[$ano][$account_group]['debit_money'] = 0;
				$debit[$ano][$account_group]['credit_money'] = $val_arr['credit_money'];
			}
		}
		//var_dump($debit);

		foreach ($debit as $ano => $group_arr) {
			foreach ($group_arr as $account_group => $val_arr) {
				if ($val_arr['code'] == '') {
					list($debit[$ano][$account_group]['debit_balance'], $debit[$ano][$account_group]['credit_balance']) = array(0,0);
				} else {
					list($debit[$ano][$account_group]['debit_balance'], $debit[$ano][$account_group]['credit_balance']) = cal_balance($balance_standard, $val_arr['code'], $val_arr['debit_money'], $val_arr['credit_money']);
				}
				
				//log_message('error', $ano.', '. $account_group);
			}
		}

		ksort($debit);
		
		return $debit;
 	}
}


/*
 * has_children=0인 계정의 차변/대변 합계를 받아서 모든 계정의 차변/대변 합계를 만든 다음에 return
 * $all_account_list : 모든 계정
 * $dc_sum_list : 차변/대변 합계를 가지고 있는 계정
 */

if ( ! function_exists('make_all_account_dc_sum')){
	function make_all_account_dc_sum($pano, &$all_accounts, $acc_arr) {
		if (array_key_exists($pano, $all_accounts)) {
			//log_message('error', 'pano:'.$pano);
			$all_accounts[$pano]['debit_money'] += $acc_arr['debit_money'];
			$all_accounts[$pano]['credit_money'] += $acc_arr['credit_money'];
			$all_accounts[$pano]['debit_balance'] += $acc_arr['debit_balance'];
			$all_accounts[$pano]['credit_balance'] += $acc_arr['credit_balance'];
			
			make_all_account_dc_sum($all_accounts[$pano]['pano'], $all_accounts, $acc_arr);
		}
		return $all_accounts;
	}
}

/*
 * has_children=0인 계정의 차변/대변 합계를 받아서 모든 계정의 차변/대변 합계를 만든 다음에 return
 * $all_account_list : 모든 계정
 * $dc_sum_list : 차변/대변 합계를 가지고 있는 계정
 */

if ( ! function_exists('make_all_account_dc_sum_group')){
	function make_all_account_dc_sum_group($pano, &$all_accounts, $acc_arr, $account_group) {
		//log_message('error', 'make_all_account_dc_sum_group');
		//log_message('error', $account_group);
		if (array_key_exists($pano, $all_accounts)) {
			//foreach ($account_group_list as $account_group => $temp) {
				//log_message('error', 'pano:'.$pano);
				$all_accounts[$pano][$account_group]['debit_money'] += $acc_arr['debit_money'];
				$all_accounts[$pano][$account_group]['credit_money'] += $acc_arr['credit_money'];
				$all_accounts[$pano][$account_group]['debit_balance'] += $acc_arr['debit_balance'];
				$all_accounts[$pano][$account_group]['credit_balance'] += $acc_arr['credit_balance'];
			//}
			
			make_all_account_dc_sum_group($all_accounts[$pano]['pano'], $all_accounts, $acc_arr, $account_group);
		}
		return $all_accounts;
	}
}

/*
 * 손익계산서의 계정에 보고서용 계정 추가하기
 * '사업외수익'을 '사업외비용' 앞으로 옮기기
 * '사업총이익'을 '일반관리비' 앞으로 '추가
 * '사업이익'을 '사업외수익' 앞으로 추가 - '사업외수익'이 비용(ano=10)으로 옮겨갔으므로 pano=10이다
 * '법인세차감전순이익'을 '법인세비용'앞으로 추가
 * '당기순이익'을 맨 마지막(법인세비용 뒤)에 추가
 * 
 * 합계잔액시산표, 손익계산서에서 사용
 * $account_sum_list : 차변/대변 합계를 가지고 있는 계정
 * $prev_account_sum_list : 전기의 차변/대변 합계를 가지고 있는 계정
 * $account_group : 모든 회계분류 정보가 있는 배열
 * $ano : 합계를 하고자하는 계정의 ano
 */
//추가한 계정의 합계 구하기 - 17:사업수익, 18:사업외수익, 19:고유목적사업비용, 20:일반관리비, 21:사업외비용, 22:법인세비용
//법인세차감전순이익 : 사업이익+사업외수익-사업외비용
//당기순이익 : 법인세차감전순이익-법인세비용

if ( ! function_exists('make_sonik_account')){
	function make_sonik_account(&$all_accounts) {

		//'사업외수익'을 '사업외비용' 앞으로 옮기기
		$all_accounts = hierarchical_accounts_resort($all_accounts, 9, 18, 10, 21); 
		//$all_accounts = hierarchical_accounts_resort($all_accounts, 9, 18, 10, 'tax_profit'); 

		//ano - 20:일반관리비, 17:사업수익, 18:사업외수익, 21:사업외비용, 22:법인세비용
		//'사업총이익'을 '일반관리비' 앞으로 '추가
		$src_arr = array('total_business_profit' => array('depth'=>2, 'ano'=>'total_business_profit', 'weight'=>0, 'title_owner'=>'사업총이익'));
		$all_accounts = hierarchical_accounts_add($all_accounts, $src_arr, 10, 20); 
		
		//'사업이익'을 '사업외수익' 앞으로 추가 - '사업외수익'이 비용(ano=10)으로 옮겨갔으므로 pano=10이다
		$src_arr = array('business_profit' => array('depth'=>2, 'ano'=>'business_profit', 'weight'=>0, 'title_owner'=>'사업이익'));
		$all_accounts = hierarchical_accounts_add($all_accounts, $src_arr, 10, 18); 

		//'법인세차감전순이익'을 '법인세비용'앞으로 추가
		$src_arr = array('tax_profit' => array('depth'=>2, 'ano'=>'tax_profit', 'weight'=>0, 'title_owner'=>'법인세차감전순이익'));
		$all_accounts = hierarchical_accounts_add($all_accounts, $src_arr, 10, 22); 
		
		//'당기순이익'을 맨 마지막(법인세비용 뒤)에 추가
		$src_arr = array('this_profit' => array('depth'=>2, 'ano'=>'this_profit', 'weight'=>0, 'title_owner'=>'당기순이익'));
		$all_accounts = hierarchical_accounts_add($all_accounts, $src_arr, 10, 0, 'end');

		return $all_accounts;
	}
}

/*
 * 재무상태표의 계정에 보고서용 계정 추가하기
 * '자산총계'을 '부채' 앞으로 '추가
 * '부채총계'을 '자본' 앞으로 '추가
 * '자본총계'을 맨 마지막에 추가
 * '부채와 자본 총계'을 맨 마지막에 추가
 * 
 * 재무상태표에서 사용
 * $account_sum_list : 차변/대변 합계를 가지고 있는 계정
 * $prev_account_sum_list : 전기의 차변/대변 합계를 가지고 있는 계정
 * $account_group : 모든 회계분류 정보가 있는 배열
 * $ano : 합계를 하고자하는 계정의 ano
 */
//추가한 계정의 합계 구하기 - 7:부채, 8:자본
//법인세차감전순이익 : 사업이익+사업외수익-사업외비용
//당기순이익 : 법인세차감전순이익-법인세비용

if ( ! function_exists('make_jaemusang_account')){
	function make_jaemusang_account(&$all_accounts) {

		//ano - 7:부채, 8:자본
		//'자산총계'을 '부채' 앞으로 '추가
		$src_arr = array('asset_total' => array('depth'=>1, 'ano'=>'asset_total', 'weight'=>0, 'title_owner'=>'[ 자산 ] 총계'));
		$all_accounts = hierarchical_accounts_add($all_accounts, $src_arr, 1, 7); 
		
		//'부채총계'을 '자본' 앞으로 '추가
		$src_arr = array('liabilities_total' => array('depth'=>1, 'ano'=>'liabilities_total', 'weight'=>0, 'title_owner'=>'[ 부채 ] 총계'));
		$all_accounts = hierarchical_accounts_add($all_accounts, $src_arr, 1, 8); 

		
		//'자본총계'을 맨 마지막에 추가
		$src_arr = array('capital_total' => array('depth'=>1, 'ano'=>'capital_total', 'weight'=>0, 'title_owner'=>'[ 자본 ] 총계'));
		$all_accounts = hierarchical_accounts_add($all_accounts, $src_arr, 1, 0, 'end');

		//'부채와 자본 총계'을 맨 마지막에 추가
		$src_arr = array('liabilities_capital_total' => array('depth'=>1, 'ano'=>'liabilities_capital_total', 'weight'=>0, 'title_owner'=>'[ 부채와 자본 ] 총계'));
		$all_accounts = hierarchical_accounts_add($all_accounts, $src_arr, 1, 0, 'end');

		return $all_accounts;
	}
}

/*
 * 손익계산서의 계정에 보고서용 계정 추가하기
 * '사업외수익'을 '사업외비용' 앞으로 옮기기
 * '사업총이익'을 '일반관리비' 앞으로 '추가
 * '사업이익'을 '사업외수익' 앞으로 추가 - '사업외수익'이 비용(ano=10)으로 옮겨갔으므로 pano=10이다
 * '법인세차감전순이익'을 '법인세비용'앞으로 추가
 * '당기순이익'을 맨 마지막(법인세비용 뒤)에 추가
 * 
 * 합계잔액시산표, 손익계산서에서 사용
 * $account_sum_list : 차변/대변 합계를 가지고 있는 계정
 * $prev_account_sum_list : 전기의 차변/대변 합계를 가지고 있는 계정
 * $account_group : 모든 회계분류 정보가 있는 배열
 * $ano : 합계를 하고자하는 계정의 ano
 */
//추가한 계정의 합계 구하기 - 17:사업수익, 18:사업외수익, 19:고유목적사업비용, 20:일반관리비, 21:사업외비용, 22:법인세비용
//법인세차감전순이익 : 사업이익+사업외수익-사업외비용
//당기순이익 : 법인세차감전순이익-법인세비용

if ( ! function_exists('make_sonik_account')){
	function make_sonik_account(&$all_accounts) {

		//'사업외수익'을 '사업외비용' 앞으로 옮기기
		$all_accounts = hierarchical_accounts_resort($all_accounts, 9, 18, 10, 21); 
		//$all_accounts = hierarchical_accounts_resort($all_accounts, 9, 18, 10, 'tax_profit'); 

		//ano - 20:일반관리비, 17:사업수익, 18:사업외수익, 21:사업외비용, 22:법인세비용
		//'사업총이익'을 '일반관리비' 앞으로 '추가
		$src_arr = array('total_business_profit' => array('depth'=>2, 'ano'=>'total_business_profit', 'weight'=>0, 'title_owner'=>'사업총이익'));
		$all_accounts = hierarchical_accounts_add($all_accounts, $src_arr, 10, 20); 
		
		//'사업이익'을 '사업외수익' 앞으로 추가 - '사업외수익'이 비용(ano=10)으로 옮겨갔으므로 pano=10이다
		$src_arr = array('business_profit' => array('depth'=>2, 'ano'=>'business_profit', 'weight'=>0, 'title_owner'=>'사업이익'));
		$all_accounts = hierarchical_accounts_add($all_accounts, $src_arr, 10, 18); 

		//'법인세차감전순이익'을 '법인세비용'앞으로 추가
		$src_arr = array('tax_profit' => array('depth'=>2, 'ano'=>'tax_profit', 'weight'=>0, 'title_owner'=>'법인세차감전순이익'));
		$all_accounts = hierarchical_accounts_add($all_accounts, $src_arr, 10, 22); 
		
		//'당기순이익'을 맨 마지막(법인세비용 뒤)에 추가
		$src_arr = array('this_profit' => array('depth'=>2, 'ano'=>'this_profit', 'weight'=>0, 'title_owner'=>'당기순이익'));
		$all_accounts = hierarchical_accounts_add($all_accounts, $src_arr, 10, 0, 'end');

		return $all_accounts;
	}
}

/*
 * 특정계정(원래 계정에는 없으나 보고서용으로 추가한 계정등)의 합계 구하기
 * 합계잔액시산표에서 사용(손익계산서는 배열키에 회계분류추가,전기금액이 있음. 그 외 동일)
 * $account_sum_list : 차변/대변 합계를 가지고 있는 계정
 * $account_group : 모든 회계분류 정보가 있는 배열
 * $ano : 합계를 하고자하는 계정의 ano
 * 
 * 추가한 계정의 합계 구하기 - 17:사업수익, 18:사업외수익, 19:고유목적사업비용, 20:일반관리비, 21:사업외비용, 22:법인세비용, 
 * total_business_profit:사업충이익, business_profit:사업이익, tax_profit:법인세차감전순이익, this_profit:당기순이익
 * 사업총이익 : 사업수익-사업비용
 * 사업이익 : 사업총이익-일반관리비
 * 법인세차감전순이익 : 사업이익+사업외수익-사업외비용
 * 당기순이익 : 법인세차감전순이익-법인세비용
 */
if ( ! function_exists('make_dc_sum_of_sisan')){
	function make_dc_sum_of_sisan(&$account_sum_list) {
		
		//////////////////////////////////////////////////////////
		//사업총이익 : 사업수익-사업비용
		//$account_sum_list['total_business_profit']['debit_money'] = $account_sum_list[17]['debit_money'] - $account_sum_list[19]['debit_money'];
		$account_sum_list['total_business_profit']['debit_balance'] = $account_sum_list[17]['debit_balance'] - $account_sum_list[19]['debit_balance'];
		//$account_sum_list['total_business_profit']['credit_money'] = $account_sum_list[17]['credit_money'] - $account_sum_list[19]['credit_money'];
		$account_sum_list['total_business_profit']['credit_balance'] = $account_sum_list[17]['credit_balance'] - $account_sum_list[19]['credit_balance'];

		//////////////////////////////////////////////////////////
		//사업이익 : 사업총이익-일반관리비
		//$account_sum_list['business_profit']['debit_money'] = $account_sum_list['total_business_profit']['debit_money'] - $account_sum_list[20]['debit_money'];
		$account_sum_list['business_profit']['debit_balance'] = $account_sum_list['total_business_profit']['debit_balance'] - $account_sum_list[20]['debit_balance'];
		//$account_sum_list['business_profit']['credit_money'] = $account_sum_list['total_business_profit']['credit_money'] - $account_sum_list[20]['credit_money'];
		$account_sum_list['business_profit']['credit_balance'] = $account_sum_list['total_business_profit']['credit_balance'] - $account_sum_list[20]['credit_balance'];

		//////////////////////////////////////////////////////////
		//법인세차감전순이익 : 사업이익+사업외수익-사업외비용
		//$account_sum_list['tax_profit']['debit_money'] = $account_sum_list['business_profit']['debit_money'] + $account_sum_list[18]['debit_money'] - $account_sum_list[21]['debit_balance'];
		$account_sum_list['tax_profit']['debit_balance'] = $account_sum_list['business_profit']['debit_balance'] + $account_sum_list[18]['debit_balance'] - $account_sum_list[21]['debit_balance'];
		//$account_sum_list['tax_profit']['credit_money'] = $account_sum_list['business_profit']['credit_money'] + $account_sum_list[18]['credit_money'] - $account_sum_list[21]['credit_money'];
		$account_sum_list['tax_profit']['credit_balance'] = $account_sum_list['business_profit']['credit_balance'] + $account_sum_list[18]['credit_balance'] - $account_sum_list[21]['credit_balance'];

		//////////////////////////////////////////////////////////
		//당기순이익 : 법인세차감전순이익-법인세비용
		//$account_sum_list['this_profit']['debit_money'] = $account_sum_list['tax_profit']['debit_money'] - $account_sum_list[22]['debit_money'];
		$account_sum_list['this_profit']['debit_balance'] = $account_sum_list['tax_profit']['debit_balance'] - $account_sum_list[22]['debit_balance'];
		//$account_sum_list['this_profit']['credit_money'] = $account_sum_list['tax_profit']['credit_money'] - $account_sum_list[22]['credit_money'];
		$account_sum_list['this_profit']['credit_balance'] = $account_sum_list['tax_profit']['credit_balance'] - $account_sum_list[22]['credit_balance'];
		
		//당기순이익은 잔액을 더해서 _+이면 대변에, -이면 차변에 보여준다. 
		$balance = $account_sum_list['this_profit']['debit_balance'] + $account_sum_list['this_profit']['credit_balance'];
		
		$account_sum_list['this_profit']['debit_money'] = $account_sum_list['this_profit']['debit_balance'] = 0;
		$account_sum_list['this_profit']['credit_money'] = $account_sum_list['this_profit']['credit_balance'] = 0;
		
		if ($balance > 0) {
			$account_sum_list['this_profit']['debit_money'] = $account_sum_list['this_profit']['debit_balance'] = $balance;
		} else {
			$account_sum_list['this_profit']['credit_money'] = $account_sum_list['this_profit']['credit_balance'] = $balance;
		}

		return $account_sum_list;
	}
}


/*
 * 특정계정(원래 계정에는 없으나 보고서용으로 추가한 계정등)의 합계 구하기
 * 재무상태표에서 사용
 * $account_sum_list : 차변/대변 합계를 가지고 있는 계정
 * $account_group : 모든 회계분류 정보가 있는 배열
 * $ano : 합계를 하고자하는 계정의 ano
 * 
 * asset_total:자산 총계, liabilities_total:부채 총계, capital_total:자본 총계, liabilities_capital_total:부채와 자본 총계
 * 자산:6, 부채:7, 자본:8
 */
if ( ! function_exists('make_dc_sum_of_jaemusang')){
	function make_dc_sum_of_jaemusang(&$account_sum_list, $account_group) {
		//////////////////////////////////////////////////////////
		//자산 총계 
		foreach ($account_group as $key => $value) {
			$account_sum_list['asset_total'][$key]['debit_balance'] = $account_sum_list[6][$key]['debit_balance'];
			$account_sum_list['asset_total'][$key]['credit_balance'] = $account_sum_list[6][$key]['credit_balance'];
		}

		//////////////////////////////////////////////////////////
		//부채 총계 
		foreach ($account_group as $key => $value) {
			$account_sum_list['liabilities_total'][$key]['debit_balance'] = $account_sum_list[7][$key]['debit_balance'];
			$account_sum_list['liabilities_total'][$key]['credit_balance'] = $account_sum_list[7][$key]['credit_balance'];
		}

		//////////////////////////////////////////////////////////
		//자본 총계 
		foreach ($account_group as $key => $value) {
			$account_sum_list['capital_total'][$key]['debit_balance'] = $account_sum_list[8][$key]['debit_balance'];
			$account_sum_list['capital_total'][$key]['credit_balance'] = $account_sum_list[8][$key]['credit_balance'];
		}

		//////////////////////////////////////////////////////////
		//부채와 자본 총계
		foreach ($account_group as $key => $value) {
			$account_sum_list['liabilities_capital_total'][$key]['debit_balance'] = $account_sum_list[7][$key]['debit_balance'] + $account_sum_list[8][$key]['debit_balance'];
			$account_sum_list['liabilities_capital_total'][$key]['credit_balance'] = $account_sum_list[7][$key]['credit_balance'] + $account_sum_list[8][$key]['credit_balance'];
		}

		//return array($account_sum_list, $prev_account_sum_list);
		return $account_sum_list;
	}
}


/*
 * 특정계정(원래 계정에는 없으나 보고서용으로 추가한 계정등)의 합계 구하기
 * 손익계산서에서 사용(합계잔액시산표는 배열키에 회계분류없음,전기없음. 그 외 동일)
 * $account_sum_list : 차변/대변 합계를 가지고 있는 계정
 * $account_group : 모든 회계분류 정보가 있는 배열
 * $ano : 합계를 하고자하는 계정의 ano
 * 
 * 추가한 계정의 합계 구하기 - 17:사업수익, 18:사업외수익, 19:고유목적사업비용, 20:일반관리비, 21:사업외비용, 22:법인세비용, 
 * total_business_profit:사업충이익, business_profit:사업이익, tax_profit:법인세차감전순이익, this_profit:당기순이익
 * 사업총이익 : 사업수익-사업비용
 * 사업이익 : 사업총이익-일반관리비
 * 법인세차감전순이익 : 사업이익+사업외수익-사업외비용
 * 당기순이익 : 법인세차감전순이익-법인세비용
 */
if ( ! function_exists('make_dc_sum_of_sonik')){
	function make_dc_sum_of_sonik(&$account_sum_list, $account_group) {
		
		//////////////////////////////////////////////////////////
		//사업총이익 : 사업수익-사업비용
		foreach ($account_group as $key => $value) {
			$account_sum_list['total_business_profit'][$key]['debit_balance'] = $account_sum_list[17][$key]['debit_balance'] - $account_sum_list[19][$key]['debit_balance'];
			$account_sum_list['total_business_profit'][$key]['credit_balance'] = $account_sum_list[17][$key]['credit_balance'] - $account_sum_list[19][$key]['credit_balance'];
		}

		//$prev_account_sum_list['total_business_profit']['debit_balance'] = $prev_account_sum_list[17]['debit_balance'] - $prev_account_sum_list[19]['debit_balance'];

		//$prev_account_sum_list['total_business_profit']['credit_balance'] = $prev_account_sum_list[17]['credit_balance'] - $prev_account_sum_list[19]['credit_balance'];
		
		
		//////////////////////////////////////////////////////////
		//사업이익 : 사업총이익-일반관리비
		foreach ($account_group as $key => $value) {
			$account_sum_list['business_profit'][$key]['debit_balance'] = $account_sum_list['total_business_profit'][$key]['debit_balance'] - $account_sum_list[20][$key]['debit_balance'];
			$account_sum_list['business_profit'][$key]['credit_balance'] = $account_sum_list['total_business_profit'][$key]['credit_balance'] - $account_sum_list[20][$key]['credit_balance'];
		}
		
		//////////////////////////////////////////////////////////
		//법인세차감전순이익 : 사업이익+사업외수익-사업외비용
		foreach ($account_group as $key => $value) {
			$account_sum_list['tax_profit'][$key]['debit_balance'] = $account_sum_list['business_profit'][$key]['debit_balance'] + $account_sum_list[18][$key]['debit_balance'] - $account_sum_list[21][$key]['debit_balance'];
			$account_sum_list['tax_profit'][$key]['credit_balance'] = $account_sum_list['business_profit'][$key]['credit_balance'] + $account_sum_list[18][$key]['credit_balance'] - $account_sum_list[21][$key]['credit_balance'];
		}
		
		//////////////////////////////////////////////////////////
		//당기순이익 : 법인세차감전순이익-법인세비용
		foreach ($account_group as $key => $value) {
			$account_sum_list['this_profit'][$key]['debit_balance'] = $account_sum_list['tax_profit'][$key]['debit_balance'] - $account_sum_list[22][$key]['debit_balance'];
			$account_sum_list['this_profit'][$key]['credit_balance'] = $account_sum_list['tax_profit'][$key]['credit_balance'] - $account_sum_list[22][$key]['credit_balance'];
		}
		
		//return array($account_sum_list, $prev_account_sum_list);
		return $account_sum_list;
	}
}


/*
 * 계층구조로 가져온 계정목록을 사용자가 원하는 순서로 재정렬한다.
 * $all_accounts : 계층구조를 가지고 있는 계정목록,  $all_accounts[pano][ano] = array(); 형식을 가지고 있어야 한다.
 * $src_pano : 위치를 바꾸고자 하는 계정이 속한 부모계정 ID
 * $src_pano : 위치를 바꾸고자 하는 계정의 ID
 * $src_ano : 위치를 바꾸고자 하는 계정ID
 * $trg_ano : 옮겨갈 위치에 있는 계정
 * $trg_offest : -1-$trg_ano의 앞, 1:$trg_ano의 뒤, 현재는 앞만 구현됨
 * 
 */
if ( ! function_exists('hierarchical_accounts_resort')){
	function hierarchical_accounts_resort(&$all_accounts, $src_pano, $src_ano, $trg_pano, $trg_ano, $trg_offest=-1) {
		//위치를 바꾸고자 하는 계정이 속한 부모계정 ID가 $all_accounts에  있어야 한다.
		if (array_key_exists($src_pano, $all_accounts) && array_key_exists($trg_pano, $all_accounts)) {
						
			//$src_ano 과 $trg_ano 이 계정목록에 존재해야한다.
			if (array_key_exists($src_ano, $all_accounts[$src_pano]) && array_key_exists($trg_ano, $all_accounts[$trg_pano])) {
				
				//전체게정에서 $src_ano 추출/저장
				//추출한 값과, 원래 pano에 속한 배열을 각각 저장한다.
				$src_arr = array();			//추출한 src_ano 의 값이 담길 배열
				$src_org_arr = array();	//추출한 src_ano를 제외한 원래 값이 담길 배열
				foreach ($all_accounts[$src_pano] as $ano => $arr) {
					if ($src_ano == $ano) {
						$src_arr[$ano] = $arr;
					} else {
						$src_org_arr[$ano] = $arr;
					}
				}
				$all_accounts[$src_pano] = $src_org_arr;

				//전체게정에서 $trg_ano 추출/저장
				$trg_arr = array();			//추출한 $trg_ano 의 값이 담길 배열
				$trg_weight = 100;
				$trg_pano_arr = array();	//추출한 $trg_ano 부터 그 뒤에 있는 마지막까지 계정까지 모두 담을 배열
				foreach ($all_accounts[$trg_pano] as $ano => $arr) {
					if ($trg_ano == $ano || $arr['weight'] > $trg_weight) {
						$trg_arr[$ano] = $arr;
						$trg_weight = $arr['weight'];	//$trg_ano를 담은 다음에는 그 이상의 weight를 가진 모든 값을 배열에 답는다.
						//그 뒤에 있는거 모두 가져올 것
					} else {
						$trg_pano_arr[$ano] = $arr;
					}
				}
				$all_accounts[$trg_pano] = $trg_pano_arr;

				//$trg_ano 부터 마지막까지 부모배열에 추가
				foreach ($src_arr as $ano => $arr) {
					$all_accounts[$trg_pano][$ano] = $arr;
				}

				foreach ($trg_arr as $ano => $arr) {
					$all_accounts[$trg_pano][$ano] = $arr;
				}
			}
		}

		return $all_accounts;
	}
}
/*
 * 계층구조로 가져온 계정목록을 사용자가 원하는 순서로 재정렬한다.
 * $all_accounts : 계층구조를 가지고 있는 계정목록,  $all_accounts[pano][ano] = array(); 형식을 가지고 있어야 한다.
 * $src_arr : 추가하고자하는 계정 정보
 * $src_ano : 위치를 바꾸고자 하는 계정ID
 * $trg_pano : 옮겨갈 위치에 있는 계정의 부모계정, 'end' : 맨 마지막에 추가
 * $trg_ano : 옮겨갈 위치에 있는 계정, 0:$trg_pano의 앞or뒤에 계정을 추가한다.
 * $trg_offest : -1-$trg_ano의 앞, 1:$trg_ano의 뒤, end:마지막, 현재는 앞과 마지막만 구현됨 
 * 
 */
if ( ! function_exists('hierarchical_accounts_add')){
	function hierarchical_accounts_add(&$all_accounts, $src_arr, $trg_pano, $trg_ano, $trg_offest=-1) {
		//위치를 바꾸고자 하는 계정이 속한 부모계정 ID가 $all_accounts에  있어야 한다.
		if (array_key_exists($trg_pano, $all_accounts)) {
			//$src_ano 과 $trg_ano 이 계정목록에 존재해야한다.
			if ($trg_offest == 'end') {	//마지막에 단순히 추가만 하는 경우
				foreach ($src_arr as $ano => $arr) {
					$all_accounts[$trg_pano][$ano] = $arr;
				}
			} else {	//중간에 추가하는 경우
				//전체게정에서 $trg_ano 추출/저장
				$trg_arr = array();			//추출한 $trg_ano 의 값이 담길 배열
				$trg_weight = 100;
				$trg_pano_arr = array();	//추출한 $trg_ano 부터 그 뒤에 있는 마지막까지 계정까지 모두 담을 배열
				foreach ($all_accounts[$trg_pano] as $ano => $arr) {
					if ($trg_ano == $ano || $arr['weight'] > $trg_weight) {
						$trg_arr[$ano] = $arr;
						$trg_weight = $arr['weight'];	//$trg_ano를 담은 다음에는 그 이상의 weight를 가진 모든 값을 배열에 답는다.
						//그 뒤에 있는거 모두 가져올 것
					} else {
						$trg_pano_arr[$ano] = $arr;	//$trg_ano 이전의 모든 배열을 저장
					}
				}
				$all_accounts[$trg_pano] = $trg_pano_arr;
				foreach ($src_arr as $ano => $arr) {
					$all_accounts[$trg_pano][$ano] = $arr;
				}
				foreach ($trg_arr as $ano => $arr) {
					$all_accounts[$trg_pano][$ano] = $arr;
				}
			}
		}

		return $all_accounts;
	}
}




