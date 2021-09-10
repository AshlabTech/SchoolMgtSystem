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
	 
	 
	 $guidian_name = mysqli_real_escape_string($conn,$_POST['guidian_name']);
	 $guidian_phone_number = mysqli_real_escape_string($conn,$_POST['guidian_phone_number']);
	 $guidian_other_phone_number = mysqli_real_escape_string($conn,$_POST['guidian_other_phone_number']);
	 $guadian_relationship = mysqli_real_escape_string($conn,$_POST['guadian_relationship']);
	 $guidain_occupation = mysqli_real_escape_string($conn,$_POST['guidain_occupation']);
	 $guidian_address = mysqli_real_escape_string($conn,$_POST['guidian_address']);

	 //insert into the 
			$sql_insert_command = "insert into application_form (first_name,last_name,other_name,gender,religion,date_of_birth,state_id,lga_id,";
			$sql_insert_command .= "tribe,email_address,phone_number,guidian_other_phone_number,residential_address,postal_code,guidian_name,guidian_phone_number,";
			$sql_insert_command .= "guadian_relationship,guidian_address,previous_school,reason_for_leaving_the_school,";
			$sql_insert_command .= "guidain_occupation,date_applied,class_id)";
			$sql_insert_command .= "values ('$first_name','$last_name','$other_name','$gender','$religion','$date_of_birth','$state_id','$lga_id',";
			$sql_insert_command .= "'$tribe','$email_address','$phone_number','$guidian_other_phone_number','$residential_address','$postal_code','$guidian_name','$guidian_phone_number',";
			$sql_insert_command .= "'$guadian_relationship','$guidian_address','$previous_school','$reason_for_leaving_the_school',";
			$sql_insert_command .= "'$guidain_occupation',now(),'$student_class')";
			$php_process_sql_query_function = mysqli_query($conn,$sql_insert_command) or die(mysqli_error($conn));
				if($php_process_sql_query_function){
						echo 'Application is submited successfully';
				}else{
					ECHO 'OPERATION FAILED...';
					}
			
	


?>