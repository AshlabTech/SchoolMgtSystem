<?php session_start(); ?>
<?php
	if(isset($_SESSION['staff_info_id'])){
		
	}
	else{
		header('location:../');
	}
	include_once('../php/connection.php');
	
	
	
	if(isset($_POST['session_id'])){
	    //get current session id
	    
	    $session_id = $_POST['session_id'];
			$get_current_session_id_query = mysqli_query($conn,"select * from session where section_id = '$session_id' ") or die(mysqli_error($conn));
			$get_current_session_id_arr = mysqli_fetch_array($get_current_session_id_query);
			$session_id = $get_current_session_id_arr['section_id'];
			$current_session = $get_current_session_id_arr['section'];
			
		$term_id = $_POST['term_id'];
			$term = mysqli_query($conn,"select * from term  where id='$term_id' ORDER BY status DESC") or die(mysqli_error($conn));
			$term_array = mysqli_fetch_array($term);
			$term = $term_array['term'];
			$term_id = $term_array['id'];
			$term_full = $term_array['description'];
	}else{
	    //get current session id
			$get_current_session_id_query = mysqli_query($conn,"select * from session where status = '1' ") or die(mysqli_error($conn));
			$get_current_session_id_arr = mysqli_fetch_array($get_current_session_id_query);
			$session_id = $get_current_session_id_arr['section_id'];
			$current_session = $get_current_session_id_arr['section'];
			
			// Get the current term
			$term = mysqli_query($conn,"select * from term  where status = '1'") or die(mysqli_error($conn));
			$term_array = mysqli_fetch_array($term);
			$term = $term_array['term'];
			$term_id = $term_array['id'];
			$term_full = $term_array['description'];
		
			echo 'Current session n term';
	}
	
			
				// realises this term		
					$total_for_term_sql = "select SUM(amount_paid) from school_fees where  ";
					$total_for_term_sql .= "session_id = '$session_id' and term_id = '$term' and  (status = '1' or status = '2')";
					$total_for_term_query =  mysqli_query($conn,$total_for_term_sql) or die(mysqli_error($conn));
					$total_for_term_row = mysqli_fetch_row($total_for_term_query);		
					$total_for_term = $total_for_term_row[0];
					
					
					$sn=1;
					$all_months = mysqli_query($conn,"select * from months") or die(mysqli_error($conn));
					while($rows = mysqli_fetch_array($all_months)){
						$month_id  = $rows['month_id'];
						$month_full  = $rows['month_full'];
						
						$total_for_term_Monthly_sql = "select SUM(amount_paid) from school_fees where month = '$month_id' and ";
						$total_for_term_Monthly_sql .= "session_id = '$session_id' and term_id = '$term' and  (status = '1' or status = '2')";
						$total_for_term_Monthly_query =  mysqli_query($conn,$total_for_term_Monthly_sql) or die(mysqli_error($conn));
						$total_for_term_Monthly_row = mysqli_fetch_row($total_for_term_Monthly_query);	
						$total_for_month = $total_for_term_Monthly_row[0];
						
												$tr .='
																		<tr>
																			<td class="text-center" width="2%">'.$sn++.'</td>
																			<td class="text-left">'.$month_full.'</td>
																			<td class="text-center" width="20%">N'.$total_for_month.'</td>
																			
																			
																	</tr>
																	';	
					}
						
					
					
		
?>
<div id="ca_heading">
					<h1 style="margin-top:10px;font-size:20px;text-align:center"><?php echo strtoupper($term_full).' '.$current_session.' ACADEMIC SESSION\'S PAYMENT SUMMARY FOR ALL THE MONTHS'; ?></h1>
					
</div>
<table class="table table-bordered" style="margin:20px 5px">
	<?php echo $tr; ?>
</table>
<div id="ca_heading">
					<h1>Total = <?php echo 'N'.$total_for_term; ?></h1>
</div>
<h1 class="text-center"><a href="../php/print_term_payment_summary.php?tok=<?php echo md5('nothing');?>" target="_Blank" class="btn btn-primary"> <span class="fa fa-print"></span>  Print Slip</a></h1>