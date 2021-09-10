<?php
		include_once('connection.php');
		
	 $subject_name = mysqli_real_escape_string($conn,$_POST['subject_name']);
	 $subject_code = mysqli_real_escape_string($conn,$_POST['subject_code']);
	 $school_section = mysqli_real_escape_string($conn,$_POST['school_section']);


		$check_query = mysqli_query($conn,"select * from subject where (subject = '$subject_name' and school_section = '$school_section') or subject_code = '$subject_code'");
		if(mysqli_num_rows($check_query) < 1){
			//insert into the 
			$sql_insert_command = "insert into subject(subject,subject_code,school_section) values ('$subject_name','$subject_code','$school_section')";
			$php_process_sql_query_function = mysqli_query($conn,$sql_insert_command) or die(mysqli_error($conn));
				if($php_process_sql_query_function){
						echo 'Subject is added successfully';
				}else{
					ECHO 'OPERATION FAILED...';
					}
		}else{
			echo 'Subject already exist...';
		}
	 
			
	


?>