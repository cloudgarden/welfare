/* Chinese initialisation for the jQuery UI date picker plugin. */
/* Written by Ressol (ressol@gmail.com). */
jQuery(function($){
	$.datepicker.regional['ko'] = {
		autoSize: true,
		changeMonth: true, //월변경가능
		changeYear: true, //년변경가능

		closeText: '닫기',
		prevText: '이전달',
		nextText: '다음달',
		//yearRange:'2012:+0', // 연도 셀렉트 박스 범위(현재와 같으면 1988~현재년)
		currentText: '오늘',
		monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		dayNames: ['일요일','월요일','화요일','수요일','목요일','금요일','토'],
		dayNamesShort: ['일','월','화','수','목','금','토'],
		dayNamesMin: ['일','월','화','수','목','금','토'],


		weekHeader: 'Wk',
		dateFormat: 'yy/mm/dd',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: true, //년 뒤에 월 표시
		//yearSuffix: '년'

		showOtherMonths: true,
		selectOtherMonths: true
	};
	$.datepicker.setDefaults($.datepicker.regional['ko']);
});

