<?php
		include_once('connection.php');
		$student_info_id = $_POST['token'];
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
	 
	 
	 $guidian_name = mysqli_real_escape_string($conn,$_POST['guidian_name']);
	 $guidian_phone_number = mysqli_real_escape_string($conn,$_POST['guidian_phone_number']);
	 $guidian_other_phone_number = mysqli_real_escape_string($conn,$_POST['guidian_other_phone_number']);
	 $guadian_relationship = mysqli_real_escape_string($conn,$_POST['guadian_relationship']);
	 $guidain_occupation = mysqli_real_escape_string($conn,$_POST['guidain_occupation']);
	 $guidian_address = mysqli_real_escape_string($conn,$_POST['guidian_address']);

	 //insert into the 
			$sql_update_command = "update  student_info set first_name = '$first_name',last_name = '$last_name',other_name = '$other_name',";
			$sql_update_command .="gender = '$gender',religion = '$religion',date_of_birth = '$date_of_birth',state_id = '$state_id',lga_id = '$lga_id',";
			$sql_update_command .= "tribe = '$tribe',email_address = '$email_address',phone_number = '$phone_number',guidian_other_phone_number = '$guidian_other_phone_number',";
			$sql_update_command .= "residential_address = '$residential_address',postal_code = '$postal_code',guidian_name = '$guidian_name',guidian_phone_number = '$guidian_phone_number',";
			$sql_update_command .= "guadian_relationship = '$guadian_relationship',guidian_address = '$guidian_address',";
			$sql_update_command .= "previous_school = '$previous_school',reason_for_leaving_the_school = '$reason_for_leaving_the_school',";
			$sql_update_command .= "guidain_occupation = '$guidain_occupation' where student_info_id = '$student_info_id'";
	
			$php_process_sql_query_function = mysqli_query($conn,$sql_update_command) or die(mysqli_error($conn));
				if($php_process_sql_query_function){
						
							
										$sql_insert_command3 = "update student_classes set class_id = '$student_class' , last_date_modified = now() where student_info_id = '$student_info_id'";
										$php_process_sql_query_function3 = mysqli_query($conn,$sql_insert_command3) or die(mysqli_error($conn));
										
											if($php_process_sql_query_function3){
													
												echo 'Updated successfully....';
											}
											else{
												echo 'Update FAILED....';
												}
				}else{
					ECHO 'OPERATION FAILED...';
					}
			
	


?>