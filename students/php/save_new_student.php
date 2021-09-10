<?php
		include_once('connection.php');
	 $first_name = mysqli_real_escape_string($conn,$_POST['first_name']);
	 $last_name = mysqli_real_escape_string($conn,$_POST['last_name']);
	 $other_name = mysqli_real_escape_string($conn,$_POST['other_name']);
	 $gender = mysqli_real_escape_string($conn,$_POST['gender']);
	 $religion = mysqli_real_escape_string($conn,$_POST['religion']);
	 $date_of_birth = mysqli_real_escape_string($conn,$_POST['date_of_birth']);
	 $state_id = mysqli_real_escape_string($conn,$_POST['state']);
	 $lga_id = mysqli_real_escape_string($conn,$_POST['lga']);
	 $tribe = mysqli_real_escape_string($conn,$_POST['tribe']);
	 $email_address = mysqli_real_escape_string($conn,$_POST['email_address']);
	 $phone_number = mysqli_real_escape_string($conn,$_POST['phone_number']);
	// $other_phone_numbers = mysqli_real_escape_string($conn,$_POST['other_phone_numbers']);
	 $residential_address = mysqli_real_escape_string($conn,$_POST['residential_address']);
	 $postal_code = mysqli_real_escape_string($conn,$_POST['postal_code']);
	 $student_class = mysqli_real_escape_string($conn,$_POST['student_class']);
	 $previous_school = mysqli_real_escape_string($conn,$_POST['previous_school']);
	 $reason_for_leaving_the_school = mysqli_real_escape_string($conn,$_POST['reason_for_leaving_the_school']);
	 $student_type = mysqli_real_escape_string($conn,$_POST['student_type']);
	 
	 
	 $guidian_name = mysqli_real_escape_string($conn,$_POST['guidian_name']);
	 $guidian_phone_number = mysqli_real_escape_string($conn,$_POST['guidian_phone_number']);
	 $guidian_other_phone_number = mysqli_real_escape_string($conn,$_POST['guidian_other_phone_number']);
	 $guadian_relationship = mysqli_real_escape_string($conn,$_POST['guadian_relationship']);
	 $guidain_occupation = mysqli_real_escape_string($conn,$_POST['guidain_occupation']);
	 $guidian_address = mysqli_real_escape_string($conn,$_POST['guidian_address']);
	 
		//get current session id
			$query2 = mysqli_query($conn,"select * from session where status = '1' ") or die(mysqli_error($conn));
			$class_array2 = mysqli_fetch_array($query2);
			$session_id = $class_array2['section_id'];
						
			//GET term ID
			$term_query = mysqli_query($conn,"select * from term where status = '1'") or die(mysqli_error($conn));
			$term_array = mysqli_fetch_array($term_query);
			$term_id = $term_array['term'];
							
							// get student section nur,pry,jss or ss
			$sql_student_section = "select * from classes where class_id = '$student_class' and status = '1'";
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


		if($student_type ==2 or $student_type =='2'){
					// get due payment for the session	i.e how much is the student expected to pay		
						$sql_sum_amount = "select  SUM(amount) from payment_details where current_session_id = '$session_id' ";
						$sql_sum_amount .= " and school_section_id = '$school_section_id'  and sex = '$gender' and status = '1' and category =2 ";
						$query_sum_amount =  mysqli_query($conn,$sql_sum_amount) or die(mysqli_error($conn));
						$sum_amount_rows =  mysqli_num_rows($query_sum_amount);
						$sum_row = mysqli_fetch_row($query_sum_amount);		
						$total_amount = $sum_row[0];
			}else if($student_type ==1 or $student_type =='1'){
				// get due payment for the session	i.e how much is the student expected to pay		
									$sql_sum_amount = "select  SUM(amount) from payment_details where current_session_id = '$session_id' ";
									$sql_sum_amount .= " and school_section_id = '$school_section_id'   and status = '1' and category =1";
									$query_sum_amount =  mysqli_query($conn,$sql_sum_amount) or die(mysqli_error($conn));
									$sum_amount_rows =  mysqli_num_rows($query_sum_amount);
									$sum_row = mysqli_fetch_row($query_sum_amount);		
									 $total_amount = $sum_row[0];
			}
			
			if(Intval($total_amount) <= 0){
				echo '<b style="color:red">Sorry, we can\'t  proceeed as payment has not been defined...</select>';
				exit();
			}
	 //insert into the 
			$sql_insert_command = "insert into student_info (first_name,last_name,other_name,gender,religion,date_of_birth,state_id,lga_id,";
			$sql_insert_command .= "tribe,email_address,phone_number,guidian_other_phone_number,residential_address,postal_code,guidian_name,guidian_phone_number,";
			$sql_insert_command .= "guadian_relationship,guidian_address,previous_school,reason_for_leaving_the_school,";
			$sql_insert_command .= "guidain_occupation,date_enrolled,status)";
			$sql_insert_command .= "values ('$first_name','$last_name','$other_name','$gender','$religion','$date_of_birth','$state_id','$lga_id',";
			$sql_insert_command .= "'$tribe','$email_address','$phone_number','$guidian_other_phone_number','$residential_address','$postal_code','$guidian_name','$guidian_phone_number',";
			$sql_insert_command .= "'$guadian_relationship','$guidian_address','$previous_school','$reason_for_leaving_the_school',";
			$sql_insert_command .= "'$guidain_occupation',now(),'$student_type')";
			$php_process_sql_query_function = mysqli_query($conn,$sql_insert_command) or die(mysqli_error($conn));
				if($php_process_sql_query_function){
						//get the staff id from the student_info table
							/*$sql_insert_command2 = "select MAX(student_info_id) from student_info";
							$php_process_sql_query_function2 = mysqli_query($conn,$sql_insert_command2) or die(mysqli_error($conn));
							$student_info_id_array = mysqli_fetch_row($php_process_sql_query_function2);*/
							$student_info_id = mysqli_insert_id($conn);
							
						$sql_insert_command3 = "insert into  student_classes (student_info_id,class_id,session_id,term_id,school_fees,date_promoted_enrolled,status)";
						$sql_insert_command3 .= "values ('$student_info_id','$student_class','$session_id','$term_id','$total_amount',now(),'$student_type')";
						$php_process_sql_query_function3 = mysqli_query($conn,$sql_insert_command3) or die(mysqli_error($conn));
						
							if($php_process_sql_query_function3){
									
								echo 200;
								exit();
							}
							else{
								echo 'student is not added....';
							}
				}else{
					ECHO 'OPERATION FAILED...';
				}
			
	


?>