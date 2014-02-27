<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
if ( ! function_exists('show_account')){
	/*
	 * type - '':기본링크, popup:opener로 값 넘김
	 */
	//계정과목을 계층적으로 보이기 
    function show_account($accounts, $pano, $depth, $type='', $frm_name=''){
    	
		//$pano에 속하는 첫번째 계정의 자식계정이 자식이 있는 지 여부를 확인한다.
		$next_is_last = false;
				
		//1. $pano에 속하는 첫번째 계정
		$first_account = current($accounts[$pano]);
		
		//2. 자식계정 중 첫번째 자식계정 
		$sub_account = current($accounts[$first_account['ano']]);
  	
		//자식 계정 밑으로 더이상 자식 계정이 없으면 
		$depth_org = $depth;	//넘어온 depth 값 보관
		if (!array_key_exists($sub_account['ano'], $accounts)) {
			$next_is_last = true;
			$depth=3;
		}
    	?>
		<table class="ta<?php echo $depth; ?>">
		<?php 
		foreach ($accounts[$pano] as $sub_ano => $sub_entry) {
			if ($type == 'popup') {
				$href_link = 'href="#" onclick="opener.account_form.'.$frm_name.'.value = \''.$sub_entry['title'].'\';self.close();" ';
			} else {
				$href_link = 'href="/basis_info/account_category/?ano='.$sub_ano.'"';
			}
		?>
			<tr>
				<th><a <?php echo $href_link; ?>><?php echo $sub_entry['title']; ?></a><br>(<?php echo $sub_entry['code']; ?>)</th>
				<td>
					<?php
						if (!$next_is_last) show_account($accounts, $sub_ano, $depth+1, $type, $frm_name);
						else {
							//마지막 depth의 계정 출력
					?>
							<table class="ta4">
								<tr>
									<?php if ($depth_org==2) {echo '<th> </th>';} ?>
									<td>
											<?php
											foreach ($accounts[$sub_ano] as $last_ano => $last_entry) {
			if ($type == 'popup') {
				$href_link = 'href="#" onclick="opener.account_form.'.$frm_name.'.value = \''.$last_entry['title'].'\';self.close();" ';
			} else {
				$href_link = 'href="/basis_info/account_category/?ano='.$last_ano.'"';
			}
												
											?>
											<span style='display:inline-block'>(<?php echo $last_entry['code']; ?>)<a <?php echo $href_link; ?>><?php echo $last_entry['title']; ?></a>&nbsp;</span>
											<?php } ?>
									</td>
								</tr>
							</table>
					<?php
						}
					?>
				</td>
			</tr>
		<?php
		}
		echo '</table>';
	}
}
 

//임시사용
if ( ! function_exists('view_subaccount')){
	//계정과목을 계층적으로 보이기 
    function view_subaccount($accounts, $pano){
		//var_dump($accounts);
		
		echo '<table border=1>';
		
		//for ($a=0; $a<count($accounts[$pano]); $a++) {
		foreach ($accounts[$pano] as $sub_mid => $sub_entry) {
			//$sub_entry = $accounts[$pano][$a];
			?>
			<tr>
				<td>
					<table>
						<tr>
							<td>(<?php echo $sub_entry['code']; ?>)<a href="/account/add/<?php echo $sub_entry['ano']; ?>"><?php echo $sub_entry['title']; ?></a></td>
							<td>
								<?php
									if ($sub_entry['has_children'] == 1) view_subaccount($accounts, $sub_entry['ano']);
								?>
							</td>
						</tr>
					</table>
				</td>

			<?php if ($sub_entry['has_children'] == 1) { ?>
			</tr>
			<tr>
			<?php } ?>
				<td>
					
				<?php
					$attributes = array('class' => 'form-horizontal');
					echo form_open('/account/add', $attributes);
					
					//부모가 같은 계정의 순서
					//새로 생성할 계정의 서브코드
					if ($sub_entry['has_children'] == 0) {
						$weight = 1;
						$sub_code = 65;	//'A'
					} else {
						$weight = count($accounts[$sub_entry['ano']])+1;
						$sub_code = 65+$weight-1;
						if ($weight>26) $sub_code = 97+$weight-26-1;
					} 
					
					//새로 생성할 계정의 코드
					$code = $sub_entry['code'].chr($sub_code);
			
					$data = array('pano' => $sub_entry['ano'], 'has_children' => $sub_entry['has_children'], 'depth' => $sub_entry['depth']+1, 'weight' => $weight);
					echo form_hidden($data);
					?>
					<input type="text" id="title" name="title" placeholder='<?php echo $sub_entry['title']; ?> 의 자식계정과목'>
					<input type="text" id="code" name="code" value="<?php echo $code; ?>">
					<input type="submit" class="btn btn-primary" value="입력" />
					<?php echo form_close(); ?>		
				</td>
			</tr>
		<?php
		}
		echo '</table>';		
    }
}