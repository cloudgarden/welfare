<?php
$config = array(
 	/*
	 * 역학, 사용자 관련
	 */
	'auth/login' => array(
		array(
			'field' => 'uid'
			,'label' => 'ID'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'password'
			,'label' => '비밀번호'
			//,'rules' => 'trim|required|min_length[6]|max_length[30]'
			,'rules' => 'trim|required'
		)
	)
	, 'auth/add_user' => array(
		array(
			'field' => 'uid'
			,'label' => 'ID'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'password'
			,'label' => '비밀번호'
			//,'rules' => 'trim|required|min_length[5]|max_length[20]'
			,'rules' => 'trim|required'
		)
	)
	, 'auth/add_manager' => array(
		array(
			'field' => 'email'
			,'label' => '이메일 주소'
			,'rules' => 'trim|required|valid_email|is_unique[user.email]'
		)
		, array(
			'field' => 'nickname'
			,'label' => '닉네임'
			,'rules' => 'trim|required|min_length[5]|max_length[20]'
		)
		, array(
			'field' => 'password'
			,'label' => '비밀번호'
			,'rules' => 'trim|required|min_length[6]|max_length[30]|matches[re_password]'
		)
		, array(
			'field' => 're_password'
			,'label' => '비밀번호 확인'
			,'rules' => 'trim|required'
		)
	)



	/*
	 * 전자결제
	 */
	/*
	 * 신청서 결제
	 */
	, 'e_approval/apply_approval' => array(
		array(
			'field' => 'pno'
			,'label' => '신청서ID'
			,'rules' => 'trim|required'
		)
	)
	
	/*
	 * 메뉴 관련
	 */
	, 'basis_info/setting/menu' => array(
		array(
			'field' => 'title'
			,'label' => '메뉴명'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'mid'
			,'label' => '메뉴 ID'
			,'rules' => 'trim|required|alpha_dash|is_update_unique[menu.mid.'.trim($this->input->post('org_mid')).']'
		)
		, array(
			'field' => 'type'
			,'label' => '메뉴 Type'
			,'rules' => 'trim|required|alpha'
		)
	)
	
	/*
	 * 분류 관련
	 */
	, 'basis_info/setting/category' => array(
		array(
			'field' => 'gid'
			,'label' => '분류명'
			,'rules' => 'trim|required|is_update_unique[categorization.gid.'.trim($this->input->post('org_gid')).']'
		)
	)
	
	, 'menu/delete' => array(
		array(
			'field' => 'mid'
			,'label' => '메뉴 ID'
			,'rules' => 'trim|required|alpha_dash'
		)
	)
	 	 
	/*
	 * 사용자 정의 Table 관련
	 */
	, 'basis_info/customize_table' => array(
		array(
			'field' => 'fid'
			,'label' => 'Table명'
			,'rules' => 'trim|required|is_unique[custom_table.fid]'
		)
		,array(
			'field' => 'fname'
			,'label' => 'Title'
			,'rules' => 'trim|required'
		)
	)
	
	, 'menu/delete' => array(
		array(
			'field' => 'mid'
			,'label' => '메뉴 ID'
			,'rules' => 'trim|required|alpha_dash'
		)
	)
	 	 
	 	 
	/*
	 * 사용자 정의 Table 항목 관련
	 */
	, 'basis_info/setting/customize' => array(
		array(
			'field' => 'column_title'
			,'label' => '필드명'
			,'rules' => 'trim|required'
		)
	)
	
	, 'menu/delete' => array(
		array(
			'field' => 'mid'
			,'label' => '메뉴 ID'
			,'rules' => 'trim|required|alpha_dash'
		)
	)
	 	 
	/*
	 * Popup
	 */
	, 'popup/inputbalance' => array(
		array(
			'field' => 'stage'
			,'label' => '기수'
			,'rules' => 'trim|required|alpha_dash'
		)
		, array(
			'field' => 'mode'
			,'label' => '상태'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'data_name'
			,'label' => '종류'
			,'rules' => 'trim|required'
		)
	)

	/*
	 * Popup - 목적사업신청 전표생성
	 */
	, 'popup/statement' => array(
		array(
			'field' => 'account_kind'
			,'label' => '분류'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'input_date'
			,'label' => '날짜'
			,'rules' => 'trim|required|numeric_dash'
		)
		/*
		, array(
			'field' => 'target'
			,'label' => '대상'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'target_name'
			,'label' => '사원명 or 거래처명'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'target_id'
			,'label' => '사번 or 사업자번호'
			,'rules' => 'trim|required'
		)
		 */
		, array(
			'field' => 'account_no'
			,'label' => '계정'
			,'rules' => 'trim|required|numeric_dash'
		)
		, array(
			'field' => 'tax'
			,'label' => '부가세 여부'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'debit_main'
			,'label' => '차변 계정과목'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'debit_main_money'
			,'label' => '차변 금액'
			,'rules' => 'trim|required|numeric'
		)
		, array(
			'field' => 'credit_main'
			,'label' => '대변 계정과목'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'credit_main_money'
			,'label' => '대변 금액'
			,'rules' => 'trim|required|numeric'
		)
	)
	
	/*
	 * 대부금 이자 계산표
	 */
	, 'popup/loan_cal' => array(
	)


	
	/*
	 * 기금정보입력
	 */
	, 'basis_info/fund_info/basic_fund_info' => array(
		array(
			'field' => 'business_number1'
			,'label' => '사업자등록번호1'
			,'rules' => 'trim|numeric|exact_length[3]'
		)
		, array(
			'field' => 'business_number2'
			,'label' => '사업자등록번호2'
			,'rules' => 'trim|numeric|exact_length[2]'
		)
		/*
		, array(
			'field' => 'business_number3'
			,'label' => '사업자등록번호3'
			,'rules' => 'trim|required|numeric|exact_length[5]|valid_bizno['.trim($this->input->post('business_number1')).'.'.trim($this->input->post('business_number2')).']'
		)
		*/
		, array(
			'field' => 'business_number3'
			,'label' => '사업자등록번호3'
			,'rules' => 'trim|numeric|exact_length[5]'
		)
		, array(
			'field' => 'corporation_number1'
			,'label' => '법인등록번호1'
			,'rules' => 'trim|numeric|exact_length[6]'
		)
		, array(
			'field' => 'corporation_number2'
			,'label' => '법인등록번호2'
			,'rules' => 'trim|numeric|exact_length[7]'
		)
		, array(
			'field' => 'permission_number'
			,'label' => '인가번호'
			,'rules' => 'trim|numeric'
		)
		, array(
			'field' => 'representative_sn1'
			,'label' => '주민번호1'
			,'rules' => 'trim|numeric|exact_length[6]'
		)
		, array(
			'field' => 'representative_sn2'
			,'label' => '주민번호2'
			,'rules' => 'trim|numeric|exact_length[7]'
		)
	)

	/*
	 * 계정과목 관련
	 */
	, 'account/add' => array(
		array(
			'field' => 'title'
			,'label' => '계정과목'
			,'rules' => 'trim|required|is_unique[account.title]'
		)
		, array(
			'field' => 'code'
			,'label' => '코드'
			,'rules' => 'trim|required|max_length[30]|is_unique[account.code]'
		)
	)
	
	, 'basis_info/account_category' => array(
		array(
			'field' => 'code'
			,'label' => '코드'
			,'rules' => 'trim|required_hidden'
		)
		, array(
			'field' => 'ano'
			,'label' => '계정과목 ID'
			,'rules' => 'trim|required_hidden'
		)
		, array(
			'field' => 'pano'
			,'label' => '부모 계정과목 ID'
			,'rules' => 'trim|required_hidden'
		)
		, array(
			'field' => 'title'
			,'label' => '고유 계정과목'
			,'rules' => 'trim|required_hidden'
		)
		, array(
			'field' => 'title_owner'
			,'label' => '계정과목'
			,'rules' => 'trim|required'
		)
		/*
		, array(
			'field' => 'account_option[income][use]	'
			,'label' => '수입 해당여부'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'account_option[expense][use]	'
			,'label' => '지출 해당여부'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'account_option[movement][use]	'
			,'label' => '자산이동 해당여부'
			,'rules' => 'trim|required'
		)
		 * */
	)
		
	/*
	 * 거래처입력
	 */
	, 'basis_info/customer' => array(
		array(
			'field' => 'customer_name'
			,'label' => '거래처명'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'business_number1'
			,'label' => '사업자등록번호1'
			,'rules' => 'trim|required|numeric|exact_length[3]'
		)
		, array(
			'field' => 'business_number2'
			,'label' => '사업자등록번호2'
			,'rules' => 'trim|required|numeric|exact_length[2]'
		)
		/*
		, array(
			'field' => 'business_number3'
			,'label' => '사업자등록번호3'
			,'rules' => 'trim|required|numeric|exact_length[5]|valid_bizno['.trim($this->input->post('business_number1')).'.'.trim($this->input->post('business_number2')).']'
		)
		*/
		, array(
			'field' => 'business_number3'
			,'label' => '사업자등록번호3'
			,'rules' => 'trim|required|numeric|exact_length[5]'
		)
		, array(
			'field' => 'corporation_number1'
			,'label' => '법인등록번호1'
			,'rules' => 'trim|numeric|exact_length[6]'
		)
		, array(
			'field' => 'corporation_number2'
			,'label' => '법인등록번호2'
			,'rules' => 'trim|numeric|exact_length[7]'
		)
		, array(
			'field' => 'com_tel'
			,'label' => '전화번호'
			,'rules' => 'trim|numeric_dash'
		)
		, array(
			'field' => 'com_fax'
			,'label' => '전화번호'
			,'rules' => 'trim|numeric_dash'
		)
		, array(
			'field' => 'com_email'
			,'label' => '대표 Email'
			,'rules' => 'trim|valid_email'
		)
		, array(
			'field' => 'charge_tel'
			,'label' => '담당자 전화번호'
			,'rules' => 'trim|numeric_dash'
		)
		, array(
			'field' => 'charge_handtel'
			,'label' => '담당자 휴대폰번호'
			,'rules' => 'trim|numeric_dash'
		)
		, array(
			'field' => 'charge_email'
			,'label' => '담당자 Email'
			,'rules' => 'trim|valid_email'
		)
		, array(
			'field' => 'bank_account'
			,'label' => '계좌번호'
			,'rules' => 'trim|numeric_dash'
		)
	)
	
	/*
	 * 사원입력
	 */
	, 'basis_info/employee' => array(
		array(
			'field' => 'enumber'
			,'label' => '사번'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'ename'
			,'label' => '이름'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'is_work'
			,'label' => '재직상태'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'sn1'
			,'label' => '주민번호1'
			,'rules' => 'trim|required|numeric|exact_length[6]'
		)
		, array(
			'field' => 'sn2'
			,'label' => '주민번호2'
			,'rules' => 'trim|required|numeric|exact_length[7]'
		)
		, array(
			'field' => 'company'
			,'label' => '사업장'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'department'
			,'label' => '부서'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'home_tel'
			,'label' => '전화번호'
			,'rules' => 'trim|numeric_dash'
		)
		, array(
			'field' => 'hand_tel'
			,'label' => '휴대폰번호'
			,'rules' => 'trim|numeric_dash'
		)
		, array(
			'field' => '이메일'
			,'label' => 'Email'
			,'rules' => 'trim|valid_email'
		)
		, array(
			'field' => 'bank_account'
			,'label' => '계좌번호'
			,'rules' => 'trim|numeric_dash'
		)
	)
	
	/*
	 * 계좌관리
	 */
	, 'basis_info/bank_account' => array(
		array(
			'field' => 'account'
			,'label' => '해당계정'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'use'
			,'label' => '계좌용도'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'bank_name'
			,'label' => '은행'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'alias'
			,'label' => '통장명칭'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'bank_account'
			,'label' => '계좌번호'
			,'rules' => 'trim|required|numeric_dash'
		)
		, array(
			'field' => 'money'
			,'label' => '잔액'
			,'rules' => 'trim|numeric'
		)
	)
	
	/*
	 * 재무상태표
	 */
	, 'basis_info/base_info/inputbalance' => array(

	)

	/*
	 * 기초자료입력 - 고유목적사업준비금
	 */
	, 'basis_info/base_info/input_goubiz' => array(
		array(
			'field' => 'mode'
			,'label' => 'mode'
			,'rules' => 'trim|required'
		)

	)

	/*
	 * 거래입력
	 */
	, 'accounting/acc_billing/basic' => array(
		array(
			'field' => 'account_kind'
			,'label' => '분류1'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'input_date'
			,'label' => '날짜'
			,'rules' => 'trim|required|numeric_dash'
		)
		/*
		, array(
			'field' => 'target'
			,'label' => '대상'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'target_name'
			,'label' => '사원명 or 거래처명'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'target_id'
			,'label' => '사번 or 사업자번호'
			,'rules' => 'trim|required'
		)
		 */
		, array(
			'field' => 'account_no'
			,'label' => '계정'
			,'rules' => 'trim|required|numeric_dash'
		)
		, array(
			'field' => 'tax'
			,'label' => '부가세 여부'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'debit_main'
			,'label' => '차변 계정과목'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'debit_main_money'
			,'label' => '차변 금액'
			,'rules' => 'trim|required|numeric'
		)
		, array(
			'field' => 'credit_main'
			,'label' => '대변 계정과목'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'credit_main_money'
			,'label' => '대변 금액'
			,'rules' => 'trim|required|numeric'
		)
	)
	/*
	 * 전표조회
	 */
	, 'accounting/billing_search' => array(
	)
	
	/*
	 * 장부관리
	 */
	/*
	 * 일(월)계표
	 */
	, 'accounting/jangbu/acc_ilge' => array(
	)
	/*
	 * 계정보조부
	 */
	, 'accounting/jangbu/acc_bojo' => array(
		array(
			'field' => 'account_group'
			,'label' => '회계분류'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'account_no'
			,'label' => '계정'
			,'rules' => 'trim|required|numeric_dash'
		)
	)
	/*
	 * 합계잔액시산표
	 */
	, 'accounting/jangbu/jae_sisan' => array(
		array(
			'field' => 'account_group'
			,'label' => '회계분류'
			,'rules' => 'trim|required'
		)
	)
	
	
	/*
	 * 복합분개설정
	 */
	, 'accounting/bunge/bunge_setting' => array(
		array(
			'field' => 'journal_name'
			,'label' => '복합분개명칭'
			,'rules' => 'trim|required'
		)
		,array(
			'field' => 'account_kind[1]'
			,'label' => '1차분개 분류'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'target[1]'
			,'label' => '1차분개 대상'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'account_no[1]'
			,'label' => '1차분개 계정'
			,'rules' => 'trim|required|numeric_dash'
		)
		, array(
			'field' => 'tax[1]'
			,'label' => '1차분개 부가세 여부'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'debit_account_main[1]'
			,'label' => '1차분개 차변 계정과목'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'credit_account_main[1]'
			,'label' => '1차분개 대변 계정과목'
			,'rules' => 'trim|required'
		)
		/*
		,array(
			'field' => 'account_kind[2]'
			,'label' => '2차분개 분류'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'target[2]'
			,'label' => '2차분개 대상'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'account_no[2]'
			,'label' => '2차분개 계정'
			,'rules' => 'trim|required|numeric_dash'
		)
		, array(
			'field' => 'tax[2]'
			,'label' => '2차분개 부가세 여부'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'debit_account_main[2]'
			,'label' => '2차분개 차변 계정과목'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'credit_account_main[2]'
			,'label' => '2차분개 대변 계정과목'
			,'rules' => 'trim|required'
		)
		 * 
		 */
	)
	
	/*
	 * 목적사업 - 경조비지원
	 */
	, 'purpose_business/purpose_apply/pur_ceremony' => array(
		array(
			'field' => 'ano'
			,'label' => '계정정보'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'ename'
			,'label' => '신청자명'
			,'rules' => 'trim|required'
		)
	)
	
	/*
	 * 목적사업 - 장학금지원
	 */
	, 'purpose_business/purpose_apply/pur_scholarship' => array(
		array(
			'field' => 'ano'
			,'label' => '계정정보'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'ename'
			,'label' => '신청자명'
			,'rules' => 'trim|required'
		)
	)
	
	/*
	 * 목적사업 - 체육문화활동지원
	 */
	, 'purpose_business/purpose_apply/pur_event' => array(
		array(
			'field' => 'ano'
			,'label' => '계정정보'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'ename'
			,'label' => '신청자명'
			,'rules' => 'trim|required'
		)
	)
	
	/*
	 * 목적사업 - 건강증진지원
	 */
	, 'purpose_business/purpose_apply/pur_health' => array(
		array(
			'field' => 'ano'
			,'label' => '계정정보'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'ename'
			,'label' => '신청자명'
			,'rules' => 'trim|required'
		)
	)
	
	/*
	 * 목적사업 - 장기근속자지원신청
	 */
	, 'purpose_business/purpose_apply/pur_longservice' => array(
		array(
			'field' => 'ano'
			,'label' => '계정정보'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'ename'
			,'label' => '신청자명'
			,'rules' => 'trim|required'
		)
	)
	
	/*
	 * 목적사업 - 출산육아지원
	 */
	, 'purpose_business/purpose_apply/pur_child' => array(
		array(
			'field' => 'ano'
			,'label' => '계정정보'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'ename'
			,'label' => '신청자명'
			,'rules' => 'trim|required'
		)
	)
	
	/*
	 * 목적사업 - 주택자금지원신청
	 */
	, 'purpose_business/purpose_apply/pur_house' => array(
		array(
			'field' => 'ano'
			,'label' => '계정정보'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'ename'
			,'label' => '신청자명'
			,'rules' => 'trim|required'
		)
	)
	
	/*
	 * 목적사업 - 기념품 지원신청
	 */
	, 'purpose_business/purpose_apply/pur_gift' => array(
		array(
			'field' => 'ano'
			,'label' => '계정정보'
			,'rules' => 'trim|required'
		)
	)
	
	/*
	 * 목적사업 - 재난재해지원
	 */
	, 'purpose_business/purpose_apply/pur_disaster' => array(
		array(
			'field' => 'ano'
			,'label' => '계정정보'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'ename'
			,'label' => '신청자명'
			,'rules' => 'trim|required'
		)
	)
	
	/*
	 * 목적사업 - 의료비 보조
	 */
	, 'purpose_business/purpose_apply/pur_healthcare' => array(
		array(
			'field' => 'ano'
			,'label' => '계정정보'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'ename'
			,'label' => '신청자명'
			,'rules' => 'trim|required'
		)
	)
	
	/*
	 * 목적사업 - 목적사업조회
	 */
	, 'purpose_business/porpose_search' => array(
	)
	
	/*
	 * 목적사업 설정
	 */
	, 'purpose_business/purpose_biz_setting' => array(
		array(
			'field' => 'ano'
			,'label' => '분류'
			,'rules' => 'trim|required'
		)
	)
	
	/*
	 * 대부금신청 설정
	 */
	, 'loan/loan_apply/app_apply' => array(
		array(
			'field' => 'ano'
			,'label' => '분류'
			,'rules' => 'trim|required'
		)
		, array(
			'field' => 'ename'
			,'label' => '신청자명'
			,'rules' => 'trim|required'
		)
	)
	
	/*
	 * 대부금 통합전표 생성
	 */
	, 'loan/loan_execution' => array(
	)
	
	/*
	 * 대부사업 설정
	 */
	, 'loan/loan_setting' => array(
		array(
			'field' => 'ano'
			,'label' => '분류'
			,'rules' => 'trim|required'
		)
	)
	
	
	
)


?>