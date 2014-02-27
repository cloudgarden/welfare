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
			else if ($extract_type==='new')
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

 /**
  * 두개의 배열을 합치면서 키값이 같으면 합산
  * 두 배열($a, $b)의 element의는 반드시 연관배열이여야 하고, 그 연관배열의 크기는 같아야 한다.
  * 
  * $a = array('1'=>array('total'=>4, 'title'=>'정기예금'), '2'=>array('total'=>10, ''title'=>선급법인세')); 
  * $b = array('1'=array('total'=>3, 'title'=>'정기예금'), '3'=>array('total'=>11, 'title'=>'소모품비'), '4'=>array('total'=>20, 'title'=>'기본재산')); 
  *  => $c = array('1'=>array('total'=>7, 'title'=>'정기예금'), '2'=>array('total'=>10, 'title'=>'선급법인세'), '3'=>array('total'=>11, 'title'=>'소모품비'), '4'=>array('total'=>20, 'title'=>'기본재산')); 
  * 
  * @access	public
  * @param	array 
  * @param	array 
  * @param	array sum을 할 배열의 key
  * 
  * @return	array

 if ( ! function_exists('array_merge_sum')){
	function array_merge_sum($target, $src, $sum_key){
		foreach ($target as $key => $val_arr) {
			if (array_key_exists($key, $src)) {
				$src[$key][$sum_key] = $target[$key][$sum_key];
			} else {
				//array_push($src, array($key => $val_arr));
				$src[$key] = $val_arr;
			}
		}

		ksort($src);
		
		return $src;
 	}
}
  */

 /**
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

 /**
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
 * 계층구조로 가져온 계정목록을 사용자가 원하는 순서로 재정렬한다.
 * $all_accounts : 계층구조를 가지고 있는 계정목록,  $all_accounts[pano][ano] = array(); 형식을 가지고 있어야 한다.
 * $pano : 위치를 바꾸고자 하는 계정이 속한 부모계정 ID
 * $src_ano : 위치를 바꾸고자 하는 계정ID
 * $trg_ano : 옮겨갈 위치에 있는 계정
 * $offest : -1-$trg_ano의 앞, 1:$trg_ano의 뒤
 * 
 */

 if ( ! function_exists('hierarchical_accounts_resort')){
	function hierarchical_accounts_resort(&$all_accounts, $src_pano, $src_ano, $trg_pano, $trg_ano, $offest=-1) {
		//위치를 바꾸고자 하는 계정이 속한 부모계정 ID가 $all_accounts에  있어야 한다.
		if (array_key_exists($src_pano, $all_accounts)) {
						
			//$src_ano 과 $trg_ano 이 계정목록에 존재해야한다.
			if (array_key_exists($src_ano, $all_accounts[$src_pano]) && array_key_exists($trg_ano, $all_accounts[$trg_pano])) {
				$temp_arr = $all_accounts[$src_pano];
				$src_arr = array();
				$trg_arr = array();
				$src_idx = $trg_idx = 0;	
					
				for ($i=0; $i<count($all_accounts[$src_pano]); $i++) {
					if (array_key_exists($src_ano, $all_accounts[$src_pano])) {
						$src_idx = $i;
						$src_arr = $all_accounts[$src_pano][$i];
					}
					if (array_key_exists($trg_ano, $all_accounts[$src_pano])) {
						$trg_idx = $i;
						array_splice($temp_arr, 0, $trg_idx);
						$trg_arr = $temp_arr;
						//$trg_arr = $all_accounts[$src_pano][$i];
						//그 뒤에 있는거 모두 가져올 것
					}
				}
				//$src_ano 추출
				array_splice($all_accounts[$src_pano], $src_idx, 1);
				
				//$trg_ano 부터 마지막까지 추출
				array_splice($all_accounts[$src_pano], $trg_idx);
				
				//$src_ano를 부모배열에 추가
				array_push($all_accounts[$src_pano], $src_arr);
				array_push($all_accounts[$src_pano], $trg_arr);
				//$trg_ano 부터 마지막까지 부모배열에 추가
								
			}
		}

		if (array_key_exists($trg_ano, $all_accounts)) {
			//$src_ano 과 $trg_ano 이 계정목록에 존재해야한다.
			if (array_key_exists($src_ano, $all_accounts[$trg_ano]) && array_key_exists($trg_ano, $all_accounts[$trg_ano])) {
				$temp_arr = $all_accounts[$trg_ano];
				$src_arr = array();
				$trg_arr = array();
				$src_idx = $trg_idx = 0;		
				for ($i=0; $i<count($all_accounts[$trg_ano]); $i++) {
					if (array_key_exists($src_ano, $all_accounts[$trg_ano])) {
						$src_idx = $i;
						$src_arr = $all_accounts[$trg_ano][$i];
					}
					if (array_key_exists($trg_ano, $all_accounts[$trg_ano])) {
						$trg_idx = $i;
						array_splice($temp_arr, 0, $trg_idx);
						$trg_arr = $temp_arr;
						//$trg_arr = $all_accounts[$trg_ano][$i];
						//그 뒤에 있는거 모두 가져올 것
					}
				}
				//$src_ano 추출
				array_splice($all_accounts[$trg_ano], $src_idx, 1);
				
				//$trg_ano 부터 마지막까지 추출
				array_splice($all_accounts[$trg_ano], $trg_idx);
				
				//$src_ano를 부모배열에 추가
				array_push($all_accounts[$trg_ano], $src_arr);
				array_push($all_accounts[$trg_ano], $trg_arr);
				//$trg_ano 부터 마지막까지 부모배열에 추가
								
			}
		}

		return $all_accounts;
	}
}


