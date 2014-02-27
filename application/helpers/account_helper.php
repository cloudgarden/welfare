<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
if ( ! function_exists('show_account')){
	/*
	 * type - '':기본링크, popup:opener로 값 넘김
	 */
	//계정과목을 계층적으로 보이기 
    function show_account($accounts, $pano, $depth, $max_depth, $type='', $frm_name=''){
    	$ta = $depth;
		if (!array_key_exists($pano, $accounts)) $ta = $max_depth;
   	?>
	<?php 
		foreach ($accounts[$pano] as $ano => $entry) {
				//log_message('error', $ano);
				if ($type == 'popup') {
					$href_link = '<a href="#" onclick="opener.account_form.'.$frm_name.'.value = \''.$entry['ano'].'|'.$entry['title_owner'].'\';self.close();" >';
					$href_link_end = '</a>';
				} else {
					//$href_link = '<a href="/basis_info/account_category/?ano='.$ano.'">';
					$href_link = '<label ano='.$ano.'>';
					$href_link_end = '</label>';
				}
			if (array_key_exists($ano, $accounts)) {
			?>
				<table class="ta<?php echo $ta; ?>">
					<tr>
						<th><?php echo $href_link; ?><?php echo $entry['title_owner']; ?>(<?php echo $entry['code']; ?>)<?php echo $href_link_end; ?></th>
						<td>
							<?php
								show_account($accounts, $ano, $depth+1, $max_depth, $type, $frm_name);
							?>
						</td>
					</tr>
				</table>
			<?php
			}
			else {
			?>
				<?php
				//for ($i=$max_depth-$depth; $i<$max_depth; $i++) {
				//	echo '<td> </td>';
				//}
				?>
					<span style='display:inline-block'><?php echo $href_link; ?>(<?php echo $entry['code']; ?>)<?php echo $entry['title_owner']; ?><?php echo $href_link_end; ?></span>
			<?php
			}
		} // end of foreach
	} //end of function
}

 
 
/*
 * 합계잔액시산표에서 계정의 계층구조와 차변/대변/잔액 보이기
 * $all_accounts : 계층구조를 가지고 있는 계정목록
 * $sum_list : 차변/대변/잔액 을 가지고 있는 계정목록
 * $first_weight : 보여줄 계정목록중 첫번째 depth인 것
 */
if ( ! function_exists('show_account_in_sisan')){
    function show_account_in_sisan($pano, $accounts, $sum_list, $ol, &$first_weight, &$total){
		//var_dump($accounts);
		//total_business_profit:사업충이익, business_profit:사업이익, tax_profit:법인세차감전순이익, this_profit:당기순이익
		foreach ($accounts[$pano] as $ano => $entry) {
			if (in_array($ano, array('total_business_profit', 'business_profit', 'tax_profit'))) continue;
			
			$depth = $entry['depth'];
			$weight = $entry['weight']-1;
			if ($depth == 2) {
				$weight = $first_weight;
				$first_weight++;
				
				//새로 추가한 계정은 전체합계에서 제외
				if (!in_array($ano, array('total_business_profit', 'business_profit', 'tax_profit', 'this_profit'))) {
					$total[0] += $sum_list[$ano]['debit_money'];
					$total[1] += $sum_list[$ano]['debit_balance'];
					$total[2] += $sum_list[$ano]['credit_money'];
					$total[3] += $sum_list[$ano]['credit_balance'];
				}
			}
			
			if ($depth > 1) {
				$depth_str = str_repeat('&nbsp&nbsp;', $depth);
				//$title = $ol[$depth-1][$weight].'. '.$entry['title_owner'];
				
				$title = $ol[$depth-1][$weight];
				if ($depth < 4) $title .= '.';	//()숫자와 원문자에서 ',' 제외
				$title .= ' '.$entry['title_owner'];
				if ($depth == 1) $title = '<b>'.$title.'</b>';

				//$debit_money = $credit_money = $debit_balance = $credit_balance = 0;
				//if (array_key_exists($ano, $sum_list)) {
				//}
				$debit_money = ($sum_list[$ano]['debit_money'] == 0) ? '-' : number_format($sum_list[$ano]['debit_money']);
				$credit_money = ($sum_list[$ano]['credit_money'] == 0) ? '-' : number_format($sum_list[$ano]['credit_money']);
				$debit_balance = ($sum_list[$ano]['debit_balance'] == 0) ? '-' : number_format($sum_list[$ano]['debit_balance']);
				$credit_balance = ($sum_list[$ano]['credit_balance'] == 0) ? '-' : number_format($sum_list[$ano]['credit_balance']);
			?>
				<tr>
					<td><?php echo $debit_money; ?></td>
					<td><?php echo $debit_balance; ?></td>
					<td><?php echo $depth_str; ?><?php echo $title; ?></td>
					<td><?php echo $credit_balance; ?></td>
					<td><?php echo $credit_money; ?></td>
				</tr>
		<?php
			}
			if (array_key_exists($ano, $accounts))
				show_account_in_sisan($ano, $accounts, $sum_list, $ol, $first_weight, $total);
		}
    }
}

