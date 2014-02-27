<?php
//var_dump($form_values);

$account_basic = $form_values['basic'];			//선택된 계정과목 - 기본정보
$account_option = $form_values['account_option'];		//선택된 계정과목 - 해당여부, 차대구분, 회계분류
$account_relation = $form_values['account_relation'];	//선택된 계정과목 - 묶음/상대 계정
//$account_summary = $form_values['account_summary'];	//적요

//var_dump($accounts);
//var_dump($form_values);
//var_dump($kind);
//var_dump($account_basic);
//var_dump($account_relation);

//var_dump($_POST);

?>


<div class="contentInner">
	<div class="row-fluid">

		<div class="title">

			<div class="text">
			<blockquote><h3><?php echo $menus[$this->uri->segment(1)][$this->uri->segment(2)]['title']; ?></h3></blockquote>
			</div>
			<div class="appbtn">
				<li>
					<div class="iconbox"><img src="/wwf/img/icons/14x14/printer1.png" alt="프린트">
					</div>
				</li>
				<li>
					<div class="iconbox"><img src="/wwf/img/icons/14x14/comment5.png" alt="페이지 도움말">
					</div>
				</li>
				<li>
					<div class="iconbox"><img src="/wwf/img/icons/14x14/upload1.png" alt="엑셀다운">
					</div>
				</li>
			</div>
			<div class="clear"></div>

		</div>
		<!--div class="appbtn">
			<a href='/basis_info/update_all_weights'>계정 정리</a>
		</div-->

		<?php show_account($accounts, 0, 0, $max_depth); ?>

		<br/>
		<div class="title">
			
			<?php 
			if(validation_errors()) {
					$validation_errors = str_replace("'코드'는(은) 필수항목입니다.", "<b>선택된 계정이 없습니다.</b>", validation_errors());
					echo '<br><br>'.$validation_errors;
					$this->load->view('validation_modal');		
			?>
			<a href="#validationModal" role="button" class="btn btn-small btn-warning" data-toggle="modal">오류가 있습니다. 확인해 주세요.</a>
			<?php 
			}
			?>
		</div>

		<?php
		$attributes = array('name'=>'account_form','class'=>'form-horizontal');
		echo form_open('/basis_info/account_category', $attributes);

		$hidden_data = array('ano'=>'', 'pano'=>'', 'code'=>'', 'depth'=>'', 'has_children'=>'', 'weight'=>'');
		echo form_hidden($hidden_data);
		?>

		<table class="table" >
			<colgroup>
				<col width="120px">
				
				<?php
				if ($this->input->query_string('pano')>0) {echo '<div class="text"><h3><b>"'.trim($account_basic['title_breadcrumbs'],'> ').'"</b> 의 하위 계정을 추가합니다.</h3></div>';}
				?>
				</col>
				<col />
				<col width="120px">
				</col>
				<col />
			</colgroup>
			<tr>
				<th>소속코드</th>
				<td><label name='code_breadcrumbs'></label><label name='code'></label>
					<input type="button" class="btn btn-small" id='add_sub_account' value="하위계정추가" />
					<input type="button" class="btn btn-small" id='delete_account' value="현재계정삭제" />
					<label name='sub_account_title'></label>
				</td>
			</tr>
			<tr>
				<th>소속계정</th>
				<td><label name='title_breadcrumbs'></label>
				<input type=text name='title_owner'>
				(고유명:<input type=text name='title' style="width:150px">)
				</td>
			</tr>

			<tr>
				<th>사용</th>
				<td>
					<input type="radio" name="use" value='Y'>사용
					<input type="radio" name="use" value='N'>사용안함
				</td>
			</tr>
			<tr>
				<th>계정과목설명</th>
				<td><textarea rows="3" name='description' style="width:100%;"></textarea></td>
			</tr>

			<tr>
				<th>분류</th>
				<td>
				<ul class="nav nav-tabs" data-tabs="tabs">
					<?php
						foreach ($kind as $key_name=>$kind_title) {
							if ($key_name == 'income') $active = 'class="active"';
							else  $active = '';
							echo '<li '.$active.'>';
							echo '<a data-toggle="tab" data-target="#'.$key_name.'" href="#'.$key_name.'">'.$kind_title.'</a>';
							echo '</li>';
						}
					?>
				</ul>
				<div class="tab-content">
					<?php
						foreach ($kind as $key_name=>$kind_title) {
							if ($key_name == 'income') $active = 'active';
							else  $active = '';
					?>
							<script>
							  $(document).ready(function() {
								<?php foreach ($relation_name as $rel_key=>$rel_title) {
									if ($rel_key == 'target') continue;
								?>
									$('a.<?php echo $key_name; ?>_<?php echo $rel_key; ?>').click(function(){
										var data_text = $("input[name='<?php echo $key_name; ?>_<?php echo $rel_key; ?>']");
										if (data_text.attr('value') == '') {
											alert('계정을 선택하세요.');
											exit;
										}
										var exist_value = $("input[name='<?php echo $key_name; ?>[<?php echo $rel_key; ?>]']");
										$.each(exist_value,function(item){
											alert(item);
										});
							
										var addtext = '<span style="display:inline-block"><a href="javascript:;" class="<?php echo $key_name; ?>_<?php echo $rel_key; ?>-minuspack" style="color:#ff0000; font-weight:bold;">X</a>  <input type="text" name="<?php echo $key_name; ?>[<?php echo $rel_key; ?>][]" value="'+data_text.attr('value')+'" style="width:180px; border:none;" class="packtext" ></span>';
										data_text.removeAttr('value');
										$(this).closest('td').append(addtext);
									});
									
									$('td').on("click","a.<?php echo $key_name; ?>_<?php echo $rel_key; ?>-minuspack",function(){
										$(this).next('input').remove();
										//$(this).prev('br').remove();
										$(this).remove();
									});
								
								<?php } ?>
							  });
							</script>


						<div class="tab-pane <?php echo $active; ?>" id="<?php echo $key_name; ?>">
							<table class="table">
								<colgroup>
									<col width="182px">
									</col>
									<col>
									</col>
								</colgroup>
								<tr> 
									<th>해당여부</th>
									<td>
										<input type="radio" name="account_option[<?php echo $key_name; ?>][use]" value="Y">
										해당
										<input type="radio" name="account_option[<?php echo $key_name; ?>][use]" value="N">
										해당없음</td>
								</tr>
								<tr>
									<th>차대구분</th>
									<td>
										<input type="radio" name="account_option[<?php echo $key_name; ?>][dc]" value="debit">
										차변
										<input type="radio" name="account_option[<?php echo $key_name; ?>][dc]" value="credit">
										대변</td>
								</tr>
								
								<tr>
									<th>회계분류</th>
									<td>
										<input type="radio" name="account_option[<?php echo $key_name; ?>][group]" value="purpose">목적사업회계
										<input type="radio" name="account_option[<?php echo $key_name; ?>][group]" value="income">수익사업회계
										<input type="radio" name="account_option[<?php echo $key_name; ?>][group]" value="N">해당분류 없음
										</td>
								</tr>
								<tr>
									<th>대상</th>
									<td>
										<?php foreach ($this->config->item('target') as $target_key=>$target_value) {
											//echo '<input type="checkbox" name="account_option['.$key_name.'][target]" value="'.$target_key.'" />'.$target_value.' ';
											echo '<input type="checkbox" name="'.$key_name.'[target][]" value="'.$target_key.'" />'.$target_value.' ';
										} ?>
									</td>
								</tr>




	<?php foreach ($relation_name as $rel_key=>$rel_title) {
		if ($rel_key == 'target') continue;
		if ($rel_key == 'summary') $onclick = '';
		else $onclick = 'onclick=\'window.open("/popup/account_list?frm_name='.$key_name.'_'.$rel_key.'&max_depth='.$max_depth.'", "pop", "width=900, height=600, scrollbars=yes, status=yes"); return false;\' readonly placeholder="여기를 클릭하세요."';
	?>
	<tr>
		<th><?php echo $rel_title; ?></th>
		<td><input type="text" name='<?php echo $key_name; ?>_<?php echo $rel_key; ?>' style="width:180px" class="packtext" <?php echo $onclick; ?>> <a href="javascript:;" class="btn btn-small btn-info <?php echo $key_name; ?>_<?php echo $rel_key; ?>">추가</a><br />
	<?php 
	//echo $form_values[$key_name][$rel_key];
	if (is_array($account_relation[$key_name][$rel_key])) {
		foreach ($account_relation[$key_name][$rel_key] as $value) {
			if ($value == '') continue;
		echo '<span style="display:inline-block"><a href="javascript:;" class="'.$key_name.'_'.$rel_key.'-minuspack" style="color:#ff0000; font-weight:bold;">X</a><input type="text" name="'.$key_name.'['.$rel_key.'][]" value="'.$value.'" style="width:180px; border:none;" class="packtext" ></span>';
		}
	}
	?>

			
		</td>
	</tr>

	<?php } ?>

								
							</table>
						</div>
					<?php
						}
					?>

				</div></td>
			</tr>
			

		</table>
		<div class="buttonline"><input type="submit" class="btn btn-primary" value="저장" /></div>
		<?php echo form_close(); ?>
	</div>
