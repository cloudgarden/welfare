	</div>
		<div class="appbox">
			<input type="button" class="btn btn-large btn-success" value="<?php echo $menus[$this->uri->segment(2)][$this->uri->segment(3)]['title']; ?> 신청" onclick='return check_form(this.form);' />
		</div>
	</div>

</div>

<?php echo form_close(); ?>

<script>
	//form 값 체크, form_validation 과는 별개
	function check_form(form){
		if (form.ano.value == '') {
			alert('분류를 선택해주세요.');
			return false;
		}
		form.submit();
	}


</script>


<script>
	////////////////////////////////////////////////////////////////////////////////////////////////
	//autocomplete
	//$(function() {
	$('input').live('keyup', function() {
		$this = $(this);
		//alert($this.attr('name') + ':'+ $this.attr('value'));
		
		if ($this.attr('name') =='target_name') {
			$("input[name='target_id']").val('');
		
			//console.log('target : '+$("input[name='target']:checked").val()+', '+ $("input[name='target_name']").val());
			var query_string = 'target='+($("input[name='target']:checked").val())+'&type=purpose_business';
			
			$("input[name='target_name']").autocomplete({
				source: '/json_data/target_info?'+query_string,
				minLength: 2,
				select: function( event, ui ) {
					$("input[name='enumber']").val(ui.item.enumber );
					$("input[name='ename']").val(ui.item.ename );
					$("input[name='company']").val(ui.item.company );
					$("input[name='join_date']").val(ui.item.join_date );
					$("input[name='department']").val(ui.item.department );
					$("input[name='etype']").val(ui.item.etype );
					$("input[name='sn']").val(ui.item.sn + '-*******' );
					$("input[name='position']").val(ui.item.position );
					$("input[name='hand_tel']").val(ui.item.hand_tel );
					
					$("input[name='bank_name']").val(ui.item.bank_name );
					$("input[name='bank_account']").val(ui.item.bank_account );
					$("input[name='bank_owner']").val(ui.item.bank_owner );

					$("input[name='rank']").val(ui.item.rank );
					$("input[name='duty']").val(ui.item.duty );
					$("input[name='home_tel']").val(ui.item.home_tel );
					$("input[name='direct_tel']").val(ui.item.direct_tel );
					$("input[name='extension_num']").val(ui.item.extension_num );
					
					
					//$("input[name='join_date']").val(ui.item.join_date );
				}
				//, error: function(){ alert('11');  alert('22'); }
			});
		}
	});
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	//validation_errors나 수정시 해당 값들 셋팅
	if ("<?php echo $s_info['pno']; ?>" != '') {
		$("input[name='target'][value='<?php echo $s_info['target']; ?>']").attr("checked", "checked").parents().addClass("checked");
		/*
		$("input[name='account_kind'][value='<?php echo $s_info['account_kind']; ?>']").trigger('click');
		
		*/

		//alert($("input[name='debit_main_money']").val());
	}

</script>
