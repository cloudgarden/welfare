<!DOCTYPE html>
<html lang="ko">
	<head>
		<meta charset="utf-8">
		<title></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- Le styles -->
		<link href="/wwf/css/bootstrap.css" rel="stylesheet">
		<link href="/wwf/css/bootstrap-responsive.css" rel="stylesheet">
		<link href="/wwf/css/general.css" rel="stylesheet">
		<link href="/wwf/css/colors/noise-blue.css" rel="stylesheet" id="theme">
		
		<!--datepicker 테마 css-->
   		<link href="/wwf/lib/jquery/themes/base/jquery.ui.all.css" rel="stylesheet" id="theme">

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<link href="/wwf/css/ie8.css" rel="stylesheet">
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<script src="/wwf/js/respond/respond.min.js"></script>
		<![endif]-->

		<!-- Fav and touch icons -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/wwf/img/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/wwf/img/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/wwf/img/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="/wwf/img/ico/apple-touch-icon-57-precomposed.png">
		<link rel="shortcut icon" href="/wwf/img/ico/favicon.png">

		<script src="/wwf/js/jquery-1.8.3.js"></script>
		<script src="/wwf/lib/jquery/jquery.number.js"></script>
		<script>
            //* hide all elements & show preloader
            document.documentElement.className += 'loader';
		</script>
	</head>

	<body>

		<div class="loading"><img src="/wwf/img/ajaxLoader/loader01.gif" alt="">
		</div>

		<div class="mainContainer">

			<?php $this -> load -> view('/menu/header_menu'); ?>
			
			<?php $this -> load -> view('/menu/menu', array('menus' => $menus, 'sub_menu_css'=>$sub_menu_css)); ?>

			<div class="content">

				<?php //$this -> load -> view('/menu/breadcrumbs.php', array('menus' => $menus)); ?>
