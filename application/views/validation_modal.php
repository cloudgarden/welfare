

<div id="validationModal" class="modal hide fade" tabindex="10" role="dialog" aria-labelledby="validationModalLabel" aria-hidden="true" style="width:400px; align=center;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="validationModalLabel">아래 내용을 확인하세요.</h3>
  </div>
  <div class="modal-body">
	<?php 
		if(validation_errors()) {
			$validation_errors = str_replace("'코드'는(은) 필수항목입니다.", "<b>선택된 계정이 없습니다.</b><br><br>", validation_errors());
			echo $validation_errors;
		}			
	?>
	
  </div>
  <div class="modal-footer">
  </div>
</div>

<script>
	$(document).ready(function() {
	    $('#validationModal').modal('show');
	});
</script>
