<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Table 변수
 */

//역할
define('ROLE', 'role');
define('ROLE_PERMISSION', 'role_permission');
define('ROLE_USER', 'role_user');

//사용자
define('USER', 'user');
define('USER_LOGIN', 'user_login');


// - 기준정보
define('BASIC_FUND_INFO', 'basic_fund_info');
define('BASIC_DATA', 'basic_data');	//기초자료입력
define('BASIC_DATA_LIST', 'basic_data_list');	//기초자료입력
define('BASIC_DATA_PURPOSE_LIST', 'basic_data_purpose_list');	//기초자료입력 - 고유목적사업비

define('ACCOUNT', 'account');
define('ACCOUNT_OPTION', 'account_option');
define('ACCOUNT_RELATION', 'account_relation');
define('ACCOUNT_SUMMARY', 'account_summary');
define('ACCOUNT_TARGET', 'account_target');

define('CUSTOMER', 'customer');
define('EMPLOYEE', 'employee');
define('BANK_ACCOUNT', 'bank_account');

define('MENU', 'menu');
define('CATEGORIZATION', 'categorization');
define('CUSTOM_TABLE', 'custom_table');
define('CUSTOM_FIELD', 'custom_field');


///////////////////////////////////////////////
//Table 변수 - 회계관리
define('STATEMENT', 'statement');	//전표, 기본정보
define('STATEMENT_LIST', 'statement_list');	//전표 금액
define('STATEMENT_PAYMENT', 'statement_payment');	//결제방법, 증빙

///////////////////////////////////////////////
//Table 변수 - 목적사업
define('PURPOSE_BUSINESS', 'purpose_business');	
define('PURPOSE_BUSINESS_META', 'purpose_business_meta');	

///////////////////////////////////////////////
//Table 변수 - 대부사업
define('LOAN', 'loan');	
define('LOAN_META', 'loan_meta');	
define('LOAN_SCHEDULE', 'loan_schedule');	


//Table 변수 - 복합 분개 설정
define('COMPOUND_ENTRY', 'compound_entry');



/*
 * 서브메뉴의 class 속성
 * depth - top level menu:1, 2th menu:2, 3th menu:3
 */
$config['submenu_class_css'] = array('navigation', 'subMenu', '', '');
$config['no_submenu'] = array('menu', 'make');


$config['account_kind'] = array('income'=>'수입', 'expense'=>'지출', 'movement'=>'자산이동');	//계정과목 분류
$config['account_group'] = array('purpose'=>'목적사업회계', 'income'=>'수익사업회계', 'N'=>'해당분류 없음');	//회계분류

$config['target'] = array('employee'=>'사원', 'customer'=>'거래처', 'fund_employee'=>'복지기금내부직원', 'fund_inner'=>'기금내부전출입');	//전표입력시 대상

//기초자료입력
$config['basic_data'] = array('jaemusang'=>'재무상태표', 'sonik'=>'손익계산서', 'earned_surplus'=>'이익잉여금처분 계산서');

//전표 입력시 생성되는 전표
$config['statement'] = array(
'debit_main' => array('account_no'=>0, 'money'=>0),'debit_sub' => array('account_no'=>0, 'money'=>0)
,'credit_main' => array('account_no'=>0, 'money'=>0),'credit_sub' => array('account_no'=>0, 'money'=>0)
);

$config['payment_method'] = array('account_transfer'=>'계좌이체', 'cash'=>'현금', 'corp_debit_card'=>'법인직불카드', 'corp_card'=>'법인카드');	//결제방법
$config['payment_kind'] = array('1'=>'송금', '2'=>'세금계산서', '3'=>'카드영수증', '4'=>'세금(면세)', '5'=>'지출증빙현금영수증', '6'=>'간이영수증', '7'=>'소득공제영수증', '8'=>'해당없음');	//결제방법의 증빙

//잔액계산을 위한 계정코드기준
//자산, 비용 :차변잔액=차변-대변
//부채,자본,수익 : 대변잔액=대변-차변
$config['balance'] = array(array('AA', 'BB'), array('AB', 'AC', 'BA'));	//전표입력시 대상

