<?php
//var_dump($all_list);
//var_dump($s_info);
//var_dump($_POST);


?>

<div class="contentInner">
	<div class="row-fluid">

		<div class="title">
		
			<div class="text">
				<blockquote><h3>복합분계설정</h3></blockquote>
			</div>

			<div class="appbtn">
				<li><div class="iconbox"><img src="/wwf/img/icons/14x14/printer1.png" alt="프린트"></div> </li>
				<li><div class="iconbox"><img src="/wwf/img/icons/14x14/upload1.png" alt="엑셀다운"></div></li>
			</div>
			<div class="clear"></div>
		
		</div>
		<div class="inparea_s top_box">
		<?php foreach ($all_list as $title) {?>
			<label><input type="radio" name='journal_title' value='<?php echo $title['journal_name']; ?>' <?php if ($this->input->post('journal_name') == $title['journal_name']) echo 'checked'; ?>><?php echo $title['journal_name']; ?></label>
		<?php } ?>
		</div>
<?php 
if(validation_errors()) {
		$validation_errors = str_replace("'코드'는(은) 필수항목입니다.", "<b>선택된 계정이 없습니다.</b>", validation_errors());
		echo '<br><br>'.$validation_errors;
		$this->load->view('validation_modal');		
}
?>

<?php
$attributes = array('name'=>'account_form','class' => 'form-horizontal');
echo form_open('/accounting/bunge/bunge_setting', $attributes);

$hidden_data = array();	//data 수정시 DB의 값을 셋팅할 때 사용. 1:처음 로딩
for ($i=1; $i<=4; $i++) {
	$hidden_data['dc['.$i.']']='';
}

echo form_hidden($hidden_data);
?>
		
		
		<div class="table_top">
			<div class="table_left"><h2>복합분개명칭</h2> <input type="text" name='journal_name' value='<?php echo $s_info['journal_name']; ?>' /></div>
		</div>
		<div class="clear"></div>
		

	<?php for($i=1; $i<=4; $i++) { ?>
		<h4><?php echo $i; ?>차 분개</h4>
		<table class="table">
			<tr>
				<th>분류</th>
				<td>
					<ul>
					<?php foreach ($this->config->item('account_kind') as $key => $title) { ?>
					<li><input type="radio" name='account_kind[<?php echo $i; ?>]' value='<?php echo $key; ?>' idx="<?php echo $i; ?>" /><?php echo $title; ?></li> 
					<?php } ?>
					</ul>
				</td>
				<th>대상</th>
				<td>
				<ul class="tabs">
					<label for="tab1"><input type="radio" name="target[<?php echo $i; ?>]" value="employee" />사원</label>
					<label for="tab2"><input type="radio" name="target[<?php echo $i; ?>]" value="customer" />거래처</label>
					<label for="tab3"><input type="radio" name="target[<?php echo $i; ?>]" value="fund_employee" />복지기금내부직원</label>
				</ul>
				</td>
			</tr>
			<tr>
				<th>계정</th>
				<td colspan="3">
					<ul>
						<li><select name='account_no[<?php echo $i; ?>]' idx="<?php echo $i; ?>" style="width:150px"></select></li>
						
						<li>적요 : 
						<select name='account_summary[<?php echo $i; ?>]' style="width:150px"></select>
						<div id="Forms">
							<div style="display:none;">
								<input type="text" name='account_summary_direct[<?php echo $i; ?>]' style="width:200px;"  /> 
							</div>
						</div>
						</li>
					</ul>
				</td>
			</tr>
			<tr>
				<th>부가세</th>
				<td>							
						<label><input type="radio" name="tax[<?php echo $i; ?>]" value='Y'>적용 </label>
						<label><input type="radio" name="tax[<?php echo $i; ?>]" value='N'>무시 </label>
						<label><input type="radio" name="tax[<?php echo $i; ?>]" value='F'>면세 </label>
				</td>
				<th>회계분류</th>
				<td>
					<label><input type="radio" name="account_group[<?php echo $i; ?>]" value='purpose'>목적사업회계 </label>
					<label><input type="radio" name="account_group[<?php echo $i; ?>]" value='income'>수익사업회계 </label>
				</td>
			</tr>
			<tr>
				<th>차변 계정</th>
				<td>
					<select name='debit_account_main[<?php echo $i; ?>]' style="width:150px"></select>
					<select name='debit_account_sub[<?php echo $i; ?>]' style="width:150px"></select>
				</td>
				<th>대변 계정</th>
				<td>
					<select name='credit_account_main[<?php echo $i; ?>]' style="width:150px"></select>
					<select name='credit_account_sub[<?php echo $i; ?>]' style="width:150px"></select>
				</td>
			</tr>
			<tr>
				<th>분개 설명</th>
				<td colspan="3"><textarea name='note[<?php echo $i; ?>]' style="width:100%" ></textarea></td>
			</tr>
		</table>
	<?php } ?>
		<div class="appbox">
			<input type="button" class="btn btn-large btn-success" value="저장" onclick='return check_form(this.form);' />
		</div>

