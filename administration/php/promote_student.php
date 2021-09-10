<?php 

	$output='Output..';
	$c_class_id;
	$n_class_id;
	$session_id;
	$student_info_id;
	$check_new_class_id;
	$get_current_class_id;
	$fetch_current_id_row;
	
	include_once('connection.php');
	if(isset($_POST['token'])){
		$student_info_id = $_POST['token'];
		
										//get current session id
											$query2 = mysqli_query($conn,"select * from session where status = '1' ") or die(mysqli_error($conn));
											$class_array2 = mysqli_fetch_array($query2);
											$session_id = $class_array2['section_id'];

		//get current class id
		
		$get_current_class_id = mysqli_query($conn,"select * from student_classes where student_info_id = '$student_info_id' and (status = '1' or status = '2')") or die(mysqli_error($conn));
		$fetch_current_id_row = mysqli_fetch_array($get_current_class_id);
		$c_class_id = $fetch_current_id_row['class_id'];
		
		$n_class_id =  $c_class_id+1;
		
		// get student section nur,pry,jss or ss
							$sql_student_section = "select * from classes where class_id = '$n_class_id' and status = '1'";
							$query_student_section=  mysqli_query($conn,$sql_student_section) or die(mysqli_error($conn));
							$num_rows_sql_student_section = mysqli_num_rows($query_student_section);		
								if($num_rows_sql_student_section > 0){
									$class_section = mysqli_fetch_array($query_student_section);
									$school_section_id = $class_section['school_section_id'];
									$class_description = $class_section['description'];
								}else{
									echo 'No Response 2';
										exit();
								}
									
									// get due payment for the session	i.e how much is the student expected to pay		
													$sql_sum_amount = "select  SUM(amount) from payment_details where current_session_id = '$session_id' ";
													$sql_sum_amount .= " and school_section_id = '$school_section_id'   and status = '1' and category =1";
													$query_sum_amount =  mysqli_query($conn,$sql_sum_amount) or die(mysqli_error($conn));
													$sum_row = mysqli_fetch_row($query_sum_amount);		
													 $total_amount = $sum_row[0];
													 
													 
			if($n_class_id <= 15){
				$check_new_class_id = mysqli_query($conn,"select * from student_classes where student_info_id = '$student_info_id' and class_id = '$n_class_id'") or die(mysqli_error($conn));
				
					if(mysqli_num_rows($check_new_class_id) >0){
						
						$output =  ' Done ';
					}else{
						
						$update_student_class = mysqli_query($conn,"update student_classes set status ='3' where student_info_id = '$student_info_id'") or die(mysqli_error($conn));
						$change_student_class = mysqli_query($conn,"insert into student_classes(student_info_id,class_id,session_id,school_fees,date_promoted_enrolled,status,last_date_modified)values('$student_info_id','$n_class_id','$session_id','$total_amount',now(),'1',now())") or die(mysqli_error($conn));
						
							if($change_student_class and $update_student_class){
								$output = ' PROMOTED... ';
							}
							else{
								$output =  'Failed...';
							}
					}
			}else{
				$update_student_class = mysqli_query($conn,"update student_classes set status ='3' where student_info_id = '$student_info_id'") or die(mysqli_error($conn));
				$output = 'Removed Successfully...';
			}
			mysqli_close($conn);
			echo $output;
	}

		
?>