/*
 * 합계잔액시산표에서 계정의 계층구조와 차변/대변/잔액 보이기
 * $all_accounts : 계층구조를 가지고 있는 계정목록
 * $sum_list : 차변/대변/잔액 을 가지고 있는 계정목록
 */
if ( ! function_exists('show_account_in_jaemusang')){
    function show_account_in_jaemusang($pano, $accounts, $prev_sum_list, $sum_list, $ol, $kind=''){
		//var_dump($sum_list);
		
		foreach ($accounts[$pano] as $ano => $entry) {
			$depth = $entry['depth'];
			$weight = $entry['weight'];
			
			if ($depth > 0) {
				$depth_str = str_repeat('&nbsp', $depth-1);
				if ($depth==1) {
					$title = $entry['title_owner'];
					if (is_numeric($ano))
						$title = '['.$entry['title_owner'].']';
				} else if ($depth>1)
					$title = $ol[$depth-1][$weight-1].'. '.$entry['title_owner'];

				if ($depth < 3) $title = '<b>'.$title.'</b>';
				
				$prev_balance = $purpose_balance = $income_balance = $N_balance = $total_balance = $increase_balance = $increase_rate = 0;
				if (array_key_exists($ano, $prev_sum_list)) {
					//$prev_balance = $prev_sum_list[$ano]['debit_balance'] + $prev_sum_list[$ano]['credit_balance'];
					$prev_balance = $prev_sum_list[$ano]['total_money'];
				}
				if (array_key_exists($ano, $sum_list)) {
					$purpose_balance = $sum_list[$ano]['purpose']['debit_balance'] + $sum_list[$ano]['purpose']['credit_balance'];
					$income_balance = $sum_list[$ano]['income']['debit_balance'] + $sum_list[$ano]['income']['credit_balance'];
					$N_balance = $sum_list[$ano]['N']['debit_balance'] + $sum_list[$ano]['N']['credit_balance'];
					$total_balance = $purpose_balance + $income_balance;
					$increase_balance = $total_balance - $prev_balance;	//증감액
	
					if ($prev_balance>0)
						$increase_rate = $increase_balance*100 / $prev_balance;	//증감율
				}
				
				$prev_balance = ($prev_balance == 0) ? '-' : number_format($prev_balance);
				$purpose_balance = ($purpose_balance == 0) ? '-' : number_format($purpose_balance);
				$income_balance = ($income_balance == 0) ? '-' : number_format($income_balance);
				$total_balance = ($total_balance == 0) ? '-' : number_format($total_balance);
				$increase_balance = ($increase_balance == 0) ? '-' : number_format($increase_balance);
				$increase_rate = ($increase_rate == 0) ? '-' : number_format($increase_rate, 2).'%';
				
				//기초자료입력인 경우는 $prev_sum_list와 $sum_list 가 없다.
				//if (count($prev_sum_list) != 0 && count($sum_list) != 0) {
				if (count($sum_list) != 0) {
			?>
					<tr>
						<td><?php echo $depth_str; ?><?php echo $title; ?></td>
			<?php
					if ($depth == 1 && is_numeric($ano))
						echo '<td></td><td></td><td></td><td></td><td></td><td></td>';
					else {
			?>
						<td><?php echo $prev_balance; ?></td>
						<td><?php echo $purpose_balance; ?></td>
						<td><?php echo $income_balance; ?></td>
						<td><?php echo $total_balance; ?>
							<?php if ($N_balance>0) echo '('.$N_balance.')';?>
						</td>
						<td><?php echo $increase_balance; ?></td>
						<td><?php echo $increase_rate; ?></td>
			<?php
					}
					echo '</tr>';
				}
				//기초자료입력인 경우 data를 집접 입력할 수 있도록 한다.
				else {
					$temp=0;
					show_account_in_inputbalance($ano, $entry, $ol, $temp, $kind);
				
				}
			}
			if (array_key_exists($ano, $accounts))
				show_account_in_jaemusang($ano, $accounts, $prev_sum_list, $sum_list, $ol, $kind);
		}
    }
}


