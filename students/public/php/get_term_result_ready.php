<?php 

	
		
										$get_all_cc_sql = "select SUM(total) from  contineous_accessment where student_info_id = '$student_info_id'  and session_id = '$session_id'";
										$get_all_cc_sql .= "and class_id = '$class_id' and term_id = '$term_id' and status = '1'";
										$get_all_cc_query = mysqli_query($conn,$get_all_cc_sql) or die(mysqli_error($conn));
										$total_score_for_term_row = mysqli_fetch_row($get_all_cc_query);
										$total_score_for_term  =  $total_score_for_term_row[0];
										
										//Term result - calculate all the current score of the student for all the subject or term
											$check = mysqli_query($conn,"select * from  term_result where student_info_id='$student_info_id' and class_id = '$class_id' and session_id = '$session_id' and 	term_id = '$term_id' and status = '1'");
											$num_rows_check = mysqli_num_rows($check);
												if($num_rows_check > 0){
													$update_sql2 = "UPDATE term_result SET total='$total_score_for_term' where student_info_id = '$student_info_id' and session_id = '$session_id'";
													$update_sql2 .= "and class_id = '$class_id' and term_id = '$term_id' and status = '1'";
													$update_query2 = mysqli_query($conn,$update_sql2) or die(mysqli_error($conn));
												}else{
													$insert_sql2 = "INSERT INTO term_result(student_info_id,session_id,class_id,term_id,total)";
													$insert_sql2 .= "VALUES('$student_info_id','$session_id','$class_id','$term_id','$total_score_for_term')";
													$insert_query2 = mysqli_query($conn,$insert_sql2) or die(mysqli_error($conn));
												}
												
												
												//Get position
												
													$postion_sql = mysqli_query($conn,"select distinct total from  term_result where class_id = '$class_id' and session_id = '$session_id' and 	term_id = '$term_id' and status = '1' order by total desc");
													$postion_sql_num_rows = mysqli_num_rows($postion_sql);
													if($postion_sql_num_rows>0){
														$position = 0;
														while($position_row = mysqli_fetch_array($postion_sql)){
															//$id = $position_row['id'];
															//$student_info_id = $position_row['student_info_id'];
															$total_mark = $position_row['total'];
															
																$position++;
																	$update_sql2 = "UPDATE term_result SET position='$position' where class_id = '$class_id' and session_id = '$session_id' and term_id = '$term_id' ";
																	$update_sql2 .= "and total='$total_mark' and status='1'";
																	$update_query2 = mysqli_query($conn,$update_sql2) or die(mysqli_error($conn));
														}
													}
						
		
												//if they are in third term
												//take the average of the three term
												
												if($term_id==3){
													//sum of first term marks
													$first_term_sum_sql = "select SUM(total) from  contineous_accessment where student_info_id = '$student_info_id'  and session_id = '$session_id'";
													$first_term_sum_sql .= "and class_id = '$class_id' and term_id = '1' and status = '1'";
													$first_term_sum_query = mysqli_query($conn,$first_term_sum_sql) or die(mysqli_error($conn));
													$first_term_sum_row = mysqli_fetch_row($first_term_sum_query);
													$first_term_sum  =  $first_term_sum_row[0];
													
													//sum of second term marks
													$second_term_sum_sql = "select SUM(total) from  contineous_accessment where student_info_id = '$student_info_id'  and session_id = '$session_id'";
													$second_term_sum_sql .= "and class_id = '$class_id' and term_id = '2' and status = '1'";
													$second_term_sum_query = mysqli_query($conn,$second_term_sum_sql) or die(mysqli_error($conn));
													$second_term_sum_row = mysqli_fetch_row($second_term_sum_query);
													$second_term_sum  =  $second_term_sum_row[0];
													
													//find total and average score
													$total_yr_score = $first_term_sum + $second_term_sum + $total_score_for_term; // Total score
													$average_score = $total_yr_score/3; //average score
													
													//check if the students average score has been recorded before
													$check_yearly_score = mysqli_query($conn,"select * from yearly_result where student_info_id = '$student_info_id' and session_id = '$session_id' and class_id = '$class_id' and status = '1'")or die(mysqli_error($conn));
													$check_yearly_score_num_rows = mysqli_num_rows($check_yearly_score);
														if($check_yearly_score_num_rows > 0){
													//if yes	
														$yrly_id_arr = mysqli_fetch_array($check_yearly_score);
															$yrly_id = $yrly_id_arr['id'];
															
																	$update_yrly_result_sql = "UPDATE yearly_result SET average_score='$average_score',total_score='$total_yr_score' where id='$yrly_id' and status='1'";
																	$update_yrly_result_query = mysqli_query($conn,$update_yrly_result_sql) or die(mysqli_error($conn));
														}else{
															
														//if no
																$insert_yrly_score_sql = "INSERT INTO yearly_result(student_info_id,session_id,class_id,average_score,total_score)";
																$insert_yrly_score_sql .= "VALUES('$student_info_id','$session_id','$class_id','$average_score','$total_yr_score')";
																$insert_yrly_score_query = mysqli_query($conn,$insert_yrly_score_sql) or die(mysqli_error($conn));
														}
												
												}
		
	
		
?>