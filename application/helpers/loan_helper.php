<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
if ( ! function_exists('lon_schedule')){
    function lon_schedule($s_info){
		//총이자
		$s_info['total_rate'] = 0;

		////////////////////////////////////////////////////////////////////////////////////
		//상환 스케쥴
		$s_info['schedule'] = array();

		////////////////////////////////////////////////////////////////////////////////////
		//총 입금일 리스트(입금일은 무조건 급여공제기준일)
		//대부게시일이 급여공제기준일 전이거나 같을경우, 입금일은 다음달 급여공제기준일
		if (date("d",strtotime($s_info['loan_start'])) <= $s_info['pay_date'])
			$s_info['schedule'][]['deposit_date'] = date("Y-m-d",strtotime($s_info['pay_date'].' +1 month'));
		//대부게시일이 급여공제기준일 후일경우, 입금일은 다다음달 급여공제기준일
		else
			$s_info['schedule'][]['deposit_date'] = date("Y-m-d",strtotime($s_info['pay_date'].' +2 month'));
		
		//입금일 List
		for ($i=1; $i<($s_info['unredeemed_month']+$s_info['repayment_month']); $i++) {
			$last_schedule = end($s_info['schedule']);
			$s_info['schedule'][]['deposit_date'] = date("Y-m-d",strtotime($last_schedule['deposit_date'].' +1 month'));
		}
		
		
		////////////////////////////////////////////////////////////////////////////////////
		//첫달의 일수
		$first_days = intval((strtotime($s_info['schedule'][0]['deposit_date'])-strtotime($s_info['loan_start']))/86400);
		
		////////////////////////////////////////////////////////////////////////////////////
		//거치기간동안의 이자계산, 거치기간동안은 이자만 내므로 모든 방식이 '원금 균등상환'만 따른다.
		$unredeemed_rate=0;
		//$s_info['schedule'][0]['repayment_money'] = 0;
		for ($i=0; $i<$s_info['unredeemed_month']; $i++) {
			$s_info['schedule'][$i]['principal'] = 0;	//상환 원금
			$s_info['schedule'][$i]['balance'] = $s_info['loan_money'];	//남은 원금
			
			$month_days = 30;	//하루의 일수

			//상환 첫달
			if ($i==0) {
				//첫달은 대부날짜가 일정하지 않으므로 무조건 날짜로 계산
				$s_info['schedule'][$i]['interest'] =  ($s_info['loan_money']*$s_info['year_rate']/100/365 * $first_days);
			} else {
				$s_info['schedule'][$i]['interest'] = (($s_info['loan_money']*$s_info['year_rate']/100/365) * date('t', strtotime($s_info['schedule'][$i-1]['deposit_date'])));	//이자(전월의 일자로 계산)
				//$s_info['schedule'][$i]['interest'] =  $s_info['loan_money']*$s_info['year_rate']/100/12;	//이자(한달을 무조건 30일로 계산)
			}
			
			$s_info['schedule'][$i]['payed_principal'] = 0;	//납입원금 합계
			$s_info['schedule'][$i]['repayment_money'] = $s_info['schedule'][$i]['interest'];	//납부금 합계
			
			$s_info['total_rate'] +=  $s_info['schedule'][$i]['interest'];	//총이자
		}

		//echo count($s_info['schedule']).', '. $s_info['repayment_month'].'|';
		
		////////////////////////////////////////////////////////////////////////////////////
		//상환기간동안의 원금과 이자
		for ($i=$s_info['unredeemed_month']; $i<($s_info['unredeemed_month']+$s_info['repayment_month']); $i++) {
			//echo $i.', '. $s_info['unredeemed_month'].'|';
	
			//원금 균등상환 방식 - 상환원금이 항상 동일
			if ($s_info['repayment_method'] == 'P') {
				$s_info['schedule'][$i]['principal'] = ($s_info['loan_money'] / $s_info['repayment_month']);	//상환 원금
				
				//첫달인 경우
				if ($i==$s_info['unredeemed_month']) {
					$s_info['schedule'][$i]['balance'] = $s_info['loan_money']-$s_info['schedule'][$i]['principal'];	//남은 원금
					$s_info['schedule'][$i]['payed_principal'] = $s_info['schedule'][$i]['principal'];	//납입원금 합계
					
					$s_info['schedule'][$i]['interest'] = (($s_info['loan_money']*$s_info['year_rate']/100/365) * $first_days);	
				} else {
					$s_info['schedule'][$i]['balance'] = $s_info['schedule'][$i-1]['balance']-$s_info['schedule'][$i]['principal'];	//남은 원금
					$s_info['schedule'][$i]['interest'] = (($s_info['loan_money']*$s_info['year_rate']/100/365) * date('t', strtotime($s_info['schedule'][$i-1]['deposit_date'])));	//이자(전월의 일자로 계산)
					//$s_info['schedule'][$i]['interest'] = $s_info['loan_money']*$s_info['year_rate']/100/12;	//이자(한달을 무조건 30일로 계산)
					$s_info['schedule'][$i]['payed_principal'] = $s_info['schedule'][$i-1]['payed_principal']+$s_info['schedule'][$i]['principal'];	//납입원금 합계
				}
							
				$s_info['schedule'][$i]['repayment_money'] = $s_info['schedule'][$i]['principal']+$s_info['schedule'][$i]['interest'];	//납부금 합계
				
			}
			//원리금 균등상환 방식 - 상환금액이 항상 동일(이자 포함)
			//잔여정년 균등분할 방식
			//대출이자, 원리금 균등상환은 이자를 날짜별로 계산하지 않는다.
			else if ($s_info['repayment_method'] == 'F' || $s_info['repayment_method'] == 'R') {
				//매월할부금:{대출원금*월금리(1+월금리)^계약개월수}/{(1+월금리)^계약개월수-1}
				$temp_number = pow((1+$s_info['year_rate']/100/12), $s_info['repayment_month']);
				$repayment_money = $s_info['loan_money']*$s_info['year_rate']/100/12*$temp_number / ($temp_number-1);

				$s_info['schedule'][$i]['repayment_money'] = $repayment_money;	//납부금 합계
				
				//첫달인 경우
				if ($i==$s_info['unredeemed_month']) {
					//할부상환 1회의 할부이자는 대출원금*이자율/12, 원미만 절사
					$s_info['schedule'][$i]['interest'] = ($s_info['loan_money']*$s_info['year_rate']/100 / 12);
					$s_info['schedule'][$i]['principal'] = $repayment_money - $s_info['schedule'][$i]['interest'];	//원금
					$s_info['schedule'][$i]['balance'] = $s_info['loan_money']-$s_info['schedule'][$i]['principal'];	//남은 원금
					$s_info['schedule'][$i]['payed_principal'] = $s_info['schedule'][$i]['principal'];	//납입원금 합계
				
					//첫번째 납부때 이자는 첫번째 납부일에서 1달의 기간을 제외한 날의 이자를 더한다.
					$first_days_rate = $s_info['loan_money']*$s_info['year_rate']/100 * ($first_days-30) / 365;	//첫달의 대출이자(전월의 일자로 계산)
					$s_info['schedule'][$i]['repayment_money'] += $first_days_rate;
					$s_info['schedule'][$i]['interest'] += $first_days_rate;
				} else {
					$s_info['schedule'][$i]['interest'] = ($s_info['schedule'][$i-1]['balance']*$s_info['year_rate'] /100 / 12) ; //이자(한달을 무조건 30일로 계산)

					$s_info['schedule'][$i]['principal'] = $repayment_money - $s_info['schedule'][$i]['interest'];	//원금
					$s_info['schedule'][$i]['balance'] = $s_info['schedule'][$i-1]['balance']-$s_info['schedule'][$i]['principal'];	//남은 원금
					$s_info['schedule'][$i]['payed_principal'] = $s_info['schedule'][$i-1]['payed_principal']+$s_info['schedule'][$i]['principal'];	//납입원금 합계
				}
				//$s_info['schedule'][$i]['interest_by_day'] =  $s_info['schedule'][$i]['interest'];	//대출이자
			}

			$s_info['total_rate'] +=  $s_info['schedule'][$i]['interest'];
			if ($s_info['schedule'][$i]['balance'] < 0) $s_info['schedule'][$i]['balance'] *= -1;
		}
		//var_dump($s_info['schedule']);
		

		return $s_info;	//return data : total_rate, deposit_date_list, schedule
    }
}