</div>
<br><br><br><br>

<script>
var speed=0;	//click 이벤트 발생이 안하는 에러를 없애기 위해 선언함 값, 어디선가 쓰이고 있음.

	//하위계정 추가버튼  숨기기
	$('#add_sub_account').hide();
	$('#delete_account').hide();

	function init_form_value() {
		$("input[name='ano']").val('');
		$("input[name='pano']").val('');
		$("input[name='code']").val('');
		$("input[name='depth']").val('');
		$("input[name='has_children']").val('');
		$("label[name='title_breadcrumbs']").text('');
		$("label[name='code_breadcrumbs']").text('');

		$("label[name='code']").text('');
		$("input[name='title']").val('');
		$("input[name='title_owner']").val('');
		
		$("input[name='use']").parents().removeClass("checked");
		$("textarea[name='description']").val('');
		
		<?php foreach ($this->config->item('account_kind') as $key=>$value) { ?>
			$("input[name='account_option[<?php echo $key; ?>][use]']").parents().removeClass("checked");
			$("input[name='account_option[<?php echo $key; ?>][dc]']").parents().removeClass("checked");
			$("input[name='account_option[<?php echo $key; ?>][group]']").parents().removeClass("checked");
			$("input[name='account_option[<?php echo $key; ?>][target]']").parents().removeClass("checked");
			
			$(".<?php echo $key; ?>_bundle-minuspack").trigger('click');
			$(".<?php echo $key; ?>_contra-minuspack").trigger('click');
			$(".<?php echo $key; ?>_contra_bundle-minuspack").trigger('click');
			$(".<?php echo $key; ?>_summary-minuspack").trigger('click');
		<?php } ?>
	}