<?php echo form_close(); ?>


	</div>
</div>

<br><br><br><br>
<script>
function check_form(form){
	//console.log($("input[name='account_kind[1]:checked']").val());
	//console.log(form.account_kind[1].value);
	form.submit();
	//return false;
}

var speed=0;	//click 이벤트 발생이 안하는 에러를 없애기 위해 선언함 값, 어디선가 쓰이고 있음.

	//계정의 분류 선택시, 해당 계정목록 셋팅
	$('input').live('click', function() {
		$this = $(this);
		//alert($this.attr('name') + ':'+ $this.attr('value'));
		var idx = ($this.attr('idx'));	//선택된 것의 배열index
		//alert(idx);
		
		//분류를 선택했을 때 해당 값들 셋팅
		if ($this.attr('name') =='account_kind['+idx+']') {
			//alert($this.attr('value'));
			//alert($("input[name='target['+idx+']']:checked").val());
			//alert($("select[name='account_no']").attr('value'));
			$("select[name='account_no["+idx+"]']").empty();
			
			$.ajax({
				url:'/json_data/account_list_by_kind', 
				data:{
					'account_kind':$this.attr('value'),
					'target':$("input[name='target["+idx+"]']:checked").val()	//대상의 선택된 값
					},
				dataType:'json',
				success:function(result){
					//alert(result['ano'].length);
					for (var i=0; i<result['ano'].length; i++) {
						$("select[name='account_no["+idx+"]']").append('<option value="'+result['ano'][i]+'">'+result['title_owner'][i]+'</option>');
					}
					//계정과목의 첫번째 값을 기본으로 선택하고, change 이벤트 강제 발생시켜서  적요등을 셋팅한다.
					$("select[name='account_no["+idx+"]']").trigger('change');	//계정의 첫번재 값이 선택되도록 활성화
				},
				error:function(msg) {
					alert('분류의 계정정보를 가져오는데 실패했습니다.');
					//alert(msg.responseText);
				}
			});
		}
		//복합분개명칭을 선택했을 때 해당 값들 셋팅
		else if ($this.attr('name') =='journal_title') {
			var journal_title = $("input[name='journal_title']:checked").val();
			console.log('journal_title:'+journal_title);
			$("input[name='journal_name']").val(journal_title);
			
			$.ajax({
				url:'/json_data/get_compound_entry', 
				data:{
					'journal_name':journal_title,
					'target':$("input[name='target["+idx+"]']:checked").val()	//대상의 선택된 값
					},
				dataType:'json',
				success:function(result){
					for(jidx in result){
						console.log('jidx:'+jidx);
						//차변/대변 여부
						var dc=result[jidx]['account_sub']['dc'];
						var reverse_dc=(dc=='debit') ? 'credit' : 'debit'; //선택된 계정의 반대 차변/대변

						for(name in result[jidx]){
							console.log(name+', '+result[jidx][name]);
						}
						//alert(result[jidx]['account_kind']);
						//분류 셋팅
						//라디오버튼의 경우, 화면에 클릭된 것으로 보일려면 부모의 span에 check를 해야하고, 실제 값을 click된 것으로 하기 위해 자기 자신에도 check를 해야한다.
						$("input[name='account_kind["+result[jidx]['journal_order']+"]'][value='"+result[jidx]['account_kind']+"']").attr("checked", "checked").parents().addClass("checked");
						$("input[name='target["+result[jidx]['journal_order']+"]'][value='"+result[jidx]['target']+"']").attr("checked", "checked").parents().addClass("checked");
						$("input[name='tax["+result[jidx]['journal_order']+"]'][value='"+result[jidx]['tax']+"']").attr("checked", "checked").parents().addClass("checked");
						$("input[name='account_group["+result[jidx]['journal_order']+"]'][value='"+result[jidx]['account_group']+"']").attr("checked", "checked").parents().addClass("checked");

						$("textarea[name='note["+result[jidx]['journal_order']+"]']").val(result[jidx]['note']);
						
						//계정목록 셋팅
						for(idx in result[jidx]['account_list']){
							$("select[name='account_no["+result[jidx]['journal_order']+"]']").append('<option value="'+result[jidx]['account_list'][idx]['ano']+'">'+result[jidx]['account_list'][idx]['title_owner']+'</option>');
							
							//차변/대변의 메인계정 셋팅
							if (result[jidx]['account_no'] == result[jidx]['account_list'][idx]['ano']) {
								$("select[name='"+dc+"_account_main["+result[jidx]['journal_order']+"]']").append('<option value="'+result[jidx]['account_list'][idx]['ano']+'">'+result[jidx]['account_list'][idx]['title_owner']+'</option>');
							}
						}

						//적요 셋팅
						for (var i=0; i<result[jidx]['account_sub']['summary'].length; i++) {
							$("select[name='account_summary["+result[jidx]['journal_order']+"]']").append('<option value="'+result[jidx]['account_sub']['summary'][i]+'">'+result[jidx]['account_sub']['summary'][i]+'</option>');
						}

						//차변/대변 계정 셋팅
						//for(name in result[jidx]['account_sub']){
							//console.log(name+', '+result[jidx]['account_sub'][name]);
						//}

						//차변/대변의 묶음계정
						for (var i=0; i<result[jidx]['account_sub']['bundle'].length; i++) {
							$("select[name='"+dc+"_account_sub["+result[jidx]['journal_order']+"]']").append('<option value="'+result[jidx]['account_sub']['bundle'][i]+'">'+result[jidx]['account_sub']['bundle_name'][i]+'</option>');
						}
						
						//차변/대변의 상대계정
						for (var i=0; i<result[jidx]['account_sub']['contra'].length; i++) {
							$("select[name='"+reverse_dc+"_account_main["+result[jidx]['journal_order']+"]']").append('<option value="'+result[jidx]['account_sub']['contra'][i]+'">'+result[jidx]['account_sub']['contra_name'][i]+'</option>');
						}

						//차변/대변의 상대묶음 계정
						for (var i=0; i<result[jidx]['account_sub']['contra_bundle'].length; i++) {
							$("select[name='"+reverse_dc+"_account_sub["+result[jidx]['journal_order']+"]']").append('<option value="'+result[jidx]['account_sub']['contra_bundle'][i]+'">'+result[jidx]['account_sub']['contra_bundle_name'][i]+'</option>');
						}

						/*
						*/
						
						/*
						//각종 라디오 버튼 셋팅

						alert($("select[name='account_no[1]'] option").size());

						//$("select[name='account_no[1]']").val('87').attr("selected", "selected");
						$("select[name='account_no[1]'] option:eq(3)").attr("selected", "selected");
						
						//$("select[name='account_no["+result[jidx]['journal_order']+"]']").val(result[jidx]['account_no']).attr("selected", "selected");
						*/

					}
				},
				error:function(msg) {
					alert('결과값을 가져오는데 실패했습니다.');
					//alert(msg.responseText);
				}
			});
		}
	});


	//계정을 선택했을 때 계정의 정보를 가져와서 셋팅
	$('select').live('change', function() {
		$this = $(this);
		var idx = ($this.attr('idx'));	//선택된 것의 배열index
		
		//계정이 선택됐을 때 
		if ($this.attr('name') =='account_no['+idx+']') {
			var selected_name = $("select[name='"+$this.attr('name')+"'] option:selected").text();	// 선택된 계정명
			var selected_no = $this.attr('value');	// 선택된 계정 ID
			//alert(selected_no);
			var dc='';	//차변/대변 정보, ajax로 계정정보를 가져온 다음에 셋팅됨
			var reverse_dc=''; //선택된 계정의 반대 차변/대변

			//계정과 관련된 값들 초기화
			$("select[name='account_summary["+idx+"]']").empty();
			$("select[name='debit_account_main["+idx+"]']").empty();
			$("select[name='debit_account_sub["+idx+"]']").empty();
			$("select[name='credit_account_main["+idx+"]']").empty();
			$("select[name='credit_account_sub["+idx+"]']").empty();
			
			$("input[name='tax["+idx+"]']").parents().removeClass("checked");		//부가세 초기화
			$("input[name='account_group["+idx+"]']").parents().removeClass("checked");	//회계분류 초기화
			
			//$("input[name='tax["+idx+"]']").attr("checked", false).parents().removeClass("checked");		//부가세 초기화
			//$("input[name='account_group["+idx+"]']").attr("checked", false).parents().removeClass("checked");	//회계분류 초기화

			$.ajax({
				url:'/json_data/account_info_by_kind', 
				data:{
					'ano': selected_no,
					'account_kind': $("input[name='account_kind["+idx+"]']:checked").val(),
					'target': $("input[name='target["+idx+"]']:checked").val()	//대상의 선택된 값
					},
				dataType:'json',
				success:function(result){

					for(name in result){
						//alert(name);
						//계정의 option정보 셋팅
						if (name == 'group') {
							//validation_errors나 수정시 해당 값들 셋팅
							$("input[name='account_group["+idx+"]'][value='"+result[name]+"']").parents().addClass("checked");
						}
						//계정의 적요 셋팅
						else if (name == 'summary') {
							for (var i=0; i<result[name].length; i++) {
								$("select[name='account_summary["+idx+"]']").append('<option value="'+result[name][i]+'">'+result[name][i]+'</option>');
							}
						}
						//계정의 차변/대변 정보 셋팅
						else if (name == 'dc') { //차변/대변 
							dc = result[name];
							reverse_dc = (dc=='debit') ? 'credit' : 'debit';

							//차변/대변에 메인 계정 보이기, 계정의 입력된 정보의 따라 메인 계정이 차변/대변이 된다.
							$("input[name='"+name+"["+idx+"]']").val(dc);
							$("select[name='"+dc+"_account_main["+idx+"]']").append('<option value="'+selected_no+'">'+selected_name+'</option>');
						}
						//계정의 묶음계정 셋팅
						else if (name == 'bundle') {
							for (var i=0; i<result[name].length; i++) {
								$("select[name='"+dc+"_account_sub["+idx+"]']").append('<option value="'+result[name][i]+'">'+result[name+'_name'][i]+'</option>');
							}
						}
						//계정의 상대계정 셋팅
						else if (name == 'contra') {
							for (var i=0; i<result[name].length; i++) {
								$("select[name='"+reverse_dc+"_account_main["+idx+"]']").append('<option value="'+result[name][i]+'">'+result[name+'_name'][i]+'</option>');
							}
						}
						//계정의 상대묶음계정 셋팅
						else if (name == 'contra_bundle') {
							for (var i=0; i<result[name].length; i++) {
								$("select[name='"+reverse_dc+"_account_sub["+idx+"]']").append('<option value="'+result[name][i]+'">'+result[name+'_name'][i]+'</option>');
							}
						}
					}
					//alert($("select[name='account_no[1]'] option").size());
				},
			   error:function(msg) {
			   		alert('계정정보를 가져오는데 실패했습니다.');
					//alert(msg.responseText);
			   }
			});
		}
	});
	
	//새로고침등 선택된 복합분개 명칭이 있을 때 셋팅
	if ($("input[name='journal_name']").val() != '') {
		//$("input[name='journal_title']:checked").parents().trigger('click');
		$("input[name='journal_title'][value='"+$("input[name='journal_name']").val()+"']").trigger('click');
	}
	
</script>

