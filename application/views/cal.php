<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>jQuery UI Datepicker - Default functionality</title>
		<script src="/wwf/lib/jquery/jquery-1.9.1.js"></script>
		<script src="/wwf/lib/jquery/ui/jquery.ui.core.js"></script>
		<script src="/wwf/lib/jquery/ui/jquery.ui.widget.js"></script>
		<script src="/wwf/lib/jquery/ui/jquery.ui.datepicker.js"></script>
		<script src="/wwf/lib/jquery/demos/datepicker/jquery.ui.datepicker-ko.js"></script>

		<link rel="stylesheet" href="/wwf/lib/jquery/themes/base/jquery.ui.all.css">
		<link rel="stylesheet" href="/wwf/lib/jquery/demos/demos.css">
		<script>
            $(function() {
               $("#datepicker11").datepicker($.datepicker.regional["ko"]);
                
                //개별설정
                $( "#datepicker11" ).datepicker( "option", "yearRange", "-3:+0" );
                $( "#datepicker11" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

            });
		</script>

	</head>
	<body>

		<p>
			Date:
			<input type="text" id="datepicker11">
		</p>

	</body>
</html>
