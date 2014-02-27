<?php
//var_dump();	//계정과목 분류

?>
<style>
	#Forms {position:absolute; left:543px; top:253px; height:28px; width:200px;}
</style>

	<style>
	.ui-autocomplete-loading {
		background: white url('/wwf/images/ui-anim_basic_16x16.gif') right center no-repeat;
	}
	#city { width: 25em; }
	</style>

<div class="contentInner">
	<div class="row-fluid">

		<div class="title">

			<div class="text">
				<blockquote>
					<h3>거래입력</h3>
				</blockquote>
			</div>

			<div class="appbtn">
				<li>
					<div class="iconbox"><img src="/wwf/img/icons/14x14/printer1.png" alt="프린트">
					</div>
				</li>
				<li>
					<div class="iconbox"><img src="/wwf/img/icons/14x14/upload1.png" alt="엑셀다운">
					</div>
				</li>
			</div>
			<div class="clear"></div>

		</div>


		<?php
		$attributes = array('name'=>'account_form','class' => 'form-horizontal');
		echo form_open('/basis_info/account_category', $attributes);

		$hidden_data = array();
		//$hidden_data = array('mode'=> 'update', 'ano' => $account_basic['ano'], 'pano' => $account_basic['pano'], 'has_children' => $account_basic['has_children'], 'depth' => $account_basic['depth'], 'weight' => $account_basic['weight']);
		echo form_hidden($hidden_data);
		?>

		<div class="table_top" >		
			<div class="outputlist" style="margin-bottom:20px;">
			<a href="#" class="btn btn-large btn-success">복합분개마법사</a>
			<a href="#" class="btn btn-large btn-primary">고유목적사업비 설정마법사</a>
			<a href="#" class="btn btn-large btn-warning">기금출연</a>
			</div>
		</div>

		<table class="table">
			<colgroup>
				<col width="80px">
				</col>
				<col>
				</col>
			</colgroup>
			<tbody>
				<tr>
					<th>분류</th>
					<td>
						<ul>
						<?php foreach ($this->config->item('account_kind') as $key => $title) { ?>
						<li><input type="radio" name='account_kind' id='account_kind' value='<?php echo $key; ?>' /><?php echo $title; ?></li>
						<?php } ?>
						</ul>
					</td>
					<th>날짜</th>
					<td>
					<input type="text" style="width:120px" id="datepicker" >
					</td>
				</tr>
				<tr>
					<th>대상</th>
					<td colspan="3">
					<ul class="tabs">
						<li id="tab-1">
							<label for="tab1">
								<input type="radio" name="target" id="target" value="employee" checked />
								사원</label>
						</li>
						<li id="tab-2">
							<label for="tab2">
								<input type="radio" name="target" id="target" value="customer" />
								거래처</label>
						</li>
						<li id="tab-3">
							<label for="tab3">
								<input type="radio" name="target" id="target" value="internalstaff" />
								복지기금내부직원</label>
						</li>
					</ul>
					<div class="tab_container">
						<input type="text" name='target_id' id='target_id' style="width:120px" readonly placeholder=""> 
						<input type="text" name='target_name' id='target_name' style="width:250px" placeholder="이름이나 거래처명을 2글자 이상 입력하세요.">
					</div>
					</td>
				</tr>
				<tr>
					<th>계정</th>
					<td colspan="3">
						<ul>
							<li><select name='account' style="width:100px"></select></li>
							<li> 묶음계정 <select name='account_bundle' style="width:100px"></select></li>
							
							<li>적요 : 
							<select name='account_summary' style="width:100px"></select>
							<div id="Forms">
								<div style="display:none;">
									<input type="text" name='account_summary_direct' style="width:200px;"  /> 
								</div>
							</div>
							</li>
						</ul>
					</td>
				</tr>

				
				<tr>
					<th>부가세</th>
					<td colspan="3">							
							<label><input type="radio">적용 </label>
							<label><input type="radio">무시 </label>
							<label><input type="radio">면세 </label>
					</td>
					</tr>

					<tr>
					<th>회계분류</th>
					<td colspan="3"><label><input type="radio">목적사업회계 </label> <label><input type="radio">기금관리회계 </label></td>
					</tr>
					<tr>
					<th>메모</th>
					<td  colspan="3"><textarea  style="width:100%" > </textarea>
					</td>
				</tr>

			</tbody>
		</table>


		<div class="table_top">
			<div class="clear"></div>
			<div class="table_left" style="width:49%;">
				<table class="table">
					<thead>
						<tr>
							<th colspan="4">차변</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th>계정과목</th>
							<th>금액</th>
							<th>거래처</th>
							<th>적요</th>
						</tr>
						<tr>
							<td style="padding:9px 4px 9px 15px;">의료비</td>
							<td><input type="text" style="width:100px" /></td>
							<td>99</td>
							<td></td>
						</tr>
						<tr>
							<td style="padding:9px 4px 9px 15px;">건강진단</td>
							<td><input type="text" style="width:100px" /></td>
							<td>20</td>
							<td></td>
						</tr>
					</tbody>
					<thead>
						<tr>
							<th colspan="4">차변합계 : \1,000,000</th>
						</tr>
					</thead>

				</table>

			</div>

			<div class="table_right" style="width:49%;">
				<table class="table">
					<thead>
						<tr>
							<th colspan="4">대변</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th>계정과목</th>
							<th>금액</th>
							<th>거래처</th>
							<th>적요</th>
						</tr>
						<tr>
							<td>
							<select style="width:120px">
								<option>계정과목</option>
							</select></td>
							<td><input type="text" style="width:100px" /></td>
							<td>99</td>
							<td></td>
						</tr>
						<tr>
							<td>
							<select style="width:120px">
								<option>계정과목</option>
							</select></td>
							<td><input type="text" style="width:100px" /></td>
							<td>20</td>
							<td></td>
						</tr>
					</tbody>
					<thead>
						<tr>
							<th colspan="4">대변합계 : \1,000,000</th>
						</tr>
					</thead>

				</table>

			</div>

		</div>



		<table class="table">
			<colgroup>
				<col width="80px">
				</col>
				<col>
				</col>
				<col width="80px">
				</col>
				<col>
				</col>

			</colgroup>
			<tbody>
				<tr>
					<th>증빙</th>
					<td colspan="3"><label>
						<input type="radio">
						송금 </label><label>
						<input type="radio">
						세금계산서 </label><label>
						<input type="radio">
						카드영수증 </label><label>
						<input type="radio">
						세금(면세) </label><label>
						<input type="radio">
						지출증빙현금영수증 </label><label>
						<input type="radio">
						간이영수증 </label><label>
						<input type="radio">
						소득공제영수증 </label><label>
						<input type="radio">
						해당없음 </label></td>
				</tr>
				<tr>
					<th>회계소속</th>
					<td><label>
						<input type="radio">
						목적사업회계 </label><label>
						<input type="radio">
						기금관리회계 </label></td>
					<th>증빙일자</th>
					<td>
					<input type="text" id="datepicker" />
					</td>

				</tr>
				<tr>
					<th>증빙파일</th>
					<td  colspan="3">
					<input type="text" style="width:180px">
					<a href="#" class="btn btn-small btn-inverse">찾아보기</a><a href="#" class="btn btn-small btn-success">추가</a><a href="#" class="btn btn-small  btn-danger">제거</a></td>
				</tr>

				<tr>
					<th>note</th>
					<td  colspan="3">					<textarea  style="width:100%"> </textarea></td>
				</tr>

			</tbody>
		</table>

		<div class="appbox">
			<a href="#" class="btn btn-large btn-success">저장</a><a href="#" class="btn btn-large btn-primary">동일거래처 계속</a><a href="#" class="btn btn-large btn-warning">동일거래 계속</a><a href="#" class="btn btn-large btn-inverse">자주쓰는거래 등록</a>
		</div>

		<table class="table">
			<thead>
				<tr>
					<th>번호</th>
					<th>전표번호</th>
					<th>차변</th>
					<th>차변금액</th>
					<th>대변</th>
					<th>대변금액</th>
					<th>작성자</th>
					<th>작성일자</th>
					<th>상태</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>1</td>
					<td>20130212</td>
					<td>기업자유예금</td>
					<td>300,000</td>
					<td>지급수수료</td>
					<td>300,000</td>
					<td>류자현</td>
					<td>2013-03-15</td>
					<td>보류</td>
				</tr>
				<tr>
					<td>1</td>
					<td>20130212</td>
					<td>기업자유예금</td>
					<td>300,000</td>
					<td>지급수수료</td>
					<td>300,000</td>
					<td>류자현</td>
					<td>2013-03-15</td>
					<td>보류</td>
				</tr>
			</tbody>

		</table>
		<?php echo form_close(); ?>
	</div>
