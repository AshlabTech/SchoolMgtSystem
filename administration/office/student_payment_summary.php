<?php 
		
	// get distinct classes	
	$distinct_class_sql = "select distinct class_id from school_fees where student_info_id = '$student_info_id' order by id desc";
	$distinct_class_query =  mysqli_query($conn,$distinct_class_sql) or die(mysqli_error($conn));
	$distinct_class_query_rows =  mysqli_num_rows($distinct_class_query);
		if($distinct_class_query_rows > 0){
			$ss = 1;
			while($rows = mysqli_fetch_array($distinct_class_query)){
				$class_id = $rows['class_id'];
				
				$get_session_id_sql = "select * from school_fees where student_info_id = '$student_info_id' and class_id = '$class_id' order by id desc";
				$get_session_id_query =  mysqli_query($conn,$get_session_id_sql) or die(mysqli_error($conn));
				$get_session_id_array =  mysqli_fetch_array($get_session_id_query);
				$session_id = $get_session_id_array['session_id'];
				
					// get session e.g 2017/2018
					$get_session = mysqli_query($conn,"select * from session where 	section_id = '$session_id'") or die(mysqli_error($conn));
					$get_session_arr = mysqli_fetch_array($get_session);
					$session = $get_session_arr['section'];
				
				
												
				//get class name
				$distinct_className_sql = "select * from classes where class_id = '$class_id' and status = '1'";
				$distinct_className_query=  mysqli_query($conn,$distinct_className_sql) or die(mysqli_error($conn));
				$distinct_className_rows = mysqli_num_rows($distinct_className_query);		
				if($distinct_className_rows > 0){
					$class_name_array= mysqli_fetch_array($distinct_className_query);
					$school_section_id = $class_name_array['school_section_id'];
					$class_description = $class_name_array['description'];
					$class_name = $class_name_array['class_name'];
				}
						$distinct_term_sql = "select distinct term_id from school_fees where student_info_id = '$student_info_id' and class_id = '$class_id' order by id desc";
						$distinct_term_query =  mysqli_query($conn,$distinct_term_sql) or die(mysqli_error($conn));
						$distinct_term_query_rows =  mysqli_num_rows($distinct_term_query);
							if($distinct_term_query_rows > 0){
								
								while($term_rows = mysqli_fetch_array($distinct_term_query)){
									$term_id = $term_rows['term_id'];
									
									
										$term_payments_sql = "select * from school_fees where student_info_id = '$student_info_id' and class_id = '$class_id' and term_id = '$term_id' ";
										$term_payments_query =  mysqli_query($conn,$term_payments_sql) or die(mysqli_error($conn));
										$term_payments_query_rows =  mysqli_num_rows($term_payments_query);
											if($term_payments_query_rows > 0){
												if($term_id == 1){
													$tm = 'First Term';
												}
												elseif($term_id == 2){
													$tm = 'Second Term';
												}
												else if($term_id == 3){
													$tm = 'Third Term';
												}
												echo '<h5>Your '.$class_name.' <b style="color:red"> '.$tm.'</b> Payment Summary - <b>  '.$session.'  </b>Academic Session</h5>';
												echo '<table class="table table-bordered payment_summary_table">';
												echo '<tr>
													<td >SN</td>
													<td>Amount</td>
													<td>Ballance</td>
													<td>Payment Number</td>
													<td>Date</td>
													<td>Payment By</td>
													<td></td>
													
												</tr>';
												$sn = 1;
												$tt = 0;
												while($term_payment_rows = mysqli_fetch_array($term_payments_query)){
															$status = $term_payment_rows['status'];
															 	$id = $term_payment_rows['id'];
															$year = $term_payment_rows['year'];
															$month = $term_payment_rows['month'];
															$day = $term_payment_rows['day'];
															$payment_number = $term_payment_rows['payment_number'];
															$amount_paid = $term_payment_rows['amount_paid'];
															$ballance = $term_payment_rows['ballance'];
															$payment_madeBy = $term_payment_rows['payment_madeBy'];
															$date = $day.' - '.$month.' - '.$year;
															$tt = $tt + $amount_paid;
																echo '<tr>
																		<td>'.$sn.'</td>
																		<td>'.$amount_paid.'</td>
																		<td>'.$ballance.'</td>
																		<td>'.$payment_number.'</td>
																		<td>'.$date.'</td>
																		<td>'.$payment_madeBy.'</td>
																		<td>
																		<button class="btn btn-sm btn-danger" onclick="deleteStudentPayment('.$id.','.$student_info_id.')"><span class="fa fa-trash"></span></button>
																		</td>
																		
																		
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
												echo '<table>';
												echo '<a  href="../php/receipt_whole_term.php?token='.$student_info_id.'&cl='.$class_id.'&tr='.$term_id.'" target="_BLANK" class="btn btn-primary" ><span class="glyphicon glyphicon-print"></span>  Print Receipt</a>';
											}
								}
							}
				
			} // end of distinct class while loop
			
			
		}else{
			echo 'No Payment summary for the moment';
		}
	

?>


<script>
    
    
    
</script>