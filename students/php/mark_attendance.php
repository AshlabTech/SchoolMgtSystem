<?php
	include_once('connection.php');
	
	$class;
	$term;
	$year;
	$query;
	$term_id;
	$class_id;
	$class_array;
	$month_id;
	$term_array;
	$class_idd;
	$hash_class;
	$session_id;
	$description;
	$day_in_month;
	$class_id_hash;
	$student_info_id;
	
		
	
	 $student_info_id = mysqli_real_escape_string($conn,$_POST['token']);
	 $session_id = mysqli_real_escape_string($conn,$_POST['session_id']);
	 $class_id_hash = mysqli_real_escape_string($conn,$_POST['class_id']);
	 $year = mysqli_real_escape_string($conn,$_POST['year']);
	 $month_id = mysqli_real_escape_string($conn,$_POST['month_id']);
	 $day_in_month = mysqli_real_escape_string($conn,$_POST['day_in_month']);
	 
				$query = mysqli_query($conn,"select * from classes") or die(mysqli_error($conn));
					while($class_array = mysqli_fetch_array($query)){
						$class_idd = $class_array['class_id'];
						$class = $class_array['class_name'];
						$hash_class = md5($class_idd);
							if($class_id_hash == $hash_class){
								$class_id = $class_idd;
							}
					}
	 
	 
	 	
	// Get the current term
		$term = mysqli_query($conn,"select * from term  where status = '1'") or die(mysqli_error($conn));
		$term_array = mysqli_fetch_array($term);
		$term = $term_array['term'];
		$term_id = $term_array['id'];
		$description = $term_array['description'];
			
	 // check if the attendance is marked absent or present
	 //if not marked, mark present
	 // if is marked present , unmark as absent
	 
	 $check_sql = "select * from student_attendance where student_info_id = '$student_info_id' and session_id = '$session_id'";
	 $check_sql .= "and year = '$year' and month = '$month_id' and day = '$day_in_month' and class_id = '$class_id' and term_id = '$term_id'";
	 $check_query = mysqli_query($conn,$check_sql) or die(mysqli_error($conn));
		$num_rows_check = mysqli_num_rows($check_query);
			if($num_rows_check < 1){
				
				$sql_insert_command3 = "insert into student_attendance (student_info_id,session_id,year,month,day,class_id,term_id)";
							$sql_insert_command3 .= "values ('$student_info_id','$session_id','$year','$month_id','$day_in_month','$class_id','$term_id')";
							$php_process_sql_query_function3 = mysqli_query($conn,$sql_insert_command3) or die(mysqli_error($conn));
											if($php_process_sql_query_function3){
												echo 'Marked';
											}else{
												echo 'Not Marked';
											}
			}else{
				//if already  marked
				
				//if marked present
				//then mark it absent
				$check_array = mysqli_fetch_array($check_query);
				$status = $check_array['status'];
					if($status == '1'){
						
							$update_sql1 = "update student_attendance set status = '0' where student_info_id = '$student_info_id' and class_id = '$class_id'";
							$update_sql1 .= "and term_id = '$term_id'  and session_id= '$session_id' and year= '$year' and month = '$month_id'";
							$update_sql1 .= "and day = '$day_in_month'";
							$update_query1 = mysqli_query($conn,$update_sql1);
						if($update_query1){
									echo 'Marked absent';
									}else{
										echo 'Not unMarked';
									}
					}
					else{
						//if marked  absent 
						//then mark it present
						
						$update_sql2 = "update student_attendance set status = '1' where student_info_id = '$student_info_id' and class_id = '$class_id'";
						$update_sql2 .= "and term_id = '$term_id' and session_id= '$session_id' and year= '$year' and month = '$month_id'";
						$update_sql2 .= "and day = '$day_in_month'";
						$update_query2 = mysqli_query($conn,$update_sql2);
							if($update_query2){
									echo 'Marked Present';
									}else{
										echo 'Not Marked';
									}
					}
						
					
			}
							
							


?>