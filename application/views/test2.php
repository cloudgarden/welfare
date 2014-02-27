<!DOCTYPE html>
<html lang="ko">
	<head>
		<meta charset="utf-8">
     <body>
    	<br><br><br>
        <div id="result"></div>
        <input type="text" id="msg" />
        <input type="button" value="get result" id="getResult" />
        <script>
            $('#getResult').click( function() {
                $('#result').html('');
                $.ajax({
                	type:'GET',
                   url:'/json_data/json2_test', 
                   dataType:'json', 
                   success:function(result) {
                    	alert(result.hanja);
                   },
				   error:function(msg) {
				   		alert('fail');
				   }
                    
                });
            })
        </script>
    </body>
</html>

