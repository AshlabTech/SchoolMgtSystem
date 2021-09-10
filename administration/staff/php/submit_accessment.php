<?php 
		include_once('connection.php');
		
		$ca;
		$ca1;
		$ca2;
		$ca3;
		$exam;
		$total;
		$grade;
		$term_id;
		$class_id;
		$subject_id;
		$student_info_id;
		
		$student_info_id = $_POST['token'];
		$term_id = $_POST['term_id'];
		$class_id = $_POST['cl'];
		$subject_id = $_POST['sub'];
		//$ca = $_POST['ca'];
		
		$exam = mysqli_real_escape_string($conn,$_POST['exam']);
		$ca1 = mysqli_real_escape_string($conn,$_POST['ca1']);
		$ca2 = mysqli_real_escape_string($conn,$_POST['ca2']);
		$ca3 = mysqli_real_escape_string($conn,$_POST['ca3']);
		
		$ca1 = preg_replace('#[^0-9]#','',$ca1);
		$ca2 = preg_replace('#[^0-9]#','',$ca2);
		$ca3 = preg_replace('#[^0-9]#','',$ca3);
		$exam = preg_replace('#[^0-9]#','',$exam);
		
		
		$total = $ca1 + $ca2 + $ca3 + $exam;
		$grade = '';
		
		
		if($total >100){
			$total = 100;
		}else if($total < 0){
			$total = 0;
		}
			
		
		
		if($total >=70 and $total <=100){
			$grade = 'A';
		}
		else if($total >=60 and $total <=69){
			$grade = 'B';
		}
		else if($total >=50 and $total <=59){
			$grade = 'C';
		}
		else if($total >=40 and $total <=49){
			$grade = 'D';
		}else{
			$grade = 'F';
		}
			
		
		//GET SESSION ID
				$session_query = mysqli_query($conn,"select * from session where status = '1'") or die(mysqli_error($conn));
				$session_array = mysqli_fetch_array($session_query);
				$session_id = $session_array['section_id'];
				
		//check if any thing has been enter before for that subject ,term ,class and session
				$check = mysqli_query($conn,"select * from  contineous_accessment where student_info_id='$student_info_id' and class_id = '$class_id' and session_id = '$session_id' and 	term_id = '$term_id' and subject_id ='$subject_id' and status = '1'");
				$num_rows_check = mysqli_num_rows($check);
					if($num_rows_check > 0){
		//yes
						$check_array = mysqli_fetch_array($check);
						$id = $check_array['id'];
						$update_sql = "UPDATE contineous_accessment SET ca1 = '$ca1',ca2 = '$ca2',ca3 = '$ca3',exam='$exam',total = '$total',grade = '$grade'";
						$update_sql .= "WHERE id = '$id'";
						$update_query = mysqli_query($conn,$update_sql) or die(mysqli_error($conn));
							if($update_query){
										
										include('get_term_result_ready.php');
													
										
								//total and grade that will show on the page
								echo $total.'|'.$grade;
							}else{
								echo 'failed';
							}
				
					
			// NO
					}else{
						$insert_sql = "INSERT INTO contineous_accessment(student_info_id,session_id,class_id,term_id,subject_id,ca1,ca2,ca3,exam,total,grade)";
						$insert_sql .= "VALUES('$student_info_id','$session_id','$class_id','$term_id','$subject_id','$ca1','$ca2','$ca3','$exam','$total','$grade')";
							$insert_query = mysqli_query($conn,$insert_sql) or die(mysqli_error($conn));
								
								if($insert_query){
									
										include('get_term_result_ready.php');
										
								//total and grade that will show on the page
									echo $total.'|'.$grade;
								}
								else{
									echo 'failed';
								}
					}
												
											

?>