</div>
</div>
					<script>
					function selectForm(frm){
					  var hiddenForms = document.getElementById("Forms");
					  theForm = hiddenForms.getElementsByTagName("div");
					  for(x=0; x<theForm.length; x++){
						theForm[x].style.display = "none";
					  }
					  if (theForm[frm-1]){
						theForm[frm-1].style.display = "block";
					  }
					}
					</script>		


		<script>
			var xmlhttp;
			if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else {// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			//$("input[name=account_kind]").eq(0).attr("checked", true);
			//$("input[name=account_kind][value=" + resultValue + "]").attr("checked", true);
					
			//계정의 분류 선택시, 해당 계정목록 셋팅
			$('input').live('click', function() {
				$this = $(this);
				//alert($this.attr('name') + ':'+ $this.attr('value'));
				
				if ($this.attr('name') =='account_kind') {
					$("select[name='account']").empty();
					
					//$("input[name=account_kind][value=" + $this.attr('value') + "]").attr("checked", true);
					
					//선택된 값을 셋팅해서 ajax로 넘기기
					var userObj = new Object();
					userObj.account_kind = $this.attr('value');
					userObj.target = $(":radio[name='target']:checked").val();	//대상의 선택된 값
					//var userObj.target = $("input[name='target']:checked").val();
					//alert(userObj.account_kind);
			
					xmlhttp.open("GET", '/json_data/account_list/?data='+JSON.stringify(userObj), true);
					xmlhttp.send();
			
					/*
					var sParam = "data="+JSON.stringify(userObj);
					xmlhttp.open("POST", '/json.php', false);
					//xmlhttp.onreadystatechange = 'getMessage';
					xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
					xmlhttp.send(sParam);
					*/
					
					//ajax를 통해 넘어온 값을 화면에 뿌리기
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							var resp = JSON.parse(xmlhttp.responseText);
							
							//var str = '';
							for(name in resp){
								if (name == 'ano') {
									//$(select).empty();
									for (var i=0; i<resp[name].length; i++) {
										//alert(resp[name][i]);
										$("select[name='account']").append('<option value="'+resp['ano'][i]+'">'+resp['title'][i]+'</option>');
									}
									
									//계정과목의 첫번째 값을 기본으로 선택하고, change 이벤트 강제 발생시켜서  적요등을 셋팅한다.
									//$("#account option:eq(4)").attr("selected", "selected");
									$("select[name='account']").trigger('change');
								}
								//str += '<option>'+name+' : '+$.isArray(resp[name])+'-'+resp[name]+'</li>';
								//str += '<li>'+name+' : '+resp[name]+'</li>';
							}
							//document.getElementByName("account").innerHTML = str;
						}
					}
					//return false;
				}
			});
			
			//계정을 선택했을 때 계정의 정보를 가져와서 셋팅
			$('select').live('change', function() {
				$this = $(this);
				
				if ($this.attr('name') =='account') {
					$("select[name='account_summary']").empty();
					$("select[name='account_bundle']").empty();

					//선택된 계정의 ano
					//var ano = $this.val();
					var ano = $this.attr('value');
					var account_kind = $(":radio[name='account_kind']:checked").val();
					
					//선택된 값을 셋팅해서 ajax로 넘기기
					var userObj = new Object();
					userObj.ano = ano;	//대상의 선택된 값
					userObj.account_kind = account_kind;
					userObj.target = $(":radio[name='target']:checked").val();	//대상의 선택된 값
					
					xmlhttp.open("GET", '/json_data/account_info_by_kind/?data='+JSON.stringify(userObj), true);
					xmlhttp.send();

					//ajax를 통해 넘어온 값을 화면에 뿌리기
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							var resp = JSON.parse(xmlhttp.responseText);
							
							//var str = '';
							for(name in resp){
								if (name == 'summary') {
									for (var i=0; i<resp[name].length; i++) {
										//alert(resp[name][i]);
										$("select[name='account_summary']").append('<option value="'+resp['summary'][i]+'">'+resp['summary'][i]+'</option>');
									}
									$("select[name='account_summary']").append('<option value="직접입력">직접입력</option>');
									
								}
								else if (name == 'bundle') {
									for (var i=0; i<resp[name].length; i++) {
										$("select[name='account_bundle']").append('<option value="'+resp['bundle'][i]+'">'+resp['bundle_name'][i]+'</option>');
									}
								}
							}
							//document.getElementByName("account_bundle").innerHTML = str;
						}
					}
					//return false;
				} else if ($this.attr('name') =='account_summary') {
					//직접입력 text box 보이기, 숨기기
					var hiddenForms = document.getElementById("Forms");
					theForm = hiddenForms.getElementsByTagName("div");
					if ($this.attr('value')=='직접입력') {
						if (theForm[0]){
							theForm[0].style.display = "block";
						}
					} else {
						for(x=0; x<theForm.length; x++){
							theForm[x].style.display = "none";
						}
					}
				}
			});
		</script> 

		<!--autocomplete-->
		<script>
		
			//$(function() {
			$('input').live('keyup', function() {
				$( "#target_id" ).val('');
				$this = $(this);
				//alert($this.attr('name') + ':'+ $this.attr('value'));
				
				if ($this.attr('name') =='target_name') {
				
					var query_string = 'target='+($(":radio[name='target']:checked").val());
	
					function log( message ) {
						$( "#target_id" ).val(message );	//id
						$( "#target_name" ).val(message );	//value
					}
			
					$( "#target_name" ).autocomplete({
						source: "/json_data/target_info?"+query_string,
						minLength: 2,
						select: function( event, ui ) {
							log(
								ui.item ?  ui.item.id : + ui.item.value
			 				);
						}
						//, error: function(){ alert('11');  alert('22'); }
					});
				}
			});
		</script>
		
	</script>
		 
