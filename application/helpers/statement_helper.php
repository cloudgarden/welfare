<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
if ( ! function_exists('cal_balance')){
	/*
	 * 차변, 대변을 이용해 잔액 계산하기
	 */
	function cal_balance($balance_standard, $code, $debit_money, $credit_money){
 
		if (strlen($code)>2) $code = substr($code, 0, 2);
		$debit_balance = $credit_balance = 0;
		if (in_array($code, $balance_standard[0])) $debit_balance = $debit_money-$credit_money;
		else if (in_array($code, $balance_standard[1])) $credit_balance = $credit_money-$debit_money;
		
		return array($debit_balance, $credit_balance);
 	} //end of function
}
 
if ( ! function_exists('settlement_term_info')){
	/*
	 * 결산기 return
	 * $registration_date : 법인설립등기일
	 * $settlement_term : 결산기
	 * return array('결산검색 시작일 목록', '결산검색 종요일 목록');
	 */
	function settlement_term_info($registration_date, $settlement_term){
		//검색기간 시작일과 미김일 list, 법인설립등기일과 결산기를 기준으로 나눈다.
		$start_date_list = array();
		$end_date_list = array();
		
		$settlement_term_start = $settlement_term+1;	//결산 시작월
		if ($settlement_term_start>12) $settlement_term_start = 1;
		
		$registration_Y = date("Y",strtotime($registration_date));	//법인설립등기 년도
		$today_Y = date('Y');	//올해의 년도
		$today_settlement = date('Y-m-t',strtotime($today_Y.'-'.$settlement_term.'-01'));	//올해의 결산 마감일
		
		/*
		//올해의 검색기간
		//법인설립등기일이 올해가 아닌 경우에만
		if ($today_Y>$registration_Y) {
			$start_date_list[] = date('Y-m-01',strtotime(($today_Y-1).'-'.$settlement_term_start.'-01'));
			$end_date_list[] = date('Y-m-t',strtotime($start_date_list[0].' +11 months'));
		}

		for ($i=1; $i<$today_Y-$registration_Y-1; $i++) {
			//log_message('error', $i); 
			$start_date_list[$i] = date('Y-m-01',strtotime($start_date_list[$i-1].' -1 years'));
			$end_date_list[$i] = date('Y-m-t',strtotime($start_date_list[$i].' +11 months'));
		}
		
		//오늘이 올해의 결산마감일이 지났을 경우, 새로운 기수 추가
		if (date('Y-m-d')>$today_settlement) {
			array_unshift($start_date_list, date('Y-m-01',strtotime($today_settlement.' +1 day')));
			array_unshift($end_date_list, date('Y-m-d'));
		} else if ($today_Y>$registration_Y) {
			if ($settlement_term==12) {
				array_unshift($start_date_list, date('Y-m-01',strtotime($today_Y.'-'.$settlement_term_start.'-01')));
				//array_unshift($end_date_list, date('Y-m-d'));
				array_unshift($end_date_list, date('Y-m-t',strtotime(reset($start_date_list).' +11 months')));
			}
		}
		
		//법인설립등기일이 결산월 이전일 경우, 법인설립등기일~ 결산월까지 새로운 기수 추가
		if ($registration_date<date('Y-m-t',strtotime($registration_Y.'-'.$settlement_term.'-01'))) {
			if ($settlement_term!=12) {
				array_push($start_date_list, date('Y-m-01',strtotime($registration_Y.'-'.$settlement_term_start.'-01')));
				array_push($end_date_list, date('Y-m-t',strtotime(end($start_date_list).' +11 months')));
			}

			array_push($start_date_list, $registration_date);
			array_push($end_date_list, date('Y-m-t',strtotime($registration_Y.'-'.$settlement_term.'-01')));
		} else {
			array_push($start_date_list, $registration_date);
			array_push($end_date_list, date('Y-m-t',strtotime(($registration_Y+1).'-'.$settlement_term.'-01')));
		}
		 * 
		 */

		//올해의 결산기 1: 오늘이 올해의 결산마감일이 지났을 경우
		if (date('Y-m-d')>$today_settlement) {
			$start_date_list[] = date('Y-m-01',strtotime($today_settlement.' +1 day'));
			$end_date_list[] = date('Y-m-d');
		}
		
		//올해의 결산기 2: 결산마감일 이전의 기수
		if ($settlement_term==12) {
			$start_date_list[] = date('Y-m-01',strtotime($today_Y.'-'.$settlement_term_start.'-01'));
			$end_date_list[] = date('Y-m-t',strtotime(end($start_date_list).' +11 months'));
		} else {
			$start_date_list[] = date('Y-m-01',strtotime(($today_Y-1).'-'.$settlement_term_start.'-01'));
			$end_date_list[] = date('Y-m-t',strtotime(end($start_date_list).' +11 months'));
		}

		//올해 이전의 결산기(법인설립등기일의 해는 제외)
		for ($i=0; $i<$today_Y-$registration_Y-1; $i++) {
			//log_message('error', $i); 
			$start_date_list[] = date('Y-m-01',strtotime(end($start_date_list).' -1 years'));
			$end_date_list[] = date('Y-m-t',strtotime(end($start_date_list).' +11 months'));
		}
		
		//법인설립등기일이, 결산기 이전일 경우
		if ($registration_date<date('Y-m-t',strtotime($registration_Y.'-'.$settlement_term.'-01'))) {
			$start_date_list[] = $registration_date;
			$end_date_list[] = date('Y-m-t',strtotime($registration_Y.'-'.$settlement_term.'-01'));
		}
		
		//1기의 최초 시작일은 법인설립등기일 보다 우선할 수 없다.
		if ($start_date_list[count($start_date_list)-1] < $registration_date) {
			//$start_date_list[count($start_date_list)-1] = $registration_date;
		}
		
		return array($start_date_list, $end_date_list);
 	} //end of function
}
 
 