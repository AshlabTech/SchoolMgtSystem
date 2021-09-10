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
															$id = $position_row['id'];
															$student_info_id = $position_row['student_info_id'];
															$total = $position_row['total'];
															
																$position++;
																	$update_sql2 = "UPDATE term_result SET position='$position' where class_id = '$class_id' and session_id = '$session_id' and term_id = '$term_id' ";
																	$update_sql2 .= "and total='$total' and status='1'";
																	$update_query2 = mysqli_query($conn,$update_sql2) or die(mysqli_error($conn));
														}
													}
						
		
		
		
	
		
?>