/*
 * 손익계산서 계정의 계층구조와 차변/대변/잔액 보이기
 * $all_accounts : 계층구조를 가지고 있는 계정목록
 * $sum_list : 차변/대변/잔액 을 가지고 있는 계정목록
 */
if ( ! function_exists('show_account_in_sonik')){
    function show_account_in_sonik($pano, $accounts, $prev_sum_list, $sum_list, $ol, &$first_weight, $kind=''){
		//var_dump($accounts);
		
		foreach ($accounts[$pano] as $ano => $entry) {
			//if ($pano == 87 || $ano == 87) continue;	//법인세비용 전까지만 출력
			$depth = $entry['depth'];
			$weight = $entry['weight'];
			if ($depth == 2) {
				$weight = $first_weight;
				$first_weight++;
			}

			//if ($depth > 0) {
			if ($depth > 1) {
				$depth_str = str_repeat('&nbsp', $depth-1);
				if ($depth==1)
					$title = '['.$entry['title_owner'].']';
				else if ($depth>1)
					$title = $ol[$depth-1][$weight-1].'. '.$entry['title_owner'];

				if ($depth < 3) $title = '<b>'.$title.'</b>';
				
				$prev_balance = $purpose_balance = $income_balance = $N_balance = $total_balance = $increase_balance = $increase_rate = 0;
				if (array_key_exists($ano, $prev_sum_list)) {
					//$prev_balance = $prev_sum_list[$ano]['debit_balance'] + $prev_sum_list[$ano]['credit_balance'];
					$prev_balance = $prev_sum_list[$ano]['total_money'];
				}
				if (array_key_exists($ano, $sum_list)) {
					$purpose_balance = $sum_list[$ano]['purpose']['debit_balance'] + $sum_list[$ano]['purpose']['credit_balance'];
					$income_balance = $sum_list[$ano]['income']['debit_balance'] + $sum_list[$ano]['income']['credit_balance'];
					$N_balance = $sum_list[$ano]['N']['debit_balance'] + $sum_list[$ano]['N']['credit_balance'];
					$total_balance = $purpose_balance + $income_balance;
					$increase_balance = $total_balance - $prev_balance;	//증감액
					
					if ($prev_balance>0)
						$increase_rate = $increase_balance*100 / $prev_balance.'%';	//증감율
				}

				$prev_balance = ($prev_balance == 0) ? '-' : number_format($prev_balance);
				$purpose_balance = ($purpose_balance == 0) ? '-' : number_format($purpose_balance);
				$income_balance = ($income_balance == 0) ? '-' : number_format($income_balance);
				$total_balance = ($total_balance == 0) ? '-' : number_format($total_balance);
				$increase_balance = ($increase_balance == 0) ? '-' : number_format($increase_balance);
				$increase_rate = ($increase_rate == 0) ? '-' : number_format($increase_rate, 2);

				//기초자료입력인 경우는 $prev_sum_list와 $sum_list 가 없다.
				//if (count($prev_sum_list) != 0 && count($sum_list) != 0) {
				if (count($sum_list) != 0) {
				
			?>
			
				<tr>
					<td><?php echo $depth_str; ?><?php echo $title; ?></td>
					<td><?php echo $prev_balance; ?></td>
					<td><?php echo $purpose_balance; ?></td>
					<td><?php echo $income_balance; ?></td>
					<td><?php echo $total_balance; ?>
						<?php if ($N_balance>0) echo '('.$N_balance.')';?>
					</td>
					<td><?php echo $increase_balance; ?></td>
					<td><?php echo $increase_rate; ?></td>
				</tr>
		<?php
				}
				//기초자료입력인 경우 data를 집접 입력할 수 있도록 한다.
				else {
					show_account_in_inputbalance($ano, $entry, $ol, $first_weight, $kind);
				
				}
			}
			if (array_key_exists($ano, $accounts))	//ano가 부모계정일 때만 실행
				show_account_in_sonik($ano, $accounts, $prev_sum_list, $sum_list, $ol, $first_weight, $kind);
		}
    }
}


