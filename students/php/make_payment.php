<?php
		//  make mysqli database connection
	  include_once('connection.php');
	  
		//variables
		$student_info_id = 0;
		$class = 0;
		$term = 0;
		$month = 0;
		$day = 0;
		$amount = 0;
		$payee_name = '';
		$session_id = 0;
		$payment_number = 0;
		$total_amount = 0;
		$ballance = 0;
		$total_amount_paid = 0;
		$new_total_paid = 0;
		$status = '0';
		
		//gather all ajax posted data
	 $student_info_id = mysqli_real_escape_string($conn,$_POST['token']);
	 $class = mysqli_real_escape_string($conn,$_POST['class_pay_for']);
	 $term = mysqli_real_escape_string($conn,$_POST['school_fee_term']);
	 $payment_type = mysqli_real_escape_string($conn,$_POST['payment_type']);
	 $day = mysqli_real_escape_string($conn,$_POST['school_fee_day']);
	 $month =$_POST['school_fee_month'];
	 $year = mysqli_real_escape_string($conn,$_POST['school_fee_year']);
	 $amount = preg_replace('#[^0-9]#', '', $_POST['amount_paid']);
	 $payee_name = preg_replace('#[^a-zA-Z]#', '', $_POST['payee_name']);
	// $session_id = mysqli_real_escape_string($conn,$_POST['payment_session']);
	 $payment_number = mysqli_real_escape_string($conn,$_POST['payment_number']);
		
		//if payment is cash generate payment number
		if($payment_type==2){
			$payment_number = @date('y').@date('m').@date('d').(@date('h')-1).@date('i').@date('s'); 
		}
		
		//session_id 
			$get_sessionid_sql = "select * from student_classes where student_info_id = '$student_info_id' and class_id='$class'";
			$get_sessionid_query = mysqli_query($conn,$get_sessionid_sql) or die(mysqli_error($conn));
			$get_sessionid_arr = mysqli_fetch_array($get_sessionid_query);
			$session_id = $get_sessionid_arr['session_id'];
			$total_amount = $get_sessionid_arr['school_fees'];
			$student_status = $get_sessionid_arr['status'];
			$registered_term = $get_sessionid_arr['term_id'];
			
			
			//get status new or old student
							$query_student_info_query =  mysqli_query($conn,"select * from student_info where student_info_id = '$student_info_id'") or die(mysqli_error($conn));
							$num_rows_student_info = mysqli_num_rows($query_student_info_query);		
								$arrray_student_info = mysqli_fetch_array($query_student_info_query);
									$student_info_status = $arrray_student_info['status'];
									
			// get student section nur,pry,jss or ss
							$query_student_section=  mysqli_query($conn,"select * from classes where class_id = '$class' and status = '1'") or die(mysqli_error($conn));
							$num_rows_sql_student_section = mysqli_num_rows($query_student_section);		
									$class_section = mysqli_fetch_array($query_student_section);
									$school_section_id = $class_section['school_section_id'];
									$class_description = $class_section['description'];
								
			// if the school fees has not been define for their section
					if($total_amount == '' or $total_amount == 0){
						echo '<b>Payment Operation is not available at the moment</b>';
						exit();
					}
		// form validation
					if(empty($class)){
						echo '<b style="color:red">Class  must be selected..</select>';
					}
					else if(empty($term)){
						echo '<b style="color:red">Term  must be selected..</select>';
					}
					else if(empty($payment_type)){
						echo '<b style="color:red">Payment Type  must be selected..</select>';
					}
					else if(empty($day)){
						echo '<b style="color:red">The day payment is made..</select>';
					}
					else if(empty($month)){
						echo 'month = '.$month;// '<b style="color:red">The month payment is made..</select>';
					}
					else if(empty($year)){
						echo '<b style="color:red">The year payment is made..</select>';
					}
					else if(empty($amount)){
						echo '<b style="color:red">Enter the amount..</select>';
					}
				
					else if(empty($session_id)){
						echo '<b style="color:red">Please select academic session..</select>';
					}
					else if(empty($payment_number) and $payment_type==1){
						echo '<b style="color:red">Teller number..</select>';
					}
					else if(empty($payee_name)){
						echo '<b style="color:red">Name of the person who\'s making the payment..</select>';
					}
					else{
							//get the total amount due for payment this session for the students
							// at first is the student new or old student ?
							//  check the status of the student
							// 1. old student 2. new student
							
						
						//Beginning of check for old students if they still owing last class
											if($student_status==1 or $student_status==3){
												// check if the student is owning from previous class 
												
																	$query = mysqli_query($conn,"select * from student_classes where student_info_id = '$student_info_id' ") or die(mysqli_error($conn));
																	while($class_array = mysqli_fetch_array($query)){
																		$id = $class_array['class_id'];
																	
																		if($id != $class){
																			
																				$sql_prev_class_b = "select * from school_fees where student_info_id = '$student_info_id' and ";
																				$sql_prev_class_b .= " class_id ='$id'   order by id desc limit 1";
																				$query_prev_class_b =  mysqli_query($conn,$sql_prev_class_b) or die(mysqli_error($conn));
																				$num_rows_class_b = mysqli_num_rows($query_prev_class_b);
																				$prev_class_array = mysqli_fetch_array($query_prev_class_b);
																				$prev_class_ballance = $prev_class_array['ballance'];
																				$prev_class_status = $prev_class_array['status'];
																				
																				
																				//previous class first term 
																				$sql_prev_class_term1 = "select * from school_fees where student_info_id = '$student_info_id' and ";
																				$sql_prev_class_term1 .= " class_id ='$id'  and term_id ='1' order by id desc limit 1";
																				$query_prev_class_term1 =  mysqli_query($conn,$sql_prev_class_term1) or die(mysqli_error($conn));
																				$num_rows_class_term1_rows = mysqli_num_rows($query_prev_class_term1);
																				$prev_class_term1_ar = mysqli_fetch_array($query_prev_class_term1);
																				$prev_class_term1_ballance = $prev_class_term1_ar['ballance'];
																				$prev_class_term1_status = $prev_class_term1_ar['status'];
																				//previous class second term 
																				$sql_prev_class_term2 = "select * from school_fees where student_info_id = '$student_info_id' and ";
																				$sql_prev_class_term2 .= " class_id ='$id'  and term_id ='2' order by id desc limit 1";
																				$query_prev_class_term2 =  mysqli_query($conn,$sql_prev_class_term2) or die(mysqli_error($conn));
																				$num_rows_class_term2_rows = mysqli_num_rows($query_prev_class_term2);
																				$prev_class_term2_ar = mysqli_fetch_array($query_prev_class_term2);
																				$prev_class_term2_ballance = $prev_class_term2_ar['ballance'];
																				$prev_class_term2_status = $prev_class_term2_ar['status'];
																				//previous class third term 
																				$sql_prev_class_term3 = "select * from school_fees where student_info_id = '$student_info_id' and ";
																				$sql_prev_class_term3 .= " class_id ='$id'  and term_id ='3' order by id desc limit 1";
																				$query_prev_class_term3 =  mysqli_query($conn,$sql_prev_class_term3) or die(mysqli_error($conn));
																				$num_rows_class_term3_rows = mysqli_num_rows($query_prev_class_term3);
																				$prev_class_term3_ar = mysqli_fetch_array($query_prev_class_term3);
																				$prev_class_term3_ballance = $prev_class_term3_ar['ballance'];
																				$prev_class_term3_status = $prev_class_term3_ar['status'];
																				
																					$query2 = mysqli_query($conn,"select * from classes where class_id = '$id'") or die(mysqli_error($conn));
																						$class_array2 = mysqli_fetch_array($query2);
																							$class_id = $class_array2['class_id'];
																							$class_name = $class_array2['class_name'];
																							
																							//if the student did not make any payment in the last class....
																								if($num_rows_class_b == 0 and $id !=1){
																										
																											echo "<b style='color:red'>The student is still owing ".$class_name." school fees</b>";
																											exit();
																									} 
																									
																									if($prev_class_term1_status==1 or $num_rows_class_term1_rows ==0 or $prev_class_term1_ballance > 0 ){
																										echo "<b style='color:red'>The student is still owing ".$prev_class_term1_ballance." First Term ".$class_name." school fees</b>";
																											exit();
																									}else if($prev_class_term2_status==1 or $num_rows_class_term2_rows ==0 or $prev_class_term2_ballance > 0 ){
																										echo "<b style='color:red'>The student is still owing ".$prev_class_term2_ballance." Second Term ".$class_name." school fees</b>";
																											exit();
																									}
																									else if($prev_class_term3_status==1 or $num_rows_class_term3_rows ==0 or $prev_class_term3_ballance > 0){
																										echo "<b style='color:red'>The student is still owing ".$prev_class_term3_ballance." Third Term ".$class_name." school fees</b>";
																											exit();
																									}
																									// end here ...
																							
																		}else{
																			break;
																		}
																	
																	
																	}								
											}   //end of check for old students if they still owing last class
									
										
										if( ($prev_class_status == 2 or $prev_class_ballance <= 0) or $student_status==2 ){ // if the student is not owing from previous class or is new students
										
														// check if the student is owning last term 
														 if($term==2){
															 
																//Current class second term 
																				$sql_current_class_term2 = "select * from school_fees where student_info_id = '$student_info_id' and ";
																				$sql_current_class_term2 .= " class_id ='$class'  and term_id ='1' order by id desc limit 1";
																				$query_current_class_term2 =  mysqli_query($conn,$sql_current_class_term2) or die(mysqli_error($conn));
																				$current_class_term2_rows = mysqli_num_rows($query_current_class_term2);
																				$current_class_term2_ar = mysqli_fetch_array($query_current_class_term2);
																				$current_class_term2_ballance = $current_class_term2_ar['ballance'];
																				$current_class_term2_status = $current_class_term2_ar['status'];
																				
																				 if($current_class_term2_status==1 or $current_class_term2_rows ==0 or $current_class_term2_ballance > 0 ){
																					 if($registered_term < 2){
																						echo "<b style='color:red'>The student is still owing ".$	." First Term  school fees</b>";exit();
																					}
																				 }
														 }
														 
														 if($term==3){
															 
																//Current class Third term 
																	$sql_current_class_term3 = "select * from school_fees where student_info_id = '$student_info_id' and ";
																	$sql_current_class_term3 .= " class_id ='$class'  and term_id ='2' order by id desc limit 1";
																	$query_current_class_term3 =  mysqli_query($conn,$sql_current_class_term3) or die(mysqli_error($conn));
																	$current_class_term3_rows = mysqli_num_rows($query_current_class_term3);
																	$current_class_term3_ar = mysqli_fetch_array($query_current_class_term3);
																	$current_class_term3_ballance = $current_class_term3_ar['ballance'];
																	$current_class_term3_status = $current_class_term3_ar['status'];
																	
																	 if($current_class_term3_status==1 or $current_class_term3_rows ==0 or $current_class_term3_ballance > 0 ){
																			if($registered_term < 3){
																				echo "<b style='color:red'>The student is still owing ".$current_class_term3_ballance." Second Term  school fees</b>";exit();
																			}
																							
																	 }
														 }
														
														
															 // if the student is not owing from last term or if its first term
															 
																	// get amount the student has paid for the session , class, and term 		
																			$sql_prev_amount = "select SUM(amount_paid) from school_fees where student_info_id = '$student_info_id' and ";
																			$sql_prev_amount .= "session_id = '$session_id' and term_id = '$term' and class_id ='$class' and (status = '1' or status = '2')";
																			$query_prev_amount =  mysqli_query($conn,$sql_prev_amount) or die(mysqli_error($conn));
																			$sum_prev_amount = mysqli_fetch_row($query_prev_amount);		
																			$total_amount_paid = $sum_prev_amount[0];
																			
																			// new total paid
																			$new_total_paid = $total_amount_paid + $amount;		
																			
																			// calculate ballance
																			$previous_ballance = $total_amount - $total_amount_paid;
																			$ballance = $total_amount - $new_total_paid ;
																			
																			//school fees table
																			// status
																			//1.part payment
																			// 2. full payee_name
																			//0. payment deleted
																			if($new_total_paid == $total_amount){
																				$status = '2';
																				
																							//let's change the student payment as old student now...
																								if($student_info_status==2){
																									//  how much is the student expected to pay as from next term		
																											$sql_sum_amount = "select  SUM(amount) from payment_details where current_session_id = '$session_id' ";
																											$sql_sum_amount .= " and school_section_id = '$school_section_id'   and status = '1' and category =1";
																											$query_sum_amount =  mysqli_query($conn,$sql_sum_amount) or die(mysqli_error($conn));
																											$sum_row = mysqli_fetch_row($query_sum_amount);		
																											 $next_term_school_fees = $sum_row[0];
																											 
																										//update status and school fees fields 
																										$change_student_status = mysqli_query($conn,"update student_classes set school_fees = '$next_term_school_fees' where student_info_id = '$student_info_id' and class_id='$class'") or die(mysqli_error($conn));
																										$change_student_info_status = mysqli_query($conn,"update student_info set status = '1' where student_info_id = '$student_info_id'") or die(mysqli_error($conn));
																								}
																			}else{
																				$status = '1';
																			}
																			
																			if($new_total_paid <= $total_amount){
																			
																				$submit_payment_sql = "INSERT INTO school_fees (student_info_id	,class_id,year,month,day,dateTime,payment_type,session_id,";
																				$submit_payment_sql .= "payment_number,term_id,bursar_id,payment_madeBy,amount_paid,ballance,status)";
																				$submit_payment_sql .= "values ('$student_info_id','$class','$year','$month','$day',now(),'$payment_type','$session_id',";
																				$submit_payment_sql .= "'$payment_number','$term','','$payee_name','$amount','$ballance','$status')";
																				$submit_payment = mysqli_query($conn,$submit_payment_sql) or die(mysqli_error($conn));
																								if($submit_payment){
																									//echo '<b style="color:green"><u> Done : </u> To Balance is N '.$ballance.' </b>';
																								include('payment_summary_class_term.php');
																								}else{
																									echo '<b style="color:red"><u> Alert :</u> Operation Failed... </b>';
																								}
																			}else{
																					echo '<div style="text-align:left"><b style="color:red">Alert :</b>
																					<ol>
																						<li>The student has completed his payment OR</li>
																						<li>The amount about paying is more than the school fee</li>
																						<li>Kindly check his payment summary to see</li>
																					</ol></div>';
																			}
															
															
												
													
										}else{ // if the student is not owing from previous class
													$owing_balance_row = mysqli_fetch_row($query_prev_class_b);		
													$owing_balance = $owing_balance_row[0];
														echo "<b style='color:red'>The student is still owing ".$owing_balance." in the class</b>";
										}
								
							
							
						
					} // end of form validation
		
		
		
			
?>