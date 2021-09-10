<?php 
	if($term == 1){
		$tm = 'First Term';
	}
	elseif($term == 2){
		$tm = 'Second Term';
	}
	else if($term == 3){
		$tm = 'Third Term';
	}
	// get amount the student has paid for the session , class, and term 		
	$sql_summary_amount = "select * from school_fees where student_info_id = '$student_info_id' and ";
	$sql_summary_amount .= "session_id = '$session_id' and term_id = '$term' and class_id ='$class' ";
	$query_summary_amount =  mysqli_query($conn,$sql_summary_amount) or die(mysqli_error($conn));
	$query_summary_amount_num_rows =  mysqli_num_rows($query_summary_amount);
		if($query_summary_amount_num_rows > 0){
			echo '<h4>Your <b style="color:red">'.$tm.'</b> Payment Summary</h4>';
			echo '<table class="table table-bordered">';
			echo '<tr>
				<td>SN</td>
				<td>Amount</td>
				<td>Ballance</td>
				<td>Payment Number</td>
				<td>Date</td>
				<td>Payment By</td>
				
			</tr>';
			$sn = 1;
			$tt = 0;
			while($rows = mysqli_fetch_array($query_summary_amount)){
				$year = $rows['year'];
				$month = $rows['month'];
				$day = $rows['day'];
				$payment_number = $rows['payment_number'];
				$amount_paid = $rows['amount_paid'];
				$ballance = $rows['ballance'];
				$payment_madeBy = $rows['payment_madeBy'];
				$date = $day.' - '.$month.' - '.$year;
				$tt = $tt + $amount_paid;
					echo '<tr>
							<td>'.$sn.'</td>
							<td>'.$amount_paid.'</td>
							<td>'.$ballance.'</td>
							<td>'.$payment_number.'</td>
							<td>'.$date.'</td>
							<td>'.$payment_madeBy.'</td>
							
							
						</tr>';
						$sn++;
			}
			if($status == '2'){
				$rr = '<b style="color:green">Complete payment</b>';
			}else{
				$rr = '<b style="color:red">Still Owing us</b>';
			}
				echo '<tr>
							<td colspan="2"><b >Total =  '.$tt.'</b></td>
						
							<td colspan="4">'.$rr .'</td>
							
							
							
						</tr>';
			echo '</table>';
		}else{
			echo 'Cannot display summary for the moment';
		}
	

?>