/*
 * 이익잉여금처분계산서
 * $earned_surplus_acc : 이익잉여금처분계산서 계정목록
 * $accounts : 기초자료로 입력된 계장과목과 금액 
 */
if ( ! function_exists('show_account_in_earned_surplus')){
    function show_account_in_earned_surplus($earned_surplus_acc, $accounts, $ol, &$first_weight){
		//var_dump($accounts);
		
		foreach ($earned_surplus_acc[1] as $ano1 => $entry) {
			$second = each($earned_surplus_acc[2]);//결손금처리계산서 계정
			
			//var_dump($second);
			//echo $second['key'];
			
			$depth1 = $entry['depth'];
			$weight1 = $entry['weight']-1;
			if ($depth1 == 1) {
				$weight1 = $first_weight;
			}

			
			$depth_str1 = str_repeat('&nbsp', $depth1);
			$title1 = $ol[$depth1][$weight1].'. '.$entry['title_owner'];
			if ($depth1 == 1) $title1 = '<b>'.$title1.'</b>';
			
			$total_money1 = $entry['total_money'];
			if (array_key_exists($ano1, $accounts))
				$total_money1 = $accounts[$ano1]['total_money'];
			
			$total_money1 = ($total_money1 == 0) ? '' : number_format($total_money1);
			
			if ($second) {
				$ano2 = $second['key'];
				$depth2 = $second[1]['depth'];
				$weight2 = $second[1]['weight']-1;
				
				if ($depth2 == 1) {
					$weight2 = $first_weight;
				}
				
				$depth_str2 = str_repeat('&nbsp', $depth2);
				$title2 = $ol[$depth2][$weight2].'. '.$second[1]['title_owner'];
				if ($depth2 == 1) $title2 = '<b>'.$title2.'</b>';
				
				$total_money2 = $second[1]['total_money'];
				if (array_key_exists($ano2, $accounts))
					$total_money2 = $accounts[$ano2]['total_money'];
				$total_money2 = ($total_money2 == 0) ? '' : number_format($total_money2);
			} else {
				$depth_str2 = $title2 = '';
			}
			
			if ($depth1 == 1) {
				$first_weight++;
			}

			//기초자료입력인 경우는 $prev_sum_list와 $sum_list 가 없다.
			//if ($this_profit == '') {
				$style1 = 'style="width:120px; text-align:right;"';
			
		?>
		
			<tr>
				<input type='hidden' name='account_no[<?php echo $ano1; ?>]' value='<?php echo $ano1; ?>'>
				<input type='hidden' name='title_owner[<?php echo $ano1; ?>]' value='<?php echo $entry['title_owner']; ?>'>
				<input type='hidden' name='depth[<?php echo $ano1; ?>]' value='<?php echo $entry['depth']; ?>'>
				<input type='hidden' name='weight[<?php echo $ano1; ?>]' value='<?php echo $entry['weight']; ?>'>

				<?php if ($second) { ?>
				<input type='hidden' name='account_no[<?php echo $ano2; ?>]' value='<?php echo $ano2; ?>'>
				<input type='hidden' name='title_owner[<?php echo $ano2; ?>]' value='<?php echo $second[1]['title_owner']; ?>'>
				<input type='hidden' name='depth[<?php echo $ano2; ?>]' value='<?php echo $second[1]['depth']; ?>'>
				<input type='hidden' name='weight[<?php echo $ano2; ?>]' value='<?php echo $second[1]['weight']; ?>'>
				<?php } ?>

				<td><?php echo $depth_str1; ?><?php echo $title1; ?></td>
				<td><input type='text' name='total_money[<?php echo $ano1; ?>]' value='<?php echo $total_money1; ?>' <?php echo $style1; ?>></td>
				<td><?php echo $depth_str2; ?><?php echo $title2; ?></td>
				<td>
					<?php if ($second) { ?>
					<input type='text' name='total_money[<?php echo $ano2; ?>]' value='<?php echo $total_money2; ?>' <?php echo $style1; ?>>
					<?php } ?>
				</td>
			</tr>
		<?php
			//}
			//기초자료입력인 경우 data를 집접 입력할 수 있도록 한다.
			//else {
				//show_account_in_inputbalance($ano1, $entry, $ol, $first_weight);
			
			//}
		}
    }
}


/*
 * 기초자료 입력
 * $all_accounts : 계층구조를 가지고 있는 계정목록
 * $sum_list : 차변/대변/잔액 을 가지고 있는 계정목록
 */