</script>		

<script>
	//계정의 분류 선택시, 해당 계정목록 셋팅
	$('label').live('click', function() {
		$this = $(this);
		//console.log($this.attr('ano'));
		
		//분류를 선택했을 때 해당 값들 셋팅
		if ($this.attr('ano') !='') {
			//alert('account_kind clicked');
			//$("select[name='account_no']").empty();
			
			//입력폼 초기화
			init_form_value();
			
			$.ajax({
				url:'/json_data/account_info', 
				data:{
					'ano':$this.attr('ano')
					},
				dataType:'json',
				success:function(result){
					for(name in result){
						if (name == 'basic') {
							$("input[name='ano']").val(result[name]['ano']);
							$("input[name='pano']").val(result[name]['pano']);
							$("input[name='code']").val(result[name]['code']);
							$("input[name='depth']").val(result[name]['depth']);
							$("input[name='has_children']").val(result[name]['has_children']);
							$("input[name='weight']").val(result[name]['weight']);
							
							$("label[name='title_breadcrumbs']").text(result[name]['title_breadcrumbs']);
							$("label[name='code_breadcrumbs']").text(result[name]['code_breadcrumbs']);

							$("label[name='code']").text(result[name]['code']);
							$("input[name='title']").val(result[name]['title']);
							$("input[name='title_owner']").val(result[name]['title_owner']);
							
							$("input[name='use'][value='"+result[name]['use']+"']").attr("checked", "checked").parents().addClass("checked");
							$("textarea[name='description']").val(result[name]['description']);
							
						} else if (name == 'option') {
							for(kind in result[name]){
								//console.log(kind+', '+result[name][kind]['use']);
								//account_option[income][use]
								$("input[name='account_option["+kind+"][use]'][value='"+result[name][kind]['use']+"']").attr("checked", "checked").parents().addClass("checked");
								$("input[name='account_option["+kind+"][dc]'][value='"+result[name][kind]['dc']+"']").attr("checked", "checked").parents().addClass("checked");
								$("input[name='account_option["+kind+"][group]'][value='"+result[name][kind]['group']+"']").attr("checked", "checked").parents().addClass("checked");
								$("input[name='account_option["+kind+"][target]'][value='"+result[name][kind]['target']+"']").attr("checked", "checked").parents().addClass("checked");
							}
							
						} else if (name == 'relation') {
							for(kind in result[name]){
								for(type in result[name][kind]){ //type : 묶음, 상대, 상대묶음계정
									//console.log(kind+', '+type+', '+result[name][kind][kind2]);
									if (type == 'contra_name' || type == 'bundle_name' || type == 'contra_bundle_name') continue;
									
									for(idx in result[name][kind][type]){ //type : 묶음, 상대, 상대묶음계정
										//console.log(kind+', '+type+', '+result[name][kind][type][idx]);
										
										var value = result[name][kind][type][idx] + '|' + result[name][kind][type+'_name'][idx];

										var addtext = '<span style="display:inline-block"><a href="javascript:;" class="'+kind+'_'+type+'-minuspack" style="color:#ff0000; font-weight:bold;">X</a>  <input type="text" name="'+kind+'['+type+'][]" value="'+value+'" style="width:180px; border:none;" class="packtext" ></span>';
										$("input[name='"+kind+"_"+type+"']").closest('td').append(addtext);
									}
								}
							}
						} else if (name == 'summary') {
							for(kind in result[name]){
								for(idx in result[name][kind]){ //type : 묶음, 상대, 상대묶음계정
									//console.log(kind+', '+idx+', '+result[name][kind][idx]);
									var addtext = '<span style="display:inline-block"><a href="javascript:;" class="'+kind+'_summary-minuspack" style="color:#ff0000; font-weight:bold;">X</a>  <input type="text" name="'+kind+'[summary][]" value="'+result[name][kind][idx]+'" style="width:180px; border:none;" class="packtext" ></span>';
									$("input[name='"+kind+"_summary']").closest('td').append(addtext);
								}
							}
						} else if (name == 'target') {
							for(kind in result[name]){
								for(idx in result[name][kind]){ //type : 대상
									//console.log(kind+', '+idx+', '+result[name][kind][idx]);
									$("input[name='"+kind+"[target][]'][value='"+result[name][kind][idx]+"']").attr("checked", "checked").parents().addClass("checked");
								}
							}
						}
					}
					$('#add_sub_account').show();
					$('#delete_account').show();
					$("input[name='title_owner']").focus();
				},
				error:function(msg)
				{
					alert('결과값을 가져오는데 실패했습니다.');
					//alert(msg.responseText);
				}
			});
		}
		/*
		
		*/
	});

	//하위계정추가 선택시, 해당 계정목록 셋팅
	$('#add_sub_account').on('click', function() {
		$this = $(this);
		
		$this.hide();
		$('#delete_account').hide();
		var ano = $("input[name='ano']").val();
		var pano = $("input[name='pano']").val();
		var code = $("input[name='code']").val();
		var depth = $("input[name='depth']").val();
		var has_children = $("input[name='has_children']").val();
		var title_owner = $("input[name='title_owner']").val();
		//console.log('has_children : ' + has_children);
		
		$("label[name='sub_account_title']").text(' ("'+title_owner + '"의 하위 계정을 추가를 시작 합니다.)');
		
		//입력폼 초기화
		init_form_value();
		
		$.ajax({
			url:'/json_data/child_account_info', 
			data:{
				'ano':ano,
				'pano':pano,
				'code':code,
				'depth':depth,
				'has_children':has_children
				},
			dataType:'json',
			success:function(result){
				$("input[name='ano']").val(result['ano']);
				$("input[name='pano']").val(result['pano']);
				$("input[name='code']").val(result['code']);
				$("input[name='depth']").val(result['depth']);
				$("input[name='weight']").val(result['weight']);
				$("input[name='has_children']").val('0');
				
				$("label[name='title_breadcrumbs']").text(result['title_breadcrumbs']);
				$("label[name='code_breadcrumbs']").text(result['code_breadcrumbs']);

				$("label[name='code']").text(result['code']);
				$("input[name='title_owner']").focus();
				
				/*
				console.log('ano : ' + result['ano']);
				console.log('pano : ' + result['pano']);
				console.log('code : ' + result['code']);
				console.log('depth : ' + result['depth']);
				console.log('weight : ' + result['weight']);
				*/
			},
			error:function(msg) {
				alert('하위 계정의 초기값을 생성하는데 실패했습니다.');
				//alert(msg.responseText);
			}
		});
	});

	//계정삭제
	$('#delete_account').on('click', function() {
		$this = $(this);
		
		var ano = $("input[name='ano']").val();
		var pano = $("input[name='pano']").val();
		var depth = $("input[name='depth']").val();
		var weight = $("input[name='weight']").val();
		var has_children = $("input[name='has_children']").val();
		var title_owner = $("input[name='title_owner']").val();
		
		if (has_children == 1) {
			alert('"' + title_owner+'"의 하위 계정이 있으므로 삭제할 수 없습니다.');
		} else {
			$.ajax({
				url:'/json_data/delete_account', 
				data:{
					'ano':ano,
					'pano':pano,
					'depth':depth,
					'weight':weight,
					'has_children':has_children
					},
				dataType:'json',
				success:function(result){
					alert('"' + title_owner+'"을(를) 삭제하였습니다.');
					$("label[ano='"+ano+"']").remove();
				},
				error:function(msg) {
					alert('"' + title_owner+'"을(를) 삭제하는데 실패했습니다.');
					//alert(msg.responseText);
				}
			});
		}
		
		//console.log('ano : ' + ano);
		//console.log('has_children : ' + has_children);
	});

	
</script>