//이익잉여금처분계산서 계정
//이익잉여금처분계산서
$config['earned_surplus'][1] = array('unfinished_surplus' => array('account_no'=>'', 'title_owner'=>'미처분이익잉여금', 'depth'=>'1', 'weight'=>'1', 'total_money'=>0)
						,'prev_unfinished_surplus' => array('account_no'=>'', 'title_owner'=>'전기이월미처분이익잉여금<br>(또는 전기이월 미처리결손금)', 'depth'=>'2', 'weight'=>'1', 'total_money'=>0)
						,'this_profit' => array('account_no'=>'', 'title_owner'=>'당기순이익<br>(또는 당기순손실)', 'depth'=>'2', 'weight'=>'2', 'total_money'=>0)
						,'profit_option_reserve' => array('account_no'=>'', 'title_owner'=>'임의적립금 등의 이입액', 'depth'=>'1', 'weight'=>'1', 'total_money'=>0)
						,'total' => array('account_no'=>'', 'title_owner'=>'합 계', 'depth'=>'2', 'weight'=>'1', 'total_money'=>0)
						,'earned_surplus' => array('account_no'=>'', 'title_owner'=>'이익잉여금 처분액', 'depth'=>'1', 'weight'=>'1', 'total_money'=>0)
						,'profit_reserve_fund' => array('account_no'=>'', 'title_owner'=>'이익준비금', 'depth'=>'2', 'weight'=>'1', 'total_money'=>0)
						,'legal_reserve' => array('account_no'=>'', 'title_owner'=>'기타법정적립금', 'depth'=>'2', 'weight'=>'2', 'total_money'=>0)
						,'next_unfinished_surplus' => array('account_no'=>'', 'title_owner'=>'차기이월미처분이익잉여금', 'depth'=>'1', 'weight'=>'1', 'total_money'=>0)
						);
//결손금처리계산서
$config['earned_surplus'][2] = array('unfinished_loss' => array('account_no'=>'', 'title_owner'=>'미처리결손금', 'depth'=>'1', 'weight'=>'1', 'total_money'=>0)
						,'prev_unfinished_loss' => array('account_no'=>'', 'title_owner'=>'전기이월미처리결손금<br>(또는 전기이월미처분이익잉여금)', 'depth'=>'2', 'weight'=>'1', 'total_money'=>0)
						,'this_loss' => array('account_no'=>'', 'title_owner'=>'당기순손실<br>(또는 당기순이익)', 'depth'=>'2', 'weight'=>'2', 'total_money'=>0)
						,'loss_output' => array('account_no'=>'', 'title_owner'=>'결손금처리액', 'depth'=>'1', 'weight'=>'1', 'total_money'=>0)
						,'loss_option_reserve' => array('account_no'=>'', 'title_owner'=>'임의적립금이입액', 'depth'=>'2', 'weight'=>'1', 'total_money'=>0)
						,'next_unfinished_loss' => array('account_no'=>'', 'title_owner'=>'차기이월미처리결손금', 'depth'=>'1', 'weight'=>'1', 'total_money'=>0)
						);

//계층구조에 붙이는 순서
$config['ol'][1] = array('I','II','III','IV','V','VI','VII','VIII','IX','XI','XII','XIII','XIV');
$config['ol'][2] = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15');
$config['ol'][3] = array('(1)', '(2)', '(3)', '(4)', '(5)', '(6)', '(7)', '(8)', '(9)', '(10)', '(11)', '(12)', '(13)', '(14)', '(15)');
$config['ol'][4] = array('①', '②', '③', '④', '⑤', '⑥', '⑦', '⑧', '⑨', '⑩', '⑪', '⑫', '⑬', '⑭', '⑮');
$config['ol'][5] = array('가', '나', '다', '라', '마', '바', '사', '아', '자', '차', '카', '타', '파', '하');



//결재
$config['status'] = array('N'=>'신청', 'F'=>'결재', 'P'=>'보류', 'R'=>'부결', 'D'=>'삭제', 'U'=>'수정');

/*
 * 대부사업
 */
//상환방법
$config['repayment'] = array('P'=>'원금 균등상환', 'F'=>'원리금 균등상환', 'R'=>'잔여정년 균등분할');

//담보제공방법 - 보증보험 : surety_insurance, 연대보증 : joint_surety, 퇴직금 : retirement_pay
$config['collateral'] = array('surety_insurance'=>'보증보험', 'joint_surety'=>'연대보증', 'retirement_pay'=>'퇴직금');
$config['loan_status'] = array('N'=>'신청', 'F'=>'결재', 'P'=>'보류 계산서', 'R'=>'부결', 'D'=>'삭제', 'U'=>'수정');




/* End of file submenu.php */
/* Location: ./application/config/wwf/submenu.php */