if ( ! function_exists('show_account_in_inputbalance')){
    function show_account_in_inputbalance($ano, $entry, $ol, &$first_weight='', $kind=''){
		$depth = $entry['depth'];
		$weight = $entry['weight'];
		$depth_str = str_repeat('&nbsp', $depth-1);
		
		if ($kind == 'jaemusang') {

			if ($depth==1) {
				$title = $entry['title_owner'];
				if (is_numeric($ano))
					$title = '['.$entry['title_owner'].']';
			} else if ($depth>1) {
				$title = $ol[$depth-1][$weight-1].'. '.$entry['title_owner'];
				//$title = $ol[$depth][$weight].'. '.$entry['title_owner'];
			}
	
			if ($depth < 3) $title = '<b>'.$title.'</b>';
		}
		

		if ($kind == 'sonik') {
			//손익계산서
			if ($depth == 2) {
				$weight = $first_weight;
				//$first_weight++;
			}
			
			$depth_str = str_repeat('&nbsp', $depth-1);
			if ($depth==2)
				$title = $ol[$depth-1][$weight-2].'. '.$entry['title_owner'];
			else if ($depth>2)
				$title = $ol[$depth-1][$weight-1].'. '.$entry['title_owner'];

			if ($depth < 3) $title = '<b>'.$title.'</b>';
		}


		$prev_money = $purpose_money = $fund_money = $total_money = $increase_money = $increase_rate = 0;
		
		if (array_key_exists('prev_money', $entry)) {
			$prev_money = $entry['prev_money'];
			$purpose_money = $entry['purpose_money'];
			$fund_money = $entry['fund_money'];
			$increase_money = $entry['increase_money'];
			$increase_rate = $entry['increase_rate'];
		}

		if (array_key_exists('total_money', $entry)) {
			$total_money = $entry['total_money'];
		}

		$prev_money = ($prev_money == 0) ? '' : number_format($prev_money);
		$purpose_money = ($purpose_money == 0) ? '' : number_format($purpose_money);
		$fund_money = ($fund_money == 0) ? '' : number_format($fund_money);
		$total_money = ($total_money == 0) ? '' : number_format($total_money);
		$increase_money = ($increase_money == 0) ? '' : number_format($increase_money);
		$increase_rate = ($increase_rate == 0) ? '' : number_format($increase_rate, 2).'%';

		$style1 = 'style="width:120px; text-align:right;"';
		$style2 = 'style="width:40px; text-align:right;"';
	?>
		<tr>
			<input type='hidden' name='account_no[<?php echo $ano; ?>]' value='<?php echo $ano; ?>'>
			<input type='hidden' name='title_owner[<?php echo $ano; ?>]' value='<?php echo $entry['title_owner']; ?>'>
			<input type='hidden' name='depth[<?php echo $ano; ?>]' value='<?php echo $entry['depth']; ?>'>
			<input type='hidden' name='weight[<?php echo $ano; ?>]' value='<?php echo $entry['weight']; ?>'>
			
			<td><?php echo $depth_str; ?><?php echo $title; ?></td>
			<td><input type='text' name='prev_money[<?php echo $ano; ?>]' value='<?php echo $prev_money; ?>' <?php echo $style1; ?>></td>
			<td><input type='text' name='purpose_money[<?php echo $ano; ?>]' value='<?php echo $purpose_money; ?>' <?php echo $style1; ?>></td>
			<td><input type='text' name='fund_money[<?php echo $ano; ?>]' value='<?php echo $fund_money; ?>' <?php echo $style1; ?>></td>
			<td><input type='text' name='total_money[<?php echo $ano; ?>]' value='<?php echo $total_money; ?>' <?php echo $style1; ?>></td>
			<td><input type='text' name='increase_money[<?php echo $ano; ?>]' value='<?php echo $increase_money; ?>' <?php echo $style1; ?>></td>
			<td><input type='text' name='increase_rate[<?php echo $ano; ?>]' value='<?php echo $increase_rate; ?>' <?php echo $style2; ?>>%</td>
		</tr>
	<?php
	}
}

/*
 * 기초자료입력
 * $all_accounts : 계층구조를 가지고 있는 계정목록
 * $sum_list : 차변/대변/잔액 을 가지고 있는 계정목록
 */
if ( ! function_exists('show_account_in_basic_data')){
    function show_account_in_basic_data($pano, $accounts, $prev_sum_list, $sum_list, $ol, &$first_weight, &$total){
    	
    }
}
    	
